<?php

namespace reketaka\helps\modules\catalog\models;

use reketaka\helps\modules\catalog\Module;

/**
 * Class PriceType
 * @package reketaka\helps\modules\catalog\models
 *
 * @property integer $default
 * @property string $title
 * @property string $alias
 * @property string $description
 * @property string $uid
 */
class PriceType extends BaseModel {

    public $behaviorTimestamp = true;

    public static function tableName()
    {
        return Module::$tablePrefix."price_type";
    }

    public function rules(){
        return [
            [['default'], 'integer'],
            [['title', 'alias', 'description'], 'string'],
            [['uid'], 'unique', 'skipOnEmpty'=>true]
        ];
    }

    public function attributeLabels()
    {
        return [
            'title'=>\Yii::t('app', 'title'),
            'alias'=>\Yii::t('app','alias'),
            'description'=>\Yii::t('app', 'description'),
            'uid'=>\Yii::t('app', 'uid')
        ];
    }


}