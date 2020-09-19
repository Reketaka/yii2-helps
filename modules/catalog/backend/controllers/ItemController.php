<?php

namespace reketaka\helps\modules\catalog\backend\controllers;

use common\modules\client\models\records\ClientRecord;
use reketaka\azia\models\View;
use reketaka\helps\common\helpers\Bh;
use reketaka\helps\modules\catalog\models\Item;
use reketaka\helps\modules\catalog\models\ItemSearch;
use reketaka\helps\modules\catalog\models\PriceType;
use reketaka\helps\modules\catalog\models\Store;
use reketaka\helps\modules\catalog\Module;
use reketaka\helps\modules\catalog\traits\ModuleTrait;
use Yii;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use function array_map;
use function implode;
use function strlen;

class ItemController extends Controller{

    use ModuleTrait;

    public function actionIndex(){

        $searchModel = new ItemSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $stores = $this->getModule()->storeClass::find()->all();
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

        $modelClass = $this->module->modelsPath['ItemUpdateModel'];
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

        $priceTypes = $this->module->modelsPath['PriceType']::find()->all();

        return $this->render('create', [
            'model'=>$model,
            'priceTypes'=>$priceTypes
        ]);
    }

    public function actionUpdate($id){
        $model = $this->findModel($id);
        $model->loadPrices();

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

        $priceTypes = $this->module->modelsPath['PriceType']::find()->all();


        return $this->render('update', [
            'model'=>$model,
            'priceTypes'=>$priceTypes
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


        $itemPrices = $model->getPrices()->with(['priceType'])->all();
        $itemStores = $model->getItemStores()->with(['store'])->all();

        return $this->render('view', [
            'model'=>$model,
            'itemPrices'=>$itemPrices,
            'itemStores'=>$itemStores
        ]);
    }

    public function actionFindByTitle($q){
        Yii::$app->response->format = Response::FORMAT_JSON;

        if(strlen($q) < 3){
            return [];
        }

        $items = $this->module->itemClass::find()
            ->select(['id', new Expression("title as 'text'")])
            ->where(['like', 'title', new Expression("'$q%'")])
            ->asArray()
            ->all();


        return $items;
    }

    /**
     * @param $id
     * @return Item|null
     * @throws NotFoundHttpException
     */
    public function findModel($id){

        $itemClass = $this->module->modelsPath['ItemUpdateModel'];
        if(!$model = $itemClass::findOne($id)){
            throw new NotFoundHttpException('Item not found');
        }

        return $model;
    }

}