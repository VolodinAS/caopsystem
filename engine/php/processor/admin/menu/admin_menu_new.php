<?php
$response['stage'] = $action;
$response['msg'] = 'begin';

$HTTP = $_POST;
extract($HTTP, EXTR_PREFIX_SAME, '_caop');

$RequiredField = array(
	'headmenu_access',
	'headmenu_link',
	'headmenu_order',
	'headmenu_title',
	'headmenu_enabled'
);

$isChecked = checkRequiredFields($RequiredField, $_POST);

if ($isChecked === true)
{
	
	$param_values = $_POST;
	$NewMenu = appendData(CAOP_HEADMENU, $param_values);
	if ( $NewMenu[ID] > 0 )
	{
		$response['result'] = true;
		$response['msg'] = 'Новый пункт меню успешно добавлен!';
	}
	
} else
{
	$response['msg'] = 'Вы не заполнили обязательные поля: ' . implode(', ', $isChecked);
}