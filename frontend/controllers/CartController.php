<?php

namespace frontend\controllers;


use common\models\CartItem;
use common\models\Product;
use Yii;
use yii\filters\ContentNegotiator;
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
            ]
        ];
    }

    public function actionIndex(){

        if(Yii::$app->user->isGuest){
            //get the items from session
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

   public function actionAdd(){
        $id = Yii::$app->request->post('id');
        $product = Product::find()->id($id)->published()->one();
        if (!$product){
            throw new NotFoundHttpException('Artigo nao encontrado');
        }

       if(Yii::$app->user->isGuest){
           //get the items from session
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
}