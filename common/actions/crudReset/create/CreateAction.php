<?php

namespace reketaka\helps\common\actions\crudReset\create;


use Closure;
use reketaka\helps\common\actions\crudReset\BaseAction;
use reketaka\helps\common\helpers\Bh;
use Yii;
use yii\base\Action;
use yii\db\ActiveRecord;
use function array_search;
use function in_array;

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

        foreach(['id', 'created_at', 'updated_at'] as $column):
            if(in_array($column, $this->columns)){
                $key = array_search($column, $this->columns);
                unset($this->columns[$key]);
            }
        endforeach;

    }

    public function run(){

        if($this->optionalsClosure instanceof \Closure){
            $m = $this->optionalsClosure;
            $this->optionals = $m();
        }

        if($this->afterInitCallback instanceof Closure){
            $func = $this->afterInitCallback;
            $func($this->model);
        }

        $this->formatColumns();
        $this->metaCall();

        if(Yii::$app->request->isPost && ($this->model->load(Yii::$app->request->post())) && $this->model->validate() && $this->model->save()){

            if (Yii::$app->request->isAjax) {
                // JSON response is expected in case of successful save
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return ['success' => true];
            }

            return $this->controller->redirect([$this->redirect, 'id'=>$this->model->getPrimaryKey()]);
        }



        return $this->render($this->renderView, [
            'model'=>$this->model,
            'columns'=>$this->columns,
            'optionals'=>$this->optionals,
            'booleanAttributes'=>$this->booleanAttributes,
            'dateAttributes'=>$this->dateAttributes,
            'selectAttributes'=>$this->selectAttributes,
            'optionalsClosure'=>$this->optionalsClosure
        ]);
    }

}