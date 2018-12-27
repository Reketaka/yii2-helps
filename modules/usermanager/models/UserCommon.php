<?php

namespace reketaka\helps\modules\usermanager\models;


use common\helpers\BaseHelper;
use yii\db\ActiveRecord;
use Yii;

abstract class UserCommon extends ActiveRecord{

    CONST SESSION_KEY_USER_ROLES = 'userRoles';

    /**
     * Используется для записи в сессию текущих ролей пользователя
     * @param $event
     */
    public static function setUserRoleEvent($event){
        /**
         * @var $identity User
         */
        $identity = $event->identity;

        if(Yii::$app->authManager) {
            $userRoles = Yii::$app->authManager->getRolesByUser($identity->id);
            $userRoles = array_keys($userRoles);

            Yii::$app->session->set(self::SESSION_KEY_USER_ROLES, $userRoles);
        }
    }

    /**
     * Возвращает роли пользоватлея
     * @return mixed
     */
    public function getRoles($userId = false){
        if(!$userId) {
            return Yii::$app->session->get(self::SESSION_KEY_USER_ROLES);
        }

        return Yii::$app->authManager->getAssignments($userId);
    }



    abstract public function getUserStatuses();

    /**
     * Возвращает все группы в которых состоит пользователь
     * @return \yii\db\ActiveQuery
     */
    public function getGroups(){
        return $this->hasMany(UserGroup::class, ['id'=>'group_id'])
            ->viaTable(UserInGroup::tableName(), ['user_id'=>'id']);
    }

    public function hasGroup($groupAlias){
        $r = $this->getGroups()->where(['alias'=>$groupAlias])->one();

        if($r){
            return true;
        }

        return false;
    }
}
