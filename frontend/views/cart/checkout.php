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
use yii\helpers\Url;

?>
<script src="https://www.paypal.com/sdk/js?client-id=ASFZffsL6n-6i12QjwScdfAjeFYe0ujYCJrU27wr6RL0z0s1-4dXGMwcAmak37jncFLf7Ktny3e7dWWL&currency=USD"></script>



<div class="row">

    <div class="col">
        <?php $form = ActiveForm::begin([
            'id' => 'checkout-form',
        ]); ?>
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
        <?php ActiveForm::end(); ?>
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
            <div id="paypal-button-container">

            </div>
<!--            <p class="text-right mr-4">-->
<!--                <button class="btn btn-secondary">Pagar</button>-->
<!--            </p>-->
        </div>

    </div>




</div>





<script>
    // Render the PayPal button into #paypal-button-container
    paypal.Buttons({
        // Call your server to set up the transaction
        createOrder: function(data, actions) {
            return actions.order.create({
                purchase_units: [{
                    amount: {
                        value: <?php echo $totalPrice?>
                    }
                }]
            })
        },

        onApprove: function(data, actions) {
            return actions.order.capture().then(function (details){
                const $form = $('#checkout-form');
                const data = $form.serializeArray();

                // console.log(typeof(data));
                data.push({
                    name: 'transactionId',
                    value: details.id
                });
                data.push({
                    name: 'status',
                    value: details.status
                });
                $.ajax({
                    method: 'post',
                    url: '<?php echo Url::to(['/cart/create-order'])?>',
                    data: data,
                    success:  function (res){
                        console.log(res);
                    }
                })
                alert('Transacao concluida por ' + details.payer.name.given_name);
            })
        }

    }).render('#paypal-button-container');
</script>