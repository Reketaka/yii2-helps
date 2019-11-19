<?php

namespace reketaka\helps\modules\usermanager\helpers;


use yii\helpers\Html;
use yii\helpers\Url;
use yii\rbac\Permission;
use yii\web\View;

class Helper{

    public static function generateRolesHierarchy($rolesArray = [], $user){
        if(!$rolesArray){
            return null;
        }

        $addRoleToUserUrl = Url::to(['/usermanager/user/add-role-to-user']);
        $jsText = <<<JS
            $(".addRoleToUser").click(function(e){
                e.preventDefault();
                
                var userId = $(this).data('user-id');
                var roleName = $(this).data('role-name');
                
                $.getJSON('{$addRoleToUserUrl}?userId='+userId+'&roleName='+roleName, function(data){
                    if(data.success){
                        $(".userRoleBox").prepend("<div class='list-group-item'>"+data.roleName+" <button type='button' class='btn btn-default btn-xs deleteRoleFromUser' data-name='"+data.roleName+"' data-user-id='"+data.userId+"'><span class='glyphicon glyphicon-remove'></span></button></div>");
                    }
                    if(!data.success){
                        alert(data.error);
                    }
                })
                
            })
JS;

        \Yii::$app->view->registerJs($jsText, View::POS_LOAD);


        echo Html::beginTag('div', ['class'=>'list-group']);
        foreach($rolesArray as $roleArray){
            echo Html::beginTag('div', ['class'=>'list-group-item']);

                echo $roleArray['name'];
                echo " ";

//                if(isset($roleArray['type'])){
//                    BaseHelper::dump($roleArray['type']);
//                    BaseHelper::dump(Permission::class);
//                }


                if((isset($roleArray['type']) || in_array($roleArray['name'], \Yii::$app->getModule('usermanager')->rootRoles)) && (isset($roleArray['type']) && ($roleArray['type'] != Permission::class))) {
                    echo Html::tag(
                        'button',
                        Html::tag('span', null, ['class' => 'glyphicon glyphicon-plus']),
                        [
                            'class' => 'btn btn-default btn-xs addRoleToUser',
                            'data-user-id' => $user->id,
                            'data-role-name' => $roleArray['name']
                        ]
                    );
                }

                if(array_key_exists('childs', $roleArray)){
                    self::generateRolesHierarchy($roleArray['childs'], $user);
                }


            echo Html::endTag('div');
        }
        echo Html::endTag('div');


    }

}