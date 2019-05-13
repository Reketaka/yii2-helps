<?php

namespace reketaka\helps\modules\dictionaries\models;

use reketaka\helps\common\helpers\Bh;
use Yii;

/**
 * This is the model class for table "dictionaries_name".
 *
 * @property int $id
 * @property string $title
 * @property string $alias
 * @property string $description
 *
 * @property DictionariesValue[] $dictionariesValues
 */
class DictionariesName extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'dictionaries_name';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['alias'], 'unique'],
            [['title', 'alias', 'description'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'title' => Yii::t('app', 'Title'),
            'alias' => Yii::t('app', 'Alias'),
            'description' => Yii::t('app', 'Description'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDictionariesValues()
    {
        return $this->hasMany(DictionariesValue::className(), ['dictionary_id' => 'id']);
    }

    public function beforeDelete()
    {
        if (!parent::beforeDelete()) {
            return false;
        }

        Bh::deleteAll(DictionariesValue::class, ['dictionary_id'=>$this->id]);

        return true;
    }
}
