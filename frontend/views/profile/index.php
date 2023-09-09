<?php
use common\models\User;
use common\models\UserAddress;
use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;
use yii\web\View;

/** @var User $user */
/** @var UserAddress $userAddress */
/** @var View $this */


?>


<div class="row">
    <div class="col">
        <div class="card">
            <div class="card-header">
                Informacao de endereco
            </div>
            <div class="card-body">
                <?php \yii\widgets\Pjax::begin([
                    'enablePushState' => false
                ])?>
                <?php echo $this->render('user_address', [
                    'userAddress' => $userAddress
                ]) ?>
                <?php \yii\widgets\Pjax::end()?>

            </div>
        </div>
    </div>

    <div class="col">
        <div class="card">
            <div class="card-header">
                Informacao de conta
            </div>
            <div class="card-body">
                <?php \yii\widgets\Pjax::begin([
                    'enablePushState' => false
                ])?>
                <?php echo $this->render('user_account', [
                    'user' => $user
                ]) ?>
                <?php \yii\widgets\Pjax::end()?>
            </div>
        </div>

    </div>
</div>



