<?php
$response['stage'] = $action;
$response['msg'] = 'begin';

$HTTP = $_POST;
extract($HTTP, EXTR_PREFIX_SAME, '_caop');

$CheckUzi = getarr(CAOP_SCHEDULE_UZI_DOCTORS, "uzi_id='$uzi_id' AND uzi_doctor_id='$doctor_id'");
if ( count($CheckUzi) == 1 )
{
 
	$CheckUzi = $CheckUzi[0];
	
	$param_add_temp = array(
	    'temp_uzi_id' => $CheckUzi['uzi_id'],
		'temp_doctor_id' => $CheckUzi['uzi_doctor_id']
	);
	
	$AddTemp = appendData(CAOP_SCHEDULE_UZI_DATES_SHIFTS_TEMP, $param_add_temp);
	if ( $AddTemp[ID] > 0 )
	{
	
		$SUCCESS = 0;
		$TOTAL = 0;
		for ($week_day=0; $week_day<7; $week_day++)
		{
			$TOTAL++;
		    $param_add_day = array(
		        'temp_subid' => $AddTemp[ID],
			    'temp_week_day' => $week_day
		    );
		    $AddDay = appendData(CAOP_SCHEDULE_UZI_DATES_SHIFTS_TEMP, $param_add_day);
		    if ( $AddDay[ID] > 0 )
		    {
		    	$SUCCESS++;
		    }
		}
		
		if ($SUCCESS == $TOTAL)
		{
			$response['result'] = true;
		} else
		{
			$response['msg'] = 'Какой-то из запросов при создании шаблона не выполнился...';
		}
	
	} else
	{
		$response['msg'] = 'Проблема с добавлением шаблона';
	}
	
} else
{
	$response['msg'] = 'Такого врача УЗИ не существует';
}