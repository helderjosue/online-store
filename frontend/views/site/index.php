<?php

/** @var yii\web\View $this */
/** @var \yii\data\ActiveDataProvider $dataProvider */

use yii\bootstrap4\LinkPager;
use yii\widgets\ListView;

$this->title = 'BreShopp Online Store';
?>
<div class="site-index">
    <div class="body-content">
        <?php echo ListView::widget(
            [
                'dataProvider' => $dataProvider,
                'itemView' => '_product_item',
                'layout' => '<div class="row">{items}</div>{pager} ',
                'itemOptions' => [
                    'class' => 'row col-lg-4 col-md-6 mb-4 product-item'
                ],
                'pager' => [
                        'class' => LinkPager::class
                ]
            ]
        )?>
    </div>
</div>
