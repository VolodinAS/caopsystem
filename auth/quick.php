<?php
$USER_PROFILE = quickHashByDocarr($USER_PROFILE);

$QUICK = $USER_PROFILE['doctor_quick'];

$quick_link = "/quick.php?$QUICK";

bt_notice(wrapper('Перейдите по <a href="'.$quick_link.'" target="_blank">этой ссылке</a> и добавьте ее в закладки, чтобы быстро авторизоваться при входе в личный кабинет'), BT_THEME_PRIMARY);