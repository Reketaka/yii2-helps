<?php

namespace reketaka\helps\common\behaviors;

use common\models\BaseHelper;
use function file_exists;
use Imagine\Image\ManipulatorInterface;
use function is_dir;
use function pathinfo;
use yii\base\Behavior;
use Yii;
use yii\db\ActiveRecord;
use yii\helpers\FileHelper;
use yii\imagine\Image;
use function str_replace;
use function unlink;

class FileBehavior extends Behavior{

    public $fileAttribute = 'image';

    public $webRoot = null;
    public $serverRoot = "@frontend/web/";

    public function events()
    {
        return [
            ActiveRecord::EVENT_AFTER_DELETE => 'afterDelete',
        ];
    }

    public function isFileExist(){
        $file = Yii::getAlias("$this->serverRoot{$this->owner->{$this->fileAttribute}}");
        if(is_dir($file)){
            return false;
        }
        return file_exists($file);
    }
    
    public function getFilePath($web = false){
        if(!$this->isFileExist()){
            return false;
        }

        if($web){
            if($this->webRoot instanceof \Closure){
                $f = $this->webRoot;
                return $f($this->owner->{$this->fileAttribute});
            }
            return Yii::$app->params['domains.frontend'].$this->owner->{$this->fileAttribute};
        }

        return Yii::getAlias("$this->serverRoot{$this->owner->{$this->fileAttribute}}");
    }

    public function afterDelete($event){
        if($this->isFileExist()){
            @unlink($this->getFilePath());
        }
    }

}