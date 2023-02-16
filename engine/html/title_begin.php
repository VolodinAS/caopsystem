<?php
require_once "engine/html/html_content/html.php";
?>
<div id="overlayLoader">
    <div>
        Загрузка. Подождите, пожалуйста...
    </div>
</div>

<div class="container-fluid"
     id="mainContainerLoaded"> <!-- MAIN CONTAINER -->

    <div id="updateNotifier"
         style="display: none">
		<? bt_notice('<b>Внимание! Данные на странице устарели! Нажмите "Обновить"!</b>', BT_THEME_DANGER); ?>
    </div>

    <style>
        nav {
            background: white;
            box-shadow: 0 2px 0 0 #c9cdce;
            border-top: 1px solid #c9cdce;
            text-align: center;
        }
        nav a {
            text-decoration: none;
            display: block;
            transition: .1s linear;
        }
        nav ul {
            list-style: none;
            margin: 0;
            padding: 0;
        }
        .topmenu > li {
            display: inline-block;
            position: relative;
            margin-right: -4px;
            border-left: 1px solid #c9cdce;
        }
        .topmenu > li:last-child {border-right: 1px solid #c9cdce;}
        .topmenu > li > a {
            font-weight: bold;
            padding: 10px 10px;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: #1c1c1c;
        }
        /*.active:after, .submenu-link:after {*/
        /*    content: "\f107";*/
        /*    font-family: "FontAwesome";*/
        /*    color: inherit;*/
        /*    margin-left: 10px;*/
        /*}*/
        .topmenu .active, .topmenu > li > a:hover, .submenu li a:hover
        {
            color: #ddbe86;
        }
        .submenu {
            position: absolute;
            left: -1px;
            z-index: 5;
            width: 240px;
            border-bottom: 1px solid #c9cdce;
            /*visibility: hidden;*/
            display: none;
            /*opacity: 0;*/
            /*transform: translateY(10px);*/
            /*transition: .3s ease-in-out;*/
            background-color:#17a2b8;
        }
        /*ECF1F2*/
        .submenu li {position: relative;}
        .submenu a {
            background: #f8f9fa;
            border-top: 1px solid #c9cdce;
            border-right: 1px solid #c9cdce;
            border-left: 1px solid #c9cdce;
            color: #1c1c1c;
            text-align: left;
            font-size: 18px;
            letter-spacing: 1px;
            padding: 10px 20px;
            /*background-color:#17a2b8;*/
        }
        .submenu .submenu {
            position: absolute;
            top: 0;
            left: calc(100% - 1px);
            left: -webkit-calc(100% - 1px);
        }
        nav li:hover > .submenu {
            /*visibility: visible;*/
            display: block;
            
            /*opacity: 1;*/
            /*transform: translateY(0px);*/
        }
    </style>

    <?php
//    require_once ("engine/html/title_begin_headmenu.php");
    require_once ("engine/html/title_begin_headmenu_new.php");

	if ($params['calendar'] == 1)
	{
		$CALENDAR = '
        <div class="flex-row-reverse ml-auto">
            <form id="dateShow" class="form-inline" method="post" action="#">
            <div class="input-group">
                <div class="input-group-prepend">
                <span class="input-group-text" id="dateShow" ' . super_bootstrap_tooltip('Переход на выбранную дату') . '>
                    ' . BT_ICON_ARROW_RIGHT_SQUARE_FILL . '
                </span>
                </div>
                <input type="date" class="form-control" placeholder="Дата" aria-label="Username" aria-describedby="dateShow" id="calendar">
                <button class="btn btn-outline-success my-2 my-sm-0" type="submit">' . BT_ICON_OK . '</button>
            </div>
            </form>
        </div>
        ';
	}
	?>

    <div class="<?= $params['container']; ?>">
		<? if (strlen($params['title']) != 0): ?>
            <nav class="navbar navbar-light navbar-expand-sm bg-light">
                <a class="navbar-brand"
                   href="/<?= $params['link']; ?>">
                    <img src="/engine/images/icons/<?= $params['icon']; ?>.<?= $params['icon_ext']; ?>"
                         width="30"
                         height="30"
                         class="d-inline-block align-top"
                         alt="">
                    <span class="h3"><?= $params['head']; ?></span>
                </a>
				<?= $CALENDAR; ?>
            </nav>
		<? endif; ?>

<?php
//debug($params['headmenu']);


?>