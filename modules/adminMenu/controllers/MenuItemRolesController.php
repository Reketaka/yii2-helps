<?php

namespace reketaka\helps\modules\adminMenu\controllers;

use reketaka\helps\modules\adminMenu\models\MenuItem;
use reketaka\helps\modules\adminMenu\models\MenuItemRoles;
use reketaka\helps\modules\adminMenu\models\MenuItemRolesSearch;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * MenuItemRolesController implements the CRUD actions for MenuItemRoles model.
 */
class MenuItemRolesController extends Controller
{

    /**
     * Lists all MenuItemRoles models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new MenuItemRolesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $menuItems = ArrayHelper::toArray(MenuItem::find()->all(), [
            'backend\models\menu\MenuItem'=>[
                'id',
                'title'=>function($model){
                    return $model->title.' '.$model->url;
                }
            ]
        ]);
        $menuItems = ArrayHelper::map($menuItems, 'id', 'title');

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'menuItems'=>$menuItems
        ]);
    }

    /**
     * Displays a single MenuItemRoles model.
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
     * Creates a new MenuItemRoles model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new MenuItemRoles();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        $menuItems = ArrayHelper::toArray(MenuItem::find()->all(), [
            'backend\models\menu\MenuItem'=>[
                'id',
                'title'=>function($model){
                    return $model->title.' '.$model->url;
                }
            ]
        ]);
        $menuItems = ArrayHelper::map($menuItems, 'id', 'title');

        return $this->render('create', [
            'model' => $model,
            'menuItems'=>$menuItems
        ]);
    }

    /**
     * Updates an existing MenuItemRoles model.
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

        $menuItems = ArrayHelper::toArray(MenuItem::find()->all(), [
            'backend\models\menu\MenuItem'=>[
                'id',
                'title'=>function($model){
                    return $model->title.' '.$model->url;
                }
            ]
        ]);
        $menuItems = ArrayHelper::map($menuItems, 'id', 'title');

        return $this->render('update', [
            'model' => $model,
            'menuItems'=>$menuItems
        ]);
    }

    /**
     * Deletes an existing MenuItemRoles model.
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
     * Finds the MenuItemRoles model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return MenuItemRoles the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = MenuItemRoles::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
