<?php

/**
 * @var $this View
 * @var $model User
 * @var $userViewAttributes[]
 * @var $allRolesHeirarchy
 * @var $userRoles
 * @var $userGroups
 * @var $allGroups
 */

use common\helpers\BaseHelper;
use common\models\User;
use reketaka\helps\modules\usermanager\helpers\Helper;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\DetailView;

?>



<div class="user-view">

    <div class="col-md-3">
        <h3>User information</h3>
        <?php

            $attributes = [];
            foreach($userViewAttributes as $userViewAttribute){
                $attributes[] = $userViewAttribute;
            }


            echo DetailView::widget([
                'model'=>$model,
                'attributes' => $attributes
            ]);


        ?>
    </div>

    <?=$this->render('_include/roles', [
        'userRoles'=>$userRoles,
        'user'=>$model
    ])?>

    <?=$this->render('_include/availableRoles', [
        'allRolesHeirarchy'=>$allRolesHeirarchy,
        'user'=>$model
    ])?>

    <div class="col-md-3">
        <?=$this->render('_include/availableGroups', [
            'allGroups'=>$allGroups,
            'user'=>$model,
            'userGroups'=>$userGroups
        ])?>

        <?=$this->render('_include/groups', [
            'user'=>$model,
            'userGroups'=>$userGroups
        ])?>
    </div>
</div>
