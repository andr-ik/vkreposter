<?php

use yii\bootstrap\Html;

?>
<?php if($items): ?>
<div class="row_image">
    <?php foreach($items as $item){
    echo Html::img($item['video']['photo_320'], [
        'class' => 'attachment',
        'data-attachment' => $item['type'].$item['video']['owner_id'].'_'.$item['video']['id']
    ]);
    } ?>
</div>
<?php endif; ?>

