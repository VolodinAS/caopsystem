<?php
$response['stage'] = $action;
$response['msg'] = 'begin';

$HTTP = $_POST;
extract($HTTP, EXTR_PREFIX_SAME, '_caop');

$DeleteAction = deleteitem(CAOP_CARTRIDGE_ACTION, "action_id='{$cartridge_id}'");
if ( $DeleteAction ['result'] === true )
{
	$response['msg'] = 'Действие успешно удалено';
	$response['result'] = true;
} else
{
	$response['msg'] = 'Ошибка удаления действия';
	$response['debug']['$DeleteAction'] = $DeleteAction;
}