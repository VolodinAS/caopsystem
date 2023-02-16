<?php
$response['stage'] = $action;
$response['msg'] = 'begin';

$HTTP = $_POST;
extract($HTTP, EXTR_PREFIX_SAME, '_caop');

if ( $action_type_id > 0 )
{

	$param_add = $HTTP;
	
	$param_add['action_date_add_unix'] = time();
	$param_add['action_date_update_unix'] = time();
	
	$NewAction = appendData(CAOP_CARTRIDGE_ACTION, $param_add);
	if ( $NewAction[ID] > 0 )
	{
		$response['msg'] = 'Действие добавлено!';
		$response['result'] = true;
	} else
	{
		$response['msg'] = 'Проблемы с добавлением действия!';
		$response['debug']['$NewAction'] = $NewAction;
	}

} else
{
	$response['msg'] = 'Вы не выбрали действие с картриджем';
}