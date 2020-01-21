<?php

namespace reketaka\helps\common\actions\crudReset\update;

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

class UpdateAction extends BaseAction {

    public $columns = [];

    /**
     * Шаблон вывода render
     * @var string
     */
    public $renderView = '@reketaka/helps/common/actions/crudReset/update/views/index';

    public $redirect = 'view';

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
        $this->generateMeta('title');
        $this->generateMeta('h1');
        $this->generateMeta('description');
        $this->generateBreadcrumbs();

        if(Yii::$app->request->isPost && ($this->model->load(Yii::$app->request->post())) && $this->model->validate()){
            return $this->controller->redirect([$this->redirect, 'id'=>$this->model->getPrimaryKey()]);
        }

        return $this->controller->render($this->renderView, [
            'model'=>$this->model,
            'columns'=>$this->columns
        ]);
    }

}