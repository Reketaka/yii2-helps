<?php

namespace reketaka\helps\modules\adminMenu\models;

use common\models\User;
use Yii;
use common\models\CommonRecord;

/**
 * This is the model class for table "menu_item_user".
 *
 * @property int $id
 * @property int $menu_item_id
 * @property int $menu_section_id
 * @property int $order
 * @property int $user_id
 *
 * @property MenuItem $menuItem
 * @property MenuSectionUser $menuSection
 */
class MenuItemUser extends CommonRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'menu_item_user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['menu_item_id', 'menu_section_id', 'order'], 'integer'],
            [['order'], 'default', 'value' => 0],
            [['menu_item_id'], 'exist', 'targetRelation' => 'menuItem', 'skipOnError' => false, 'skipOnEmpty' => false],
            [['menu_section_id'], 'exist', 'targetRelation' => 'menuSection', 'skipOnEmpty' => false, 'skipOnError' => false],
            [['user_id'], 'exist', 'targetRelation' => 'user', 'skipOnError' => false, 'skipOnEmpty' => false],
            [['menu_section_id'], function($attr){
                if(($menuSection = $this->menuSection) && ($menuSection->user_id != $this->user_id)){
                    $this->addError($attr, 'Секция меню не принадлежит выбранному пользователю');
                    return false;
                }
            }]
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
            'menu_section_id' => yii::t('app', 'menu section id'),
            'order' => yii::t('app', 'order'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMenuItem()
    {
        return $this->hasOne(MenuItem::className(), ['id' => 'menu_item_id']);
    }

    public function getUser(){
        return $this->hasOne(User::class, ['id'=>'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMenuSection()
    {
        return $this->hasOne(MenuSectionUser::className(), ['id' => 'menu_section_id']);
    }
}
