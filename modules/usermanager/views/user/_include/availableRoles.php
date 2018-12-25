<?php

use yii\web\View;
use reketaka\helps\modules\usermanager\helpers\Helper;

/**
 * @var $this View
 * @var $allRolesHeirarchy
 * @var $user
 */

?>

<div class="col-md-3">
    <h3>Available roles</h3>

    <?php

        Helper::generateRolesHierarchy($allRolesHeirarchy, $user);

    ?>

</div>
