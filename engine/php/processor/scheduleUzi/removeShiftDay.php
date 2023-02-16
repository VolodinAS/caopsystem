<?php
$response['stage'] = $action;
$response['msg'] = 'begin';

$HTTP = $_POST;
extract($HTTP, EXTR_PREFIX_SAME, '_caop');

$CheckDates = getarr(CAOP_SCHEDULE_UZI_DATES, "dates_id='$dates_id'");
if ( count($CheckDates) == 1 )
{
    $CheckDates = $CheckDates[0];
    
    $response['debug']['$CheckDates'] = $CheckDates;
    
    $CheckPatUzi = getarr(CAOP_SCHEDULE_UZI_PATIENTS, "patient_date_id='{$CheckDates['dates_id']}'");
    if ( count($CheckPatUzi) > 0 )
    {
        $response['msg'] = 'Невозможно удалить смену, так как в расписании записаны пациенты. Обновите список!';
    } else
    {
    	$DeleteDate = deleteitem(CAOP_SCHEDULE_UZI_DATES, "dates_id='{$CheckDates['dates_id']}'");
    	if ( $DeleteDate ['result'] === true )
	    {
	    	$response['result'] = true;
	    	$response['msg'] = 'Смена успешно удалена!';
	    	$response['unix'] = $CheckDates['dates_date_unix'];
	    } else
	    {
	    	$response['msg'] = 'Проблема с удалением смены!';
	    	$response['debug']['$DeleteDate'] = $DeleteDate;
	    }
    }
} else
{
	$response['msg'] = 'Такой смены в графике нет!';
}