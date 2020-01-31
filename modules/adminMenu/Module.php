<?php

namespace reketaka\helps\modules\adminMenu;

class Module extends \yii\base\Module{

    public $userModelClass;
    public $userModelSearchClass;
    public $superAdminRole = 'superadmin';
    public $superAdminLogin = 'superadmin';

    public $i18nUse = false;
    public $i18nSection = 'mainMenu';

    public function init(){
        parent::init();

//        if (\Yii::$app instanceof \yii\console\Application) {
//            $this->controllerNamespace = 'backend\modules\webmaster\commands';
//        }
    }

    public function getUserModel(){
        $class = $this->userModelClass;
        $class = new $class();

        return $class;
    }

    public function getUserModelSearch(){
        $class = $this->userModelSearchClass;
        $class = new $class();

        return $class;
    }

    public function isSuperAdminAuth(){
        return $this->superAdminLogin == \Yii::$app->user->identity->username;
    }

}