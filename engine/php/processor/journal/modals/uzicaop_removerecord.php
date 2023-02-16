<?php
$response['stage'] = $action;
$response['msg'] = 'begin';

$HTTP = $_POST;
extract($HTTP, EXTR_PREFIX_SAME, '_caop');

$CheckPatUzi = getarr(CAOP_SCHEDULE_UZI_PATIENTS, "patient_date_id='$date_id' AND patient_time_id='$patuzi_id'");
if ( count($CheckPatUzi) == 1 )
{
    $CheckPatUzi = $CheckPatUzi[0];
    
    $DeleteUzi = deleteitem(CAOP_SCHEDULE_UZI_PATIENTS, "patient_id='{$CheckPatUzi['patient_id']}'");
    if ( $DeleteUzi ['result'] === true )
    {
    	$response['result'] = true;
    	$response['msg'] = 'Запись на УЗИ ЦАОП у пациента успешно снята!';
    } else
    {
    	$response['msg'] = 'Проблема при снятии талона';
    	$response['debug']['$DeleteUzi'] = $DeleteUzi;
    }
} else
{
	$response['msg'] = 'Пациент уже удалён. Обновите текущую неделю';
}