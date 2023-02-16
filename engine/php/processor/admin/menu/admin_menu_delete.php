<?php
$response['stage'] = $action;
$response['msg'] = 'begin';

$HTTP = $_POST;
extract($HTTP, EXTR_PREFIX_SAME, '_caop');

$MenuRM = RecordManipulation($menu_id, CAOP_HEADMENU, 'headmenu_id');
if ( $MenuRM['result'] )
{
    $MenuData = $MenuRM['data'];

    $DeleteMenu = deleteitem(CAOP_HEADMENU, "headmenu_id='{$MenuData['headmenu_id']}'");
    if ( $DeleteMenu ['result'] === true )
    {
    	$response['result'] = true;
    	$response['msg'] = 'Пункт меню успешно удалён';
    }

} else $response['msg'] = $MenuRM['msg'];