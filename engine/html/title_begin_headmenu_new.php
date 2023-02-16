<?php
//debug($params);
//debug();
$user_menu = getTreeMenu($params['profile']);
//debugn($user_menu, '$user_menu');

$user_menu_tree = getTree($user_menu);
//debugn($user_menu_tree, '$user_menu_tree');
//debugn($params,'$params');
$cat_menu = showCat($user_menu_tree, $params);
?>

<nav class="navbar navbar-light navbar-expand-xl">

    <a class="navbar-brand"
       href="/news">
        <img src="/engine/images/logo/main_logo.png"
             width="30"
             height="30"
             class="d-inline-block align-top"
             alt="">
        <span class="font-weight-bolder">ЦАОП</span>
    </a>

    <button class="navbar-toggler"
            type="button"
            data-toggle="collapse"
            data-target="#new_menu"
            aria-controls="new_menu"
            aria-expanded="false"
            aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="new_menu">
        <ul class="topmenu" style="z-index: 6">
		    <?=$cat_menu;?>
        </ul>
    </div>

    <span class="navbar-text font-weight-bolder">
        <?= $params['username']; ?>
    </span>
</nav>
