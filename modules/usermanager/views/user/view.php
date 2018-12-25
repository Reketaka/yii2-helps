<?php

/**
 * @var $this View
 * @var $model User
 * @var $userViewAttributes[]
 * @var $allRolesHeirarchy
 * @var $userRoles
 * @var $userGroups
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

    <div class="col-md-3">
        <h3>Available roles</h3>

        <?php

            Helper::generateRolesHierarchy($allRolesHeirarchy);

        ?>

    </div>

    <div class="col-md-3">
        <h3>User Groups</h3>
        <div class="list-group">
            <?php foreach($userGroups as $userGroup):?>
                <?=Html::a($userGroup->title, ['/usermanager/user-group/view', 'id'=>$userGroup->id], ['class'=>'list-group-item'])?>
            <?php endforeach; ?>
        </div>
    </div>
</div>
