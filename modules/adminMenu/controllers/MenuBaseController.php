<?php

namespace reketaka\helps\modules\adminMenu\controllers;

use function mb_strtolower;
use reketaka\helps\common\helpers\Bh;
use reketaka\helps\modules\adminMenu\models\MenuDynamic;
use function strpos;
use function strtolower;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\Response;

class MenuBaseController extends Controller {

    public function actionIndex(){

        $this->view->title = 'All system features available to you.';

        $menuItems = (new MenuDynamic)->generate(true);

        return $this->render('index', [
            'menuItems'=>$menuItems
        ]);
    }

    public function actionGetItemByQuery($q){

        $menuItems = (new MenuDynamic)->generate(true);


        $items = [];
        foreach($menuItems as $sectionData){
            foreach($sectionData['items'] as $itemData){

                $title = $sectionData['label']." - ".$itemData['label'];

                if(strpos(mb_strtolower($title), mb_strtolower($q)) === FALSE){
                    continue;
                }

                $items[] = [
                    'label'=>$title,
                    'url'=>$itemData['url'],
                    'icon'=>$sectionData['icon']
                ];
            }
        }

        return $this->asJson($items);
    }

    public function actionGetSystemUser($q){

        \Yii::$app->response->format = Response::FORMAT_JSON;

        $userClass = \Yii::$app->getModule('adminmenu')->userModelClass;

        $users = $userClass::find()
            ->where(['like','username', $q])
            ->all();

        $users = ArrayHelper::toArray($users, [
            trim($userClass, '\\')=>[
                'id',
                'text'=>function($model){
                    return $model->username;
                }
            ]
        ]);

        return ['results'=>$users];

    }

}