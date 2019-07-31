<?php
namespace reketaka\helps\common\models;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;

/**
 * Форма авторизации в админке
 *
 */
class LoginForm extends Model
{
    public $username;
    public $password;
    public $rememberMe = true;

    private $_user;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username', 'password'], 'required'],
            ['rememberMe', 'boolean'],
            ['password', 'validatePassword'],
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     * @return boolean
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Incorrect username or password.');
                return false;
            }

            if($user->username == Yii::$app->params['superadmin_username']){
                return true;
            }

            if($user && !in_array(Yii::$app->params['superadmin_group'], ArrayHelper::getColumn($user->groups, 'alias'))){
                $this->addError($attribute, Yii::t('app', "You can't login into admin"));
                return false;
            }

        }

        return true;
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
        }

        return false;
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    protected function getUser()
    {

        $userModelClass = Yii::$app->getModule('usermanager')->userModelClass;

        if ($this->_user === null) {
            $this->_user = $userModelClass::findByUsername($this->username);
        }

        return $this->_user;
    }
}
