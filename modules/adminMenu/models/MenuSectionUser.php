<?php

namespace reketaka\helps\modules\adminMenu\models;

use Yii;
use common\models\CommonRecord;
use common\models\User;

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
class MenuSectionUser extends CommonRecord
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
    public function getMenuItemUsers()
    {
        return $this->hasMany(MenuItemUser::className(), ['menu_section_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
