<?php

namespace reketaka\helps\common\validators;

use function date_create_from_format;
use reketaka\helps\common\helpers\Bh;
use Yii;
use yii\validators\Validator;

class DateFormatValidator extends Validator{

    public function validateAttribute($model, $attribute)
    {
        if(!$date = date_create_from_format('d.m.Y', $model->$attribute)) {
            $this->addError($model, $attribute, Yii::t('errors', 'date_format_error'));
            return false;
        }

        $model->$attribute = $date->format("Y-m-d");
        return true;
    }

}