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


}
