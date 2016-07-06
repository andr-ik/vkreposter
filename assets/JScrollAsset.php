<?php

namespace app\assets;

use yii\web\AssetBundle;

class JScrollAsset extends AssetBundle
{
    public $sourcePath = '@bower/jscroll';

    public $js = [
        'jquery.jscroll.min.js',
    ];

    public $depends = [
        'yii\web\JqueryAsset',
    ];
}
