<?php

namespace reketaka\helps\modules\catalog\models;

use reketaka\helps\common\helpers\Bh;
use reketaka\helps\modules\catalog\Module;
use Yii;
use yii\base\BaseObject;
use function htmlspecialchars;
use function strlen;
use function strpos;
use function strrpos;
use function substr;

class YmlConfigBase extends BaseObject{

    public $fileName = 'index.xml';
    public $name = 'Супер Магазин';
    public $companyName = 'Супер Магазин';
    public $url = 'domains.frontend';

    /**
     * @var $module Module
     */
    private $module;

    public function init()
    {
        $this->module = Yii::$app->getModule('catalog');
        $this->url = Yii::$app->params[$this->url];
        parent::init(); // TODO: Change the autogenerated stub
    }

    public function getItems(){
        $items = $this->module->itemClass::find()
            ->withPrice()
            ->pricePositive()
            ->andWhere(['>', 'catalog_item.total_amount', 0])
            ->andWhere(['catalog_item.active'=>1]);

        return $items;
    }
    
    public function generateXmlItem(Item $item){
        $dom = new \DOMDocument("1.0", "utf-8");
        $offerNode = $dom->createElement('offer');
        $offerNode->setAttribute('id', $item->id);
        $offerNode->setAttribute('available', $item->total_amount > 0?"true":"false");

//        $categoryNode = $dom->createElement('categoryId', $item->catalog_id);
//        $offerNode->appendChild($categoryNode);

//        if($item->isImageExist()){
//            $modelNode = $dom->createElement('picture', $item->getImagePath(true));
//            $offerNode->appendChild($modelNode);
//        }

        $modelNode = $dom->createElement('model', htmlspecialchars($item->title));
        $offerNode->appendChild($modelNode);

        $priceNode = $dom->createElement('price', $item->getPrice());
        $offerNode->appendChild($priceNode);

        $storeNode = $dom->createElement('store', 'true');
        $offerNode->appendChild($storeNode);

        $storeNode = $dom->createElement('delivery', 'true');
        $offerNode->appendChild($storeNode);

        $storeNode = $dom->createElement('pickup', 'true');
        $offerNode->appendChild($storeNode);

        $paramNode = $dom->createElement('currencyId', 'RUR');
        $offerNode->appendChild($paramNode);

//        $linkNode = $dom->createElement('url', \Yii::$app->urlManagerFrontend->createAbsoluteUrl(['/item/view', 'id'=>$item->id]));
//        $offerNode->appendChild($linkNode);

        foreach($this->module->attributesSendPrice as $attributeName):
            $paramNode = $dom->createElement('param', htmlspecialchars($item->$attributeName));
            $paramNode->setAttribute('name', $item->getAttributeLabel($attributeName));
            $offerNode->appendChild($paramNode);
        endforeach;

        $dom->appendChild($offerNode);

        $element = $dom->getElementsByTagName('offer')->item(0);
        $xml = $dom->saveXML($element);
        return $xml;
    }

    public function getBaseFileArray(){
        $doc = new \DOMDocument("1.0", "utf-8");

        $root = $doc->createElement("yml_catalog");
        $root->setAttribute('date', (new \DateTime())->format("Y-m-d H:i"));

        $shopNode = $doc->createElement('shop');
        $root->appendChild($shopNode);

//        $categoriesNode = $doc->createElement('categories');
//
//        foreach(Catalog::findAll(['active'=>1]) as $catalog){
//            $param = $doc->createElement('category', htmlspecialchars($catalog->title));
//            $param->setAttribute('id', $catalog->id);
//            $categoriesNode->appendChild($param);
//        }
//        $shopNode->appendChild($categoriesNode);

        $currenciesNode = $doc->createElement('currencies');
        $currencyNode = $doc->createElement('currency');
        $currencyNode->setAttribute('id', 'RUR');
        $currencyNode->setAttribute('rate', 1);
        $currenciesNode->appendChild($currencyNode);

        $shopNode->appendChild($currenciesNode);

        $nameNode = $doc->createElement('name', $this->name);
        $shopNode->appendChild($nameNode);

        $companyNode = $doc->createElement('company', $this->companyName);
        $shopNode->appendChild($companyNode);

        $urlNode = $doc->createElement('url', $this->url);
        $shopNode->appendChild($urlNode);

        $offersNode = $doc->createElement('offers', 'offers');
        $shopNode->appendChild($offersNode);

        $doc->appendChild($root);

        $xml = $doc->saveXML();

        $partStart = substr($xml, 0, strrpos($xml, "offers</offers>"));
        $partEnd = substr($xml, strpos($xml, "offers</offers>")+6, strlen($xml));

        return [
            'start'=>$partStart,
            'end'=>$partEnd
        ];
    }

}