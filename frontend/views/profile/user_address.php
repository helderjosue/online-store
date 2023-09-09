<?php
use common\models\UserAddress;
use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;
use yii\web\View;


/** @var UserAddress $userAddress */
/** @var View $this */


?>



<?php if(isset($success) && $success): ?>
    <div class="alert alert-success">
        O seu endereco foi actualizado com sucesso!
    </div>
<?php endif; ?>

<?php $addressForm = ActiveForm::begin([
    'action' => ['/profile/update-address'],
    'options' => [
        'data-pjax' => 1
    ]
]); ?>

<?= $addressForm->field($userAddress, 'address')->textInput(['autofocus' => true]) ?>
<?= $addressForm->field($userAddress, 'city')->textInput(['autofocus' => true]) ?>
<?= $addressForm->field($userAddress, 'state')->textInput(['autofocus' => true]) ?>
<?= $addressForm->field($userAddress, 'country')->textInput(['autofocus' => true]) ?>
<?= $addressForm->field($userAddress, 'zipcode')->textInput(['autofocus' => true]) ?>
<?= Html::submitButton('Actualizar', ['class' => 'btn btn-primary']) ?>
<?php ActiveForm::end(); ?>
