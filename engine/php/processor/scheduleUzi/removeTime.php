<?php

$response['stage'] = $action;
$response['msg'] = 'begin';

$HTTP = $_POST;
extract($HTTP, EXTR_PREFIX_SAME, '_caop');

$DeleteTime = deleteitem(CAOP_SCHEDULE_UZI_TIMES, "time_id='$time_id'");
if ( $DeleteTime ['result'] === true )
{
	$response['result'] = true;
} else
{
	$response['msg'] = 'Проблема с удалением времени';
	$response['debug']['$DeleteTime'] = $DeleteTime;
}
