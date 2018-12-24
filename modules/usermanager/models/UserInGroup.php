<?php

namespace reketaka\helps\modules\usermanager\models;

use yii\db\ActiveRecord;

/**
 * Class UserInGroup
 * @package reketaka\helps\modules\usermanager\models
 * @property integer $user_id
 * @property integer $group_id
 */
class UserInGroup extends ActiveRecord{

    public static function tableName()
    {
        return 'user_in_group';
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser(){

        $modelClass = \Yii::$app->getModule('usermanager')->userModelClass;

        return $this->hasOne($modelClass::className(), ['id'=>'user_id']);
    }
}