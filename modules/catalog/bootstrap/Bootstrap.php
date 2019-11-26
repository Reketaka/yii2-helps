<?php
namespace reketaka\helps\modules\catalog\bootstrap;

use reketaka\helps\modules\catalog\models\Catalog;
use reketaka\helps\modules\catalog\models\PriceType;
use reketaka\helps\modules\catalog\models\Store;
use reketaka\helps\modules\catalog\models\Item;
use reketaka\helps\modules\catalog\models\ItemStore;
use yii\base\BootstrapInterface;
use yii\base\Event;
use yii\db\ActiveRecord;

class Bootstrap implements BootstrapInterface
{
    /**
     * @inheritdoc
     */
    public function bootstrap($app)
    {

        Event::on(Item::class, Item::EVENT_AFTER_UPDATE, ['reketaka\helps\modules\catalog\eventCallback\ItemCallbackEvent', 'changeAttribute']);
        Event::on(Item::class, Item::EVENT_AFTER_INSERT, ['reketaka\helps\modules\catalog\eventCallback\ItemCallbackEvent', 'changeAttribute']);

        Event::on(PriceType::class, PriceType::EVENT_AFTER_DELETE, ['reketaka\helps\modules\catalog\eventCallback\PriceTypeCallbackEvent', 'setDefault']);
        Event::on(PriceType::class, PriceType::EVENT_AFTER_UPDATE, ['reketaka\helps\modules\catalog\eventCallback\PriceTypeCallbackEvent', 'setDefault']);
        Event::on(PriceType::class, PriceType::EVENT_AFTER_INSERT, ['reketaka\helps\modules\catalog\eventCallback\PriceTypeCallbackEvent', 'setDefault']);

        Event::on(ItemStore::class, ItemStore::EVENT_BEFORE_UPDATE, ['reketaka\helps\modules\catalog\eventCallback\ItemStoreCallbackEvent', 'changeTotalAmount']);
        Event::on(ItemStore::class, ItemStore::EVENT_AFTER_INSERT, ['reketaka\helps\modules\catalog\eventCallback\ItemStoreCallbackEvent', 'addTotalAmount']);
        Event::on(ItemStore::class, ItemStore::EVENT_BEFORE_DELETE, ['reketaka\helps\modules\catalog\eventCallback\ItemStoreCallbackEvent', 'changeTotalAmount']);

        Event::on(Catalog::class, Catalog::EVENT_BEFORE_DELETE, ['reketaka\helps\modules\catalog\eventCallback\CatalogCallbackEvent', 'onDelete']);

        self::registerTranslations();
    }

    public static function registerTranslations(){
        \Yii::$app->i18n->translations['modules/catalog/*'] = [
            'class'          => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en-US',
            'basePath'       => \Yii::getAlias('@reketaka/helps/modules/catalog/messages'),
            'fileMap'=>[
                'modules/catalog/app'=>'app.php',
                'modules/catalog/title'=>'title.php',
            ]
        ];
    }
}



//Event::on(Item::class, Item::EVENT_CHANGE_PRICE, []);
//Event::on(Item::class, Item::EVENT_CHANGE_AMOUNT, []):

//$store = Store::getOrCreate([
//    'uid'=>'32323',
//    'title'=>'Склад №1'
//]);
//
//
//$item = Item::getByUid("34234324-23432432")
//    ->setAmountStore([
//        'uid'=>4,
//        'uid2'=>6
//    ]);

?>