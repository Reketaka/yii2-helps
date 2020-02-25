<?php

namespace reketaka\helps\modules\basket;

class Module extends \yii\base\Module{

    public $defaultRoute = 'default/index';

    /**
     * Время через которое не используемые корзины удалятся
     * @var int
     */
    public $deleteBasketDay = 30;

    public $productClass = null;
    public $basketItemClass = 'reketaka\helps\modules\basket\models\BasketItem';

    public $basketItemFields = [

    ];

    /**
     * Использовать ссылки до продуктов
     * @var bool
     */
    public $useProductLink = false;



    public function init(){
        parent::init();

        if (\Yii::$app instanceof \yii\console\Application) {
            $this->controllerNamespace = 'reketaka\helps\modules\basket\commands';
        }

        $this->registerTranslations();
    }

    public function registerTranslations()
    {
        \Yii::$app->i18n->translations['modules/basket/*'] = [
            'class'          => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en-US',
            'basePath'       => \Yii::getAlias('@reketaka/helps/modules/basket/messages'),
            'fileMap'=>[
                'modules/basket/app'=>'app.php',
                'modules/basket/title'=>'title.php',
            ]
        ];
    }

    public static function t($category, $message, $params = [], $language = null)
    {
        return \Yii::t('modules/basket/' . $category, $message, $params, $language);
    }

}