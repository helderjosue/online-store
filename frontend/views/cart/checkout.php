<?php
/**
 * @author Helder Josue <helderjosuemata@gmail.com>
 * Date 9/14/23
 */
/**
 * @var Order $order
 * @var OrderAddress $orderAddress
 * @var array $cartItems
 * @var int $productQuantity
 * @var float $totalPrice
 */

use common\models\Order;
use common\models\OrderAddress;
use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;

?>

<?php $form = ActiveForm::begin([
    'action' => [''],
]); ?>

<div class="row">

    <div class="col">
        <div class="card mb-3">
            <div class="card-header">
                <h5>Informação de endereço</h5>
            </div>
            <div class="card-body">
                <?= $form->field($orderAddress, 'address')->textInput(['autofocus' => true]) ?>
                <?= $form->field($orderAddress, 'city')->textInput() ?>
                <?= $form->field($orderAddress, 'state')->textInput() ?>
                <?= $form->field($orderAddress, 'country')->textInput() ?>
                <?= $form->field($orderAddress, 'zipcode')->textInput() ?>

            </div>
        </div>


        <div class="card mt-3">
            <div class="card-header">
                <h5>Informação de conta</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <?= $form->field($order, 'firstname')->textInput(['autofocus' => true]) ?>
                    </div>
                    <div class="col-md-6">
                        <?= $form->field($order, 'lastname')->textInput(['autofocus' => true]) ?>
                    </div>
                </div>
                <?= $form->field($order, 'email') ?>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card">
            <div class="card-header">
                <h4>Sumário da compra</h4>
            </div>
            <div class="card-body">
                <table class="table">
                    <tr>
                        <td> Total de produtos</td>
                        <td class="text-right"><?php echo  Html::tag('span', $productQuantity, ['class' => 'badge badge-danger' ])  ?></td>
                    </tr>
                    <tr>
                        <td> Preço total</td>
                        <td class="text-right"> <?php echo Yii::$app->formatter->asCurrency($totalPrice,'MZN')?></td>
                    </tr>
                </table>
            </div>
            <p class="text-right mr-4">
                <button class="btn btn-secondary">Pagar</button>
            </p>
        </div>

    </div>




</div>

<?php ActiveForm::end(); ?>
