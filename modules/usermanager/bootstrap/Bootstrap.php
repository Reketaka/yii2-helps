<?php

namespace reketaka\helps\modules\usermanager\bootstrap;

use common\helpers\BaseHelper;
use yii\base\BootstrapInterface;
use yii\base\Event;
use yii\web\Application;
use yii\web\User;

class Bootstrap implements BootstrapInterface
{
    /**
     * @inheritdoc
     */
    public function bootstrap($app)
    {
        $module = $app->getModule('usermanager');


        $app->user->on(User::EVENT_AFTER_LOGIN, ['reketaka\helps\modules\usermanager\models\UserCommon', 'setUserRoleEvent']);
    }
}