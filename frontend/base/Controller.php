<?php

namespace frontend\base;


use common\models\CartItem;

/**
 * Class Controller
 * @author Helder Josue <helderjosuemata@gmail.com>
 * Date 9/9/23
 */
class Controller extends \yii\web\Controller
{

    public function beforeAction($action)
    {
        $this->view->params['cartItemCount'] = CartItem::findBySql(
            "SELECT SUM(quantity) 
                    FROM cart_items 
                    WHERE created_by = :userId", ['userId' => \Yii::$app->user->id]
                                        )->scalar();
//        $this->view->params['cartItemCount'] = CartItem::find()->userId(\Yii::$app->user->id)->count();
        return parent::beforeAction($action); // TODO: Change the autogenerated stub
    }

}