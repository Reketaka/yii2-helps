<?php

namespace reketaka\helps\common\actions;

use common\helpers\BaseHelper;
use yii\base\Action;
use yii\base\Behavior;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Class ToggleAttributeAction
 *
 * ```php
 * public function actions()
 * {
 *  return [
 *       'toggle-attribute' => [
 *           'class' => 'reketaka\helps\common\actions\ToggleAttributeAction',
 *       ]
 *    ];
 *  }
 *```php
 *
 * @package reketaka\helps\common\actions
 *
 */
class ToggleAttributeAction extends Action {

    /**
     * @param $id
     * @param $attributeName
     * @param null $value
     * @return array|Response
     * @throws NotFoundHttpException
     */
    public function run($id, $attributeName, $value = null){
        $model = $this->controller->findModel($id);

        if(!$model->hasAttribute($attributeName)){
            return $this->controller->redirect(\Yii::$app->request->referrer);
        }

        if($model->$attributeName == 1){
            $model->$attributeName = 0;
        }else{
            $model->$attributeName = 1;
        }

        $model->save();

        return $this->controller->redirect(\Yii::$app->request->referrer);
    }

}