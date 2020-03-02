<?php

namespace reketaka\helps\modules\catalog\models;

use DateTime;
use reketaka\helps\common\helpers\Bh;
use reketaka\helps\modules\catalog\Module;
use Yii;
use yii\base\BaseObject;
use yii\db\ActiveRecord;
use yii\helpers\FileHelper;
use function file_exists;
use function file_put_contents;
use function implode;
use function str_replace;
use function unlink;
use const FILE_APPEND;

class SendPriceWork extends BaseObject{

    /**
     * @var Module $module
     */
    private $module;

    public function init()
    {
        parent::init(); // TODO: Change the autogenerated stub
        $this->module = Yii::$app->getModule('catalog');
    }

    private function workWithPrice(SendPrice $sendPrice){
        $attributesToSendPrice = $this->module->attributesSendPrice;

        $fileDir = Yii::getAlias($sendPrice::PATH_ROOT);
        FileHelper::createDirectory($fileDir);

        $fileName = "{$sendPrice->id}.csv";
        $filePath = $fileDir.$fileName;

        if(file_exists($filePath)){
            unlink($filePath);
        }

        /**
         * @var Item $itemClass
         */
        $itemClass = $this->module->itemClass;

        $demoClass = new $itemClass();

        $items = $itemClass::find()
            ->withPrice();

        if($discount = $sendPrice->discount){
            $items = $items->makeDiscount($discount->value);
        }

        $items = $items
            ->pricePositive()
            ->andWhere(['active'=>$sendPrice->active])
            ->each(100);




        $dataLine = [];
        foreach($attributesToSendPrice as $attribute){
            $dataLine[] = $this->formatLine($demoClass->getAttributeLabel($attribute));
        }

        $dataLine = implode(';', $dataLine)."\r\n";

        file_put_contents($filePath, $dataLine, FILE_APPEND);


        foreach($items as $item){

            $dataLine = [];
            foreach($attributesToSendPrice as $attribute){
                $dataLine[] = $this->formatLine($item->$attribute);
            }

            $dataLine = implode(';', $dataLine)."\r\n";

            file_put_contents($filePath, $dataLine, FILE_APPEND);
        }

        $sendPrice->updateAttributes([
            'last_send_date'=>(new DateTime())->format("Y-m-d H:i:s"),
            'file_path'=>$fileName
        ]);

        if(!$sendPrice->isFileExist()){
            echo "Файл не найден проверьте обработку".PHP_EOL;
            return false;
        }

        Yii::$app->mailer->compose()
            ->setFrom(Yii::$app->params['adminEmail'])
            ->setTo($sendPrice->emails)
            ->setSubject($sendPrice->email_header)
            ->setTextBody($sendPrice->email_content)
            ->setHtmlBody('<p>'.$sendPrice->email_content.'</p>')
            ->attach($filePath)
            ->send();

        echo "Прайс успешно отправлен ".implode(" ", $sendPrice->emails).PHP_EOL;

        return true;
    }

    public function encodeLine($line){
        return iconv("UTF-8", "CP1251", $line);
    }

    private function formatLine($line){

        $value = str_replace([';', '"', ','], ' ', $line);
        $value = $this->encodeLine($value);

        return $value;
    }

    public function run(){

        $items = SendPrice::findAll(['active'=>1]);


        foreach($items as $item){
            $this->workWithPrice($item);
        }


    }

}