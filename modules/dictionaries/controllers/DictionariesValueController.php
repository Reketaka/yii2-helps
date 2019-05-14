<?php

namespace reketaka\helps\modules\dictionaries\controllers;

use reketaka\helps\modules\dictionaries\models\DictionariesName;
use Yii;
use reketaka\helps\modules\dictionaries\models\DictionariesValue;
use reketaka\helps\modules\dictionaries\models\DictionariesValueSearch;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * DictionariesValueController implements the CRUD actions for DictionariesValue model.
 */
class DictionariesValueController extends Controller
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
     * Lists all DictionariesValue models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DictionariesValueSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $dictionaries = ArrayHelper::map(DictionariesName::find()->all(), 'id', 'title');

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'dictionaries'=>$dictionaries
        ]);
    }

    /**
     * Displays a single DictionariesValue model.
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
     * Creates a new DictionariesValue model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new DictionariesValue();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        $dictionaries = ArrayHelper::map(DictionariesName::find()->all(), 'id', 'title');

        return $this->render('create', [
            'model' => $model,
            'dictionaries'=>$dictionaries
        ]);
    }

    /**
     * Updates an existing DictionariesValue model.
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

        $dictionaries = ArrayHelper::map(DictionariesName::find()->all(), 'id', 'title');

        return $this->render('update', [
            'model' => $model,
            'dictionaries'=>$dictionaries
        ]);
    }

    /**
     * Deletes an existing DictionariesValue model.
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
     * Finds the DictionariesValue model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DictionariesValue the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DictionariesValue::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
