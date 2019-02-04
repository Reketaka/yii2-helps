<?php

namespace reketaka\helps\modules\adminMenu\bootstrap;

use common\helpers\BaseHelper;
use reketaka\helps\modules\adminMenu\models\MenuDynamic;
use yii\base\BootstrapInterface;
use yii\web\User;

class Bootstrap implements BootstrapInterface
{
    /**
     * @inheritdoc
     */
    public function bootstrap($app)
    {

        $app->user->on(User::EVENT_AFTER_LOGIN, function($event){
            MenuDynamic::clearCacheMenuForUser($event->sender->getId());
            MenuDynamic::clearCacheMenuAll();
        });
    }
}