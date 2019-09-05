<?php

namespace reketaka\helps\modules\catalog\models;


use reketaka\helps\common\models\CommonRecord;

class ItemStore extends CommonRecord{

    public $behaviorTimestamp = true;

    public static function tableName()
    {
        return 'reketaka_item_store';
    }

    /**
     * @return array
     */
    public function rules(){
        return [
            [['item_id', 'store_id', 'amount'], 'integer'],
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'title'=>\Yii::t('app', 'title'),
            'item_id'=>\Yii::t('app', 'item_id'),
            'store_id'=>\Yii::t('app', 'store_id'),
            'uid'=>\Yii::t('app','uid'),
            'created_at'=>\Yii::t('app','created_at'),
            'updated_at'=>\Yii::t('app', 'updated_at')
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStore(){
        return $this->hasOne(Store::class, ['id'=>'store_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItem(){
        return $this->hasOne(Item::class, ['id'=>'item_id']);
    }

}