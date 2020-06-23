<?php

namespace reketaka\helps\common\actions\crudReset\update;


use Closure;
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
 *           'class' => 'reketaka\helps\common\actions\crudReset\delete\UpdateAction',
 *       ]
 *    ];
 *  }
 *```php
 *
 * @package reketaka\helps\common\actions\crudReset\update
 *
 */
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
            $this->columns = array_merge($this->columns, array_keys($this->model->attributes));
        }

        foreach(['id', 'created_at', 'updated_at'] as $column):
            if(in_array($column, $this->columns)){
                $key = array_search($column, $this->columns);
                unset($this->columns[$key]);
            }
        endforeach;


    }

    public function run($id){

        $this->model = $this->controller->findModel($id);

        if($this->scenario){
            $this->model->setScenario($this->scenario);
        }

        if($this->afterFindCallback instanceof Closure){
            $func = $this->afterFindCallback;
            $func($this->model);
        }

        $this->formatColumns();
        $this->metaCall();

        if(Yii::$app->request->isPost && ($this->model->load(Yii::$app->request->post())) && $this->model->validate() && $this->model->save()){
            return $this->controller->redirect([$this->redirect, 'id'=>$this->model->getPrimaryKey()]);
        }

        return $this->controller->render($this->renderView, [
            'model'=>$this->model,
            'columns'=>$this->columns,
            'optionals'=>$this->optionals,
            'booleanAttributes'=>$this->booleanAttributes,
            'dateAttributes'=>$this->dateAttributes,
            'selectAttributes'=>$this->selectAttributes
        ]);
    }

}