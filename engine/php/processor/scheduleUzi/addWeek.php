<?php
$response['stage'] = $action;
$response['msg'] = 'begin';

$HTTP = $_POST;
extract($HTTP, EXTR_PREFIX_SAME, '_caop');

$CheckUzi = getarr(CAOP_SCHEDULE_UZI_DOCTORS, "uzi_doctor_id='$uzi_doctor_id'");
if ( count($CheckUzi) > 0 )
{
    $CheckUzi = $CheckUzi[0];
    
    $response['debug']['$CheckUzi'] = $CheckUzi;
    
    $CheckTemp = getarr(CAOP_SCHEDULE_UZI_DATES_SHIFTS_TEMP, "temp_id='$temp_id'");
    if ( count($CheckTemp) > 0 )
    {
        $CheckTemp = $CheckTemp[0];
        
        $response['debug']['$CheckTemp'] = $CheckTemp;
        
        switch ($first_add)
        {
            case "by_date":
                // по дате
				// проверяем, что указанный день - понедельник
				$week_day = date('N', strtotime($dater)) - 1;
//				$response['debug']['$week_day'] = $week_day;
                if ( $week_day === 0 )
                {
                	$dayUnix = birthToUnix($dater);
                    $GetTemps = getarr(CAOP_SCHEDULE_UZI_DATES_SHIFTS_TEMP, "temp_subid='{$CheckTemp['temp_id']}'", "ORDER BY temp_week_day ASC");
                    if ( count($GetTemps) > 0 )
                    {
                        $response['debug']['$GetTemps'] = $GetTemps;
	                    foreach ($GetTemps as $getTemp)
	                    {
	                    	$logarr = [];
		                    $ChosenDay = $dayUnix + $getTemp['temp_week_day'] * TIME_DAY;
		                    $DateChosen = date(DMY, $ChosenDay);
		                    $logarr['$ChosenDay'] = $ChosenDay;
		                    $logarr['$getTemp'] = $getTemp['temp_week_day'];
		                    $logarr['$DateChosen'] = $DateChosen;
		                    
		                    $CheckDate = getarr(CAOP_SCHEDULE_UZI_DATES, "dates_uzi_id='{$CheckUzi['uzi_id']}' AND dates_doctor_id='{$CheckUzi['uzi_doctor_id']}' AND dates_date LIKE '$DateChosen'");
		                    if ( count($CheckDate) == 1 )
		                    {
		                    	if ( $overwrite == 1 )
			                    {
				                    $CheckDate = $CheckDate[0];
				                    // меняем смену за данную шаблоном
				                    if ( $getTemp['temp_day_shift'] > 0 )
				                    {
					                    $param_update_date = array(
						                    'dates_shift_id' => $getTemp['temp_day_shift']
					                    );
					                    $UpdateShift = updateData(CAOP_SCHEDULE_UZI_DATES, $param_update_date, $CheckDate, "dates_id='{$CheckDate['dates_id']}'");
				                    } else
				                    {
					                    $DeleteDay = deleteitem(CAOP_SCHEDULE_UZI_DATES, "dates_id='{$CheckDate['dates_id']}'");
				                    }
			                    }
		                    } else
		                    {
		                    	// если не существует - создаем, но при условии, что shift_id>0
			                    if ( $getTemp['temp_day_shift'] > 0 )
			                    {
			                    	$param_add_shift = array(
			                    	    'dates_uzi_id' => $CheckUzi['uzi_id'],
					                    'dates_doctor_id' => $CheckUzi['uzi_doctor_id'],
					                    'dates_date' => $DateChosen,
					                    'dates_shift_id' => $getTemp['temp_day_shift'],
					                    'dates_date_unix' => $ChosenDay
			                    	);
			                    	$AddDateShift = appendData(CAOP_SCHEDULE_UZI_DATES, $param_add_shift);
			                    }
		                    }
		                    
		                    $response['debug']['$logarr'][] = $logarr;
                        }
                    } else
                    {
                    	$response['msg'] = 'СИСТЕМНАЯ ОШИБКА: Отсутствуют настройки дней недели';
                    }
                } else
                {
                	$response['msg'] = 'Для указания начала расписания смен выберите ПОНЕДЕЛЬНИК (выбран '.getDayRusByIndex($week_day+1).')';
                }
            break;
            case "closer":
                // ближайший понедельник
            break;
        }
        
        $ow = 'Ранее установленные смены НЕ затронуты!';
        if ($overwrite == 1)
        {
        	$ow = 'Ранее установленные смены были ПЕРЕЗАПИСАНЫ!';
        }
        
        $response['result'] = true;
        $response['unix'] = time();
        $response['msg'] = 'График на неделю с '.$dater.' успешно добавлен! ' . $ow;
    } else
    {
    	$response['msg'] = 'Шаблон не выбран или такого шаблона не существует';
    }
} else
{
	$response['msg'] = 'Такого врача УЗИ нет';
}