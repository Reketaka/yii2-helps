<?php

namespace reketaka\helps\modules\usermanager\controllers;

use common\helpers\BaseHelper;
use reketaka\helps\modules\usermanager\models\UserCommon;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;
use yii\web\Controller;
use Yii;
use yii\base\Exception;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class UserController extends Controller{

    public function actionIndex(){


        $searchModel = $this->module->getUserModelSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $this->view->title = "Users list";

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel'=>$searchModel,
            'userIndexAttributes'=>$this->module->userIndexAttributes
        ]);
    }

    public function actionUpdate($id){

        $user = $this->findModel($id);

        $this->view->title = "Edit User #".$user->id;

        $userEditAttributes = $this->module->userEditAttributes;

        return $this->render('update', [
            'model'=>$user,
            'userEditAttributes'=>$userEditAttributes
        ]);
    }

    public function actionView($id){
        $user = $this->findModel($id);

        $this->view->title = "View User #".$user->id;

        $userViewAttributes = $this->module->userViewAttributes;
        $userRoles = $user->getRoles($user->id);
        $userRoles = array_keys($userRoles);
        $allRolesHeirarchy = $this->module->getAllHierarchyRoles();
        $userGroups = $user->groups;

        return $this->render('view', [
            'model'=>$user,
            'userViewAttributes'=>$userViewAttributes,
            'userRoles'=>$userRoles,
            'allRolesHeirarchy'=>$allRolesHeirarchy,
            'userGroups'=>$userGroups
        ]);
    }

    public function actionDeleteRoleFromUser($userId, $roleName){
        Yii::$app->response->format = Response::FORMAT_JSON;

        if(!$user = $this->findModel($userId)){
            return ['success'=>false, 'error'=>'User not found'];
        }
        /**
         * @var $user UserCommon
         */

        $userRoles = $user->getRoles($user->id);
        if(!array_key_exists($roleName, $userRoles)){
            return ['success'=>false, 'error'=>'Role not found in user'];
        }

        $role = $userRoles[$roleName];
        /**
         * @var $role \yii\rbac\Assignment
         */
        $role = Yii::$app->authManager->getRole($role->roleName);

        Yii::$app->authManager->revoke($role, $user->id);

        return [
            'success'=>true
        ];
    }

    public function actionAddRoleToUser($userId, $roleName){
        Yii::$app->response->format = Response::FORMAT_JSON;

        if(!$user = $this->findModel($userId)){
            return ['success'=>false, 'error'=>'User not found'];
        }
        /**
         * @var $user UserCommon
         */

        $userRoles = $user->getRoles($user->id);
        if(array_key_exists($roleName, $userRoles)){
            return ['success'=>false, 'error'=>'Role found in user'];
        }

        if(!$role = Yii::$app->authManager->getRole($roleName)){
            return ['success'=>false, 'error'=>'Role not found'];
        }

        Yii::$app->authManager->assign($role, $userId);

        return [
            'success'=>true,
            'roleName'=>$role->name,
            'userId'=>$user->id
        ];
    }

    private function findModel($id){

        $class = $this->module->getUserModel();

        if(!$user = $class::findOne($id)){
            throw new NotFoundHttpException('Пользователь не найден');
        }

        return $user;
    }
}