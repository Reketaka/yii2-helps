<?php

namespace reketaka\helps\common\controllers;

use common\models\User;
use Yii;
use yii\helpers\Html;
use yii\web\Controller;
use reketaka\helps\common\helpers\Bh as BaseHelper;

class FrontendController extends Controller{

    /**
     * @return User|\yii\web\IdentityInterface|null
     */
    public function getUserIdentity(){
        return \Yii::$app->user->identity;
    }

    /**
     * Устанавливает содержимое тега description
     * @param $description
     */
    public function setMetaDescription($description){
        $this->view->metaTags['og:description'] = Html::tag('meta', null, ['name'=>'og:description', 'property'=>$description]);
        $this->view->metaTags['description'] = Html::tag('meta', null, ['name'=>'description', 'content'=>$description]);
    }

    /**
     * Формирует SCHEMA валидное описание хлебных крошек
     * @return bool
     */
    public function generateSchemaBreadCrumbs(){
        if(!isset($this->view->params['breadcrumbs'])){
            return false;
        }

        $schemaData = [
            '@context'=>'https://schema.org/',
            '@type'=>'BreadcrumbList',
            'itemListElement'=>[]
        ];

        foreach($this->view->params['breadcrumbs'] as $key=>$breadcrumbData){
            $num = $key+1;
            $schemaData['itemListElement'][] = [
                '@type'=>'ListItem',
                'position'=>$num,
                'name'=>$breadcrumbData['label']??$breadcrumbData,
                'item'=>isset($breadcrumbData['url'])?Yii::$app->urlManager->createAbsoluteUrl($breadcrumbData['url']):Yii::$app->request->absoluteUrl
            ];
        }

        $this->view->metaTags['schema_breadcrumbs'] = BaseHelper::generateSchema($schemaData);

    }

    public function disableDebugLog(){
        if(Yii::$app->has('log') && isset(Yii::$app->log->targets['debug'])) {
            Yii::$app->log->targets['debug']->enabled = false;
        }
    }

}