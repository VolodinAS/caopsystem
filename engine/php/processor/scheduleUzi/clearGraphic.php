<?php
$response['stage'] = $action;
$response['msg'] = 'begin';

$HTTP = $_POST;
extract($HTTP, EXTR_PREFIX_SAME, '_caop');

$CheckUzi = getarr(CAOP_SCHEDULE_UZI_DOCTORS, "uzi_doctor_id='$uzi_doctor_id'");
if ( count($CheckUzi) == 1 )
{
	$CheckUzi = $CheckUzi[0];
	$response['debug']['$CheckUzi'] = $CheckUzi;
	$unix_begin = strtotime($dater_from);
	$unix_end = strtotime($dater_to);
	
	$DeleteDates = deleteitem(CAOP_SCHEDULE_UZI_DATES, "dates_uzi_id='{$CheckUzi['uzi_id']}' AND dates_doctor_id='{$CheckUzi['uzi_doctor_id']}' AND dates_date_unix>='$unix_begin' AND dates_date_unix<='$unix_end'");
	if ( $DeleteDates ['result'] === true )
	{
		$response['result'] = true;
		$response['unix'] = time();
		$response['msg'] = 'График успешно очищен за период с '.date(DMY, $unix_begin).' по '.date(DMY, $unix_end).'!';
	} else
	{
		$response['msg'] = 'Проблема с удаленем графика смен';
		$response['debug']['$DeleteDates'] = $DeleteDates;
	}
 
} else
{
	$response['msg'] = 'Такого врача УЗИ не существует';
}