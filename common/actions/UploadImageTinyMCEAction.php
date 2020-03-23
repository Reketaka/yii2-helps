<?php

namespace reketaka\helps\common\actions;

use Yii;
use yii\base\Action;
use yii\base\Behavior;
use yii\helpers\FileHelper;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\UploadedFile;

/**
 * Class UploadImageAction
 *
 * ```php
 * public function actions()
 * {
 *  return [
 *       'upload-image' => [
 *           'class' => 'reketaka\helps\common\actions\UploadImageAction',
 *       ]
 *    ];
 *  }
 *```php
 *
 * @package reketaka\helps\common\actions
 *
 */
class UploadImageTinyMCEAction extends Action {

    public $requestFileName = 'file';
    public $dirUpload = "@frontend/web/uploads/imagesUpload/";
    public $webFilePathCallback;

    /**
     * @param $id
     * @param $attributeName
     * @param null $value
     * @return array|Response
     * @throws NotFoundHttpException
     */
    public function run(){
        Yii::$app->response->format = Response::FORMAT_JSON;

        $file = UploadedFile::getInstanceByName($this->requestFileName);

        $dir = Yii::getAlias($this->dirUpload);
        FileHelper::createDirectory($dir);

        $fileName = $file->getBaseName().".".$file->getExtension();

        $tmpFileName = Yii::getAlias($dir.$fileName);
        $file->saveAs($tmpFileName);

        $resultCallback = $this->webFilePathCallback;
        return $resultCallback($fileName);
    }

}