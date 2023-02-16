<?php

function getFirstFreeTalon($uzi_doctor_id=0, $first_date_unix=false)
{
	$is_found = false;
    $response['params'] = array(
        'uzi_doctor_id' => $uzi_doctor_id,
	    'first_date_unix' => $first_date_unix
    );
    $response['result'] = time();
    
    $go_next = false;
    
    // 1 - выбираем врачей
    
    if ($uzi_doctor_id > 0)
    {
	    $UziDoctors = getarr(CAOP_SCHEDULE_UZI_DOCTORS, "uzi_doctor_id='{$uzi_doctor_id}'");
    } else
    {
	    $UziDoctors = getarr(CAOP_SCHEDULE_UZI_DOCTORS, "1", "ORDER BY uzi_id ASC");
    }
	
	if ( count($UziDoctors) > 0 )
	{
		$go_next = true;
	} else
	{
		$response['msg'] = 'В списке нет врачей УЗИ';
	}
	
	if ($go_next)
	{
		
		// 2 - выбираем даты
		
		$go_next = false;
		
		$response['params']['doctors'] = $UziDoctors;
		
		if ($first_date_unix === false)
		{
			$FirstDateUnix = getarr(CAOP_SCHEDULE_UZI_DATES, "1", "ORDER BY dates_date_unix ASC LIMIT 1");
			if ( count($FirstDateUnix) > 0 )
			{
				$first_date_unix = $FirstDateUnix[0]['dates_date_unix'];
				$go_next = true;
			} else
			{
				$response['msg'] = 'В расписании отсутствует самый ранний талон';
			}
		} else
		{
			// начало дня
			$time_data = getCurrentDay($first_date_unix);
			$first_date_unix = $time_data['by_timestamp']['begins']['day']['unix'];
			
			$go_next = true;
		}
	}
	
	if ($go_next)
	{
		
		// 2 - получаем список доступных времен и сравниваем с количеством записанных пациентов
		
		$response['params']['first_date_unix'] = $first_date_unix;
		$response['params']['first_date'] = date(DMYHIS, $first_date_unix);
		
		$go_next = false;
		
		foreach ($UziDoctors as $uziDoctor)
		{
			$GetDates = getarr(CAOP_SCHEDULE_UZI_DATES, "dates_doctor_id='{$uziDoctor['uzi_doctor_id']}' AND dates_date_unix>='{$first_date_unix}'", "ORDER BY dates_date_unix ASC");
			if ( count($GetDates) > 0 )
			{
				foreach ($GetDates as $date_uzi)
				{
					$GetTotalTimes = getarr(CAOP_SCHEDULE_UZI_TIMES, "time_shift_id='{$date_uzi['dates_shift_id']}'", null, false, 'time_id');
					$GetBusyTimes = getarr(CAOP_SCHEDULE_UZI_PATIENTS, "patient_doctor_id='{$uziDoctor['uzi_doctor_id']}' AND patient_date_id='{$date_uzi['dates_id']}'", null, false, 'patient_id');
					$response['debug']['$GetDates_'.$date_uzi['dates_date_unix'].'_'.$date_uzi['dates_date']] = count($GetBusyTimes) . ' / ' . count($GetTotalTimes);
					
					if ( count($GetBusyTimes) < count($GetTotalTimes) )
					{
						$found_free_date_unix = $date_uzi['dates_date_unix'];
						$response['debug']['$found_free_date_unix'] = $found_free_date_unix;
						$is_found = true;
						break;
					}
				}
				
			}
		}
		if ($is_found)
		{
			$response['result'] = $found_free_date_unix;
		}
	}
    
    return $response;
}

