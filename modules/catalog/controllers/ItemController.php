<?php

namespace reketaka\helps\modules\catalog\controllers;

use reketaka\helps\modules\catalog\models\ItemSearch;
use reketaka\helps\modules\catalog\models\Store;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\Controller;

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

}