<?php

use yii\helpers\Html;
use yii\web\View;
use kartik\detail\DetailView;


/**
 * @var $this View
 * @var $users[]
 */


?>

<div class="row">
    <div class="col-md-3">
        <h3>Group Information</h3>
        <?php

            $attributes = [
                'id',
                'title',
                'alias',
                'created_at',
                'updated_at'
            ];

            echo DetailView::widget([
                'model'=>$model,
                'attributes' => $attributes
            ]);

        ?>


        <?=Html::a('View All Groups', ['/usermanager/user-group/index'], ['class'=>'btn btn-success'])?>

    </div>

    <div class="col-md-3">
        <h3>User In Groups</h3>
        <div class="list-group">
            <?php foreach($users as $user):
                echo Html::a($user->username, ['/usermanager/user/view', 'id'=>$user->id], ['class'=>'list-group-item']);
            endforeach; ?>
        </div>
    </div>


</div>
