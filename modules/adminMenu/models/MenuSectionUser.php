<?php

namespace reketaka\helps\modules\adminMenu\models;

use Yii;
use common\models\User;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "menu_section_user".
 *
 * @property int $id
 * @property string $title
 * @property int $user_id
 * @property int $order
 * @property int $parent
 * @property string $created_at
 * @property string $updated_at
 *
 * @property MenuItemUser[] $menuItemUsers
 * @property User $user
 */
class MenuSectionUser extends ActiveRecord
{
    public $behaviorTimestamp = true;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'menu_section_user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'user_id'], 'required'],
            [['user_id', 'order', 'parent'], 'integer'],
            [['order', 'parent'], 'default', 'value' => 0],
            [['title'], 'string'],
            [['user_id'], 'exist', 'targetRelation' => 'user', 'skipOnEmpty' => false, 'skipOnError' => false],
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
            'user_id' => yii::t('app', 'user id'),
            'order' => yii::t('app', 'order'),
            'parent' => yii::t('app', 'parent'),
            'created_at' => yii::t('app', 'created at'),
            'updated_at' => yii::t('app', 'updated at'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMenuItemUser()
    {
        return $this->hasMany(MenuItemUser::className(), ['menu_section_id' => 'id']);
    }

    public function getMenuItems(){
        return $this->hasMany(MenuItem::class, ['id'=>'menu_item_id'])
            ->via('menuItemUser');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(Yii::$app->getModule('adminmenu')->userModelClass, ['id' => 'user_id']);
    }

    public static function getHierarchyArray($parent = 0, $userId=false){
        $sections = MenuSectionUser::find()
            ->where(['user_id'=>!$userId?Yii::$app->user->getId():$userId])
            ->andWhere(['parent'=>$parent])
            ->orderBy(['order'=>SORT_ASC])
            ->all();

        $r = [];

        foreach($sections as $section){
            $t = [
                'title'=>$section->title,
                'id'=>$section->id
            ];

            if($items = self::getHierarchyArray($section->id)){
                $t['items'] = $items;
            }

            $r[] = $t;
        }


        return $r;
    }

    public function beforeDelete()
    {
        if (!parent::beforeDelete()) {
            return false;
        }

        foreach($this->menuItemUser as $menuItemUser){
            $menuItemUser->delete();
        }

        return true;
    }
}
