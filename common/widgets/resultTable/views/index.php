<?php

use yii\web\View;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

/**
 * @var $this View
 * @var $columns[]
 * @var $models[]
 * @var $tableAttributes
 */

?>


<?=Html::beginTag('table', $tableAttributes)?>
    <thead>
        <tr>
            <?php foreach($columns as $column):?>
                <?=Html::beginTag('th', ArrayHelper::getValue($column, 'thead.attributes', []))?>
                    <?=$column['thead']['value']?>
                <?=Html::endTag('th')?>
            <?php endforeach; ?>
        </tr>
    </thead>
    <tbody>
        <?php foreach($models as $model):

            echo Html::beginTag('tr');

                foreach($columns as $column):
                    if(!isset($column['tbody'])){
                        continue;
                    }

                    echo Html::beginTag('td', ArrayHelper::getValue($column, 'tbody.attributes', []));

                            $valueCallback = $column['tbody']['value'];
                            $valueCallback = $valueCallback($model);

                            echo $valueCallback;


                    echo Html::endTag('td');

                endforeach;
            echo Html::endTag('tr');
        endforeach; ?>
    </tbody>
<?=Html::endTag('table')?>
