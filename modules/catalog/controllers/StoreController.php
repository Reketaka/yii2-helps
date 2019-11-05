<?php

namespace reketaka\helps\modules\catalog\controllers;

use reketaka\helps\common\interfaces\ISetAttribute;
use reketaka\helps\modules\catalog\models\PriceType;
use reketaka\helps\modules\catalog\models\PriceTypeSearch;
use reketaka\helps\modules\catalog\models\Store;
use reketaka\helps\modules\catalog\models\StoreSearch;
use reketaka\helps\modules\catalog\Module;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class StoreController extends Controller{

    public function actions()
    {
        return [
            'toggle-attribute' => [
                'class' => 'reketaka\helps\common\actions\ToggleAttributeAction',
            ]
        ];
    }

    public function actionIndex(){

        $searchModel = new StoreSearch();
        $dataProvider = $searchModel->search(\Yii::$app->request->queryParams);

        $this->view->title = Module::t('title', 'store-index');

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);

    }

    public function actionCreate(){
        $model = new Store();

        if($model->load(\Yii::$app->request->post()) && $model->save()){
            return $this->redirect(['index']);
        }

        $this->view->title = Module::t('title', 'store-create');

        return $this->render('create', [
            'model'=>$model
        ]);
    }

    public function actionUpdate($id){
        $model = $this->findModel($id);

        $this->view->title = "Update Store #{$model->id}";

        if($model->load(\Yii::$app->request->post()) && $model->save()){
            return $this->redirect(['index']);
        }

        $this->view->title = Module::t('title', 'store-update', ['id'=>$model->id]);

        return $this->render('update', [
            'model'=>$model
        ]);
    }

    public function actionView($id){
        $model = $this->findModel($id);

        $this->view->title = Module::t('title', 'store-view', ['id'=>$model->id]);

        return $this->render('view', [
            'model'=>$model
        ]);
    }

    public function findModel($id){
        if(!$model = Store::findOne($id)){
            throw new NotFoundHttpException('Store Not Found');
        }

        return $model;
    }

}