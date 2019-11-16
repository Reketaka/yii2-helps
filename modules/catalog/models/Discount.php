<?php

namespace reketaka\helps\modules\catalog\models;

use reketaka\helps\common\models\CommonRecord;
use reketaka\helps\modules\catalog\Module;
use Yii;

/**
 * This is the model class for table "discount".
 *
 * @property int $id
 * @property string $title
 * @property string $alias
 * @property int $value
 * @property int $active
 * @property string $created_at
 * @property string $updated_at
 */
class Discount extends CommonRecord
{
    public $behaviorTimestamp = true;
    public $behaviorAlias = true;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'discount';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['value', 'active'], 'integer'],
            [['active', 'value'], 'default', 'value'=>0],
            [['title', 'alias'], 'string'],
            [['value'], 'integer', 'min'=>1, 'max'=>99]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Module::t('app', 'id'),
            'title' => Module::t('app', 'title'),
            'alias' => Module::t('app', 'alias'),
            'value' => Module::t('app', 'value'),
            'active' => Module::t('app', 'active'),
            'created_at' => Module::t('app', 'created at'),
            'updated_at' => Module::t('app', 'updated at'),
        ];
    }
}
