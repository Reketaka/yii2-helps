<?php

use common\modules\client\ClientModule;
use reketaka\helps\common\helpers\Bh;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/**
 * @var $items[]
 * @var $title
 * @var $options
 */

$itemTitleClosure = ArrayHelper::getValue($options, 'titleClosure');
$primaryTitleKey = ArrayHelper::getValue($options, 'primaryKeyTitle', 'id');

?>


<?php if($items):?>
    <ul class="list-group">
        <li class="list-group-item list-group-item-info"><?=$title?></li>

        <?php foreach($items as $item):

            $itemId = ArrayHelper::getValue($item, ArrayHelper::getValue($options, 'primaryKey', 'id'));

            $itemTitle = null;
            if($itemTitleClosure instanceof Closure){
                $itemTitle = $itemTitleClosure($item);
            }

            if(!$itemTitle && ($itemTitleKey = ArrayHelper::getValue($options, 'titleKey'))){
                $itemTitle = ArrayHelper::getValue($item, $itemTitleKey);
            }

        ?>

            <li class="list-group-item">
                <?php if($url = ArrayHelper::getValue($options, 'url')):
                    $url = array_merge($url, [$primaryTitleKey=>$itemId]);
                ?>
                    <?=Html::a($itemTitle, $url)?>
                <?php else: ?>
                    <?=$itemTitle?>
                <?php endif; ?>
            </li>

        <?php endforeach; ?>
    </ul>
<?php endif; ?>