<?php

namespace reketaka\helps\modules\catalog\backend\controllers;


use reketaka\azia\models\View;
use reketaka\helps\modules\catalog\models\Discount;
use reketaka\helps\modules\catalog\models\DiscountSearch;
use Yii;
use yii\web\Controller;
use reketaka\helps\modules\catalog\Module;

class DiscountController extends Controller{

    public function actions()
    {
        return [
            'toggle-attribute' => [
                'class' => 'reketaka\helps\common\actions\ToggleAttributeAction',
            ]
        ];
    }

    public function actionIndex(){
        $searchModel = new DiscountSearch();
        $dataProvider = $searchModel->search(\Yii::$app->request->queryParams);

        $this->view->title = Module::t('title', 'discount-index');

        $this->view->params['breadcrumbs'][] = ['label'=>Module::t('app', 'bc.main'), 'url'=>['default/index']];
        $this->view->params['breadcrumbs'][] = Module::t('app', 'bc.discount');

        if(Yii::$app->view instanceof View){
            $this->view->h1 = Module::t('h1', 'discount-index');
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate(){
        $model = new Discount();

        if($model->load(\Yii::$app->request->post()) && $model->save()){
            return $this->redirect(['index']);
        }

        $this->view->title = Module::t('title', 'discount-create');

        $this->view->params['breadcrumbs'][] = ['label'=>Module::t('app', 'bc.main'), 'url'=>['default/index']];
        $this->view->params['breadcrumbs'][] = ['label'=>Module::t('app', 'bc.discount'), 'url'=>['discount/index']];
        $this->view->params['breadcrumbs'][] = Module::t('app', 'bc._create');

        if(Yii::$app->view instanceof View){
            $this->view->h1 = Module::t('h1', 'discount-create');
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

        $this->view->title = Module::t('title', 'discount-update', ['id'=>$model->id]);

        $this->view->params['breadcrumbs'][] = ['label'=>Module::t('app', 'bc.main'), 'url'=>['default/index']];
        $this->view->params['breadcrumbs'][] = ['label'=>Module::t('app', 'bc.discount'), 'url'=>['discount/index']];
        $this->view->params['breadcrumbs'][] = Module::t('app', 'bc._update', ['id'=>$model->id]);

        if(Yii::$app->view instanceof View){
            $this->view->h1 = Module::t('h1', 'discount-update');
        }

        return $this->render('update', [
            'model'=>$model,
        ]);
    }

    public function actionView($id){
        $model = $this->findModel($id);

        $this->view->title = Module::t('title', 'discount-view', ['id'=>$model->id]);

        $this->view->params['breadcrumbs'][] = ['label'=>Module::t('app', 'bc.main'), 'url'=>['default/index']];
        $this->view->params['breadcrumbs'][] = ['label'=>Module::t('app', 'bc.discount'), 'url'=>['discount/index']];
        $this->view->params['breadcrumbs'][] = Module::t('app', 'bc._view', ['id'=>$model->id]);

        if(Yii::$app->view instanceof View){
            $this->view->h1 = Module::t('h1', 'discount-view');
        }

        return $this->render('view', [
            'model'=>$model
        ]);
    }

    public function actionDelete($id){
        $model = $this->findModel($id);

        $model->delete();

        return $this->redirect(['index']);
    }

    public function findModel($id){
        if(!$model = Discount::findOne($id)){
            throw new NotFoundHttpException('Discount not find');
        }

        return $model;
    }

}