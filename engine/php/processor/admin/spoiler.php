<?php
$response['stage'] = $action;
$response['msg'] = 'begin';

$HTTP = $_POST;
extract($HTTP, EXTR_PREFIX_SAME, '_caop');

$go_next = false;
switch ($act)
{
	case "hide":
		$spoiler_value = '';
		$go_next = true;
	break;
	case "pin":
		$spoiler_value = $spoiler_id;
		$go_next = true;
	break;
	default:
	    $response['msg'] = 'Такого действия не найдено';
	break;
}

if ( $go_next )
{
	$set_spolier = doctor_param("set", $USER_PROFILE['doctor_id'], 'admin_spoiler', $spoiler_value);
	if ($set_spolier['stat'] == true)
	{
		$response['result'] = true;
	} else
	{
		$response['msg'] = 'Проблема с обновлением параметра';
	}
}
