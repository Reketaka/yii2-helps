<?php

namespace reketaka\helps\modules\usermanager\controllers;

use common\helpers\BaseHelper;
use reketaka\helps\modules\usermanager\models\UserGroup;
use reketaka\helps\modules\usermanager\models\UserGroupSearch;
use yii\web\Controller;
use Yii;
use yii\web\NotFoundHttpException;

class UserGroupController extends Controller{

    public function actionIndex(){
        $searchModel = new UserGroupSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $this->view->title = "Users Group List";

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel'=>$searchModel,
        ]);
    }

    public function actionCreate(){

        $this->view->title = "Create User Group";

        $model = new UserGroup();
        if($model->load(Yii::$app->request->post()) && $model->validate()){
            $model->save();
            return $this->redirect(['/usermanager/user-group/view', 'id'=>$model->id]);
        }


        return $this->render('create', [
            'model'=>$model
        ]);
    }

    public function actionUpdate($id){

        $model = $this->findModel($id);

        $this->view->title = "Update User Group #".$model->id;

        if($model->load(Yii::$app->request->post()) && $model->validate()){
            $model->save();
            return $this->redirect(['/usermanager/user-group/view', 'id'=>$model->id]);
        }

        return $this->render('update', [
            'model'=>$model
        ]);
    }

    public function actionView($id){
        $model = $this->findModel($id);

        $this->view->title = "View User Group #".$model->id;

        $users = $model->getUsers();

        return $this->render('view', [
            'model'=>$model,
            'users'=>$users
        ]);
    }

    private function findModel($id){
        if(!$userGroup = UserGroup::findOne($id)){
            throw new NotFoundHttpException('User Group not found');
        }

        return $userGroup;
    }


}