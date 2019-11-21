<?php

namespace reketaka\helps\modules\onec;

use Yii;
use yii\helpers\FileHelper;

class Module extends \yii\base\Module{

    public $userName = 'test';
    public $maxFileSize = 102400;
    public $enableZip = true;
    public $saveDirPath = '@console/runtime/onec';
    public $saleQuery = null;
    public $saleSuccess = null;

    /**
     * Название переменной в которой хранятся успешно обработанные 1с-кой заказы
     * @var string
     */
    public $sessionKeyOrderUpload = 'onecOrderSuccessUpload';

    public function init(){
        parent::init();
    }

    /**
     * Возвращает путь по которому будут хранится файлы которые готовы к обработке
     * @return bool|string
     */
    public function getProgressDirPath(){
        return $this->saveDirPath.'/progress/';
    }

    /**
     * Возвращает путь где будут хранится новые файлы
     * @return bool|string
     */
    public function getNewDirPath(){
        return $this->saveDirPath.'/new/';
    }

    /**
     * Возвращает путь в который нужно сохранить обработанный файлы
     * @return bool|string
     */
    public function getBackupDirPath(){
        return $this->saveDirPath.'/backup/';
    }

    public function unZipFiles(){
        $progressDirPath = Yii::getAlias($this->getProgressDirPath());

        if(!$this->enableZip){
            return false;
        }

        if($this->enableZip && !extension_loaded('zip')){
            return false;
        }

        $files = FileHelper::findFiles($progressDirPath, [
            'only'=>[
                "*.zip"
            ],
        ]);

        foreach($files as $file) {
            $zip = new \ZipArchive;
            if($zip->open($file) !== TRUE){
                continue;
            }

            $zip->extractTo(dirname($file));
            $zip->close();

            unlink($file);
        }
    }

}