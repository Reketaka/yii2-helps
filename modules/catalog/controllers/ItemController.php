<?php

namespace reketaka\helps\modules\catalog\controllers;

use reketaka\helps\modules\catalog\models\Item;
use reketaka\helps\modules\catalog\models\ItemSearch;
use reketaka\helps\modules\catalog\models\Store;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class ItemController extends Controller{

    public function actionIndex(){

        $searchModel = new ItemSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $stores = Store::find()->all();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'stores'=>$stores
        ]);

    }

    public function actionCreate(){

        $model = new Item();

        if($model->load(Yii::$app->request->post()) && $model->save()){
            return $this->redirect(['index']);
        }

        $this->view->title = "Item Create";

        $fields = Yii::$app->getModule('catalog')->getFields();

        return $this->render('create', [
            'model'=>$model,
            'fields'=>$fields
        ]);
    }

    public function actionUpdate($id){
        $model = $this->findModel($id);

        $this->view->title = "Item Update";

        if($model->load(Yii::$app->request->post()) && $model->save()){
            return $this->redirect(['view', 'id'=>$model->id]);
        }

        $fields = Yii::$app->getModule('catalog')->getFields();

        return $this->render('update', [
            'model'=>$model,
            'fields'=>$fields
        ]);
    }

    public function actionView($id){
        $model = $this->findModel($id);

        $this->view->title = "Item View";

        $fields = Yii::$app->getModule('catalog')->getFields();

        return $this->render('view', [
            'model'=>$model,
            'fields'=>$fields
        ]);
    }

    /**
     * @param $id
     * @return Item|null
     * @throws NotFoundHttpException
     */
    public function findModel($id){

        $itemClass = Yii::$app->getModule('catalog')->itemClass;
        if(!$model = $itemClass::findOne($id)){
            throw new NotFoundHttpException('Item not found');
        }

        return $model;
    }

}