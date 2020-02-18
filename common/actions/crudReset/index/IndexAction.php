<?php

namespace reketaka\helps\common\actions\crudReset\index;

use function array_key_exists;
use function array_keys;
use function array_merge;
use function array_shift;
use function array_unshift;
use function in_array;
use reketaka\helps\common\actions\crudReset\BaseAction;
use reketaka\helps\common\helpers\Bh;
use Yii;
use yii\base\Action;
use yii\db\ActiveRecord;

/**
 * Class IndexAction
 *
 * ```php
 * public function actions()
 * {
 *  return [
 *       'index' => [
 *           'class' => 'reketaka\helps\common\actions\crudReset\index\IndexAction',
 *           'searchModel'=>new SiteSearch()
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

    private function formatColumns(){
        if(!$this->columns){
            $this->columns = array_keys($this->searchModel->attributes);
        }

        if($this->columns instanceof \Closure){
            $columnFunction = $this->columns;
            $this->columns = $columnFunction($this->searchModel);
        }

        if(in_array('id', $this->columns)){
            $key = array_search('id', $this->columns);
            unset($this->columns[$key]);

            array_unshift($this->columns, [
                'attribute'=>'id',
                'options'=>[
                    'style'=>'width:100px'
                ]
            ]);
            
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