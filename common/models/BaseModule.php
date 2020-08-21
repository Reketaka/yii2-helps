<?php

namespace reketaka\helps\common\models;

use reketaka\helps\common\helpers\Bh;
use Yii;
use yii\base\Module;
use function array_pop;
use function explode;
use function implode;

class BaseModule extends Module{

    /**
     * Используется для вызова модуля в чем либо
     * константа названия модуля
     */
    CONST MODULE_NAME = null;

    /**
     * Список файлов для перевода
     * ['modules/testModule/app'=>'app.php']
     * @var array
     */
    public $i18nFileMap = [];

    public $i18nEnable = true;

    public function init(){
        parent::init();

        $namespaceArray = explode("\\", static::class);
        array_pop($namespaceArray);
        $namespaceArray = implode("\\", $namespaceArray);

        if($this->isFrontend()){
            $this->viewPath = $this->getBasePath()."/frontend/views/";
            $this->controllerNamespace = $namespaceArray.'\frontend\controllers';
        }

        if($this->isBackend()){
            $this->viewPath = $this->getBasePath()."/backend/views/";
            $this->controllerNamespace = $namespaceArray."\backend\controllers";
        }

        if($this->isConsole()){
            $this->controllerNamespace = $namespaceArray."\commands\controllers";
        }

        if($this->i18nFileMap && $this->i18nEnable && static::MODULE_NAME){
            $this->registerTranslations();
        }
    }

    public function registerTranslations()
    {
        Yii::$app->i18n->translations['modules/'.static::MODULE_NAME.'/*'] = [
            'class'          => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en-US',
            'basePath'       => $this->getBasePath()."/messages",
            'fileMap'        => $this->i18nFileMap
        ];


    }

    public static function t($category, $message, $params = [], $language = null)
    {
        return Yii::t('modules/'.static::MODULE_NAME.'/' . $category, $message, $params, $language);
    }
    

    public function isBackend(){
        return Yii::$app->id == 'app-backend';
    }

    public function isFrontend(){
        return Yii::$app->id == 'app-frontend';
    }

    public function isConsole(){
        return \Yii::$app instanceof \yii\console\Application;

    }
}