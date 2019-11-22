<?php

namespace reketaka\helps\common\models;


use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\helpers\Console;
use Yii;
use yii\rbac\Permission;

/**
 * ```php
 * $roles = [
 *      'r'=>[
 *          'superadmin'=>[
 *              'r'=>[
 *                  'siteWebConfig'=>[
 *                      'r'=>[
 *                          'viewSiteWebConfig'=>[
*                               'p'=>[
 *                                  'viewSiteWebConfig'
 *                              ]
 *                          ],
 *                  ]
 *          ]
 *      ]
 * ];
 * (new RolesCreateHelper(['roles' => $roles]))
 *      ->create();
 * ```php
 *
 * Class RolesCreateHelper
 * @package reketaka\helps\common\models
 */
class RolesCreateHelper extends Model {
    CONST KEY_ROLES = 'r';
    CONST KEY_PERMISSIONS = 'p';
    CONST KEY_RULES = 'rules';

    public $roles;
    /**
     * @var \yii\rbac\ManagerInterface $auth
     */
    public $auth;

    public function init()
    {
        $this->auth = Yii::$app->authManager;
    }

    public function create(){
        if(!$this->roles){
            echo "Роли не переданны".PHP_EOL;
            return false;
        }

        $this->createSubRoles($this->roles);
    }

    private function createSubRoles($rr, $parentRole = null){

        foreach($rr[self::KEY_ROLES] as $roleName=>$rolesSub){

            if(!$role = $this->auth->getRole($roleName)) {
                $role = $this->auth->createRole($roleName);
                $this->auth->add($role);

                $textInfo = [];
                $textInfo[] = "Роль ".Console::ansiFormat($roleName, [Console::FG_GREEN])." успешно создана";
                if($parentRole) {
                    $textInfo[] = "- родитель " . Console::ansiFormat($parentRole->name, [Console::FG_GREEN]);
                }

                echo implode(' ', $textInfo).PHP_EOL;

            }else{
                echo Console::ansiFormat("Пропускаем роль $roleName уже есть такая в системе", [Console::FG_YELLOW]).PHP_EOL;

            }

            if($parentRole && !array_key_exists($role->name, $this->auth->getChildRoles($parentRole->name))){
                $this->auth->addChild($parentRole, $role);
            }

            if(is_array($rolesSub) && array_key_exists(self::KEY_ROLES, $rolesSub)){
                $this->createSubRoles($rolesSub, $role);
            }

            if(is_array($rolesSub) && array_key_exists(self::KEY_PERMISSIONS, $rolesSub)){
                $this->createPermissions($rolesSub, $role);
            }

        }
    }

    public function createPermissions($d, $role){

        foreach($d[self::KEY_PERMISSIONS] as $permissionNameAr =>$permissionData){

            $permissionName = is_array($permissionData)?$permissionNameAr:$permissionData;
            $permissionName = $permissionName.'Perm';

            if($permission = $this->auth->getPermission($permissionName)){
                echo "Разрешение ".Console::ansiFormat($permissionName, [Console::FG_GREEN])." было создано раньше".PHP_EOL;
                $this->createRules($permissionData, $permission);
                continue;
            }

            $permission = $this->auth->createPermission($permissionName);
            $this->auth->add($permission);
            $this->auth->addChild($role, $permission);


            $this->createRules($permissionData, $permission);


            echo "Разрешение ".Console::ansiFormat($permissionName, [Console::FG_GREEN])." успешно создано, роль ".Console::ansiFormat($role->name, [Console::FG_GREEN]).PHP_EOL;


        }
    }

     public function createRules($d, Permission $permission){

         if(!is_array($d)){
             return false;
         }

         if(!array_key_exists(self::KEY_RULES, $d)){
             return false;
         }

         foreach($d[self::KEY_RULES] as $ruleClass){
             $ruleModel = new $ruleClass();

             if($this->auth->getRule($ruleModel->name)){
                 echo "Правило ".Console::ansiFormat($ruleModel->name, [Console::FG_YELLOW])." уже создано в системе".PHP_EOL;
             }else{
                 $this->auth->add($ruleModel);
                 echo "Правило ".Console::ansiFormat($ruleModel->name, [Console::FG_GREEN])." успешно создано".PHP_EOL;
             }

             $permission->ruleName = $ruleModel->name;

             $this->auth->update($permission->name, $permission);
         }


     }

    public function remove(){

        if(!$this->roles){
            echo "Роли не переданны".PHP_EOL;
            return false;
        }

        $this->removeSubRoles($this->roles);
    }

    private function removeSubRoles($rr){
        foreach($rr[self::KEY_ROLES] as $roleName=>$rolesSub){




            if($role = $this->auth->getRole($roleName)) {
                $role = $this->auth->remove($role);
                echo "Роль ".Console::ansiFormat($roleName, [Console::FG_GREEN])." удалена".PHP_EOL;
            }else{
                echo "Роль ".Console::ansiFormat($roleName, [Console::FG_GREEN])." была удалена раньше".PHP_EOL;
            }

            if(is_array($rolesSub) && array_key_exists('r', $rolesSub)){
                $this->removeSubRoles($rolesSub);
            }

            if(is_array($rolesSub) && array_key_exists(self::KEY_PERMISSIONS, $rolesSub)){
                $this->removePermissions($rolesSub, $role);
            }


        }

        return true;
    }

    public function removePermissions($d){

        foreach($d[self::KEY_PERMISSIONS] as $permissionName){

            if(!$permission = $this->auth->getPermission($permissionName)){
                echo "Разрешение ".Console::ansiFormat($permissionName, [Console::FG_GREEN])." было удалено раньше".PHP_EOL;
                continue;
            }

            $permission = $this->auth->remove($permission);

            echo "Разрешение ".Console::ansiFormat($permissionName, [Console::FG_GREEN])." успешно удалено".PHP_EOL;



        }

    }

}