function getPatientDataById($patid, $getVisits=false, $getResearches=false, $getCitology=false)
{
	$response = array();
	$response['result'] = false;
	$response['error'] = true;
	$response['msg'] = 'begin';
	$response['params'] = $patid;
	$response['data']['personal'] = array();
	if ( $getVisits ) $response['data']['visits'] = array();
	if ( $getResearches ) $response['data']['researches'] = array();
	if ( $getCitology ) $response['data']['citologys'] = array();

	$Patients = getarr(CAOP_PATIENTS, "patid_id='{$patid}'");
//	debug($Patients);
	if ( count($Patients) == 0 )
	{
		$response['result'] = true;
		$response['msg'] = 'Пациент не найден';
	} else
	{
		if ( count($Patients) == 1 )
		{
			$response['result'] = true;
			$response['error'] = false;
			$response['data']['personal'] = $Patients[0];
			$response['msg'] = 'Найден';
		} else
		{
			$response['result'] = true;
			$response['msg'] = 'Найдено несколько пациентов с таким ID';
			$response['data']['list'] = $Patients;
		}
	}

	return $response;
}

function getPageData($link)
{
    $response = array();
    $response['result'] = false;

	$Pages_array = getarr('caop_pages', "pages_link='{$link}'", "LIMIT 1");
	if ( count($Pages_array) == 1 )
	{
		$Page = $Pages_array[0];
		$response['result'] = true;
		$response['data'] = $Page;
	}


	return $response;
}

function createNewDayByUnix($unix, $doctor)
{
	$response = array();
	$response['result'] = false;
    if ( strlen($unix) > 0 )
    {
	    $datedmy = date("Y-m-d", $unix );
	    $timestamp = strtotime($datedmy);
	    $CheckDay = getarr('caop_days', "day_doctor='{$doctor}' AND day_unix='{$timestamp}'");
	    if (count($CheckDay) == 1)
	    {
	    	$CheckDay = $CheckDay[0];
	    	$response['result'] = true;
	    	$response['day_id'] = $CheckDay['day_id'];
	    } else
	    {
		    $MyNurseId = 0;
	    	$defaultNurse = doctor_param('get', $doctor, 'defaultNurse');
	    	if ($defaultNurse['stat'] == RES_SUCCESS)
	    		$MyNurseId = $defaultNurse['data']['settings_param_value'];
	    	
		    $newDay = array(
				'day_doctor'    =>  $doctor,
				'day_date'      =>  date("d.m.Y", $timestamp),
				'day_unix'      =>  $timestamp,
				'day_nurse'	    =>  $MyNurseId
		    );
		    $Append = appendData('caop_days', $newDay);
		    if ( $Append[ID] > 0 )
		    {
			    $response['result'] = true;
			    $response['day_id'] = $Append[ID];
		    } else
		    {
		    	$response['msg'] = $Append;
		    }
	    }

    } else
    {
    	$response['msg'] = 'Не указано время unix';
    }
    return $response;
}

function admin_param($method="get", $param_name='', $param_value='')
{
	$ret = array();
	$ret['notice'] = '';
	$ret['stat'] = false;
	$ret['msg'] = 'begin';
	$ret['debug'] = array();
	if ( strlen($param_name) > 0 )
	{
		if ( strlen($method) > 0 )
		{
			switch ($method)
			{
				case "get":
					
					$Admin = getarr(CAOP_PARAMS, "param_name LIKE '{$param_name}'", "ORDER BY param_id ASC");
					if ( count( $Admin ) > 0 )
					{
						$ret['stat'] = true;
						$ret['msg'] = RES_SUCCESS;
						if ( count($Admin) == 1 )
						{
							$ret['data'] = $Admin[0];
						} else
						{
							$ret['data'] = $Admin;
						}
					} else
					{
						$ret['msg'] = 'notfound';
					}
					
					break;
				case "set":
					
					$Admin = getarr(CAOP_PARAMS, "param_name LIKE '{$param_name}'", "ORDER BY param_id ASC");
					if ( count($Admin) > 0 )
					{
						$paramValues = array(
							'param_value' => $param_value
						);
						$ret['notice'] = 'firstUpdated';
						$paramData = $Admin[0];
						$UpdateData = updateData(CAOP_PARAMS, $paramValues, array(), "param_id='{$paramData['param_id']}'");
						$ret['stat'] = true;
						$ret['msg'] = RES_SUCCESS;
					} else
					{
						$paramValues = array(
							'param_name' => $param_name,
							'param_value' => $param_value
						);
						$Append = appendData(CAOP_PARAMS, $paramValues);
						if ( $Append[ID] > 0 )
						{
							$ret['stat'] = true;
							$ret['msg'] = RES_SUCCESS;
						}
					}
					
					break;
				default:
					$ret['msg'] = 'unknown';
					break;
			}
		} else
		{
			$ret['msg'] = 'nomethod';
		}
	} else
	{
		// получить все параметры
		$Admin = getarr(CAOP_PARAMS, 1, "ORDER BY param_id ASC");
		if ( count( $Admin ) > 0 )
		{
			$ret['stat'] = true;
			$ret['msg'] = RES_SUCCESS;
			$ret['data'] = getDoctorsById($Admin, 'param_name', '');
		} else
		{
			$ret['msg'] = 'notfound';
		}
	}
	
	
	return $ret;
}

