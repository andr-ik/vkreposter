<?php

namespace app\assets;

use yii\web\AssetBundle;

class JqueryTimeagoAsset extends AssetBundle
{

    public $sourcePath = '@bower/jquery-timeago';

    public $js = [
        'jquery.timeago.js',
        'locales/jquery.timeago.ru.js'
    ];

    public $depends = [
        'yii\web\JqueryAsset',
    ];
}
