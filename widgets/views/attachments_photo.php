<?php

use yii\bootstrap\Html;

?>
<?php if($items): ?>
<div class="row_image">
    <?php foreach($items as $item) {
        echo Html::img($item['photo']['photo_130'], [
            'class' => 'attachment',
            'data-attachment' => $item['type'] . $item['photo']['owner_id'] . '_' . $item['photo']['id']
        ]);
    } ?>
</div>
<?php endif; ?>