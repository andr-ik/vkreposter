<?php

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

AppAsset::register($this);

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
    <div class="wrap">
        <?php
        NavBar::begin([
            //'brandLabel' => 'My Company',
            //'brandUrl' => Yii::$app->homeUrl,
            'options' => [
                'class' => 'navbar-inverse navbar-fixed-top',
            ],
        ]);
        echo "<div class='select_post_nav'>";
        echo "<a href='#' class='btn btn-link'></a>";
        echo "</div>";
        echo Nav::widget([
            'options' => ['class' => 'navbar-nav navbar-right'],
            'items' => [
                Yii::$app->user->isGuest ? (
                    ['label' => 'Войти', 'url' => ['/site/login', 'service' => 'vkontakte']]
                ) : (
                    '<li>'
                    . Html::beginForm(['/site/logout'], 'post', ['class' => 'navbar-form'])
                    . Html::submitButton(
                        'Выйти (' . Yii::$app->user->identity->username . ')',
                        ['class' => 'btn btn-link']
                    )
                    . Html::endForm()
                    . '</li>'
                )
            ],
        ]);
        NavBar::end();
        ?>

        <div class="container">
            <?php
            if (Yii::$app->getSession()->hasFlash('error')) {
                echo '<div class="alert alert-danger">'.Yii::$app->getSession()->getFlash('error').'</div>';
            }
            ?>

            <?= $content ?>
        </div>
    </div>
<?php $this->endBody() ?>
<div class="modal fade post_modal" role="dialog" data-id="">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Разместить пост</h4>
            </div>
            <div class="modal-body row">
                <div class="col-xs-7 modal-sidebar-right">
                    <textarea name="post[text]" class="post_modal_text"></textarea>
                    <textarea name="post[attachments]" class="post_modal_attachments"></textarea>
                    <input type="hidden" class="post_modal_date" >
                </div>
                <div class="col-xs-5 modal-sidebar-left">
                    <div class="owner_id">
                        <select class="owner_id_select">
                            <option>Куда публиковать?</option>
                        </select>
                    </div>
                    <label><input type="checkbox" class="from_group_check" checked="checked"> Публиковать от имени группы</label>
                    <label><input type="checkbox" class="signed_check"> Добавить подпись</label>
                    <label><input type="checkbox" class="publish_date_check"> Публиковать потом</label>
                    <div class="publish_date_block modal-center">
                        <div class="datepicker-select" data-provide="datepicker-inline"></div>
                        <input class="timepicker-select" type="text" data-format="HH:mm" data-template="HH : mm" name="datetime">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default post_modal_close">Отменить</button>
                <button type="button" class="btn btn-primary post_modal_save">Разместить</button>
            </div>
        </div>
    </div>
</div>
</body>
</html>
<?php $this->endPage() ?>
