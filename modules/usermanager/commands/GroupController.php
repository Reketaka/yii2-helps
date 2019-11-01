<?php

namespace reketaka\helps\modules\usermanager\commands;

use common\helpers\BaseHelper;
use reketaka\helps\modules\usermanager\models\UserGroup;
use yii\console\Controller;
use yii\helpers\Console;

class GroupController extends Controller{

    public $userModel;

    public function init()
    {
        parent::init(); // TODO: Change the autogenerated stub

        $this->userModel = $this->module->getUserModel();
    }

    /**
     * Добавляет указанному пользователю группу
     * @param $username
     * @param $groupAlias
     */
    public function actionAddGroupToUser($username, $groupAlias){

        if(!$group = UserGroup::findOne(['alias'=>$groupAlias])){
            echo "Группа пользователей не найдена".PHP_EOL;
            return false;
        }

        if(!$user = $this->userModel::findOne(['username'=>$username])){
            echo "Пользователь не найден".PHP_EOL;
            return false;
        }

        if(!$group->addUser($user->id)){
            echo Console::ansiFormat("Пользователь не добавлен в группу", [Console::FG_RED]).PHP_EOL;
            return false;
        }

        echo Console::ansiFormat("Пользователь успешно добавлен в группу", [Console::FG_GREEN]).PHP_EOL;
        return true;
    }

    /**
     * Создает группу пользователей
     * @param $alias
     * @param $title
     * @return bool
     */
    public function actionCreate($alias, $title){

        $group = new UserGroup([
            'title'=>$title,
            'alias'=>$alias
        ]);

        $group->save();

        if($group->hasErrors()){
            echo "Произошли ошибки".PHP_EOL;
            var_dump($group->errors);
            return false;
        }


        echo "Группа успешно создана id:".$group->id.PHP_EOL;
        return true;

    }

}