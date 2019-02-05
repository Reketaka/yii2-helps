<?php

namespace reketaka\helps\modules\adminMenu\controllers;

use common\helpers\BaseHelper;
use reketaka\helps\modules\adminMenu\models\MenuItem;
use reketaka\helps\modules\adminMenu\models\MenuItemUser;
use reketaka\helps\modules\adminMenu\models\MenuSectionUser;
use reketaka\helps\modules\adminMenu\models\MenuSectionUserSearch;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * MenuSectionUserController implements the CRUD actions for MenuSectionUser model.
 */
class MenuSectionUserController extends Controller
{

    public function behaviors()
    {
        return [
            'sortable' => [
                'class' => \kotchuprik\sortable\behaviors\Sortable::className(),
                'query' => MenuSectionUser::find(),
            ],
        ];
    }

    public function actions()
    {
        return [
            'sorting' => [
                'class' => \kotchuprik\sortable\actions\Sorting::className(),
                'query' => MenuSectionUser::find(),
        ],
    ];
}

    /**
     * Lists all MenuSectionUser models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new MenuSectionUserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);


        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'isSuperAdmin'=>$this->module->isSuperAdminAuth()
        ]);
    }

    /**
     * Displays a single MenuSectionUser model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        return $this->render('view', [
            'model' => $model,
            'menuItems'=>$model->menuItems
        ]);
    }

    /**
     * Creates a new MenuSectionUser model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new MenuSectionUser();
        $model->parent = 0;
        $model->order = 0;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }



        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing MenuSectionUser model.
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

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing MenuSectionUser model.
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

    public function actionAddItemToSection($sectionId, $itemId){
        Yii::$app->response->format = Response::FORMAT_JSON;

        $section = MenuSectionUser::find()
            ->where([
                'user_id'=>Yii::$app->user->getId(),
                'id'=>$sectionId
            ])
            ->one();

        if(!$section){
            return [
                'success'=>false,
                'message'=>'Section not found'
            ];
        }

        if(!$item = MenuItem::findOne($itemId)){
            return [
                'success'=>false,
                'message'=>false
            ];
        }

        $menuItemUser = MenuItemUser::findOne([
            'menu_item_id'=>$item->id,
            'menu_section_id'=>$section->id,
            'user_id'=>Yii::$app->user->getId()
        ]);

        if(!$menuItemUser){
            $menuItemUser = new MenuItemUser([
                'menu_item_id'=>$item->id,
                'menu_section_id'=>$section->id,
                'user_id'=>Yii::$app->user->getId()
            ]);

            $menuItemUser->save();

        }

        return [
            'success'=>true,
        ];
    }

    /**
     * Finds the MenuSectionUser model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return MenuSectionUser the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = MenuSectionUser::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
