<?php

/**
 * @var $this View
 * @var $model User
 * @var $userViewAttributes[]
 * @var $allRolesHeirarchy
 * @var $userRoles
 */

use common\helpers\BaseHelper;
use common\models\User;
use reketaka\helps\modules\usermanager\helpers\Helper;
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

    <div class="col-md-3">
        <h3>Role of user</h3>
        <div class="list-group">
            <?php foreach($userRoles as $userRole):?>
                <div class="list-group-item"><?=$userRole?></div>
            <?php endforeach; ?>
        </div>

    </div>

    <div class="col-md-3">
        <h3>Available roles</h3>

        <?php

            Helper::generateRolesHierarchy($allRolesHeirarchy);

        ?>


    </div>

</div>
