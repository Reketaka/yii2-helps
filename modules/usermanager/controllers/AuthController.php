<?php

namespace reketaka\helps\modules\usermanager\controllers;

use reketaka\helps\modules\usermanager\models\LoginForm;
use yii\filters\AccessControl;
use Yii;
use yii\web\Controller;

class AuthController extends Controller {

    public function behaviors()
    {

        $behaviors = [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'allow'=>true,
                        'actions'=>['logout'],
                        'roles'=>['@']
                    ]
                ],
            ],
        ];

        return array_merge(parent::behaviors(), $behaviors); // TODO: Change the autogenerated stub
    }

    public function beforeAction($action)
    {

        $this->layout = '@reketaka/helps/modules/usermanager/views/layouts/loginLayout';
        return parent::beforeAction($action); // TODO: Change the autogenerated stub
    }

    public function actionLogout(){

        Yii::$app->user->logout();

        return $this->goBack();
    }

    public function actionLogin(){


        $model = new LoginForm();
        if($model->load(Yii::$app->request->post()) && $model->login()){
            return $this->goBack();
        }

        $model->password = '';

        return $this->render('@reketaka/helps/modules/usermanager/views/auth/login/login', [
            'model'=>$model
        ]);
    }

}