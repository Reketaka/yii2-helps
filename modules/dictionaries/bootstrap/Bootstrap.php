<?php

namespace reketaka\helps\modules\dictionaries\bootstrap;

use common\helpers\BaseHelper;
use reketaka\helps\modules\dictionaries\models\DictionariesValue;
use yii\base\BootstrapInterface;
use yii\base\Event;
use yii\db\ActiveRecord;
use yii\web\Application;
use yii\web\User;

class Bootstrap implements BootstrapInterface
{
    /**
     * @inheritdoc
     */
    public function bootstrap($app)
    {
        Event::on(DictionariesValue::class, ActiveRecord::EVENT_BEFORE_INSERT, ['reketaka\helps\modules\dictionaries\models\DictionariesValue', 'refreshCache']);
        Event::on(DictionariesValue::class, ActiveRecord::EVENT_BEFORE_UPDATE, ['reketaka\helps\modules\dictionaries\models\DictionariesValue', 'refreshCache']);

    }
}