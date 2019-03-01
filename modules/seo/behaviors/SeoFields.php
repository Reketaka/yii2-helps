<?php

namespace reketaka\helps\modules\seo\behaviors;

use reketaka\helps\modules\seo\models\Seo;
use yii\base\Behavior;
use yii\db\ActiveRecord;
use Yii;

class SeoFields extends Behavior{

    public function events()
    {
        return [
            ActiveRecord::EVENT_AFTER_INSERT => 'updateFields',
            ActiveRecord::EVENT_AFTER_UPDATE => 'updateFields',
            ActiveRecord::EVENT_AFTER_DELETE => 'deleteFields',
        ];
    }

    public function updateFields($event)
    {
        $post = Yii::$app->request->post();

        if (($model = Seo::findOne(['item_id' => $this->owner->id, 'modelName' => $this->owner->className() ])) === null) {
            $model = new Seo;
        }
        $post['Seo']['item_id'] = $this->owner->id;

        $model->load($post);
        $model->save();
    }

    public function deleteFields($event)
    {
        if($this->owner->seo) {
            $this->owner->seo->delete();
        }

        return true;
    }

    public function getSeo()
    {
        if($model = Seo::findOne(['item_id' => $this->owner->id, 'modelName' => $this->owner->className()])) {
            return $model;
        } else {
            return new Seo;
        }
    }
}