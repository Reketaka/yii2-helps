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
    public $behaviorAlias = true;

    public static function tableName()
    {
        return Module::$tablePrefix."price_type";
    }

    public function rules(){
        return [
            [['default'], 'integer'],
            [['title', 'alias', 'description'], 'string'],
            [['uid'], 'unique', 'skipOnEmpty'=>true],
            [['uid'], 'default', 'value'=>null]
        ];
    }

    public function attributeLabels()
    {
        return [
            'title'=>Module::t('app', 'title'),
            'alias'=>Module::t('app','alias'),
            'description'=>Module::t('app', 'description'),
            'uid'=>Module::t('app', 'uid'),
            'default'=>Module::t('app', 'default'),
            'created_at'=>Module::t('app', 'created at'),
            'updated_at'=>Module::t('app', 'updated at'),
        ];
    }

    public function beforeDelete()
    {
        if (!parent::beforeDelete()) {
            return false;
        }

        ItemPrice::deleteAll(['price_type_id'=>$this->id]);

        return true;
    }


}