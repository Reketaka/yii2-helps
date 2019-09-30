<?php

namespace reketaka\helps\common\actions;

use common\helpers\BaseHelper;
use yii\base\Action;
use yii\base\Behavior;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Class SetAttributeAction
 *
 * ```php
 * public function actions()
 * {
 *  return [
 *       'set-attribute' => [
 *           'class' => 'reketaka\helps\common\actions\SetAttributeAction',
 *       ]
 *    ];
 *  }
 *```php
 *
 * @package reketaka\helps\common\actions
 *
 */
class SetAttributeAction extends Action {

    /**
     * @param $id
     * @param $attributeName
     * @param null $value
     * @return array|Response
     * @throws NotFoundHttpException
     */
    public function run($id, $attributeName, $value = null){
        \Yii::$app->response->format = Response::FORMAT_JSON;

        $model = $this->controller->findModel($id);

        if(!$model->hasAttribute($attributeName)){
            return $this->controller->redirect(\Yii::$app->request->referrer);
        }

        $model->$attributeName = $value;
        $model->save();

        return [
            'success'=>true
        ];
    }

}