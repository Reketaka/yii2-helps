<?php

namespace reketaka\helps\common\actions\crudReset\delete;

use function array_key_exists;
use function array_merge;
use function array_shift;
use function array_unshift;
use function in_array;
use reketaka\helps\common\actions\crudReset\BaseAction;
use reketaka\helps\common\helpers\Bh;
use Yii;
use yii\base\Action;
use yii\db\ActiveRecord;

class DeleteAction extends BaseAction {

    public $redirect = 'index';

    public function run($id){

        $this->model = $this->controller->findModel($id);

        $this->model->delete();

        return $this->controller->redirect([$this->redirect]);
    }

}