<?php

/**
 * @var \common\models\Product $model
 */
?>
<!--            products contrainer-->
<div class="col mb-5">
    <div class="card h-100">
        <!-- Product image-->
        <img class="card-img-top" src="<?php echo $model->getImageUrl()?>" alt="..." />
        <!-- Product details-->
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
            <a href="#" class="btn btn-primary">
                Adicionar ao carinho
            </a>
        </div>
    </div>
</div>
            <!--            end of products container-->