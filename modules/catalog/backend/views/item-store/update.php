<?php

use yii\web\View;

/**
 * @var $this View
 * @var $model \reketaka\helps\modules\catalog\models\PriceType
 * @var $stores[]
 */

?>


<?=$this->render('_form', [
    'model'=>$model,
    'stores'=>$stores
])?>
