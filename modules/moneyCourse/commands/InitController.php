<?php

namespace reketaka\helps\modules\moneyCourse\commands;

use common\helpers\BaseHelper;
use reketaka\helps\modules\moneyCourse\models\MoneyCourse;
use yii\console\Controller;
use yii\helpers\Console;
use yii\httpclient\Client;

class InitController extends Controller{

    /**
     * Запускает обновление курсов валют со сбербанка
     */
    public function actionRun(){
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
                $moneyItem = new MoneyCourse([
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