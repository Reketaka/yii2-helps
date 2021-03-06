<?php

namespace reketaka\helps\modules\usermanager\commands;

use common\models\User;
use yii\console\Controller;
use yii\helpers\Console;
use Yii;

class UserController extends Controller{

    public $userModel;

    public function init()
    {
        parent::init(); // TODO: Change the autogenerated stub

        $this->userModel = $this->module->getUserModel();
    }

    public function actionCreateRole($roleName){
        try {
            $auth = Yii::$app->authManager;
            $role = $auth->createRole('superadmin');

            $auth->add($role);
        }catch (\Exception $exception){
            echo $exception->getMessage().PHP_EOL;
            return false;
        }

        echo "Роль успешно создана".PHP_EOL;
        
        return true;
    }

    public function actionChangePassword($username, $password=null){
        if(!$user = $this->userModel::findOne(['username'=>$username])){
            echo "Пользователь не найден".PHP_EOL;
            return false;
        }

        $newPassword = $password;
        if(!$password) {
            $newPassword = \Yii::$app->security->generateRandomString();
        }

        $user->setPassword($newPassword);
        $user->save();

        if($user->hasErrors()){
            echo "Произошли ошибки при сохранени пользователя".PHP_EOL;
            foreach($user->errors as $k=>$error){
                echo "#".$k." ".$error.PHP_EOL;
            }
            return false;
        }

        echo "Новый пароль для пользователя ".Console::ansiFormat($username, [Console::FG_YELLOW])." ".Console::ansiFormat($newPassword, [Console::FG_YELLOW]).PHP_EOL;

        return true;
    }

    /**
     * Создает пользователя
     * @param $username
     * @param $password
     */
    public function actionCreate($username, $password, $email='test@mail.ru'){

        if($user = $this->userModel::findOne(['username'=>$username, 'email'=>$email])){
            echo "Пользователь с таким именем уже существует".PHP_EOL;
            return false;
        }

        $user = new User();
        $user->username = $username;
        $user->email = $email;
        $user->status = $this->userModel::STATUS_ACTIVE;
        $user->setPassword($password);
        $user->generateAuthKey();
        $user->save();

        if($user->hasErrors()){
            echo "Произошли ошибки".PHP_EOL;
            var_dump($user->errors);
            return false;
        }


        echo "Пользователь успешно создан id:".$user->id.PHP_EOL;
        return true;
    }

    /**
     * Удаляет пользователя
     * @param $username
     * @return bool
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($username){
        if(!$user = $this->userModel::findOne(['username'=>$username])){
            echo "Пользователь не найден".PHP_EOL;
            return false;
        }

        $user->delete();
        echo "Пользователь успешно удален".PHP_EOL;
        return true;
    }

    /**
     * Выводит список всех пользователей используется при малом количестве пользователей
     * @return bool
     */
    public function actionList(){
        $users = $this->userModel::find()->all();

        echo "Всего пользователей ".count($users).PHP_EOL;
        foreach($users as $user){
            echo $user->username.'('.$user->id.')'.PHP_EOL;
        }

        return true;
    }

    public function actionGetUserRoles($username){
        $auth = \Yii::$app->authManager;

        if(!$user = $this->userModel::findOne(['username'=>$username])){
            echo "Пользователь не найден".PHP_EOL;
            return false;
        }

        $assigments = $auth->getAssignments($user->id);

        foreach($assigments as $assigment){
            echo $assigment->roleName.' ';
        }
        echo PHP_EOL;

        return;
    }

    public function actionRemoveRoleFromUser($username, $roleName){
        $auth = \Yii::$app->authManager;

        if(!$role = $auth->getRole($roleName)){
            echo "Роль не найдена".PHP_EOL;
            return false;
        }


        if(!$user = $this->userModel::findOne(['username'=>$username])){
            echo "Пользователь не найден".PHP_EOL;
            return false;
        }

        if(!array_key_exists($roleName, $auth->getAssignments($user->id))){
            echo "У указанного пользователя не найдена роль".PHP_EOL;
            return false;
        }

        $auth->revoke($role, $user->id);

        echo Console::ansiFormat("Роль успешно УДАЛЕНА у пользователя", [Console::FG_GREEN]).PHP_EOL;
        return false;
    }

    /**
     * Добавляет пользователю указанную роль или перечисление ролей через пробел
     * @param $roleName
     * @param $username
     * @return bool
     * @throws \Exception
     */
    public function actionAddRoleToUser($username, $rolesName){

        $auth = \Yii::$app->authManager;

        $rolesName = explode(' ', $rolesName);

        foreach($rolesName as $roleName){
            if(!$role = $auth->getRole($roleName)){
                echo "Роль не найдена ".Console::ansiFormat($roleName, [Console::FG_GREEN]).PHP_EOL;
                return false;
            }
        }

        if(!$user = $this->userModel::findOne(['username'=>$username])){
            echo "Пользователь не найден".PHP_EOL;
            return false;
        }

        foreach($rolesName as $roleName) {
            if (array_key_exists($roleName, $auth->getAssignments($user->id))) {
                echo "У указанного пользователя уже есть данная роль" . PHP_EOL;
                return false;
            }
        }

        foreach($rolesName as $roleName){
            if(!$role = $auth->getRole($roleName)){
                echo "Роль не найдена ".Console::ansiFormat($roleName, [Console::FG_GREEN]).PHP_EOL;
                return false;
            }
            $auth->assign($role, $user->id);
            echo Console::ansiFormat("Роль $roleName успешно добавлена пользователю", [Console::FG_YELLOW]).PHP_EOL;
        }

        return false;


    }

}