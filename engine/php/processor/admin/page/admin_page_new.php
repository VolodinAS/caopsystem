<?php
$response['stage'] = $action;
$response['msg'] = 'begin';

$HTTP = $_POST;
extract($HTTP, EXTR_PREFIX_SAME, '_caop');

$RequiredField = array(
	'pages_title',
	'pages_link',
	'pages_head',
	'pages_access'
);

$isChecked = checkRequiredFields($RequiredField, $_POST);

if ($isChecked === true)
{
	
	$param_values = $_POST;
	$NewPage = appendData(CAOP_PAGES, $param_values);
	if ( $NewPage[ID] > 0 )
	{
		$response['result'] = true;
		$response['msg'] = 'Новая страница успешно добавлена!';
	}
	
} else
{
	$response['msg'] = 'Вы не заполнили обязательные поля: ' . implode(', ', $isChecked);
}