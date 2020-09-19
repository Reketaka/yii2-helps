<?php

namespace reketaka\helps\modules\catalog\backend\controllers;

use reketaka\azia\models\View;
use reketaka\helps\modules\catalog\models\Catalog;
use reketaka\helps\modules\catalog\models\CatalogSearch;
use reketaka\helps\modules\catalog\models\Store;
use reketaka\helps\modules\catalog\Module;
use reketaka\helps\modules\catalog\traits\ModuleTrait;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class CatalogController extends Controller{

    use ModuleTrait;

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

        $this->view->title = Module::t('title', 'catalog-index');

        $this->view->params['breadcrumbs'][] = ['label'=>Module::t('app', 'bc.main'), 'url'=>['default/index']];
        $this->view->params['breadcrumbs'][] = Module::t('app', 'bc.catalog');

        if(Yii::$app->view instanceof View){
            $this->view->h1 = Module::t('h1', 'catalog-view');
        }

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

        $this->view->title = Module::t('title', 'catalog-create');

        $this->view->params['breadcrumbs'][] = ['label'=>Module::t('app', 'bc.main'), 'url'=>['default/index']];
        $this->view->params['breadcrumbs'][] = ['label'=>Module::t('app', 'bc.catalog'), 'url'=>['catalog/index']];
        $this->view->params['breadcrumbs'][] = Module::t('app', 'bc._create');

        if(Yii::$app->view instanceof View){
            $this->view->h1 = Module::t('h1', 'catalog-create');
        }

        return $this->render('create', [
            'model'=>$model,
        ]);
    }

    public function actionUpdate($id){
        $model = $this->findModel($id);

        if($model->load(\Yii::$app->request->post()) && $model->save()){
            return $this->redirect(['index']);
        }

        $this->view->title = Module::t('title', 'catalog-update', ['id'=>$model->id]);

        $this->view->params['breadcrumbs'][] = ['label'=>Module::t('app', 'bc.main'), 'url'=>['default/index']];
        $this->view->params['breadcrumbs'][] = ['label'=>Module::t('app', 'bc.catalog'), 'url'=>['catalog/index']];
        $this->view->params['breadcrumbs'][] = Module::t('app', 'bc._update', ['id'=>$model->id]);

        if(Yii::$app->view instanceof View){
            $this->view->h1 = Module::t('h1', 'catalog-update');
        }

        $stores = ArrayHelper::map($this->getModule()->storeClass::find()->all(), 'id', 'title');

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

        $this->view->title = Module::t('title', 'catalog-view', ['id'=>$model->id]);

        $this->view->params['breadcrumbs'][] = ['label'=>Module::t('app', 'bc.main'), 'url'=>['default/index']];
        $this->view->params['breadcrumbs'][] = ['label'=>Module::t('app', 'bc.catalog'), 'url'=>['catalog/index']];
        $this->view->params['breadcrumbs'][] = Module::t('app', 'bc._view', ['id'=>$model->id]);

        if(Yii::$app->view instanceof View){
            $this->view->h1 = Module::t('h1', 'catalog-view');
        }

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