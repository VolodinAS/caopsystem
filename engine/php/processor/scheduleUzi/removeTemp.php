<?php
$response['stage'] = $action;
$response['msg'] = 'begin';

$HTTP = $_POST;
extract($HTTP, EXTR_PREFIX_SAME, '_caop');

$DeleteTemp = deleteitem(CAOP_SCHEDULE_UZI_DATES_SHIFTS_TEMP, "temp_id='$temp_id' OR temp_subid='$temp_id'");
if ( $DeleteTemp ['result'] === true )
{
	$response['result'] = true;
} else
{
	$response['msg'] = 'Проблема с удалением шаблона';
	$response['debug']['$DeleteTemp'] = $DeleteTemp;
}