<?php

namespace reketaka\helps\modules\catalog;

use reketaka\helps\common\models\BaseModule;
use reketaka\helps\modules\catalog\models\Catalog;
use reketaka\helps\modules\catalog\models\Item;
use reketaka\helps\modules\catalog\models\ItemStore;
use reketaka\helps\modules\catalog\models\PriceType;
use yii\base\BootstrapInterface;
use yii\base\Event;
use function array_keys;
use common\helpers\BaseHelper;
use yii\db\Schema;
use function array_pop;
use function explode;
use function implode;

class Module extends BaseModule implements BootstrapInterface {

    CONST MODULE_NAME = 'catalog';
    CONST TYPE = 'type';

    public $i18nFileMap = [
        'modules/catalog/app'=>'app.php',
        'modules/catalog/title'=>'title.php',
        'modules/catalog/description'=>'description.php',
        'modules/catalog/h1'=>'h1.php',
    ];

    public static $tablePrefix = '';

    public $tableItemFields = [];
    public $itemClass = 'reketaka\helps\modules\catalog\models\Item';
    public $storeClass = 'reketaka\helps\modules\catalog\models\Store';
    public $defaultRoute = 'default/index';
    public $attributesSendPrice = [];
    
    public $ymlPriceConfigObject = 'reketaka\helps\modules\catalog\models\YmlConfigBase';
    public $ymlPriceProgresDir = '@console/runtime/prices/xml/progress/';
    public $ymlPriceCompleteDir = '@console/runtime/prices/xml/complete/';

    public $modelsPath = [
        'ItemUpdateModel'=>'reketaka\helps\modules\catalog\backend\models\ItemUpdateModel',
        'PriceType'=>'reketaka\helps\modules\catalog\models\PriceType'
    ];

    public function init(){
        parent::init();

    }

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
    }

}