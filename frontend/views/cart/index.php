<?php
/**
 * @author Helder Josue <helderjosuemata@gmail.com>
 * Date 9/9/23
 */
/** @var array $items */

use common\models\Product;

?>

<div class="card">
    <div class="card-header">
        <h3>Produtos do seu carrinho</h3>
    </div>
    <div class="card-body p-0">
        <table class="table table-hover">
            <thead>
            <tr>
                <th>Produto</th>
                <th>Imagem</th>
                <th>Preço Unitário</th>
                <th>Quantidade</th>
                <th>Total</th>
                <th>Acção</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($items as $item): ?>

                <tr>
                    <td><?php echo $item['name'] ?></td>
                    <td>
                        <img src="<?php echo Product::formatImageUrl($item['image']) ?>"
                             width="100px"
                             alt="<?php echo $item['name']?>">
                    </td>
                    <td><?php echo $item['price'] ?></td>
                    <td><?php echo $item['quantity'] ?></td>
                    <td><?php echo $item['total_price'] ?></td>
                    <td>
                        <?php echo \yii\helpers\Html::a('Remover', ['/cart/delete', 'id' => $item['id']], [
                            'class' => 'btn btn-outline-danger btn-sm',
                            'data-method' => 'post',
                            'data-confirm' => 'Tem certeza que deseja remover este produto do carrinho?'
                        ])

                        ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
       <div class="card-body text-right">
           <?php echo \yii\helpers\Html::a('Realizar Pagamento', ['/cart/checkout'], [
               'class' => 'btn btn-primary',
               'data-method' => 'post'
           ])
           ?>
       </div>
    </div>
</div>
