<?php

namespace reketaka\helps\common\models;

use reketaka\helps\common\behaviors\AliasBehavior;
use yii\base\Exception;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\helpers\StringHelper;
use yii\log\Logger;

class CommonRecord extends ActiveRecord{

    public $behaviorAlias = false;
    public $behaviorAliasEvent = ActiveRecord::EVENT_BEFORE_VALIDATE;

    public $behaviorTimestamp = false;

    /**
     * Время кеширования для вызова функции getBy
     * @var int
     */
    public $cacheOneTime = 3600;

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        if ($this->behaviorTimestamp) {
            $behaviors = array_merge($behaviors, [
                [
                    'class' => TimestampBehavior::class,
                    'value' => \Yii::$app->formatter->asDatetime(time(), \Yii::$app->params['dateControlSave'][\kartik\datecontrol\Module::FORMAT_DATETIME]),
                ]
            ]);
        }

        if ($this->behaviorAlias) {
            $behaviors = array_merge($behaviors, [
                [
                    'class' => AliasBehavior::class,
                    'event' => $this->behaviorAliasEvent
                ]
            ]);

        }

        return $behaviors; // TODO: Change the autogenerated stub
    }

    /**
     * Пытается для найти дочерную модель по переданным данным $data.
     * В противном случае создает этуже модель использую этиже данные
     * @param $data
     * @param $validateAndSave
     * @return CommonRecord|null|static
     */
    public static function getOrCreate($data, $validateAndSave = true){
        if($elem = static::findOne($data)){
            return $elem;
        }

        $elem = new static();
        $elem->attributes = $data;

        if($validateAndSave) {
            if (!$elem->validate()) {
                \Yii::error($elem->errors, __METHOD__);
                throw new Exception(implode(' ', $elem->getFirstErrors()));
            }
            $elem->save();
        }

        return $elem;
    }

    /**
     * Аналог функции findAll только результаты кушируются
     * @param $data
     * @return CommonRecord|array|null|ActiveRecord
     */
    public static function getBy($data){
        if(!is_array($data)){
            return static::find()->cache(0)->where(['id'=>$data])->one();
        }

        return static::find()->where($data)->cache()->one();
    }

    public static function getsBy($data){
        return static::find()->where($data)->cache()->all();
    }

    public static function basename(){
        return StringHelper::basename(static::class);
    }

    public static function bn(){
        return self::basename();
    }

    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }

        if($insert && \Yii::$app->id != 'app-console'){
            $this->markOwner();
        }

        return true;
    }

    /**
     * При создании модели отмечает владельца (создателя)
     */
    public function markOwner(){

        if(!array_key_exists('owner_id', $this->attributes) || \Yii::$app->user->isGuest){
            return false;
        }

        $userId = \Yii::$app->user->getId();

        $this->owner_id = $userId;

    }

    /**
     * Возвращает модель хозяина модели
     * @return bool|User|null
     */
    public function owner(){
        if(!array_key_exists('owner_id', $this->attributes) || \Yii::$app->user->isGuest){
            return null;
        }

        return User::findOne(['user_id'=>$this->owner_id]);
    }

    /**
     * Если есть ошибки валидации записывает их в лог
     */
    public function ifErrorLog(){
        if($this->hasErrors()){
            \Yii::getLogger()->log($this->errors, Logger::LEVEL_TRACE);
        }
    }

    public function getErrorsString($delemiter = ', '){

        $text = [];
        foreach($this->getErrors() as $attribute=>$errors){
            $text[] = implode($delemiter, $errors);
        }
        return implode($delemiter, $text);
    }

    public static function findOneForUpdate($whereData) {
        $sql = self::find()
            ->where($whereData)
            ->createCommand()
            ->getRawSql();

        return self::findBySql($sql . ' FOR UPDATE')->one();
    }



}