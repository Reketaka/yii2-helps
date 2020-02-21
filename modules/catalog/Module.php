<?php

namespace reketaka\helps\modules\catalog;

use function array_keys;
use common\helpers\BaseHelper;
use yii\db\Schema;

class Module extends \yii\base\Module{

    CONST TYPE = 'type';

    public static $tablePrefix = '';

    public $tableItemFields = [];
    public $itemClass = 'reketaka\helps\modules\catalog\models\Item';
    public $defaultRoute = 'default/index';
    public $attributesSendPrice = [];

    public function init(){
        parent::init();

        if (\Yii::$app instanceof \yii\console\Application) {
            $this->controllerNamespace = 'reketaka\helps\modules\catalog\commands';
        }

        #$this->registerTranslations();
    }

    public function getFields(){
        if(!$this->tableItemFields){
            return [];
        }

        return array_keys($this->tableItemFields);
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
            'active'=>[
                self::TYPE=>$schema->createColumnSchemaBuilder(Schema::TYPE_SMALLINT)->defaultValue(1),
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

    public static function t($category, $message, $params = [], $language = null)
    {
        return \Yii::t('modules/catalog/' . $category, $message, $params, $language);
    }

}