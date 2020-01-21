<?php

namespace reketaka\helps\common\actions\crudReset\update;


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