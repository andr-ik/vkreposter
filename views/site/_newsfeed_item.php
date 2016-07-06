<?php

use app\widgets\AttachmentsAudioWidget;
use app\widgets\AttachmentsPhotoWidget;
use app\widgets\AttachmentsVideoWidget;

?>
<div class="row_item"
     data-id="<?= $model['id']; ?>"
     data-owner_id="<?= $model['owner_id']; ?>"
     data-from_id="<?= $model['from_id']; ?>"
     data-date="<?= $model['date']; ?>"
     data-signer="<?= $model['signer']; ?>"
>
    <label>
    <input type="checkbox" class="row_checkbox">
    <div class="panel panel-default">
        <div class="panel-body">
            <?php if(isset($model['text'])): ?>
                <div class="row__title <?php echo mb_strlen($model['text']) > 300 ? 'mini' : ''; ?>">
                    <?= $model['text']; ?>
                </div>
            <?php endif; ?>
            <?php if(isset($model['attachments'])): ?>
                <?php echo AttachmentsPhotoWidget::widget([
                    'attachments' => $model['attachments']
                ]); ?>
                <?php echo AttachmentsVideoWidget::widget([
                    'attachments' => $model['attachments']
                ]); ?>
                <?php echo AttachmentsAudioWidget::widget([
                    'attachments' => $model['attachments']
                ]); ?>
            <?php endif; ?>
        </div>
        <div class="panel-footer">
            <div class="row">
                <div class="col-xs-6 pull-left row_social">
                    <div class="row_social_item">
                        <span class="glyphicon glyphicon-heart" aria-hidden="true"></span>
                        <?= $model['likes']['count']; ?>
                    </div>
                    <div class="row_social_item">
                        <span class="glyphicon glyphicon-bullhorn" aria-hidden="true"></span>
                        <?= $model['reposts']['count']; ?>
                    </div>
                    <div class="row_social_item">
                        <span class="glyphicon glyphicon-comment" aria-hidden="true"></span>
                        <?= $model['comments']['count']; ?>
                    </div>
                </div>
                <div class="col-xs-3 pull-right">
                    <button type="button" class="btn btn-primary row_btn">Забрать себе</button>
                </div>
            </div>
        </div>
    </div>
    </label>
</div>