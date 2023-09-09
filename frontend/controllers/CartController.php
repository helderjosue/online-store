<?php

namespace frontend\controllers;


use common\models\CartItem;
use common\models\Product;
use Yii;
use yii\filters\ContentNegotiator;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * @author  helderjosue
 * Date 9/9/23
 */
class CartController extends \frontend\base\Controller
{

    public function behaviors()
    {
        return [
            [
                'class'=> ContentNegotiator::class,
                'only' => ['add'],
                'formats' => [
                    'application/json'=> Response::FORMAT_JSON,
                ]
            ],
            [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST', 'DELETE']
                ]
            ]
        ];
    }

    public function actionIndex(){

        if(isGuest()){
            //get the items from session
            $cartItems = Yii::$app->session->get(CartItem::SESSION_KEY, []);
        }else{
            $cartItems = CartItem::findBySql(
                "SELECT 
                          c.product_id as id,
                           p.image,
                           p.name,
                           p.price,
                           c.quantity,
                           p.price * c.quantity as total_price
                        FROM cart_items c
                            left join products p on p.id = c.product_id
                        WHERE
                           c.created_by = :userId",['userId' => Yii::$app->user->id]
            )
                ->asArray()
                ->all();

        }

        return $this->render('index', [
            'items' => $cartItems
        ]);

    }

    /**
     * @throws NotFoundHttpException
     */
    public function actionAdd(){
        $id = Yii::$app->request->post('id');
        $product = Product::find()->id($id)->published()->one();
        if (!$product){
            throw new NotFoundHttpException('Artigo nao encontrado');
        }

        if(isGuest()){
            //get the items from session
            $cartItem = [
                'id' => $id,
                'name' => $product->name,
                'image' => $product->image,
                'price' => $product->price,
                'quantity' => 1,
                'total_price' => $product->price,
            ];
            $cartItems = Yii::$app->session->get(CartItem::SESSION_KEY,[]);
            $found = false;
            foreach ($cartItems as &$item) {
                if ($item['id'] == $id) {
                    $item['quantity']++;
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                $cartItem = [
                    'id' => $id,
                    'name' => $product->name,
                    'image' => $product->image,
                    'price' => $product->price,
                    'quantity' => 1,
                    'total_price' => $product->price
                ];
                $cartItems[] = $cartItem;
            }
            Yii::$app->session->set(CartItem::SESSION_KEY,$cartItems);
        }else{
            $userId = Yii::$app->user->id;
            $cartItem = CartItem::find()->userId($userId)->productId($id)->one();

            if ($cartItem){
                $cartItem->quantity++;
            }else {


                $cartItem = new CartItem();
                $cartItem->product_id = $id;
                $cartItem->created_by = $userId;
                $cartItem->quantity = 1;
            }
            if ($cartItem->save()){
                return [
                    'success' => true
                ];
            } else
            {
                return [
                    'success' => false,
                    'errors' => $cartItem->errors
                ];
            }
        }
    }

    public function actionDelete($id): Response
    {
        if(isGuest()){

            $cartItems = Yii::$app->session->get(CartItem::SESSION_KEY,[]);
            foreach ($cartItems as $a => $cartItem){
                if($cartItem['id'] == $id){
                    array_splice($cartItems, $a, 1);
                    break;

                }
            }
            Yii::$app->session->set(CartItem::SESSION_KEY, $cartItems);
        }
        else{
            CartItem::deleteAll(['product_id' => $id, 'created_by' => currentUserId()]);
        }
        return $this->redirect(['index']);
    }
}