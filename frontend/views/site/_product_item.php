<?php

/**
 * @var \common\models\Product $model
 */
?>
<!--            products contrainer-->
<div class="col mb-5">
    <div class="card h-100">
        <!-- Product image-->
<center>
    <img class="card-img-top" style=" width: 60%; height: 100%" src="<?php echo $model->getImageUrl()?>" alt="..." />

</center>        <!-- Product details-->
        <div class="card-body">
            <h4 class="card-title">
                <a href="#"><?php echo $model->name?></a>
            </h4>
            <h5> <?php echo Yii::$app->formatter->asCurrency($model->price,'MZN')?></h5>
            <div class="card-text">
                <?php echo $model->getShortDescription()?>
            </div>
        </div>
        <!-- Product actions-->
        <div class="card-footer text-right">
            <a href="<?php echo \yii\helpers\Url::to('/cart/add')?>" class="btn btn-primary btn-add-to-cart">
                Adicionar ao carinho
            </a>
        </div>
    </div>
</div>
            <!--            end of products container-->