<?php
$response['stage'] = $action;
$response['msg'] = 'begin';

$HTTP = $_POST;
extract($HTTP, EXTR_PREFIX_SAME, '_caop');

$DoctorData = $DoctorsListId['id' . $doctor_id];
if ( $DoctorData['doctor_isUzi'] == 0 )
{
	$param_update = array(
	    'doctor_isUzi' => 1
	);
	$UpdateDoctor = updateData(CAOP_DOCTOR, $param_update, $DoctorData, "doctor_id='$doctor_id'");
	if ( $UpdateDoctor['stat'] == RES_SUCCESS )
	{
		$param_add = array(
		    'uzi_doctor_id' => $doctor_id,
			'uzi_added_unix' => time()
		);
		$AddDoctorUzi = appendData(CAOP_SCHEDULE_UZI_DOCTORS, $param_add);
		if ( $AddDoctorUzi[ID] > 0 )
		{
			$response['result'] = true;
		} else
		{
			$response['msg'] = 'Проблема с добавлением врача';
			$response['debug']['$AddDoctorUzi'] = $AddDoctorUzi;
		}
		
		
	} else
	{
		$response['msg'] = 'Проблема с обновлением данных врача';
		$response['debug']['$UpdateDoctor'] = $UpdateDoctor;
	}
	
} elseif ( $DoctorData['doctor_isUzi'] == 1 )
{
	$param_update = array(
		'doctor_isUzi' => 0
	);
	$UpdateDoctor = updateData(CAOP_DOCTOR, $param_update, $DoctorData, "doctor_id='$doctor_id'");
	if ( $UpdateDoctor['stat'] == RES_SUCCESS )
	{
		// удалить из caop_schedule_uzi_doctors
		$CheckUzi = getarr(CAOP_SCHEDULE_UZI_DOCTORS, "uzi_doctor_id='$doctor_id'");
		if ( count($CheckUzi) == 1 )
		{
			$CheckUzi = $CheckUzi[0];
			$DeleteItem = deleteitem(CAOP_SCHEDULE_UZI_DOCTORS, "uzi_doctor_id='$doctor_id'");
			if ( $DeleteItem ['result'] === true )
			{
				// УДАЛЕНИЕ ИЗ caop_schedule_uzi_dates
				$DeleteDates = deleteitem(CAOP_SCHEDULE_UZI_DATES, "dates_uzi_id='{$CheckUzi['uzi_id']}' AND dates_doctor_id='{$CheckUzi['uzi_doctor_id']}'");
				if ( $DeleteDates ['result'] === true )
				{
					// УДАЛЕНИЕ ИЗ caop_schedule_uzi_times
					$DeleteTimes = deleteitem(CAOP_SCHEDULE_UZI_TIMES, "time_uzi_id='{$CheckUzi['uzi_id']}'");
					if ( $DeleteTimes ['result'] === true )
					{
						// УДАЛЕНИЕ ИЗ caop_schedule_uzi_shifts
						$DeleteShifts = deleteitem(CAOP_SCHEDULE_UZI_SHIFTS, "shift_uzi_id='{$CheckUzi['uzi_id']}' AND shift_doctor_id='{$CheckUzi['uzi_doctor_id']}'");
						if ( $DeleteShifts ['result'] === true )
						{
							// УДАЛЕНИЕ ИЗ caop_schedule_uzi_dates_shifts_temp
							$CheckTemps = getarr(CAOP_SCHEDULE_UZI_DATES_SHIFTS_TEMP, "temp_uzi_id='{$CheckUzi['uzi_id']}' AND temp_doctor_id='{$CheckUzi['uzi_doctor_id']}'");
							if ( count($CheckTemps) > 0 )
							{
							    $CheckTemps = $CheckTemps[0];
								$DeleteTempMain = deleteitem(CAOP_SCHEDULE_UZI_DATES_SHIFTS_TEMP, "temp_uzi_id='{$CheckUzi['uzi_id']}' AND temp_doctor_id='{$CheckUzi['uzi_doctor_id']}'");
								if ( $DeleteTempMain ['result'] === true )
								{
									$DeleteTempSubs = deleteitem(CAOP_SCHEDULE_UZI_DATES_SHIFTS_TEMP, "temp_subid='{$CheckTemps['temp_id']}'");
									if ( $DeleteTempSubs ['result'] === true )
									{
										
										$response['result'] = true;
									} else
									{
										$response['msg'] = 'Проблема с удалением ДНЕЙ из списка ШАБЛОНОВ';
										$response['debug']['$DeleteTempSubs'] = $DeleteTempSubs;
									}
								} else
								{
									$response['msg'] = 'Проблема с удалением из списка ШАБЛОНОВ';
									$response['debug']['$DeleteTempMain'] = $DeleteTempMain;
								}
							} else
							{
								$response['result'] = true;
							}
							
						} else
						{
							$response['msg'] = 'Проблема с удалением из списка СМЕН';
							$response['debug']['$DeleteShifts'] = $DeleteShifts;
						}
					} else
					{
						$response['msg'] = 'Проблема с удалением из списка ВРЕМЁН';
						$response['debug']['$DeleteTimes'] = $DeleteTimes;
					}
				} else
				{
					$response['msg'] = 'Проблема с удалением из списка ДАТ';
					$response['debug']['$DeleteDates'] = $DeleteDates;
				}
			
			
			
			
			} else
			{
				$response['msg'] = 'Проблема с удалением из списка УЗИстов';
				$response['debug']['$DeleteItem'] = $DeleteItem;
			}
		} else
		{
			$response['msg'] = 'Такого врача в списке УЗИстов нет';
		}
		
	} else
	{
		$response['msg'] = 'Проблема с обновлением данных врача';
		$response['debug']['$UpdateDoctor'] = $UpdateDoctor;
	}
}
$response['debug']['$DoctorData'] = $DoctorData;