<?php

use yii\web\View;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * @var $this View
 * @var $userGroups[]
 */

$removeGroupFromUser = Url::to(['/usermanager/user/remove-group-from-user']);
$jsText = <<<JS
    $(".deleteGroupFromUser").click(function(e){
        e.preventDefault();
        
        var userId = $(this).data('user-id');
        var groupId = $(this).data('group-id');
        
        $.getJSON('{$removeGroupFromUser}?userId='+userId+'&groupId='+groupId, $.proxy(function(data){
            
            if(data.success){
                $(this).closest('a').remove();
            }
                
            if(!data.success){
                alert(data.error);
            }
        }, this));
        
    })
JS;

\Yii::$app->view->registerJs($jsText, View::POS_LOAD);


?>


<h3>User Groups</h3>
<div class="list-group userGroupListBox">
    <?php foreach($userGroups as $userGroup):

        echo Html::beginTag('a', ['class'=>'list-group-item']);

            echo $userGroup->title.' ';

            echo Html::tag(
                'button',
                Html::tag('span', null, ['class' => 'glyphicon glyphicon-remove']),
                [
                    'class' => 'btn btn-default btn-xs deleteGroupFromUser',
                    'data-user-id' => $user->id,
                    'data-group-id' => $userGroup->id
                ]
            );


        echo Html::endTag('a');


    endforeach; ?>
</div>
