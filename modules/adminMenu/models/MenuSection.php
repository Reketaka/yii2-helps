<?php

namespace reketaka\helps\modules\adminMenu\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "menu_section".
 *
 * @property int $id
 * @property string $alias
 * @property string $title
 * @property int $order
 *
 * @property MenuItem[] $menuItems
 */
class MenuSection extends ActiveRecord
{
    public $behaviorAlias = true;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'menu_section';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order'], 'integer'],
            [['alias'], 'unique'],
            [['order'], 'default', 'value' => 0],
            [['alias', 'title'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => yii::t('app', 'id'),
            'alias' => yii::t('app', 'alias'),
            'title' => yii::t('app', 'title'),
            'order' => yii::t('app', 'order'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMenuItems()
    {
        return $this->hasMany(MenuItem::className(), ['section_id' => 'id']);
    }
}
