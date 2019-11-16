<?php

namespace reketaka\helps\modules\basket\behaviors;

use reketaka\helps\modules\basket\models\Basket;
use yii\base\Behavior;

class UserBehavior extends Behavior{

    /**
     * @return mixed
     */
    public function getBasket(){
        return $this->owner->hasOne(Basket::class, ['user_id'=>'id']);
    }

}