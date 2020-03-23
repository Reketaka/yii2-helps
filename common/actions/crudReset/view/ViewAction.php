<?php

namespace reketaka\helps\common\actions\crudReset\view;

use Closure;
use function array_key_exists;
use function array_map;
use function array_merge;
use function array_shift;
use function array_unshift;
use function in_array;
use reketaka\helps\common\actions\crudReset\BaseAction;
use reketaka\helps\common\helpers\Bh;
use Yii;
use yii\base\Action;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * Class ViewAction
 *
 * ```php
 * public function actions()
 * {
 *  return [
 *       'view' => [
 *           'class' => 'reketaka\helps\common\actions\crudReset\view\ViewAction',
 *       ]
 *    ];
 *  }
 *```php
 *
 * @package reketaka\helps\common\actions\crudReset\view
 *
 */
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

        if($this->columns instanceof \Closure){
            $columnFunction = $this->columns;
            $this->columns = $columnFunction($this->model);
        }

        if($this->columns){
            $this->columns = array_map(function($v){
                if(!is_array($v)){
                    return $v;
                }

                if(!$value = ArrayHelper::getValue($v, 'value', false)){
                    return $v;
                }

                $resultValue = $value($this->model);
                $v['value'] = $resultValue;
                return $v;


            }, $this->columns);

        }

        if(!$this->columns){
            $this->columns = array_keys($this->model->attributes);
        }


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

        return $this->controller->render($this->renderView, [
            'model'=>$this->model,
            'columns'=>$this->columns,
            'optionals'=>$this->optionals
        ]);
    }

}