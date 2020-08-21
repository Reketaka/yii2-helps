<?php

namespace reketaka\helps\common\actions\crudReset\index;

use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use function array_combine;
use function array_intersect;
use function array_key_exists;
use function array_keys;
use function array_merge;
use function array_search;
use function array_shift;
use function array_unshift;
use function in_array;
use reketaka\helps\common\actions\crudReset\BaseAction;
use reketaka\helps\common\helpers\Bh;
use Yii;
use yii\base\Action;
use yii\db\ActiveRecord;
use function str_replace;
use function strrpos;

/**
 * Class IndexAction
 *
 * ```php
 * public function actions()
 * {
 *  return [
 *       'index' => [
 *           'class' => 'reketaka\helps\common\actions\crudReset\index\IndexAction',
 *           'searchModel'=>new SiteSearch(),
 *           'selectAttributes' => [
 *              'organization_id'=>'organizations:relation(organization, title)',
 *              'city_id'=>'cities:dictionary'
 *           ],
 *           'optionalsClosure'=>function(){
 *              return [
 *                 'organizations'=>OrganizationRecord::getArrayMap(),
 *                 'cities'=>[]
 *              ];
 *            }
 *       ]
 *    ];
 *  }
 *```php
 *
 * @package reketaka\helps\common\actions\crudReset\index
 *
 */
class IndexAction extends BaseAction {


    public $columns = [];
    /**
     * @var $searchModel ActiveRecord
     */
    public $searchModel;
    /**
     * Добавляет элементы управления CRUD
     * @var bool
     */
    public $addActions = true;

    /**
     * Шаблон вывода render
     * @var string
     */
    public $renderView = '@reketaka/helps/common/actions/crudReset/index/views/index';

    /**
     * Ключ атрибут значение ключ из массива optionalClosure
     * при указании в значении :dictionary Будет вызван метод модели getDictionaryValue для атрибута
     * @var array
     */
    public $selectAttributes = [];

    private function formatColumns(){
        if(!$this->columns){
            $keys = array_keys($this->searchModel->attributes);
            $this->columns = array_combine($keys, $keys);

            foreach(array_intersect($this->booleanAttributes, array_keys($this->columns)) as $column){
                $this->columns[$column] = "$column:boolean";
            }

            foreach(array_intersect($this->timestampAttributes, array_keys($this->columns)) as $column){
                $this->columns[$column] = "$column:datetime";
            }

            foreach(array_intersect(array_keys($this->selectAttributes), array_keys($this->columns)) as $column){

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
                    'attribute' => $column,
                    'format' => 'raw',
                    'content' => function ($model)use($column, $mode, $modeRelation, $modeRelationTitle) {
                        if($mode == self::DICTIONARY_POSTFIX) {
                            return $model->getDictionaryValue($column);
                        }
                        if($mode == self::RELATION_POSTFIX){
                            return $model->$modeRelation->$modeRelationTitle;
                        }
                    },
                    'filter' => Select2::widget([
                        'attribute' => $column,
                        'data' => $this->optionals[$columnValue],
                        'model' => $this->searchModel
                    ])
                ];
            }


        }

        if($this->columns instanceof \Closure){
            $columnFunction = $this->columns;
            $this->columns = $columnFunction($this->searchModel, $this->optionals);
        }


        if(in_array('id', $this->columns)){
            $key = array_search('id', $this->columns);
            unset($this->columns[$key]);

            $this->columns = [
                'id'=>[
                    'attribute'=>'id',
                    'options'=>[
                        'style'=>'width:100px'
                    ]
                ]
            ]+$this->columns;

        }



        if(!array_key_exists('actions', $this->columns) && $this->addActions){
            $this->columns['actions'] = [
                'class' => 'yii\grid\ActionColumn',
                'options'=>[
                    'style'=>'width:75px;'
                ]
            ];
        }


    }

    public function run(){

        $dataProvider = $this->searchModel->search(Yii::$app->request->queryParams);

        if($this->optionalsClosure instanceof \Closure){
            $m = $this->optionalsClosure;
            $this->optionals = $m();
        }

        $this->formatColumns();

        $this->metaCall();




        return $this->controller->render($this->renderView, [
            'searchModel' => $this->searchModel,
            'dataProvider' => $dataProvider,
            'columns'=>$this->columns,
            'addActions'=>$this->addActions,
            'optionals'=>$this->optionals
        ]);
    }

}