<?php

namespace app\assets;

use yii\web\AssetBundle;

class MomentAsset extends AssetBundle
{
    public $sourcePath = '@bower/moment';

    public $js = [
        'min/moment.min.js',
        'min/locales.min.js',
        'locale/ru.js'
    ];

    public $depends = [
    ];
}
