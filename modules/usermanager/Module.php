<?php


namespace reketaka\helps\modules\usermanager;

use common\helpers\BaseHelper;
use Yii;
use yii\base\Exception;
use yii\helpers\FileHelper;
use yii\helpers\StringHelper;

class Module extends \yii\base\Module{

    public $userModelClass = null;
    public $userModelSearchClass = null;
    public $userIndexAttributes = [
        'username',
//        'active',
        'created_at:datetime'
    ];

    public $userEditAttributes = [
        'username'
    ];
    public $userViewAttributes = [];

    public $rootRoles = ['superadmin'];

    public function init(){
        parent::init();

        if(!$this->userViewAttributes){
            $this->userViewAttributes = $this->userEditAttributes;
        }

        if(!$this->userModelClass || !$this->userModelSearchClass){
            throw new Exception('User model not set');
        }

        if (Yii::$app instanceof \yii\console\Application) {
            $this->controllerNamespace = 'reketaka\helps\modules\usermanager\commands';
        }
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

    /**
     * Возвращает
     */
    public function getAllHierarchyRoles(){
        $rootRoles = $this->rootRoles;

        $r = [];

        $authManager = Yii::$app->authManager;

        foreach($rootRoles as $rootRole){
            $role = $authManager->getRole($rootRole);

            $t = [
                'name'=>$role->name
            ];

            if($childs = $this->getChildsOfRole($role->name)){
                $t['childs'] = $childs;
            }

            $r[] = $t;

        }

        return $r;

    }

    private function getChildsOfRole($roleName){
        $roles = Yii::$app->authManager->getChildren($roleName);

        $r = [];

        if(array_key_exists($roleName, $roles)){
            unset($roles[$roleName]);
        }

        foreach($roles as $role){

            $t = [
                'name'=>$role->name,
                'type'=>$role::className()
            ];

            if($childs = $this->getChildsOfRole($role->name)){
                $t['childs'] = $childs;
            }

            $r[] = $t;
        }

        return $r;
    }
}