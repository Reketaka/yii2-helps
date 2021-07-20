<?php

namespace reketaka\helps\common\models;

use reketaka\helps\common\helpers\Bh;
use Throwable;
use yii\helpers\ArrayHelper;
use Yii;

class Request extends \yii\httpclient\Request{

    /**
     * Выполняет запрос $request оборачивает вызов в try в случае если запрос падает записывает в логи exception с параметрами вызова
     * записывает результаты ответа платежки в переданную переменную $result
     * возвращает укзанную переменную из результаты $result
     * @param \yii\httpclient\Request $request
     * @param $field
     * @return false|string|null
     */
    public function trySendAndGetField($field, &$result = null, &$lymdResponse = null){

        $response = null;
        $exception = null;
        $formUrl = null;
        try {
            $response = $this->send();


            $result = $response->data ?? [];

            $formUrl = ArrayHelper::getValue($result, $field);

            $lymdResponse = $response;

        } catch (Throwable $e) {
            $exception = $e;
        }

        if($response && !$response->isOk && !$exception){
            Yii::error(['Network error', $this->getData(), $this, $result, $response, $exception]);
            return false;
        }

        if (!$formUrl && $exception) {
            Yii::error(['Network error', $this->getData(), $this, $result, $response, $exception]);
            return false;
        }

        if(!$formUrl){
            Yii::error(['Cant find property', $this->getData(), $this, $result, $response, $exception]);
            return false;
        }

        return $formUrl;
    }

}