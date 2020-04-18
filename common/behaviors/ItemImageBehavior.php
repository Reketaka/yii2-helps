<?php

namespace reketaka\helps\common\behaviors;

use common\models\BaseHelper;
use Imagine\Image\Box;
use function file_exists;
use Imagine\Image\ManipulatorInterface;
use function getimagesize;
use function pathinfo;
use yii\base\Behavior;
use Yii;
use yii\db\ActiveRecord;
use yii\helpers\FileHelper;
use yii\imagine\Image;
use function round;
use function str_replace;

class ItemImageBehavior extends Behavior{

    public $imageAttribute = 'image';
    public $webRoot = null;
    public $serverRoot = "@frontend/web/";
    public $mode = ManipulatorInterface::THUMBNAIL_OUTBOUND;

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

        FileHelper::createDirectory($pathInfo['dirname']."/thumb/");
        $newFilePath = $pathInfo['dirname']."/thumb/".$pathInfo['filename']."_".$width."_".$height.".".$pathInfo['extension'];

        if(file_exists($newFilePath)){
            if($web){
                return str_replace(Yii::getAlias("@frontend/web/"), '', $newFilePath);
            }
            return $newFilePath;
        }

        if($height == 'auto') {
            $imagine = Image::getImagine();
            $imagine = $imagine->open($this->getImagePath());
            $sizes = getimagesize($this->getImagePath());

            $height = round($sizes[1] * $width / $sizes[0]);
            $imagine = $imagine->resize(new Box($width, $height))->save($newFilePath, ['quality' => 100]);

            if($web){
                return str_replace(Yii::getAlias("@frontend/web/"), '', $newFilePath);
            }

        }

        if($width == 'auto') {
            $imagine = Image::getImagine();
            $imagine = $imagine->open($this->getImagePath());
            $sizes = getimagesize($this->getImagePath());

            $width = round($sizes[0] * $height / $sizes[1]);
            $imagine = $imagine->resize(new Box($width, $height))->save($newFilePath, ['quality' => 100]);

            if($web){
                return str_replace(Yii::getAlias("@frontend/web/"), '', $newFilePath);
            }

        }

        if($height != 'auto' && $width != 'auto'){
            Image::thumbnail($this->getImagePath(), $width, $height, $this->mode)
                ->save($newFilePath, ['quality' => 80]);
        }

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