<?php

namespace reketaka\helps\modules\usermanager\helpers;


use yii\helpers\Html;

class Helper{

    public static function generateRolesHierarchy($rolesArray = []){
        if(!$rolesArray){
            return null;
        }

        echo Html::beginTag('div', ['class'=>'list-group']);
        foreach($rolesArray as $roleArray){
            echo Html::beginTag('div', ['class'=>'list-group-item']);

                echo $roleArray['name'];

                if(array_key_exists('childs', $roleArray)){
                    self::generateRolesHierarchy($roleArray['childs']);
                }


            echo Html::endTag('div');
        }
        echo Html::endTag('div');


    }

}