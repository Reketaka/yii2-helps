<?php

namespace reketaka\helps\modules\dictionaries\models;

use common\helpers\BaseHelper;
use Yii;
use yii\base\Event;

/**
 * This is the model class for table "dictionaries_value".
 *
 * @property int $id
 * @property int $dictionary_id
 * @property string $value
 *
 * @property DictionariesName $dictionary
 */
class DictionariesValue extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'dictionaries_value';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['dictionary_id'], 'integer'],
            [['value'], 'string'],
            [['dictionary_id'], 'exist', 'targetRelation' => 'dictionary', 'skipOnError' => false, 'skipOnEmpty' => false],
            [['value'], function($attr){
                if(self::findOne(['dictionary_id'=>$this->dictionary_id, 'value'=>$this->value])){
                    $this->addError($attr, "Duplicate Value in dictionary");
                    return false;
                }

                return true;
            }]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'dictionary_id' => Yii::t('app', 'Dictionary ID'),
            'value' => Yii::t('app', 'Value'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDictionary()
    {
        return $this->hasOne(DictionariesName::className(), ['id' => 'dictionary_id']);
    }

    public function refreshCache(Event $event){

        try{
            DictionariesHelper::clearCache($event->sender->dictionary->alias);
        }catch (\Exception $exception){
            Yii::error($exception->getMessage(), __METHOD__);
            return false;
        }

        return true;
    }
}
