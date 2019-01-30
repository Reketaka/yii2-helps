<?php

namespace reketaka\helps\modules\adminMenu\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "menu_item_roles".
 *
 * @property int $id
 * @property int $menu_item_id
 * @property string $role_name
 *
 * @property MenuItem $menuItem
 */
class MenuItemRoles extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'menu_item_roles';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['menu_item_id'], 'integer'],
            [['role_name'], 'string'],
            [['menu_item_id'], 'exist', 'targetRelation' => 'menuItem', 'skipOnError' => false, 'skipOnEmpty' => false],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => yii::t('app', 'id'),
            'menu_item_id' => yii::t('app', 'menu item id'),
            'role_name' => yii::t('app', 'role name'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMenuItem()
    {
        return $this->hasOne(MenuItem::className(), ['id' => 'menu_item_id']);
    }
}
