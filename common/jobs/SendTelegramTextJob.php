<?php

namespace reketaka\helps\common\jobs;


use SonkoDmitry\Yii\TelegramBot\Component;
use yii\base\BaseObject;
use yii\queue\JobInterface;

class SendTelegramTextJob extends BaseObject implements JobInterface
{

    public $message;
    public $type;

    public function execute($queue)
    {

        $bot = new Component([
            'apiToken'=>\Yii::$app->params['telegramBotToken']
        ]);
        $chatId = \Yii::$app->params['telegramChatId'];

        $bot->sendMessage($chatId, $this->message, $this->type);

        return true;
    }
}