/**
 *
 * Получение настроек врача
 *
 * @param string $method метод - get или set
 * @param integer $doctor_id id врача
 * @param string $param_name название параметра
 * @param string $param_value значение параметра
 * @return array
 */
function doctor_param($method="get", $doctor_id, $param_name, $param_value='')
{
	$ret = array();
	$ret['stat'] = false;
	$ret['msg'] = 'begin';
	$ret['debug'] = array();
	if ( strlen($doctor_id) > 0 )
	{
//		debug('strlen($doctor_id) > 0');
		$CheckDoctor = getarr(CAOP_DOCTOR, "doctor_id='{$doctor_id}'");
		if ( count($CheckDoctor) == 1 )
		{
//			debug('count($CheckDoctor) == 1');
			$CheckDoctor = $CheckDoctor[0];
			if ( strlen($method) > 0 )
			{
//				debug('strlen($method) > 0');
				switch ($method)
				{
					case "get":
//						debug('case "get"');
						$Params = getarr(CAOP_DOCTOR_SETTINGS, "settings_param_name LIKE '{$param_name}' AND settings_doctor_id='{$CheckDoctor['doctor_id']}'", "ORDER BY settings_id ASC");
//						debug($Params);
						if ( count( $Params ) > 0 )
						{
//							debug('count( $Params ) > 0');
							$ret['stat'] = true;
							$ret['msg'] = RES_SUCCESS;
							if ( count($Params) == 1 )
							{
								$ret['data'] = $Params[0];
							} else
							{
								$ret['data'] = $Params;
							}
						} else
						{
//							debug('count( $Params ) <= 0');
							if ( strlen($param_value) > 0 )
							{
								$param_add = array(
									'settings_param_name' => $param_name,
									'settings_param_value' => $param_value,
									'settings_doctor_id' => $CheckDoctor['doctor_id']
								);
								$NewParam = appendData(CAOP_DOCTOR_SETTINGS, $param_add);
								if ( $NewParam[ID] > 0 )
								{
									$ret['stat'] = true;
									$ret['msg'] = "ADD_NEW";
									$ret['data'] = $NewParam;
								}
							} else $ret['data'] = 'notfound';
						}
						
						break;
					case "set":
						
						$Params = getarr(CAOP_DOCTOR_SETTINGS, "settings_param_name LIKE '{$param_name}' AND settings_doctor_id='{$CheckDoctor['doctor_id']}'", "ORDER BY settings_id ASC");
						if ( count($Params) > 0 )
						{
							$paramValues = array(
								'settings_param_value' => $param_value
							);
							$ret['notice'] = 'firstUpdated';
							$paramData = $Params[0];
							$UpdateData = updateData(CAOP_DOCTOR_SETTINGS, $paramValues, array(), "settings_id='{$paramData['settings_id']}'");
							$ret['stat'] = true;
							$ret['msg'] = RES_SUCCESS;
						} else
						{
							$paramValues = array(
								'settings_param_name' => $param_name,
								'settings_param_value' => $param_value,
								'settings_doctor_id' => $CheckDoctor['doctor_id']
							);
							$Append = appendData(CAOP_DOCTOR_SETTINGS, $paramValues);
							if ( $Append[ID] > 0 )
							{
								$ret['stat'] = true;
								$ret['msg'] = RES_SUCCESS;
							}
						}
						
						break;
					default:
						$ret['msg'] = 'unknown';
						break;
				}
			} else
			{
				$ret['msg'] = 'nomethod';
			}
		} else
		{
			$ret['msg'] = 'notfound';
		}
	} else
	{
		$ret['msg'] = 'nodoctorid';
	}
	
	
	return $ret;
}

