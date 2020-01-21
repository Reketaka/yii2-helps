<?php

namespace reketaka\helps\common\actions\crudReset\delete;

use reketaka\helps\common\actions\crudReset\BaseAction;
use Yii;
use yii\base\Action;
use yii\db\ActiveRecord;

/**
 * Class DeleteAction
 *
 * ```php
 * public function actions()
 * {
 *  return [
 *       'delete' => [
 *           'class' => 'reketaka\helps\common\actions\crudReset\delete\DeleteAction',
 *       ]
 *    ];
 *  }
 *```php
 *
 * @package reketaka\helps\common\actions\crudReset\delete
 *
 */
class DeleteAction extends BaseAction {

    public $redirect = 'index';

    public function run($id){

        $this->model = $this->controller->findModel($id);

        $this->model->delete();

        return $this->controller->redirect([$this->redirect]);
    }

}