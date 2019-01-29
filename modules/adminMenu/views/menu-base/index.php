<?php

use yii\web\View;

/**
 * @var $this View
 * @var $menuItems[]
 */

$cssText = <<<CSS
    .menuContainer{columns:250px auto;}
    .menuBox{width:250px;-webkit-column-break-inside: avoid;page-break-inside: avoid;break-inside: avoid-column;}
    h4{margin-top:0px;}
CSS;

$this->registerCss($cssText);


?>

<div class="menuContainer">
    <?php foreach($menuItems as $key=>$sectionData):?>

        <div class="menuBox">
            <h4><?=$sectionData['label']?></h4>

            <div class="list-group">
                <?php foreach($sectionData['items'] as $itemData):?>
                    <a href="<?=$itemData['url']?>" class="list-group-item"><?=$itemData['label']?></a>
                <?php endforeach; ?>
            </div>
        </div>

    <?php endforeach; ?>
</div>
