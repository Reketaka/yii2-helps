<?php

namespace reketaka\helps\modules\seo\controllers;

use common\helpers\BaseHelper;
use reketaka\helps\common\models\FindControllers;
use Yii;
use reketaka\helps\modules\seo\models\Seo;
use reketaka\helps\modules\seo\models\SeoSearch;
use backend\common\controllers\BackendController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * MetaItemController implements the CRUD actions for Seo model.
 */
class MetaItemController extends BackendController
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Seo models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SeoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $controllers = [];
        foreach((new FindControllers)->getAll() as $controller=>$d){

            foreach($d as $t){
                $key = $controller.':'.$t;
                $controllers[$key] = $key;
            }

        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'controllers'=>$controllers
        ]);
    }

    /**
     * Displays a single Seo model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Seo model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Seo();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        $controllers = [];
        foreach((new FindControllers)->getAll() as $controller=>$d){

            foreach($d as $t){
                $key = $controller.':'.$t;
                $controllers[$key] = $key;
            }

        }


        return $this->render('create', [
            'model' => $model,
            'controllers'=>$controllers
        ]);
    }

    /**
     * Updates an existing Seo model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        $controllers = [];
        foreach((new FindControllers)->getAll() as $controller=>$d){

            foreach($d as $t){
                $key = $controller.':'.$t;
                $controllers[$key] = $key;
            }

        }

        return $this->render('update', [
            'model' => $model,
            'controllers'=>$controllers
        ]);
    }

    /**
     * Deletes an existing Seo model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Seo model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Seo the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Seo::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
