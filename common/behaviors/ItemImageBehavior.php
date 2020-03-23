<?php

namespace reketaka\helps\common\behaviors;

use common\models\BaseHelper;
use function file_exists;
use Imagine\Image\ManipulatorInterface;
use function pathinfo;
use yii\base\Behavior;
use Yii;
use yii\db\ActiveRecord;
use yii\helpers\FileHelper;
use yii\imagine\Image;

class ItemImageBehavior extends Behavior{

    public $imageAttribute = 'image';
    public $webRoot = null;
    public $serverRoot = "@frontend/web/";
    public $mode = ManipulatorInterface::THUMBNAIL_INSET;

    public function events()
    {
        return [
            ActiveRecord::EVENT_AFTER_DELETE => 'afterDelete',
        ];
    }

    public function isImageExist(){
        $file = Yii::getAlias("$this->serverRoot{$this->owner->{$this->imageAttribute}}");
        if(is_dir($file)){
            return false;
        }
        return file_exists($file);
    }

    public function resize($width, $height, $web = false){
        if(!$this->isImageExist()){
            return false;
        }

        if(!$pathInfo = pathinfo($this->getImagePath())){
            return false;
        }

        $newFilePath = $pathInfo['dirname']."/".$pathInfo['filename']."_".$width."_".$height.".".$pathInfo['extension'];

        if(file_exists($newFilePath)){
            if($web){
                return str_replace(Yii::getAlias("@frontend/web/"), '', $newFilePath);
            }
            return $newFilePath;
        }

        Image::thumbnail($this->getImagePath(), $width, $height, $this->mode)
            ->save($newFilePath, ['quality' => 80]);

        if($web){
            return str_replace(Yii::getAlias("@frontend/web/"), '', $newFilePath);
        }

        return $newFilePath;
    }

    public function getImagePath($web = false){
        if($web){
            if($this->webRoot instanceof \Closure){
                $f = $this->webRoot;
                return $f($this->owner->{$this->imageAttribute});
            }
            return Yii::$app->params['domains.frontend'].$this->owner->{$this->imageAttribute};
        }

        return Yii::getAlias("$this->serverRoot{$this->owner->{$this->imageAttribute}}");
    }

    public function afterDelete($event){
        if($this->isImageExist()){
            @unlink($this->getImagePath());
        }
    }

}