<?php
$response['stage'] = $action;
$response['msg'] = 'begin';

$HTTP = $_POST;
extract($HTTP, EXTR_PREFIX_SAME, '_caop');

if ( strlen($holiday_title) > 0 )
{
	
	if ( strlen($holiday_from) > 0 )
	{
		
		if ( strlen($holiday_to) > 0 )
		{
		
//			$current_year = date("Y", time());
//
//			$begin_of_year = '01.01.' . $current_year;
//			$begin_of_year_unix = birthToUnix($begin_of_year);
//
//			$date_from = $holiday_from . '.' . $current_year;
//			$date_from_unix = birthToUnix($date_from);
			
//			$date_to = $holiday_to . '.' . $current_year;
//			$response['debug']['$date_to'] = $date_to;
//			$date_to_unix = birthToUnix($date_to);
//			$response['debug']['$date_to_unix'] = $date_to_unix;
			
//			$holiday_sbboy_begin = $date_from_unix - $begin_of_year_unix;
			$holiday_sbboy_begin = ssboy($holiday_from);
//			$holiday_sbboy_end = ($date_to_unix - $begin_of_year_unix) + (TIME_DAY - 1);
			$holiday_sbboy_end = ssboy($holiday_from, 1);
			
//			$response['debug']['$holiday_sbboy_begin'] = $holiday_sbboy_begin;
//			$response['debug']['$holiday_sbboy_end'] = $holiday_sbboy_end + (TIME_DAY - 1);
			
			if ($holiday_sbboy_end >= $holiday_sbboy_begin)
			{
				
				$param_new_holiday = array(
				    'holiday_title' => $holiday_title,
					'holiday_sbboy_from' => $holiday_sbboy_begin,
					'holiday_sbboy_to' => $holiday_sbboy_end
				);
				
				$AddHoliday = appendData(CAOP_HOLIDAYS, $param_new_holiday);
				if ($AddHoliday[ID] > 0)
				{
					$response['result'] = true;
				} else $response['msg'] = 'Ошибка добавления праздника';
				
			} else $response['msg'] = 'Неправильно задан период праздника';
			
		 
		} else $response['msg'] = 'Вы не указали дату окончания праздника';
		
	} else $response['msg'] = 'Вы не указали дату начала праздника';
    
} else $response['msg'] = 'Вы не указали название праздника';