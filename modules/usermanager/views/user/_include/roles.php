<?php

use common\helpers\BaseHelper;
use yii\web\View;
use yii\helpers\Url;

/**
 * @var $this View
 * @var $userRoles
 * @var $user
 */

$urlToDeleteRoleFromUser = Url::to(['/usermanager/user/delete-role-from-user']);
$jsText = <<<JS
    $(".deleteRoleFromUser").click(function(e){
        e.preventDefault();
        var name = $(this).data('name');
        var userId = $(this).data('user-id');
        
        
        $.getJSON('{$urlToDeleteRoleFromUser}?userId='+userId+'&roleName='+name, $.proxy(function(data){
            if(data.success){
                $(this).closest('.list-group-item').remove();
            }
        }, this))
        
        
    })
JS;

$this->registerJs($jsText, View::POS_LOAD);

?>


<div class="col-md-3">
    <h3>Role of user</h3>
    <div class="list-group">
        <?php foreach($userRoles as $userRole):?>
            <div class="list-group-item">
                <?=$userRole?>
                <button type="button" class="btn btn-default btn-xs deleteRoleFromUser" data-name="<?=$userRole?>" data-user-id="<?=$user->id?>">
                    <span class="glyphicon glyphicon-remove"></span>
                </button>
            </div>
        <?php endforeach; ?>
    </div>

</div>
