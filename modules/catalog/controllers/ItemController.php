<?php

namespace reketaka\helps\modules\catalog\controllers;

use reketaka\azia\models\View;
use reketaka\helps\common\helpers\Bh;
use reketaka\helps\modules\catalog\models\Item;
use reketaka\helps\modules\catalog\models\ItemSearch;
use reketaka\helps\modules\catalog\models\PriceType;
use reketaka\helps\modules\catalog\models\Store;
use reketaka\helps\modules\catalog\Module;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class ItemController extends Controller{

    public function actionIndex(){

        $searchModel = new ItemSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $stores = Store::find()->all();
        $priceTypes = PriceType::find()->all();

        $this->view->title = Module::t('title', 'item-index');

        $this->view->params['breadcrumbs'][] = ['label'=>Module::t('app', 'bc.main'), 'url'=>['default/index']];
        $this->view->params['breadcrumbs'][] = Module::t('app', 'bc.item');

        if(Yii::$app->view instanceof View){
            $this->view->description = Module::t('description', 'item-index');
            $this->view->h1 = Module::t('h1', 'item-index');
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'stores'=>$stores,
            'priceTypes'=>$priceTypes
        ]);

    }

    public function actionCreate(){

        $modelClass = $this->module->itemClass;
        $model = new $modelClass();

        if($model->load(Yii::$app->request->post()) && $model->save()){
            return $this->redirect(['index']);
        }

        $this->view->title = Module::t('title', 'item-create');

        $this->view->params['breadcrumbs'][] = ['label'=>Module::t('app', 'bc.main'), 'url'=>['default/index']];
        $this->view->params['breadcrumbs'][] = ['label'=>Module::t('app', 'bc.item'), 'url'=>['item/index']];
        $this->view->params['breadcrumbs'][] = Module::t('app', 'bc._create');

        if(Yii::$app->view instanceof View){
            $this->view->h1 = Module::t('h1', 'item-create');
        }

        $fields = Yii::$app->getModule('catalog')->getFields();

        return $this->render('create', [
            'model'=>$model,
            'fields'=>$fields
        ]);
    }

    public function actionUpdate($id){
        $model = $this->findModel($id);

        $this->view->title = Module::t('title', 'item-update', ['id'=>$model->id]);

        $this->view->params['breadcrumbs'][] = ['label'=>Module::t('app', 'bc.main'), 'url'=>['default/index']];
        $this->view->params['breadcrumbs'][] = ['label'=>Module::t('app', 'bc.item'), 'url'=>['item/index']];
        $this->view->params['breadcrumbs'][] = Module::t('app', 'bc._update', ['id'=>$model->id]);

        if(Yii::$app->view instanceof View){
            $this->view->h1 = Module::t('h1', 'item-update');
        }

        if($model->load(Yii::$app->request->post()) && $model->save()){
            return $this->redirect(['view', 'id'=>$model->id]);
        }

        $fields = Yii::$app->getModule('catalog')->getFields();

        return $this->render('update', [
            'model'=>$model,
            'fields'=>$fields
        ]);
    }

    public function actionDelete($id){
        $model = $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionView($id){
        $model = $this->findModel($id);

        $this->view->title = Module::t('title', 'item-view', ['id'=>$model->id]);

        $this->view->params['breadcrumbs'][] = ['label'=>Module::t('app', 'bc.main'), 'url'=>['default/index']];
        $this->view->params['breadcrumbs'][] = ['label'=>Module::t('app', 'bc.item'), 'url'=>['item/index']];
        $this->view->params['breadcrumbs'][] = Module::t('app', 'bc._view', ['id'=>$model->id]);

        if(Yii::$app->view instanceof View){
            $this->view->h1 = Module::t('h1', 'item-view');
        }

        $fields = Yii::$app->getModule('catalog')->getFields();

        $itemPrices = $model->getPrices()->with(['priceType'])->all();
        $itemStores = $model->getItemStores()->with(['store'])->all();

        return $this->render('view', [
            'model'=>$model,
            'fields'=>$fields,
            'itemPrices'=>$itemPrices,
            'itemStores'=>$itemStores
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