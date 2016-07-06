<?php

use app\widgets\AttachmentsAudioWidget;
use app\widgets\AttachmentsPhotoWidget;
use app\widgets\AttachmentsVideoWidget;
use yii\bootstrap\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\ListView;

$this->registerJsFile("//vk.com/js/api/openapi.js");
$this->registerJsFile("@web/js/vkapi.js");

?>
<?php if(!$isAjax): ?>
    <div class="container container-min">
    <?php $form = ActiveForm::begin(['id' => 'newsfeed-form']); ?>
        <div class="row">
            <div class="col-xs-5 form-group">
                <?= $form->field($model, 'q')->textInput(['autofocus' => true]) ?>
            </div>
            <div class="col-xs-5 form-group">
                <?= $form->field($model, 'sq')->textInput() ?>
            </div>
            <div class="col-xs-2 form-group pull-right search_btn_block">
                <?= Html::submitButton('Найти', ['class' => 'btn btn-primary search_btn', 'name' => 'contact-button']) ?>
            </div>
        </div>
    <?php ActiveForm::end(); ?>
    <div class="scroll">
<?php endif; ?>

    <?php if($q && $itemsDataProvider->getCount() > 0): ?>
    <?php echo ListView::widget([
        'dataProvider' => $itemsDataProvider,
        'itemView' => '_newsfeed_item',
        'layout' => "{items}", // {summary}{pager}
    ]); ?>
    <?php endif; ?>

    <?php if($start_from): ?>
    <?php echo Html::a('', Url::current(['start_from' => $start_from, 'q' => $q, 'sq' => $sq]), ['class' => 'jscroll-next']); ?>
    <?php endif; ?>

<?php if(!$isAjax): ?>
    </div>
    </div>
<?php endif; ?>