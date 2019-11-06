<?php

namespace reketaka\helps\modules\catalog\models;

use Yii;

/**
 * This is the model class for table "catalog".
 *
 * @property int $id
 * @property string $title
 * @property string $alias
 * @property string $uid
 * @property string $description
 * @property integer $parent_id
 * @property string $created_at
 * @property string $updated_at
 */
class Catalog extends BaseModel
{
    CONST ROOT_CATALOG_ID = 0;

    public $behaviorTimestamp = true;
    public $behaviorAlias = true;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'catalog';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parent_id'], 'integer'],
            [['description'], 'string'],
            [['title', 'alias', 'uid'], 'string', 'max' => 255],
            [['description', 'uid'], 'default', 'value'=>null],
            [['uid'], 'unique'],
            [['parent_id'], 'default', 'value'=>self::ROOT_CATALOG_ID]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => yii::t('app', 'id'),
            'title' => yii::t('app', 'title'),
            'alias' => yii::t('app', 'alias'),
            'uid' => yii::t('app', 'uid'),
            'description' => yii::t('app', 'description'),
            'created_at' => yii::t('app', 'created at'),
            'updated_at' => yii::t('app', 'updated at'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParent(){
        return $this->hasOne(self::class, ['id'=>'parent_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChilds(){
        return $this->hasMany(self::class, ['parent_id'=>'id']);
    }
}
