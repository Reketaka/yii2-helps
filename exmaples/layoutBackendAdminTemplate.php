<?php

/**
 * @var string $content
 * @var \yii\web\View $this
 */

use backend\assets\AppAsset;
use reketaka\helps\modules\adminMenu\models\MenuDynamic;
use yii\helpers\Html;
use backend\common\helpers\MenuHelper;
use yii\helpers\Url;

$bundle = yiister\gentelella\assets\Asset::register($this);
AppAsset::register($this);

$jsText = <<<JS
    $(".nav.side-menu > li > a").click(function(e){
        if($(this).parent().children('ul').length > 0){
            e.preventDefault();
            return false;
        }
    })
    
JS;

$this->registerJs($jsText, \yii\web\View::POS_READY);

$jsText = '
        $SIDEBAR_MENU.find("a").off("click").on("click", function(ev) {
        ev.preventDefault();
        var $li = $(this).parent();

        if ($li.is(".active")) {
            $li.removeClass("active active-sm");
            $("ul:first", $li).slideUp(function() {
                setContentHeight();
            });
        } else {
            // prevent closing menu if we are on child menu
            if (!$li.parent().is(".child_menu")) {
                $SIDEBAR_MENU.find("li").removeClass("active active-sm");
                $SIDEBAR_MENU.find("li ul").slideUp();
            }
            
            $li.addClass("active");

            $("ul:first", $li).slideDown(function() {
                setContentHeight();
            });
        }
    });';

//$this->registerJs($jsText, \yii\web\View::POS_END);

?>
<?php $this->beginPage(); ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta charset="<?= Yii::$app->charset ?>" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="shortcut icon" href="/favicon.ico">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body class="nav-<?= !empty($_COOKIE['menuIsCollapsed']) && $_COOKIE['menuIsCollapsed'] == 'true' ? 'sm' : 'md' ?>" >
<?php $this->beginBody(); ?>
<div class="container body">

    <div class="main_container">

        <div class="col-md-3 left_col">
            <div class="left_col scroll-view">

                <div class="navbar nav_title" style="border: 0;">
                    <a href="/" class="site_title"><i class="fa fa-paw"></i> <span>SiteName</span></a>
                </div>
                <div class="clearfix"></div>

                <!-- menu prile quick info -->
                <div class="profile">
                    <div class="profile_pic">
                        <img src="http://placehold.it/128x128" alt="..." class="img-circle profile_img">
                    </div>
                    <div class="profile_info">
                        <span>Welcome,</span>
                        <h2><?=($user = Yii::$app->user->identity)?$user->username:null?></h2>
                    </div>
                </div>
                <!-- /menu prile quick info -->

                <br />

                <!-- sidebar menu -->
                <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">

                    <div class="menu_section">
                        <h3>&nbsp;</h3>
                        <?=
                        \yiister\gentelella\widgets\Menu::widget(
                            [
                                "items" => []
                            ]
                        )
                        ?>
                    </div>

                </div>
                <!-- /sidebar menu -->

                <!-- /menu footer buttons -->
                <div class="sidebar-footer hidden-small">
                    <a data-toggle="tooltip" data-placement="top" title="Settings">
                        <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
                    </a>
                    <a data-toggle="tooltip" data-placement="top" title="FullScreen">
                        <span class="glyphicon glyphicon-fullscreen" aria-hidden="true"></span>
                    </a>
                    <a data-toggle="tooltip" data-placement="top" title="Lock">
                        <span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span>
                    </a>
                    <a data-toggle="tooltip" data-placement="top" title="Logout">
                        <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
                    </a>
                </div>
                <!-- /menu footer buttons -->
            </div>
        </div>

        <!-- top navigation -->
        <div class="top_nav">

            <div class="nav_menu">
                <nav class="" role="navigation">
                    <div class="nav toggle">
                        <a id="menu_toggle"><i class="fa fa-bars"></i></a>
                    </div>



                    <ul class="nav navbar-nav navbar-right">

                        <li>
                            <a>
                                <?php

                                $sysLoad = sys_getloadavg();
                                foreach($sysLoad as $key=>$v){
                                    $m = 1;
                                    if($key == 1){
                                        $m = 5;
                                    }
                                    if($key == 2){
                                        $m = 15;
                                    }
                                    echo Html::tag('span', $v.' <sup>'.$m.' мин</sup> ');
                                }

                                ?>
                            </a>
                        </li>

                        <li class="">
                            <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                <img src="http://placehold.it/128x128" alt=""><?=($user = Yii::$app->user->identity)?$user->username:null?>
                                <span class=" fa fa-angle-down"></span>
                            </a>
                            <ul class="dropdown-menu dropdown-usermenu pull-right">
                                <li><a href="<?=\yii\helpers\Url::to(['/auth/logout'])?>"><i class="fa fa-sign-out pull-right"></i> <?=Yii::t('app', 'logout')?></a></li>
                            </ul>
                        </li>

                        <li>
                            <a href="<?=Url::to(['/adminmenu/menu-base'])?>">Меню</a>
                        </li>

                    </ul>
                </nav>
            </div>

        </div>
        <!-- /top navigation -->

        <!-- page content -->
        <div class="right_col" role="main">

            <div class="x_panel">
                <div class="x_title">
                    <h1><?= Html::encode($this->title) ?></h1>
                    <?php echo \yii\widgets\Breadcrumbs::widget(['links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [], ]); ?>
                </div>
                <div class="x_content">
                    <?= $content ?>
                </div>
            </div>


        </div>
        <!-- /page content -->
        <!-- footer content -->
        <footer>
        </footer>
        <!-- /footer content -->
    </div>

</div>

<!-- /footer content -->
<?php $this->endBody(); ?>
</body>
</html>
<?php $this->endPage(); ?>
