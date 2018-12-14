<?php

namespace reketaka\helps\modules\usermanager\controllers;

use common\helpers\BaseHelper;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;
use yii\web\Controller;
use Yii;
use yii\base\Exception;
use yii\web\NotFoundHttpException;

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


        return $this->render('view', [
            'model'=>$user,
            'userViewAttributes'=>$userViewAttributes,
            'userRoles'=>$userRoles,
            'allRolesHeirarchy'=>$allRolesHeirarchy
        ]);
    }

    private function findModel($id){

        $class = $this->module->getUserModel();

        if(!$user = $class::findOne($id)){
            throw new NotFoundHttpException('Пользователь не найден');
        }

        return $user;
    }
}