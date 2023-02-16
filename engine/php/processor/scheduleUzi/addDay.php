<?php
$response['stage'] = $action;
$response['msg'] = 'begin';

$HTTP = $_POST;
extract($HTTP, EXTR_PREFIX_SAME, '_caop');

if ( strlen($dater) > 0 )
{
	
	if ( $shift > 0 )
	{
		
		$CheckUzi = getarr(CAOP_SCHEDULE_UZI_DOCTORS, "uzi_doctor_id='$uzi_doctor_id'");
		if ( count($CheckUzi) > 0 )
		{
			$CheckUzi = $CheckUzi[0];
			
			$date_unix = strtotime($dater);
			
			$DatesUzi = getarr(CAOP_SCHEDULE_UZI_DATES, "dates_uzi_id='{$CheckUzi['uzi_id']}' AND dates_doctor_id='{$CheckUzi['uzi_doctor_id']}' AND dates_date_unix='$date_unix'");
			if ( count($DatesUzi) > 0 )
			{
				$response['msg'] = 'График на '.date(DMY, $date_unix).' уже существует. Выберите другую дату';
			} else
			{
				$param_new_date = array(
					'dates_uzi_id' => $CheckUzi['uzi_id'],
					'dates_doctor_id' => $CheckUzi['uzi_doctor_id'],
					'dates_date_unix' => $date_unix,
					'dates_date' => date(DMY, $date_unix),
					'dates_shift_id' => $shift
				);
				$AddDay = appendData(CAOP_SCHEDULE_UZI_DATES, $param_new_date);
				if ($AddDay[ID] > 0)
				{
					$response['result'] = true;
					$response['unix'] = time();
					$response['msg'] = 'День '.date(DMY, $date_unix).' для графика успешно добавлен';
				}
			}

//		$unix_date = date("d.m.y H:i:s", $date_unix);

//		$response['debug']['$date_unix'] = $date_unix;
//		$response['debug']['$unix_date'] = $unix_date;
//		$response['debug']['$CheckUzi'] = $CheckUzi;
		
		} else
		{
			$response['msg'] = 'Такого врача в расписании не существует';
		}
		
	} else
	{
		$response['msg'] = 'Не выбрана смена для этого дня';
	}
}  else
{
	$response['msg'] = 'Вы не указали дату';
}