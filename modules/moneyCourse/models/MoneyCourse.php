<?php

namespace reketaka\helps\modules\moneyCourse\models;

use Yii;
use yii\helpers\Console;
use yii\httpclient\Client;

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

    public static function refreshValutes(){
        $module = \Yii::$app->getModule('mc');

        echo "Send request to get valutes".PHP_EOL;

        $client = new Client();
        $request = $client->get($module->refreshSberUrl)
            ->send();

        if(!$request->isOk){
            echo Console::ansiFormat("Can't get data from url", [Console::FG_YELLOW]).PHP_EOL;
        }

        echo "Data success get".PHP_EOL;

        if(!array_key_exists('Valute', $request->data)){
            echo "Data don't have search key ".Console::ansiFormat("Valute", [Console::FG_YELLOW]).PHP_EOL;
            return false;
        }

        if(($total = count($request->data['Valute'])) && ($total <= 0)){
            echo "Amount valute is zero".PHP_EOL;
            return false;
        }


        $db = \Yii::$app->db;
        $transaction = $db->beginTransaction();

        try {
            echo "Clear database moneyCourse" . PHP_EOL;
            $db->createCommand()->truncateTable(MoneyCourse::tableName());

            echo Console::startProgress(($row = 0), $total, ($startPrefix = 'Update MoneyCourse'));

            foreach ($request->data['Valute'] as $item) {

                $nominal = 1;
                $moneyItem = new self([
                    'name' => $item['Name'],
                    'char' => $item['CharCode'],
                    'val' => str_replace(',', '.', $item['Value']) / $nominal,
                    'date' => \Yii::$app->formatter->asDatetime(time(), "Y-MM-dd H:i:s")
                ]);

                $moneyItem->save();

                $row++;
                echo Console::updateProgress($row, $total, $startPrefix . " {$moneyItem->char}");

            }

            echo Console::endProgress();

            $transaction->commit();

            echo "Update MoneyCourse success".PHP_EOL;

        }catch (\Exception $exception){
            $transaction->rollBack();
        }
    }
}
