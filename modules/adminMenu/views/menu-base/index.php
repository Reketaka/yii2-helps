<?php

use common\helpers\BaseHelper;
use reketaka\helps\modules\adminMenu\models\MenuSectionUser;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

/**
 * @var $this View
 * @var $menuItems[]
 */

$cssText = <<<CSS
    .menuContainer{columns:250px auto;}
    .menuBox{width:250px;-webkit-column-break-inside: avoid;page-break-inside: avoid;break-inside: avoid-column;}
    h4{margin-top:0px;}

    .menuSectionsUser{list-style:none;}
    .menuSectionsUser li a{padding:5px;display:inline-block;border:1px solid #CCC;-webkit-border-radius: 5px;-moz-border-radius: 5px;border-radius: 5px;margin-top:2px;margin-bottom:2px;}
    .menuSectionsUser li a:hover{background-color:rgba(0,0,0, 0.1);}
CSS;

$this->registerCss($cssText);

$urlToAddItem = Url::to(['/adminmenu/menu-section-user/add-item-to-section']);

$jsText = <<<JS
    $(".editMenu").click(function(e){
        
        if(!$(this).data('edit')){
            $(document).on('click.menuBoxItem', ".menuBox .list-group-item", function(e){
                e.preventDefault();
                
                $("#chooseSectionModal").modal({show:true});
                
                var itemId = $(this).data('item-id');
                
                $(document).on('click.menuSectionItemUser', '.menuSectionsUser a', function(e){
                    e.preventDefault();
                    oldHtml = $(this).html();
                    
                    var sectionId = $(this).data('section-id');
                    
                    var _this = this;
                    
                    $.getJSON('{$urlToAddItem}'+'?sectionId='+sectionId+'&itemId='+itemId, function(data){
                        if(!data.success){
                            alert(data.message);
                        }
                        
                        if(data.success){
                            $(_this).html(oldHtml+" <span class='glyphicon glyphicon-ok'></span>");
                        }
                        
                    });
                    
                    
                    
                })
                
                
                $('#chooseSectionModal').on('hide.bs.modal', function () {
                   $(document).off('click.menuSectionItemUser');
                   $(".menuSectionsUser a span").remove();
                })
                
            })
            
            $(".menuBox .list-group-item").each(function(index, elem){
                var oldHtml = $(elem).html();
                $(elem).html(oldHtml+" <span class='glyphicon glyphicon-plus'></span>");
            });
            
            $(this).toggleClass('btn-success btn-danger').html('Прекратить редактирование');
            $(this).data('edit', 1);
        }else{
            $(document).off('click.menuBoxItem');
             $(document).off('click.menuSectionItemUser');
            $(".menuSectionsUser a span").remove();
             
            $(".menuBox .list-group-item span").remove();
            
            $(this).toggleClass('btn-success btn-danger').html('Редактирование');
            $(this).data('edit', 0);
            
            
        }
        
        
    })
JS;

$this->registerJs($jsText, View::POS_READY);

//BaseHelper::dump(MenuSectionUser::getHierarchyArray());

function renderSectionMenu($items){

    $text = null;
    $text .= Html::beginTag('ul', ['class'=>'menuSectionsUser']);
    foreach($items as $item){
        $text .= Html::beginTag('li');
        $text .= Html::tag('a', $item['title'], ['href'=>'#', 'data-section-id'=>$item['id']]);
        if(array_key_exists('items', $item)){
            $text .= renderSectionMenu($item['items']);
        }
        $text .= Html::endTag('li');
    }
    $text .= Html::endTag('ul');
    return $text;

}

?>

<!-- Modal -->
<div class="modal fade" id="chooseSectionModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Выберите раздел в который добавить пункт меню</h4>
            </div>
            <div class="modal-body">
                <div class="alert alert-success userSectionMessage" style="display:none;">Добавлено <span class="glyphicon glyphicon-ok"></span></div>
                <?php

                    $sections = MenuSectionUser::getHierarchyArray();

                ?>
                <?=renderSectionMenu($sections)?>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>



<div class="menuOptions">

    <button type="button" class="btn btn-success editMenu">Редактирование</button>

</div>

<div class="menuContainer">
    <?php foreach($menuItems as $key=>$sectionData):?>

        <div class="menuBox">
            <h4><?=$sectionData['label']?></h4>

            <div class="list-group">
                <?php foreach($sectionData['items'] as $itemData):?>
                    <a href="<?=$itemData['url']?>" class="list-group-item" data-item-id="<?=$itemData['id']?>"><?=$itemData['label']?></a>
                <?php endforeach; ?>
            </div>
        </div>

    <?php endforeach; ?>
</div>
