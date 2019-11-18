<?php

namespace reketaka\helps\modules\usermanager\models;


use User;
use yii\db\ActiveRecord;
use Yii;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\auth\QueryParamAuth;

abstract class UserCommon extends ActiveRecord{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;

    CONST SESSION_KEY_USER_ROLES = 'userRoles';

    public $password;

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
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Возвращает роли пользоватлея
     * @return mixed
     */
    public function getRoles($userId = false){
        if(!$userId) {
            return !($userRoles = Yii::$app->session->get(self::SESSION_KEY_USER_ROLES))?[]:$userRoles;
        }

        return !($userRoles = Yii::$app->authManager->getAssignments($userId))?[]:$userRoles;
    }

    abstract public static function getUserStatuses();

    public function getUserInGroups(){
        return $this->hasMany(UserInGroup::class, ['user_id'=>'id']);
    }

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

    /**
     * Создает нового пользователя
     */
    public function create(){
        $this->setPassword($this->password);
        $this->generateAuthKey();
        $this->save();

        if($this->hasErrors()){
            return false;
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        if($type == HttpBearerAuth::class) {
            list($username, $password) = explode(":", base64_decode($token));
            if(!$user = static::findOne(['username'=>$username])){
                return null;
            }

            if(!$user->validatePassword($password)){
                return null;
            }

            return $user;
        }

        if($type == QueryParamAuth::class){
            if(!$user = static::findOne(['access_token'=>$token])){
                return null;
            }

            return $user;
        }
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }
    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    public function generateAccessToken(){
        $this->access_token = Yii::$app->security->generateRandomString();

        while(true){
            $accessToken = Yii::$app->security->generateRandomString(32);

            if(!static::findOne(['access_token'=>$accessToken])){
                $this->access_token = $accessToken;
                break;
                return true;
            }
        }

        return true;
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }
}
