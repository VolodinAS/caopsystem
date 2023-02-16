<?php
$response['stage'] = $action;
$response['msg'] = 'begin';

$HTTP = $_POST;
extract($HTTP, EXTR_PREFIX_SAME, '_caop');

$add_param_values = $_POST;

$if_not_exists = array(
	"index" => "param_name",
	"query" => "param_name='{$add_param_values['param_name']}'"
);

$AddParam = appendData(CAOP_PARAMS, $add_param_values, $if_not_exists);
if ($AddParam[ID] > 0)
{
	$response['result'] = true;
} else
{
	$response['msg'] = 'Проблема с добавлением нового параметра';
	$response['debug']['$AddParam'] = $AddParam;
}