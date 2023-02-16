<?php
$response['stage'] = $action;
$response['msg'] = 'begin';

$HTTP = $_POST;
extract($HTTP, EXTR_PREFIX_SAME, '_caop');

$DeleteShiftTimes = deleteitem(CAOP_SCHEDULE_UZI_TIMES, "time_shift_id='$shift_id'");
if ( $DeleteShiftTimes ['result'] === true )
{
	$DeleteShift = deleteitem(CAOP_SCHEDULE_UZI_SHIFTS, "shift_id='$shift_id'");
	if ( $DeleteShift ['result'] === true )
	{
		$response['result'] = true;
	} else
	{
		$response['msg'] = 'Проблема с удалением смены';
		$response['debug']['$DeleteShift'] = $DeleteShift;
	}
} else
{
	$response['msg'] = 'Проблема с удалением расписания';
	$response['debug']['$DeleteShiftTimes'] = $DeleteShiftTimes;
}