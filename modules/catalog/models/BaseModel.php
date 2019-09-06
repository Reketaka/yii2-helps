<?php

namespace reketaka\helps\modules\catalog\models;

use reketaka\helps\common\models\CommonRecord;

class BaseModel extends CommonRecord{

    public static function getByUid($uid){
        return static::findOne(['uid'=>$uid]);
    }





}