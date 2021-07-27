<?php

namespace reketaka\helps\common\models\db\mysql;

class ColumnSchemaBuilder extends \yii\db\mysql\ColumnSchemaBuilder{

    protected $asIndex = false;
    protected $asIndexUniq = false;
    protected $asForeignKey = false;
    public $referenceTable = '';
    public $referenceColumn = '';

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

    public function isForeignKey()
    {
        return $this->asForeignKey;
    }

    /*
     * ```
     * 'column' => $this->integer()->asForeignKey('user', 'id'),
     * ```
     */
    public function asForeignKey($referenceTable, $referenceColumn)
    {
        $this->asForeignKey = true;
        $this->referenceTable = $referenceTable;
        $this->referenceColumn = $referenceColumn;
        return $this;
    }
}