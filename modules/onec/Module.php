<?php

namespace reketaka\helps\modules\onec;

use Yii;
use yii\helpers\FileHelper;

class Module extends \yii\base\Module{

    public $userName = 'test';
    public $userPassword = 'test';
    public $authKeyName = 'AuthKey';
    public $authKeyVal = 'pzshkmm0VzIZru65cB1Zsr6o47xZYqpR';
    public $maxFileSize = 102400;
    public $enableZip = true;
    public $saveDirPath = '@console/runtime/onec';
    public $authKeyCallback = false;

    public function init(){
        parent::init();
    }

    /**
     * Возвращает путь по которому будут хранится файлы которые готовы к обработке
     * @return bool|string
     */
    public function getProgressDirPath(){
        return Yii::getAlias($this->saveDirPath.'/progress/');
    }

    /**
     * Возвращает путь в который нужно сохранить обработанный файлы
     * @return bool|string
     */
    public function getBackupDirPath(){
        $path = Yii::getAlias($this->module->saveDirPath.'/backup/');
        FileHelper::createDirectory($path);
        return $path;
    }

}