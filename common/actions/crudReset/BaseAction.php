<?php

namespace reketaka\helps\common\actions\crudReset;

use Closure;
use reketaka\helps\common\helpers\Bh;
use Yii;
use yii\base\Action;
use yii\db\ActiveRecord;
use function array_merge;

class BaseAction extends Action{

    CONST DICTIONARY_POSTFIX = ':dictionary';
    CONST RELATION_POSTFIX = ':relation';
    /**
     * Шаблон вывода render
     * @var string
     */
    public $renderView;

    /**
     * Либо string или Callable
     * @var $title
     */
    public $title;

    /**
     * Либо string или Callable
     * @var $h1
     */
    public $h1;

    public $description;

    public $breadcrumbs = null;

    public $scenario = null;

    public $afterFindCallback = null;
    public $afterInitCallback = null;

    /**
     * @var $model ActiveRecord
     */
    public $model;

    public $booleanAttributes = ['active'];
    public $dateAttributes = [];
    public $selectAttributes = [];
    public $timestampAttributes = [];


    /**
     * Для передачи дополнительных параметров
     * @var array
     */
    public $optionals = [];

    /**
     * Для передачи дополнительных параметров но инициализация при активном методе только
     */
    public $optionalsClosure = null;

    /**
     * Выберает метод render or renderAjax, добавляет параметр renderMethod
     * @param $view
     * @param array $params
     * @return string
     */
    public function render($view, $params = []){
        $methodRender = Yii::$app->request->isAjax?"renderAjax":"render";

        $params = array_merge($params, [
            'renderMethod'=>$methodRender
        ]);

        return $this->controller->$methodRender($this->renderView, $params);
    }

    protected function generateMeta($variable){

        if(!$this->$variable){
            return false;
        }

        if($this->$variable instanceof Closure) {
            $function = $this->$variable;
            $this->controller->view->$variable = $function($this->model);
        }else{
            $this->controller->view->$variable = $this->$variable;
        }

    }

    protected function generateBreadcrumbs(){
        $breadcrumbs = $this->breadcrumbs;

        if(!($breadcrumbs instanceof Closure)){
            return false;
        }

        $breadcrumbs($this->model);

        return true;
    }

    protected function metaCall(){
        $this->generateMeta('title');
        $this->generateMeta('h1');
        $this->generateMeta('description');
        $this->generateBreadcrumbs();
        return true;
    }

}