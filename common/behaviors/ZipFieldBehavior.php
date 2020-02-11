<?php

namespace reketaka\helps\common\behaviors;

use reketaka\helps\common\helpers\Bh;
use Yii;
use yii\base\Behavior;
use yii\base\Event;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\db\Query;
use const YII2_PATH;

/**
 * Class ZipFieldBehavior
 *
 * ```php
 * public function behaviors()
 * {
 * return array_merge(parent::behaviors(), [
 *      'zipField'=> [
 *           'class'=>'common\behaviors\ZipFieldBehavior',
 *           'tableName'=>'payment_attempt_log_zip',
 *           'fieldName'=>'log',
 *           'findFieldOnLoad'=>true
 *          ]
 *      ]);
 * }
 * ```
 *
 */
class ZipFieldBehavior extends Behavior{

    public $tableName;
    public $fieldName;
    public $findFieldOnLoad = false;
    public $useCompress = true;

    public function events()
    {
        return [
            ActiveRecord::EVENT_AFTER_FIND=>'afterFind',
            ActiveRecord::EVENT_AFTER_UPDATE=>'beforeUpdate',
            ActiveRecord::EVENT_AFTER_INSERT=>'beforeUpdate',
            ActiveRecord::EVENT_BEFORE_DELETE=>'beforeDelete'
        ];
    }

    public function afterFind($event){
        if(!$this->findFieldOnLoad){
            return false;
        }

        $valueQuery = (new Query)
            ->select([
                $this->useCompress?new Expression("UNCOMPRESS(value) as 'value'"):new Expression("value as value")
            ])
            ->from($this->tableName)
            ->where(['model_id'=>$event->sender->id])
            ->createCommand()
            ->queryOne();

        $event->sender->{$this->fieldName} = $valueQuery['value'];

        return true;
    }

    /**
     * @param $event Event
     * @return bool
     * @throws \yii\db\Exception
     */
    public function beforeDelete($event){

        $sender = $event->sender;

        \Yii::$app->db->createCommand()->delete($this->tableName, ['model_id'=>$sender->id])->execute();

        return true;
    }

    /**
     * @param $event
     * @return bool
     * @throws \yii\db\Exception
     */
    public function beforeUpdate($event){
        $sender = $event->sender;
        $value = $sender->{$this->fieldName};
        $value = \Yii::$app->db->quoteValue($value);

        \Yii::$app->db->createCommand()->upsert($this->tableName, [
            'model_id'=>$sender->id,
            'value'=>$this->useCompress?new Expression("COMPRESS($value)"):new Expression("$value")
        ], [
            'value'=>$this->useCompress?new Expression("COMPRESS($value)"):new Expression("$value")
        ])->execute();


        return true;
    }
}