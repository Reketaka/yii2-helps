<?php

namespace reketaka\helps\modules\adminMenu\controllers;

use reketaka\helps\modules\adminMenu\models\MenuDynamic;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\Controller;

class MenuBaseController extends Controller {

    public function behaviors()
    {
        $parents = parent::behaviors();

        $behaviors = [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];

        return ArrayHelper::merge($parents, $behaviors);
    }

    public function actionIndex(){

        $this->view->title = 'All system features available to you.';

        $menuItems = (new MenuDynamic)->generate();

        return $this->render('index', [
            'menuItems'=>$menuItems
        ]);
    }

}