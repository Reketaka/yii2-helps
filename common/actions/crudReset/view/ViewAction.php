<?php

namespace reketaka\helps\common\actions\crudReset\view;

use Closure;
use function array_fill_keys;
use function array_intersect;
use function array_key_exists;
use function array_keys;
use function array_map;
use function array_merge;
use function array_shift;
use function array_unshift;
use function explode;
use function in_array;
use reketaka\helps\common\actions\crudReset\BaseAction;
use reketaka\helps\common\helpers\Bh;
use Yii;
use yii\base\Action;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use function str_replace;
use function strrpos;

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

    public $dictionaryAttributes = [];

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
            $this->columns = array_combine(array_keys($this->model->attributes), array_keys($this->model->attributes));

            foreach(array_intersect($this->booleanAttributes, $this->columns) as $column){
                $this->columns[$column] = "$column:boolean";
            }

            foreach(array_intersect(array_keys($this->selectAttributes), $this->columns) as $column){


                $mode = null;
                $columnValues = $this->selectAttributes[$column];
                $columnValues = explode(":", $columnValues);
                $columnValue = array_shift($columnValues);

                if(strrpos($this->selectAttributes[$column], self::DICTIONARY_POSTFIX) !== FALSE) {
                    $columnValue = str_replace(self::DICTIONARY_POSTFIX, "", $this->selectAttributes[$column]);
                    $mode = self::DICTIONARY_POSTFIX;
                }

                $modeRelation = null;
                $modeRelationTitle = null;
                if(strrpos($this->selectAttributes[$column], self::RELATION_POSTFIX) !== FALSE){
                    $mode = self::RELATION_POSTFIX;

                    $relationData = array_shift($columnValues);
                    $relationData = str_replace([str_replace(":", "", self::RELATION_POSTFIX), '(', ')'], "", $relationData);
                    $relationData = explode(", ", $relationData);
                    list($modeRelation, $modeRelationTitle) = $relationData;
                }

                $this->columns[$column] = [
                    'attribute'=>$column,
                    'format'=>'raw',
                    'value'=>function()use($column, $modeRelationTitle, $modeRelation, $mode){
                        if($mode == self::RELATION_POSTFIX){
                            return $this->model->$modeRelation->$modeRelationTitle;
                        }
                    }
                ];
            }

            foreach(array_intersect($this->timestampAttributes, array_keys($this->columns)) as $column){
                $this->columns[$column] = "$column:datetime";
            }

            foreach(array_intersect($this->dictionaryAttributes, array_keys($this->columns)) as $column){

                $this->columns[$column] = [
                    'attribute'=>$column,
                    'format'=>'raw',
                    'value'=>function()use($column){
                        return $this->model->getDictionaryValue($column);
                    }
                ];

            }
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