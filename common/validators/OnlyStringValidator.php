<?php

namespace reketaka\helps\common\validators;

use reketaka\helps\common\helpers\Bh;
use yii\validators\Validator;

class OnlyStringValidator extends Validator{

    public function validateAttribute($model, $attribute)
    {
        $model->$attribute = Bh::onlyString($model->$attribute);
        return true;
    }

}