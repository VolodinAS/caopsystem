<?php
$response['stage'] = $action;
$response['msg'] = 'begin';

$HTTP = $_POST;
extract($HTTP, EXTR_PREFIX_SAME, '_caop');

$CheckUzi = getarr(CAOP_SCHEDULE_UZI_DOCTORS, "uzi_id='$uzi_id' AND uzi_doctor_id='$doctor_id'");
if ( count($CheckUzi) == 1 )
{
    $CheckUzi = $CheckUzi[0];
    
    $param_shift_add = array(
        'shift_uzi_id' => $CheckUzi['uzi_id'],
	    'shift_doctor_id' => $CheckUzi['uzi_doctor_id']
    );
    $AddShift = appendData(CAOP_SCHEDULE_UZI_SHIFTS, $param_shift_add);
    if ( $AddShift[ID] > 0 )
    {
    	$response['result'] = true;
    	$response['msg'] = 'Смена успешно добавлена';
    } else
    {
    	$response['msg'] = 'Проблема добавления смены';
    	$response['debug']['$AddShift'] = $AddShift;
    }
} else
{
	$response['msg'] = 'Такого врача УЗИ не существует';
}

//$response['debug']['$CheckUzi'] = $CheckUzi;