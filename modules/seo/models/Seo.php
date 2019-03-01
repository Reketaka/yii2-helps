<?php

namespace reketaka\helps\modules\seo\models;

use common\helpers\BaseHelper;
use reketaka\helps\common\models\FindControllers;
use Yii;
use yii\base\Model;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "reketaka_meta_items".
 *
 * @property int $id
 * @property string $created_at
 * @property string $updated_at
 * @property int $item_id
 * @property string $modelName
 * @property string $path
 * @property string $h1
 * @property string $title
 * @property string $keywords
 * @property string $description
 */
class Seo extends \yii\db\ActiveRecord
{
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'value' => \Yii::$app->formatter->asDatetime(time(), 'php:Y-m-d H:i:s'),
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'reketaka_meta_items';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['item_id'], 'integer'],
            [['modelName'], 'default', 'value' => null],
            [['modelName', 'path', 'h1', 'title', 'keywords', 'description'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'item_id' => 'Item ID',
            'modelName' => 'Model Name',
            'path' => 'Path',
            'h1' => 'H1',
            'title' => 'Title',
            'keywords' => 'Keywords',
            'description' => 'Description',
        ];
    }

    public static function setMeta($event){

        $findController = new FindControllers();

        $seo = null;
        if($findController->hasCurrentActiveMethod() && (list($controller, $method) = $findController->getCurrentControllerMethod())){

            if(!$seo = Seo::findOne(['path'=>$controller.':'.$method])){

                $model = array_key_exists('model', $event->params)?$event->params['model']:null;

                $seo = ($model && $model->getBehavior('seo'))?$model->seo:null;

            }

        }

        if(!$seo){
            return false;
        }

        $view = $event->sender;

        if($seo->title) {
            $view->title = $seo->title;
        }

        if($seo->description) {
            $view->registerMetaTag([
                'name' => 'description',
                'content' => $seo->description
            ]);
        }

        if($seo->keywords){
            $view->registerMetaTag([
                'name' => 'keywords',
                'content' => $seo->keywords
            ]);
        }



    }


}