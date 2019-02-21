<?php

namespace reketaka\helps\modules\moneyCourse\models;

use Yii;

/**
 * This is the model class for table "money_course".
 *
 * @property integer $id
 * @property string $name
 * @property string $char
 * @property double $val
 * @property string $date
 */
class MoneyCourse extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'reketaka_money_course';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['val'], 'double'],
            [['date'], 'safe'],
            [['name', 'char'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название',
            'val' => 'Значение',
            'date' => 'Дата обновления',
        ];
    }

    /**
     * Пример MoneyCourse::convert("UAH", 299)
     * @param string $t
     * @param int $a
     * @return float|int|string
     */
    public static function convert($t = "UAH", $a = 1){
        $course = self::findOne(['char'=>$t]);
        $v = $course->val;


        $r = $a/$v;
        $r = Yii::$app->formatter->asDecimal($r);
        return $r;
    }

    /**
     * Конвентирует рубли в указанную валюту
     */
    public static function convertIn($t = "UAH", $a = 1){
        $course = self::findOne(['char'=>$t]);
        $v = $course->val;


        $r = $a*$v;
        $r = Yii::$app->formatter->asDecimal($r);
        return $r;
    }
}
