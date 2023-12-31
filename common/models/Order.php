<?php

namespace common\models;

use Yii;
use yii\db\Exception;

/**
 * This is the model class for table "{{%orders}}".
 *
 * @property int $id
 * @property float $total_price
 * @property int $status
 * @property string $firstname
 * @property string $lastname
 * @property string $email
 * @property string|null $transaction_id
 * @property int|null $created_at
 * @property int|null $created_by
 *
 * @property User $createdBy
 * @property OrderAddress $orderAddresses
 * @property OrderItem[] $orderItems
 */
class Order extends \yii\db\ActiveRecord
{

    const STATUS_DRAFT = 0;
    const STATUS_COMPLETED = 1;
    const STATUS_PAID = 2;
    const STATUS_FAILED = 3;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%orders}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['total_price', 'status', 'firstname', 'lastname', 'email'], 'required'],
            [['total_price'], 'number'],
            [['status', 'created_at', 'created_by'], 'integer'],
            [['firstname', 'lastname', 'transaction_id'], 'string', 'max' => 45],
            [['email'], 'string', 'max' => 255],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['created_by' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'total_price' => 'Preço total',
            'status' => 'Estado',
            'firstname' => 'Primeiro nome',
            'lastname' => 'Último nome',
            'email' => 'Email',
            'transaction_id' => 'ID da Transacção',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
        ];
    }

    /**
     * Gets query for [[CreatedBy]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\UserQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::class, ['id' => 'created_by']);
    }

    /**
     * Gets query for [[OrderAddresses]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\OrderAddressQuery
     */
    public function getOrderAddresses()
    {
        return $this->hasOne(OrderAddress::class, ['order_id' => 'id']);
    }

    /**
     * Gets query for [[OrderItems]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\OrderItemQuery
     */
    public function getOrderItems()
    {
        return $this->hasMany(OrderItem::class, ['order_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\query\OrderQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\OrderQuery(get_called_class());
    }

    public function saveOrderItems(): bool
    {

        $transaction = Yii::$app->db->beginTransaction();

        $cartItems = CartItem::getItemsForCurrentUser(currentUserId());

        foreach ($cartItems as $cartItem){
            $orderItem = new OrderItem();
            $orderItem->product_name = $cartItem['name'];
            $orderItem->product_id = $cartItem['id'];
            $orderItem->unit_price = $cartItem['price'];
            $orderItem->order_id = $this->id;
            $orderItem->quantity = $cartItem['quantity'];

            if ($orderItem->save()){
                $transaction->rollBack();
                throw new Exception("Order Item was not saved!"
                    .implode('<br>', $orderItem->getFirstErrors()));
            }
        }

        $transaction->commit();
        return true;
    }

    public function saveAddress($postData)
    {
        $orderAddress = new OrderAddress();
        $orderAddress->order_id = $this->id;
        if ($orderAddress->load($postData) && $orderAddress->save()) {
            return true;
        }
        return throw new Exception("Could not save Order Address". implode("<br>", $orderAddress->getFirstErrors()));
    }
}
