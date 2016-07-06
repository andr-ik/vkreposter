<?php

namespace app\assets;

use yii\web\AssetBundle;

class BootstrapDatepickerAsset extends AssetBundle
{
    public $sourcePath = '@bower/bootstrap-datepicker/dist';

    public $js = [
        'js/bootstrap-datepicker.min.js',
        'locales/bootstrap-datepicker.ru.min.js'
    ];

    public $css = [
        'css/bootstrap-datepicker.min.css',
        'css/bootstrap-datepicker.standalone.min.css'
    ];

    public $depends = [
        'yii\web\JqueryAsset',
    ];
}
