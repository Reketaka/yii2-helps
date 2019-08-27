<?php

namespace reketaka\helps\common\models\db\mysql;

class ColumnSchemaBuilder extends \yii\db\mysql\ColumnSchemaBuilder{

    protected $asIndex = false;
    protected $asIndexUniq = false;

    public function isIndex(){
        return $this->asIndex;
    }

    public function asIndex($uniq = false){
        $this->asIndex = true;
        $this->asIndexUniq = $uniq;
        return $this;
    }

    public function getUniqIndex(){
        return $this->asIndexUniq;
    }
}