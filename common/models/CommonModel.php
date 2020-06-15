<?php

namespace reketaka\helps\common\models;

use yii\base\Model;

class CommonModel extends Model{

    public function flashMessage($msg, $type = 'success'){
        Yii::$app->session->setFlash($type, $msg);
        return true;
    }

}