function vitrina($vitrina_title=null, $vitrina_param=null, $vitrina_value=null)
{
	global $USER_PROFILE;
	$response = [];
	$response['result'] = false;
	
	if ( !is_null($vitrina_value) )
	{
		// УСТАНОВИТЬ
		if ( !is_null($vitrina_title) && !is_null($vitrina_param) )
		{
			$VitrinaData = getarr(CAOP_DOCTOR_VITRINA, "vitrina_doctor_id='{$USER_PROFILE['doctor_id']}' AND vitrina_title='{$vitrina_title}' AND vitrina_param='{$vitrina_param}'");
			if ( count($VitrinaData) > 0 )
			{
				$VitrinaData = $VitrinaData[0];
				$paramValues_vitrina = array(
					'vitrina_doctor_id'     => $USER_PROFILE['doctor_id'],
					'vitrina_value'         => $vitrina_value
				);
				$UpdateVitrina = updateData(CAOP_DOCTOR_VITRINA, $paramValues_vitrina, $VitrinaData, "vitrina_id='{$VitrinaData['vitrina_id']}'");
				if ( $UpdateVitrina['stat'] == RES_SUCCESS )
				{
					$VitrinaData = $UpdateVitrina;
				}
			} else
			{
				$paramValues_vitrina = array(
					'vitrina_doctor_id'     => $USER_PROFILE['doctor_id'],
					'vitrina_title'         => $vitrina_title,
					'vitrina_param'         => $vitrina_param,
					'vitrina_value'         => $vitrina_value
				);
				$VitrinaInsert = appendData(CAOP_DOCTOR_VITRINA, $paramValues_vitrina);
				if ( $VitrinaInsert[ID] > 0 )
				{
					$VitrinaData = $VitrinaInsert;
				} else
				{
					$response['msg'] = 'Ошибка добавления витрины';
				}
			}
		} else
		{
			$response['msg'] = 'Для установки/обновления параметра витрины нужно указать ВСЕ данные';
		}
	} else
	{
		// ПОЛУЧИТЬ
		if ( !is_null($vitrina_title) )
		{
			if ( !is_null($vitrina_param) )
			{
				$VitrinaData = getarr(CAOP_DOCTOR_VITRINA, "vitrina_doctor_id='{$USER_PROFILE['doctor_id']}' AND vitrina_title='{$vitrina_title}' AND vitrina_param='{$vitrina_param}'");
			} else
			{
				$VitrinaData = getarr(CAOP_DOCTOR_VITRINA, "vitrina_doctor_id='{$USER_PROFILE['doctor_id']}' AND vitrina_title='{$vitrina_title}'");
			}
		} else
		{
			$VitrinaData = getarr(CAOP_DOCTOR_VITRINA, "vitrina_doctor_id='{$USER_PROFILE['doctor_id']}'");
		}
	}
	
	if ( count($VitrinaData) > 0 )
	{
		$response['result'] = true;
		
		$vitrina_object = array();
		foreach ($VitrinaData as $vitrinaDatum)
		{
			$vitrina_object[ $vitrinaDatum['vitrina_title'] ][ $vitrinaDatum['vitrina_param'] ] = $vitrinaDatum['vitrina_value'];
		}
		
		$response['data'] = $vitrina_object;
		
//		if ( count($VitrinaData) == 1 )
//			$response['data'] = $VitrinaData[0];
//		else
//			$response['data'] = $VitrinaData;
	} else
	{
		$response['msg'] = 'Значений не найдено';
	}
	
	return $response;
}