<?php

namespace reketaka\helps\common\validators;

use reketaka\helps\common\helpers\Bh;
use yii\validators\Validator;

class OnlyInteger extends Validator{

    public function validateAttribute($model, $attribute)
    {
        $model->$attribute = Bh::onlyNumbers($model->$attribute);
        return true;
    }

}