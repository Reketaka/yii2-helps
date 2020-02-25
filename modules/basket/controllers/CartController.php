<?php

namespace reketaka\helps\modules\basket\controllers;

use reketaka\helps\common\helpers\Bh;
use reketaka\helps\modules\basket\assets\basket\BasketAsset;
use reketaka\helps\modules\basket\models\BasketComponent;
use reketaka\helps\modules\basket\models\CartRefresh;
use reketaka\helps\modules\basket\Module;
use reketaka\helps\modules\catalog\models\Item;
use reketaka\helps\modules\catalog\models\Store;
use Yii;
use yii\db\Expression;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\Controller;

class CartController extends Controller {

    public function actionIndex(){
        /**
         * @var $basket BasketComponent
         */
        $basket = Yii::$app->basket;
        /**
         * @var $module Module
         */
        $module = Yii::$app->getModule('basket');
        
        $this->view->registerAssetBundle(BasketAsset::class);

        $basketItems = $basket->getModel()->getItems()->with(['product'])->all();

        $model = new CartRefresh();
        if($model->load(Yii::$app->request->post()) && $model->refresh()){
            return $this->redirect(['cart/index']);
        }

        $stores = ArrayHelper::map(Store::find()->select(['id', new Expression("CONCAT(title, ' (', IFNULL(comment, ''), ')') as 'title'")])->asArray()->all(), 'id', 'title');

        return $this->render('index', [
            'basketItems'=>$basketItems,
            'basket'=>$basket,
            'h1'=>Yii::t('h1', 'cart-index'),
            'model'=>$model,
            'stores'=>$stores,
            'basketItemFields'=>$module->basketItemFields,
            'useProductLink'=>$module->useProductLink
        ]);
    }

    public function actionCreate(){
        /**
         * @var $basket BasketComponent
         */
        $basket = Yii::$app->basket;

        if(!$basket->model->getProducts()){
            return $this->redirect(['cart/index']);
        }

        $order = Order::create(Yii::$app->request->post('store_id', null));

        if(!$order){
            return $this->redirect(['cart/index']);
        }

        return $this->redirect(['order/view', 'id'=>$order->id]);
    }

    public function actionRemove($id){
        /**
         * @var $basket BasketComponent
         */
        $basket = Yii::$app->basket;

        $basket->remove($id);
        $basket->refresh();

        return $this->asJson([
            'success'=>true,
            'message'=>Yii::t('global', 'item_delete_from_basket'),
            'total_amount'=>$basket->getTotalAmount(),
            'total_price'=>$basket->getTotalPrice(),
            'total_price_format'=>Yii::t('app', 'price_with_currency', ['price'=>Yii::$app->formatter->asDecimal($basket->getTotalPrice())])
        ]);
    }

    public function actionPut($id, $amount = 1){

        $module = Yii::$app->getModule('basket');
        /**
         * @var $itemClass Item
         */
        $itemClass = $module->productClass;

        if(!$item = $itemClass::find()->withPrice()->andWhere(['catalog_item.id'=>$id, 'catalog_item.active'=>1])->one()){
            return $this->asJson(['success'=>false, 'message'=>Yii::t('errors', 'item_not_found')]);
        }

        /**
         * @var $basket BasketComponent
         */
        $basket = Yii::$app->basket;
        $basket->put($item, $amount);
        $basket->refresh();

        return $this->asJson([
            'success'=>true,
            'message'=>Yii::t('global', 'item_success_add_to_basket'),
            'total_amount'=>$basket->getTotalAmount(),
            'total_price'=>$basket->getTotalPrice()
        ]);

    }


}