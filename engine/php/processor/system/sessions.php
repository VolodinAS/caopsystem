<?php
$response['stage'] = $action;
$response['msg'] = 'begin';

$HTTP = $_POST;
extract($HTTP, EXTR_PREFIX_SAME, '_caop');

switch ($acttype)
{
	case "set":
		if ( strlen($data_name) > 0 )
		{
			$_SESSION[$data_name] = $variables;
			$response['result'] = true;
		} else
		{
			$response['msg'] = 'Не указано имя хранимых параметров';
		}
		break;
	case "reset":
		if ( strlen($data_name) > 0 )
		{
			unset($_SESSION[$data_name]);
			$response['result'] = true;
		} else
		{
			if ( $total_reset == "yes" )
			{
				unset($_SESSION);
				$response['result'] = true;
			}
		}
		break;
}