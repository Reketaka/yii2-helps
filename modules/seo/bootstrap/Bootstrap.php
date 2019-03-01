<?php

namespace reketaka\helps\modules\seo\bootstrap;

use common\helpers\BaseHelper;
use yii\base\BootstrapInterface;
use yii\base\Event;
use yii\web\Application;
use yii\web\User;
use yii\web\View;

class Bootstrap implements BootstrapInterface
{
    /**
     * @inheritdoc
     */
    public function bootstrap($app)
    {


        $app->view->on(View::EVENT_AFTER_RENDER, ['reketaka\helps\modules\seo\models\Seo', 'setMeta']);

    }
}