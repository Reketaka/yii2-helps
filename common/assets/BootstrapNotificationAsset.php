<?php

namespace reketaka\helps\common\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class BootstrapNotificationAsset extends AssetBundle
{
    public $sourcePath = '@npm/bootstrap-notify';
    public $js = [
        'bootstrap-notify.min.js'
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}
