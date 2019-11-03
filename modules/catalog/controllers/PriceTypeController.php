<?php

namespace reketaka\helps\modules\catalog\controllers;

use reketaka\helps\common\interfaces\ISetAttribute;
use reketaka\helps\modules\catalog\models\PriceType;
use reketaka\helps\modules\catalog\models\PriceTypeSearch;
use reketaka\helps\modules\catalog\Module;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class PriceTypeController extends Controller{

    public function actions()
    {
        return [
            'toggle-attribute' => [
                'class' => 'reketaka\helps\common\actions\ToggleAttributeAction',
            ]
        ];
    }

    public function actionIndex(){

        $searchModel = new PriceTypeSearch();
        $dataProvider = $searchModel->search(\Yii::$app->request->queryParams);

        $this->view->title = Module::t('title', 'price-type-index');

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);

    }

    public function actionCreate(){
        $model = new PriceType();

        if($model->load(\Yii::$app->request->post()) && $model->save()){
            return $this->redirect(['index']);
        }

        $this->view->title = Module::t('title', 'price-type-create');

        return $this->render('create', [
            'model'=>$model
        ]);
    }

    public function actionUpdate($id){
        $model = $this->findModel($id);


        if($model->load(\Yii::$app->request->post()) && $model->save()){
            return $this->redirect(['index']);
        }

        $this->view->title = Module::t('title', 'price-type-update', ['id'=>$model->id]);

        return $this->render('update', [
            'model'=>$model
        ]);
    }

    public function actionView($id){
        $model = $this->findModel($id);

        $this->view->title = Module::t('title', 'price-type-view', ['id'=>$model->id]);

        return $this->render('view', [
            'model'=>$model
        ]);
    }

    public function findModel($id){
        if(!$model = PriceType::findOne($id)){
            throw new NotFoundHttpException('Price Type Not Found');
        }

        return $model;
    }

}