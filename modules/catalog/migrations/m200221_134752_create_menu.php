<?php

namespace reketaka\helps\modules\catalog\migrations;

use reketaka\helps\common\controllers\Migration;
use reketaka\helps\modules\adminMenu\models\MenuMigrateCreatorHelper;

/**
 * Class m200221_134752_create_menu
 */
class m200221_134752_create_menu extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        
        $d = [
            [
                'label'=>'section.catalog',
                'alias'=>'catalog',
                'icon'=>'typcn typcn-folder',
                'items'=>[
                    [
                        'label'=>'element.catalog.priceType',
                        'alias'=>'catalog_price_type',
                        'url'=>['/catalog/price-type/index'],
                        'controller_uniq_id'=>'catalog/price-type'
                    ],
                    [
                        'label'=>'element.catalog.items',
                        'alias'=>'catalog_items',
                        'url'=>['/catalog/item/index'],
                        'controller_uniq_id'=>'catalog/item'
                    ],
                    [
                        'label'=>'element.catalog.stores',
                        'alias'=>'catalog_stores',
                        'url'=>['/catalog/store/index'],
                        'controller_uniq_id'=>'catalog/store'
                    ],
                    [
                        'label'=>'element.catalog.catalogs',
                        'alias'=>'catalog_items',
                        'url'=>['/catalog/catalog/index'],
                        'controller_uniq_id'=>'catalog/catalog'
                    ],
                    [
                        'label'=>'element.catalog.amountInStores',
                        'alias'=>'catalog_item_stores',
                        'url'=>['/catalog/item-store/index'],
                        'controller_uniq_id'=>'catalog/item-store'
                    ],
                    [
                        'label'=>'element.catalog.priceItems',
                        'alias'=>'catalog_items_price',
                        'url'=>['/catalog/item-price/index'],
                        'controller_uniq_id'=>'catalog/item-price'
                    ],
                    [
                        'label'=>'element.catalog.discounts',
                        'alias'=>'catalog_discount',
                        'url'=>['/catalog/discount/index'],
                        'controller_uniq_id'=>'catalog/discount'
                    ],
                    [
                        'label'=>'element.catalog.sendPrice',
                        'alias'=>'catalog_send_price',
                        'url'=>['/catalog/send-price/index'],
                        'controller_uniq_id'=>'catalog/send-price'
                    ],
                ]
            ]
        ];

        MenuMigrateCreatorHelper::updateOrCreate($d);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200221_134752_create_menu cannot be reverted.\n";

        return false;
    }
    */
}
