<?php

namespace frontend\controllers;


use common\models\CartItem;
use common\models\Order;
use common\models\OrderAddress;
use common\models\Product;
use common\models\User;
use Yii;
use yii\db\Exception;
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
                'only' => ['add', 'create-order'],
                'formats' => [
                    'application/json'=> Response::FORMAT_JSON,
                ]
            ],
            [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST', 'DELETE'],
                    'create-order' => ['POST']
                ]
            ]
        ];
    }

    public function actionIndex(){


            $cartItems = CartItem::getItemsForCurrentUser(currentUserId());


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


    public function actionChangeQuantity(){

        $id = Yii::$app->request->post('id');
        $product = Product::find()->id($id)->published()->one();
        if (!$product){
            throw new NotFoundHttpException('Artigo nao encontrado');
        }
        $quantity = Yii::$app->request->post('quantity');

        if(isGuest()){
            $cartItems = Yii::$app->session->get(CartItem::SESSION_KEY,[]);
            foreach ($cartItems as &$item){
                if ($item['id'] == $id){
                    $item['quantity'] = $quantity;
                    break;
                }
            }
            Yii::$app->session->set(CartItem::SESSION_KEY, $cartItems);

        } else{
            $cartItem = CartItem::find()->userId(currentUserId())->productId($id)->one();
            if ($cartItem){
                $cartItem->quantity = $quantity;
                $cartItem->save();
            }
        }

        return CartItem::getTotalQuantityForUser(currentUserId());
    }

    public function actionCheckout(){


        $order = new Order();
        $orderAddress = new OrderAddress();

        if(!isGuest()) {
            /**
             * @var User $user
             */
            $user = Yii::$app->user->identity;
            $userAddress = $user->getAddress();

            $order->firstname = $user->firstname;
            $order->lastname = $user->lastname;
            $order->email = $user->email;
            $order->status = Order::STATUS_DRAFT;

            $orderAddress->address = $userAddress->address;
            $orderAddress->city = $userAddress->city;
            $orderAddress->state = $userAddress->state;
            $orderAddress->country = $userAddress->country;
            $orderAddress->zipcode = $userAddress->zipcode;
        }
        $cartItems = CartItem::getItemsForCurrentUser(currentUserId());

        $productQuantity = CartItem::getTotalQuantityForUser(currentUserId());
        $totalPrice = CartItem::getTotalPriceForUser(currentUserId());

        return $this->render('checkout', [
            'order' => $order,
            'orderAddress' => $orderAddress,
            'cartItems' => $cartItems,
            'productQuantity' => $productQuantity,
            'totalPrice' => $totalPrice
        ]);

    }

    /**
     * @throws Exception
     */
    public function actionCreateOrder(){


        $transactionId =  Yii::$app->request->post('transactionId');
        $status = Yii::$app->request->post('status');

        $order = new Order();
        $order->transaction_id = $transactionId;
        $order->total_price = CartItem::getTotalPriceForUser(currentUserId());
        $order->status = $status === 'COMPLETED' ? Order::STATUS_COMPLETED : Order::STATUS_FAILED;
        $order->created_at = time();
        $order->created_by = currentUserId();

        if ($order->load(Yii::$app->request->post())
            && $order->save()
            && $order->saveAddress(Yii::$app->request->post())
            && $order->saveOrderItems()){

                return [
                    'success' => true
                ];


        }else{
            return [
                'success' => false,
                'errors' => $order->errors
            ];
        }













    }
}