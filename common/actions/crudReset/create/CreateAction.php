<?php

namespace reketaka\helps\common\actions\crudReset\create;


use reketaka\helps\common\actions\crudReset\BaseAction;
use reketaka\helps\common\helpers\Bh;
use Yii;
use yii\base\Action;
use yii\db\ActiveRecord;

/**
 * Class UpdateAction
 *
 * ```php
 * public function actions()
 * {
 *  return [
 *       'update' => [
 *           'class' => 'reketaka\helps\common\actions\crudReset\create\CreateAction',
 *       ]
 *    ];
 *  }
 *```php
 *
 * @package reketaka\helps\common\actions\crudReset\update
 *
 */
class CreateAction extends BaseAction {

    public $columns = [];

    /**
     * Шаблон вывода render
     * @var string
     */
    public $renderView = '@reketaka/helps/common/actions/crudReset/create/views/index';

    public $redirect = 'index';

    /**
     * @var $model ActiveRecord
     */
    public $model = null;

    private function formatColumns(){
        if(!$this->columns){
            $this->columns = array_merge($this->columns, array_keys($this->model->attributes));
        }


        if(in_array('id', $this->columns)){
            $key = array_search('id', $this->columns);
            unset($this->columns[$key]);
        }

    }

    public function run(){

        $this->formatColumns();
        $this->metaCall();

        if(Yii::$app->request->isPost && ($this->model->load(Yii::$app->request->post())) && $this->model->validate() && $this->model->save()){
            return $this->controller->redirect([$this->redirect, 'id'=>$this->model->getPrimaryKey()]);
        }

        return $this->controller->render($this->renderView, [
            'model'=>$this->model,
            'columns'=>$this->columns
        ]);
    }

}