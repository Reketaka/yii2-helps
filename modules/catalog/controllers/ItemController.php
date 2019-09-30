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

        return $this->render('create', [
            'model'=>$model
        ]);
    }

    /**
     * @param $id
     * @return Item|null
     * @throws NotFoundHttpException
     */
    public function findModel($id){
        if(!$model = Item::findOne($id)){
            throw new NotFoundHttpException('Item not found');
        }

        return $model;
    }

}