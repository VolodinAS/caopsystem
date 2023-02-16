<?php
$user_menu = getTreeMenu($params['profile'], 1);
//debugn($user_menu, '$user_menu');

$user_menu_tree = getTree($user_menu);


$menu_editor = showCatEditor($user_menu_tree);

?>

<button
        class="btn btn-primary mysqleditor-modal-form"
        id="myemf_button_add"
        type="button"
        data-action="add"
        data-table="<?=CAOP_HEADMENU;?>"
        data-fieldid="<?=$PK[CAOP_HEADMENU];?>"
        data-title="Добавление нового пункта меню"
>
    Добавить новый пункт меню
</button><br><br>

<?=$menu_editor;?>
<?php
spoiler_begin('debug', 'debug');
debugn($user_menu_tree, '$user_menu_tree');
spoiler_end();
?>
