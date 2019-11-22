<?php

namespace reketaka\helps\common\widgets\resultTable;

use yii\base\Widget;

class ResultTableWidget extends Widget{

    public $models;
    public $columns;
    public $tableAttributes = [];

    public function run()
    {


        return $this->render('index', [
            'models'=>$this->models,
            'columns'=>$this->columns,
            'tableAttributes'=>$this->tableAttributes
        ]);
    }

}