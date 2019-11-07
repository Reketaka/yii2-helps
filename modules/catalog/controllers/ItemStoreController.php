<?php

namespace reketaka\helps\modules\catalog\controllers;

use reketaka\helps\modules\catalog\models\ItemStore;
use reketaka\helps\modules\catalog\models\ItemStoreSearch;
use reketaka\helps\modules\catalog\models\PriceType;
use reketaka\helps\modules\catalog\models\PriceTypeSearch;
use reketaka\helps\modules\catalog\models\Store;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class ItemStoreController extends Controller{

    public function actions()
    {
        return [
            'toggle-attribute' => [
                'class' => 'reketaka\helps\common\actions\ToggleAttributeAction',
            ]
        ];
    }

    public function actionIndex(){

        $searchModel = new ItemStoreSearch();
        $dataProvider = $searchModel->search(\Yii::$app->request->queryParams);

        $this->view->title = "Item Store List";



        $stores = ArrayHelper::map(Store::find()->all(), 'id', 'title');

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'stores'=>$stores
        ]);

    }

    public function actionCreate(){
        $model = new ItemStore();

        if($model->load(\Yii::$app->request->post()) && $model->save()){
            return $this->redirect(['index']);
        }

        $this->view->title = 'Create new ItemStore';

        $stores = ArrayHelper::map(Store::find()->all(), 'id', 'title');

        return $this->render('create', [
            'model'=>$model,
            'stores'=>$stores
        ]);
    }

    public function actionUpdate($id){
        $model = $this->findModel($id);

        $this->view->title = "Update PriceType #{$model->id}";

        if($model->load(\Yii::$app->request->post()) && $model->save()){
            return $this->redirect(['index']);
        }

        $this->view->title = "Update ItemStore #{$model->id}";

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

        $this->view->title = "View ItemStore #{$model->id}";

        return $this->render('view', [
            'model'=>$model
        ]);
    }

    public function findModel($id){
        if(!$model = ItemStore::findOne($id)){
            throw new NotFoundHttpException('Price Type Not Found');
        }

        return $model;
    }

}