<?php

namespace dogrocker\echarts;

use yii\web\AssetBundle;

class EchartsAsset extends AssetBundle
{
    public $sourcePath = '@npm/echarts/dist';
    public $js = [
        'echarts.js'
    ];
}
