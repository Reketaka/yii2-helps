<?php

namespace reketaka\helps\modules\catalog\controllers;

use reketaka\helps\modules\catalog\models\ItemPrice;
use reketaka\helps\modules\catalog\models\ItemPriceSearch;
use reketaka\helps\modules\catalog\models\ItemStore;
use reketaka\helps\modules\catalog\models\ItemStoreSearch;
use reketaka\helps\modules\catalog\models\PriceType;
use reketaka\helps\modules\catalog\models\PriceTypeSearch;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class ItemPriceController extends Controller{

    public function actions()
    {
        return [
            'toggle-attribute' => [
                'class' => 'reketaka\helps\common\actions\ToggleAttributeAction',
            ]
        ];
    }

    public function actionIndex(){

        $searchModel = new ItemPriceSearch();
        $dataProvider = $searchModel->search(\Yii::$app->request->queryParams);

        $this->view->title = "Item Price List";

        $typePrices = ArrayHelper::map(PriceType::find()->all(), 'id', 'title');

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'typePrices'=>$typePrices
        ]);

    }

    public function actionCreate(){
        $model = new ItemPrice();

        if($model->load(\Yii::$app->request->post()) && $model->save()){
            return $this->redirect(['index']);
        }


        $this->view->title = 'Create new ItemPrice';
        $typePrices = ArrayHelper::map(PriceType::find()->all(), 'id', 'title');

        return $this->render('create', [
            'model'=>$model,
            'typePrices'=>$typePrices
        ]);
    }

    public function actionUpdate($id){
        $model = $this->findModel($id);

        if($model->load(\Yii::$app->request->post()) && $model->save()){
            return $this->redirect(['index']);
        }

        $this->view->title = "Update ItemPrice #{$model->id}";

        $typePrices = ArrayHelper::map(PriceType::find()->all(), 'id', 'title');

        return $this->render('update', [
            'model'=>$model,
            'typePrices'=>$typePrices
        ]);
    }

    public function actionView($id){
        $model = $this->findModel($id);

        $this->view->title = "View ItemPrice #{$model->id}";

        return $this->render('view', [
            'model'=>$model
        ]);
    }

    public function findModel($id){
        if(!$model = ItemPrice::findOne($id)){
            throw new NotFoundHttpException('ItemPrice Not Found');
        }

        return $model;
    }

}