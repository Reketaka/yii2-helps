<?php

namespace reketaka\helps\modules\catalog\backend\controllers;

use reketaka\azia\models\View;
use reketaka\helps\common\interfaces\ISetAttribute;
use reketaka\helps\modules\catalog\models\PriceType;
use reketaka\helps\modules\catalog\models\PriceTypeSearch;
use reketaka\helps\modules\catalog\Module;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class PriceTypeController extends Controller{

    public function actions()
    {
        return [
            'toggle-attribute' => [
                'class' => 'reketaka\helps\common\actions\ToggleAttributeAction',
            ]
        ];
    }

    public function actionIndex(){

        $searchModel = new PriceTypeSearch();
        $dataProvider = $searchModel->search(\Yii::$app->request->queryParams);

        $this->view->title = Module::t('title', 'price-type-index');

        $this->view->params['breadcrumbs'][] = ['label'=>Module::t('app', 'bc.main'), 'url'=>['default/index']];
        $this->view->params['breadcrumbs'][] = Module::t('app', 'bc.price-type');

        if(Yii::$app->view instanceof View){
            $this->view->h1 = Module::t('h1', 'price-type-index');
            $this->view->description = Module::t('description', 'price-type-index');
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);

    }

    public function actionCreate(){
        $model = new PriceType();

        if($model->load(\Yii::$app->request->post()) && $model->save()){
            return $this->redirect(['index']);
        }

        $this->view->title = Module::t('title', 'price-type-create');

        $this->view->params['breadcrumbs'][] = ['label'=>Module::t('app', 'bc.main'), 'url'=>['default/index']];
        $this->view->params['breadcrumbs'][] = ['label'=>Module::t('app', 'bc.price-type'), 'url'=>['price-type/index']];
        $this->view->params['breadcrumbs'][] = Module::t('app', 'bc._create');

        if(Yii::$app->view instanceof View){
            $this->view->h1 = Module::t('h1', 'price-type-create');
        }

        return $this->render('create', [
            'model'=>$model
        ]);
    }

    public function actionUpdate($id){
        $model = $this->findModel($id);


        if($model->load(\Yii::$app->request->post()) && $model->save()){
            return $this->redirect(['index']);
        }

        $this->view->title = Module::t('title', 'price-type-update', ['id'=>$model->id]);

        $this->view->params['breadcrumbs'][] = ['label'=>Module::t('app', 'bc.main'), 'url'=>['default/index']];
        $this->view->params['breadcrumbs'][] = ['label'=>Module::t('app', 'bc.price-type'), 'url'=>['price-type/index']];
        $this->view->params['breadcrumbs'][] = Module::t('app', 'bc._update', ['id'=>$model->id]);

        if(Yii::$app->view instanceof View){
            $this->view->h1 = Module::t('h1', 'price-type-update');
        }

        return $this->render('update', [
            'model'=>$model
        ]);
    }

    public function actionView($id){
        $model = $this->findModel($id);

        $this->view->title = Module::t('title', 'price-type-view', ['id'=>$model->id]);

        $this->view->params['breadcrumbs'][] = ['label'=>Module::t('app', 'bc.main'), 'url'=>['default/index']];
        $this->view->params['breadcrumbs'][] = ['label'=>Module::t('app', 'bc.price-type'), 'url'=>['price-type/index']];
        $this->view->params['breadcrumbs'][] = Module::t('app', 'bc._view', ['id'=>$model->id]);

        if(Yii::$app->view instanceof View){
            $this->view->h1 = Module::t('h1', 'price-type-view');
        }

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