<?php

namespace reketaka\helps\modules\catalog\controllers;

use reketaka\helps\modules\catalog\models\PriceType;
use reketaka\helps\modules\catalog\models\PriceTypeSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class PriceTypeController extends Controller{

    public function actionIndex(){

        $searchModel = new PriceTypeSearch();
        $dataProvider = $searchModel->search(\Yii::$app->request->queryParams);


        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);

    }

    public function actionView($id){
        $model = $this->findModel($id);

        $this->view->title = "View Price Type #{$model->id}";

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