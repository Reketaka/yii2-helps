<?php

namespace reketaka\helps\common\actions\crudReset\view;

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

class ViewAction extends BaseAction {

    public $columns = [];

    /**
     * Шаблон вывода render
     * @var string
     */
    public $renderView = '@reketaka/helps/common/actions/crudReset/view/views/index';

    /**
     * @var $model ActiveRecord
     */
    public $model = null;

    private function formatColumns(){

        if(!$this->columns){
            $this->columns = array_keys($this->model->attributes);
        }

    }

    public function run($id){

        $this->model = $this->controller->findModel($id);

        $this->formatColumns();
        $this->metaCall();

        return $this->controller->render($this->renderView, [
            'model'=>$this->model,
            'columns'=>$this->columns
        ]);
    }

}