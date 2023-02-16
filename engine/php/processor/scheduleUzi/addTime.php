<?php

$response['stage'] = $action;
$response['msg'] = 'begin';

$HTTP = $_POST;
extract($HTTP, EXTR_PREFIX_SAME, '_caop');

$CheckShift = getarr(CAOP_SCHEDULE_UZI_SHIFTS, "shift_id='$shift_id'");

if ( count($CheckShift) == 1 )
{
	$CheckShift = $CheckShift[0];
	
	$timeData = explode(':', $time);
	if ( count($timeData) == 2 )
	{
		
		$hours = $timeData[0];
		$mins = $timeData[1];
		
		if ( is_numeric($hours) )
		{
			if ( is_numeric($mins) )
			{
				
				$param_add_time = array(
				    'time_uzi_id' => $CheckShift['shift_uzi_id'],
					'time_shift_id' => $CheckShift['shift_id'],
					'time_hour' => $hours,
					'time_min' => $mins,
					'time_order' => (int)$hours * 60 + (int)$mins
				);
				
				$AddTime = appendData(CAOP_SCHEDULE_UZI_TIMES, $param_add_time);
				if ( $AddTime[ID] > 0 )
				{
					$response['result'] = true;
				} else
				{
					$response['msg'] = 'Проблемы с добавлением времени';
					$response['debug']['$AddTime'] = $AddTime;
				}
				
			} else
			{
				$response['msg'] = 'МИНУТЫ должны быть числами';
			}
		} else
		{
			$response['msg'] = 'ЧАСЫ должны быть числами';
		}
	 
	} else
	{
		$response['msg'] = 'Неверно задано время';
	}
 
} else
{
	$response['msg'] = 'Такого графика не существует';
}
