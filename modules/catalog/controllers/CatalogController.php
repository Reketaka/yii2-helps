<?php

namespace reketaka\helps\modules\catalog\controllers;

use reketaka\helps\modules\catalog\models\Catalog;
use reketaka\helps\modules\catalog\models\CatalogSearch;
use reketaka\helps\modules\catalog\models\Store;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class CatalogController extends Controller{

    public function actions()
    {
        return [
            'toggle-attribute' => [
                'class' => 'reketaka\helps\common\actions\ToggleAttributeAction',
            ]
        ];
    }

    public function actionIndex(){

        $searchModel = new CatalogSearch();
        $dataProvider = $searchModel->search(\Yii::$app->request->queryParams);

        $this->view->title = "Catalog List";

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);

    }

    public function actionCreate(){
        $model = new Catalog();

        if($model->load(\Yii::$app->request->post()) && $model->save()){
            return $this->redirect(['index']);
        }

        $this->view->title = 'Create new Catalog';

        return $this->render('create', [
            'model'=>$model,
        ]);
    }

    public function actionUpdate($id){
        $model = $this->findModel($id);

        if($model->load(\Yii::$app->request->post()) && $model->save()){
            return $this->redirect(['index']);
        }

        $this->view->title = "Update Catalog #{$model->id}";

        $stores = ArrayHelper::map(Store::find()->all(), 'id', 'title');

        return $this->render('update', [
            'model'=>$model,
            'stores'=>$stores
        ]);
    }

    public function actionDelete($id){
        $model = $this->findModel($id);

        $model->delete();

        return $this->redirect(['index']);
    }

    public function actionView($id){
        $model = $this->findModel($id);

        $this->view->title = "View Catalog #{$model->id}";

        return $this->render('view', [
            'model'=>$model
        ]);
    }

    public function findModel($id){
        if(!$model = Catalog::findOne($id)){
            throw new NotFoundHttpException('Catalog Not find');
        }

        return $model;
    }

}