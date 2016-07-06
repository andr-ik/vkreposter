<?php

namespace app\assets;

use yii\web\AssetBundle;

class CombodateAsset extends AssetBundle
{
    public $sourcePath = '@bower/combodate/src';

    public $js = [
        'combodate.js'
    ];

    public $depends = [
        'app\assets\MomentAsset',
    ];
}
