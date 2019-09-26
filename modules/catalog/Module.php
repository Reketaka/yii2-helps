<?php

namespace reketaka\helps\modules\catalog;

use common\helpers\BaseHelper;
use yii\db\Schema;

class Module extends \yii\base\Module{

    CONST TYPE = 'type';

    public static $tablePrefix = '';

    public $tableItemFields = [];
    public $defaultRoute = ['default/index'];

    public function init(){
        parent::init();

        if (\Yii::$app instanceof \yii\console\Application) {
            $this->controllerNamespace = 'reketaka\helps\modules\catalog\commands';
        }
    }

    public function getItemsFields(){
        $schema = \Yii::$app->getDb()->getSchema();

        $baseItemFields = [
            'id'=>[
                self::TYPE=>$schema->createColumnSchemaBuilder(Schema::TYPE_PK)
            ],
            'title'=>[
                self::TYPE=>$schema->createColumnSchemaBuilder(Schema::TYPE_STRING)->null()
            ],
            'uid'=>[
                self::TYPE=>$schema->createColumnSchemaBuilder(Schema::TYPE_STRING)->null()->asIndex()
            ],
            'total_amount'=>[
                self::TYPE=>$schema->createColumnSchemaBuilder(Schema::TYPE_INTEGER)->defaultValue(0)
            ],
            'created_at'=>[
                self::TYPE=>$schema->createColumnSchemaBuilder(Schema::TYPE_DATETIME)->asIndex()
            ],
            'updated_at'=>[
                self::TYPE=>$schema->createColumnSchemaBuilder(Schema::TYPE_DATETIME)->asIndex()
            ]
        ];

        foreach($this->tableItemFields as $key=> $fieldCallback){
            $this->tableItemFields[$key] = $fieldCallback($schema);
        }

        $this->tableItemFields = array_merge($baseItemFields, $this->tableItemFields);

        return $this->tableItemFields;
    }
}