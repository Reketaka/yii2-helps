<?php

use common\helpers\BaseHelper;
use yii\helpers\ArrayHelper;
use yii\web\View;
use yii\helpers\Url;
use yii\helpers\Html;

/**
 * @var $this View
 * @var $allGroups[]
 * @var $user
 * @var $userGroups
 */

$userGroups = ArrayHelper::map($userGroups, 'id', 'title');


$addGroupToUser = Url::to(['/usermanager/user/add-group-to-user']);
$jsText = <<<JS
    $(".addGroupToUser").click(function(e){
        e.preventDefault();
        
        var userId = $(this).data('user-id');
        var groupId = $(this).data('group-id');
        
        
        $.getJSON('{$addGroupToUser}?userId='+userId+'&groupId='+groupId, function(data){
            
            if(data.success){
                $(".userGroupListBox").prepend("<div class='list-group-item'>"+data.groupTitle+" <button type='button' class='btn btn-default btn-xs deleteGroupFromUser' data-group-id='"+data.groupId+"' data-user-id='"+data.userId+"'><span class='glyphicon glyphicon-remove'></span></button></div>");
            }
            if(!data.success){
                alert(data.error);
            }
        })
        
    })
JS;

\Yii::$app->view->registerJs($jsText, View::POS_LOAD);


?>


<h3>Available Groups</h3>
<div class="list-group availableGroups">
    <?php foreach($allGroups as $id=>$title):
        echo Html::beginTag('a', ['class'=>'list-group-item']);

            echo $title.' ';

            if(!array_key_exists($id, $userGroups)):
                echo Html::tag(
                    'button',
                    Html::tag('span', null, ['class' => 'glyphicon glyphicon-plus']),
                    [
                        'class' => 'btn btn-default btn-xs addGroupToUser',
                        'data-user-id' => $user->id,
                        'data-group-id' => $id
                    ]
                );
            endif;

        echo Html::endTag('a');

    endforeach; ?>
</div>
