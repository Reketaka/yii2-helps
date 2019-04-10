<?php

namespace reketaka\helps\modules\usermanager\controllers;

use common\helpers\BaseHelper;
use reketaka\helps\modules\usermanager\models\UserCommon;
use reketaka\helps\modules\usermanager\models\UserGroup;
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

    public function actionCreate(){

        $this->view->title = "Create User";

        $model = $this->module->getUserModel();

        if($model->load(Yii::$app->request->post()) && $model->validate()){
            $userModel = $model->create();
            return $this->redirect(['/usermanager/user/view', 'id'=>$userModel->id]);
        }

        $userEditAttributes = $this->module->userEditAttributes;

        return $this->render('create', [
            'model'=>$model,
            'userEditAttributes'=>$userEditAttributes
        ]);

    }

    public function actionUpdate($id){

        $user = $this->findModel($id);

        $this->view->title = "Edit User #".$user->id;

        $userEditAttributes = $this->module->userEditAttributes;

        if($user->load(Yii::$app->request->post()) && $user->save()){
            if($user->password){
                $user->setPassword($user->password);
                $user->generateAuthKey();
                $user->save();
            }

            return $this->redirect(['/usermanager/user/view', 'id'=>$user->id]);
        }

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

        $allGroups = ArrayHelper::map(UserGroup::find()->all(), 'id', 'title');

        return $this->render('view', [
            'model'=>$user,
            'userViewAttributes'=>$userViewAttributes,
            'userRoles'=>$userRoles,
            'allRolesHeirarchy'=>$allRolesHeirarchy,
            'userGroups'=>$userGroups,
            'allGroups'=>$allGroups
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

    public function actionAddGroupToUser($userId, $groupId){
        Yii::$app->response->format = Response::FORMAT_JSON;

        if(!$user = $this->findModel($userId)){
            return ['success'=>false, 'error'=>'User not found'];
        }

        if(!$group = UserGroup::findOne($groupId)){
            return ['success'=>false, 'error'=>'Group not found'];
        }
        /**
         * @var $user UserCommon
         */

        $group->addUser($user->id);

        return [
            'success'=>true,
            'groupId'=>$group->id,
            'userId'=>$user->id,
            'groupTitle'=>$group->title
        ];
    }

    public function actionDelete($id){
        $user = $this->findModel($id);

        $user->delete();

        return $this->redirect(['/usermanager/user/index']);
    }

    public function actionRemoveGroupFromUser($userId, $groupId){

        Yii::$app->response->format = Response::FORMAT_JSON;

        if(!$user = $this->findModel($userId)){
            return ['success'=>false, 'error'=>'User not found'];
        }

        if(!$group = UserGroup::findOne($groupId)){
            return ['success'=>false, 'error'=>'Group not found'];
        }
        /**
         * @var $user UserCommon
         */

        $group->removeUser($user->id);

        return [
            'success'=>true,
            'groupId'=>$group->id,
            'userId'=>$user->id,
            'groupTitle'=>$group->title
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