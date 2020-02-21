<?php

namespace reketaka\helps\modules\catalog\controllers;

use backend\common\controllers\BackendController;
use reketaka\helps\modules\catalog\models\Discount;
use reketaka\helps\modules\catalog\models\SendPrice;
use reketaka\helps\modules\catalog\models\SendPriceSearch;
use reketaka\helps\modules\catalog\Module;
use yii\helpers\Html;
use yii\web\NotFoundHttpException;
use Yii;
use function implode;

class SendPriceController extends BackendController
{

    /**
    * {@inheritdoc}
    */
    public function actions()
    {
        return [
            'index' => [
                'class' => 'reketaka\helps\common\actions\crudReset\index\IndexAction',
                'searchModel' => new SendPriceSearch(),
                'breadcrumbs' => function(){
                    $this->view->params['breadcrumbs'][] = ['label'=>Module::t('app', 'bc.main'), 'url'=>['default/index']];
                    $this->view->params['breadcrumbs'][] = Module::t('app', 'bc.send-price');
                },
                'columns' => [
                    'id',
                    [
                        'attribute'=>'emails',
                        'format'=>'raw',
                        'content'=>function($model){
                            $text = [];
                            foreach($model->emails as $email){
                                $text[] = Html::tag('p', $email);
                            }
                            return implode('', $text);
                        }
                    ],
                    'active:boolean',
                    'last_send_date',
                    [
                        'attribute'=>'discount_id',
                        'format'=>'raw',
                        'content'=>function($model){
                            if($discount = $model->discount){
                                return $discount->title;
                            }
                        }
                    ]
                ]
            ],
            'view' => [
                'class' => 'reketaka\helps\common\actions\crudReset\view\ViewAction',
                'breadcrumbs' => function($model){
                    $this->view->params['breadcrumbs'][] = ['label'=>Module::t('app', 'bc.main'), 'url'=>['default/index']];
                    $this->view->params['breadcrumbs'][] = ['label'=>Module::t('app', 'bc.send-price'), 'url'=>['send-price/index']];
                    $this->view->params['breadcrumbs'][] = Module::t('app', 'bc._view', ['id'=>$model->id]);
                },
                'columns' => [
                    'id',
                    [
                        'attribute'=>'emails',
                        'format'=>'raw',
                        'value'=>function($model){
                            $text = [];
                            foreach($model->emails as $email){
                                $text[] = Html::tag('p', $email);
                            }
                            return implode('', $text);
                        }
                    ],
                    'round_price:boolean',
                    'with_active:boolean',
                    'active:boolean',
                    'last_send_date',
                    [
                        'attribute'=>'discount_id',
                        'format'=>'raw',
                        'value'=>function($model){
                            if($discount = $model->discount){
                                return $discount->title;
                            }
                        }
                    ],
                    'email_header',
                    'email_content'
                ]
            ],
            'update' => [
                'class' => 'reketaka\helps\common\actions\crudReset\update\UpdateAction',
                'renderView' => '@reketaka/helps/modules/catalog/views/send-price/form.php',
                'breadcrumbs' => function($model){
                    $this->view->params['breadcrumbs'][] = ['label'=>Module::t('app', 'bc.main'), 'url'=>['default/index']];
                    $this->view->params['breadcrumbs'][] = ['label'=>Module::t('app', 'bc.send-price'), 'url'=>['send-price/index']];
                    $this->view->params['breadcrumbs'][] = Module::t('app', 'bc._update', ['id'=>$model->id]);
                },
                'optionals' => [
                    'discounts'=>Discount::getArrayMap()
                ]
            ],
            'create'=>[
                'class'=>'reketaka\helps\common\actions\crudReset\create\CreateAction',
                'renderView' => '@reketaka/helps/modules/catalog/views/send-price/form.php',

                'model'=> new SendPrice(),
                'breadcrumbs' => function($model){
                    $this->view->params['breadcrumbs'][] = ['label'=>Module::t('app', 'bc.main'), 'url'=>['default/index']];
                    $this->view->params['breadcrumbs'][] = ['label'=>Module::t('app', 'bc.send-price'), 'url'=>['send-price/index']];
                    $this->view->params['breadcrumbs'][] = Module::t('app', 'bc._create', ['id'=>$model->id]);
                },
                'optionals' => [
                    'discounts'=>Discount::getArrayMap()
                ]
            ],
            'delete'=>[
                'class'=>'reketaka\helps\common\actions\crudReset\delete\DeleteAction',
            ]
        ];
    }

    public function findModel($id){
        if(!$model = SendPrice::findOne($id)){
            throw new NotFoundHttpException(Yii::t('errors', 'sendprice_not_find'));
        }

        return $model;
    }

}
