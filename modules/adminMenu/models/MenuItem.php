<?php

namespace reketaka\helps\modules\adminMenu\models;

use Yii;
use common\models\CommonRecord;

/**
 * This is the model class for table "menu_item".
 *
 * @property int $id
 * @property string $title
 * @property string $alias
 * @property string $url
 * @property int $section_id
 * @property int $order
 *
 * @property MenuSection $section
 * @property MenuItemRoles[] $menuItemRoles
 */
class MenuItem extends CommonRecord
{
    public $behaviorAlias = true;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'menu_item';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['section_id', 'order'], 'integer'],
            [['order'], 'default', 'value' => 0],
            [['title', 'alias', 'url'], 'string'],
            [['section_id'], 'exist', 'targetRelation' => 'section', 'skipOnEmpty' => false, 'skipOnError' => false],
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
            'url' => yii::t('app', 'url'),
            'section_id' => yii::t('app', 'section id'),
            'order' => yii::t('app', 'order'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSection()
    {
        return $this->hasOne(MenuSection::className(), ['id' => 'section_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMenuItemRoles()
    {
        return $this->hasMany(MenuItemRoles::className(), ['menu_item_id' => 'id']);
    }

    public function beforeDelete()
    {
        if (!parent::beforeDelete()) {
            return false;
        }

        foreach($this->menuItemRoles as $role){
            $role->delete();
        }

        return true;
    }
}
