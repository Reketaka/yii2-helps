<?php

use yii\web\View;

/**
 * @var $this View
 * @var $fields[]
 */

?>


<?=$this->render('_form', [
    'model'=>$model,
    'fields'=>$fields
])?>
