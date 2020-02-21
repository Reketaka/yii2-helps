<?php

namespace reketaka\helps\modules\catalog\models;

use reketaka\helps\common\behaviors\FileBehavior;
use reketaka\helps\common\helpers\Bh;
use Yii;
use common\models\CommonRecord;
use reketaka\helps\modules\catalog\Module;
use function array_merge;
use function explode;

/**
 * This is the model class for table "send_price".
 *
 * @property int $id
 * @property string|null $emails
 * @property int|null $round_price
 * @property int|null $with_active
 * @property int|null $active
 * @property string $file_path
 * @property string $last_send_date
 * @property integer $discount_id
 * @property string $email_header
 * @property string $email_content
 *
 * @property Discount $discount
 *
 * @mixin FileBehavior
 */
class SendPrice extends CommonRecord
{
    CONST PATH_ROOT = '@console/runtime/price_send/';

    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            [
                'class'=>'reketaka\helps\common\behaviors\FileBehavior',
                'fileAttribute' => 'file_path',
                'serverRoot' => self::PATH_ROOT
            ]
        ]);
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'send_price';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['emails'], 'required'],
            [['emails'], 'each', 'rule'=>['email', 'skipOnError'=>false, 'skipOnEmpty'=>false]],
            [['round_price', 'with_active', 'active', 'discount_id'], 'integer'],
            [['round_price', 'with_active', 'active'], 'default', 'value'=>0],
            [['file_path', 'email_header', 'email_content'], 'string'],
            [['file_path', 'discount_id', 'email_header', 'email_content'], 'default', 'value'=>null]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Module::t('app', 'id'),
            'emails' => Module::t('app', 'emails'),
            'round_price' => Module::t('app', 'round price'),
            'with_active' => Module::t('app', 'with active'),
            'active' => Module::t('app', 'active'),
            'file_path'=>Module::t('app', 'file_path'),
            'last_send_date'=>Module::t('app', 'last_send_date'),
            'discount_id'=>Module::t('app', 'discount id'),
            'email_header'=>Module::t('app', 'email_header'),
            'email_content'=>Module::t('app', 'email_content')
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDiscount(){
        return $this->hasOne(Discount::class, ['id'=>'discount_id']);
    }

    public function afterFind() {
        parent::afterFind();
        $this->emails = \yii\helpers\Json::decode($this->emails);
    }

    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }

        $this->emails = \yii\helpers\Json::encode($this->emails);

        return true;
    }

    /**
     * @inheritdoc
     */
    public function beforeDelete()
    {
        if (!parent::beforeDelete()) {
            return false;
        }

        return true;
    }

}