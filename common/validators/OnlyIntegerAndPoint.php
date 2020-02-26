<?php

namespace reketaka\helps\common\validators;

use yii\validators\Validator;

class OnlyIntegerAndPoint extends Validator{

    public function validateAttribute($model, $attribute)
    {
        $model->$attribute = preg_replace("/[^.0-9]/", '', $model->$attribute);
        return true;
    }

}