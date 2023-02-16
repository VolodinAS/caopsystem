<?php
$response['stage'] = $action;
//$response['debug']['$_POST'] = $_POST;
$response['msg'] = 'begin';

include ( "engine/php/processor/include/miasImportConfig.php" );

$HTTP = $_POST;
extract($HTTP, EXTR_PREFIX_SAME, '_caop');

$MIAS_STRING = trim($miasText2);

$MIAS_DATA = explode("\r\n", $MIAS_STRING);

//$response['debug']['$MIAS_DATA'] = $MIAS_DATA;



if ( $editIgnore == "1" )
{
	$UsefulFields[] = 'Статус';
}



// 1 - парсим поля
$GotFields = array();
$isFields = false;
$lastFieldIndex = -1;
foreach ($MIAS_DATA as $miasString)
{
	if ( $isFields )
	{
		if ( ifound($miasString, $IsListText) )
		{
		
		} else
		{
			if ( ifound($miasString, $EndListText) )
			{
				break;
			} else $GotFields[] = $miasString;
		}
		
	} else
	{
		if ( ifound($miasString, $StartFieldText) )
		{
			$isFields = true;
		}
	}
}
//$response['debug']['$GotFields'] = $GotFields;

// 2 - парсим список пациентов
$PatientsListText = array();
foreach ($MIAS_DATA as $miasString)
{
	$miasPatientData = explode($IsListText, $miasString);
	if ( count($miasPatientData) == count($GotFields) )
	{
		$PatientsListText[] = $miasPatientData;
	}
}
//$response['debug']['$PatientsListText'] = $PatientsListText;

// 3 - сверяем переданные поля с необходимыми
$isCorrect = true;
$IndexedFields = array();
foreach ($UsefulFields as $usefulField)
{
	if ( !in_array($usefulField, $GotFields) )
	{
		$isCorrect = false;
	} else
	{
		$index = array_search($usefulField, $GotFields);
		$IndexedFields[$usefulField] = $index;
	}
//	$response['debug']['$FieldChecker'][$usefulField] = $isCorrect;
}

// 4 - Если все поля сформированы верно
$StopIt = false;
if ( $isCorrect )
{
//	$response['debug']['$IndexedFields'] = $IndexedFields;
	
	$Today_Array = getarr('caop_days', "day_unix='{$CURRENT_DAY['unix']}' AND day_doctor='{$USER_PROFILE['doctor_id']}'");
	if ( count($Today_Array) == 1 )
	{
		$Today_Array = $Today_Array[0];
		
		$PATIENT_LIST = array();
		
		foreach ($PatientsListText as $patientData)
		{
			$PatientData = array();
			
			foreach ($IndexedFields as $indexedField=>$index)
			{
				if ( ifound($indexedField, 'ический адрес пациента') )
				{
					if ( strlen($patientData[$index]) < 5 )
					{
//						$response['debug'][] = array($patientData, $index, $indexedField, $IndexedFields['Адрес регистрации пациента']);
						$patientData[$index] = $patientData[ $IndexedFields['Адрес регистрации пациента'] ];
					}
				}
				$PatientData[$indexedField] = $patientData[$index];
			}
			
			
			if ( $editIgnore == "1" )
			{
				if ( isset($PatientData['Статус']) )
				{
					if ( ifound($PatientData['Статус'], "казана") )
					{
					
					} else $PATIENT_LIST[] = $PatientData;
				} else
				{
					$StopIt = true;
					$response['msg'] = 'Отсутствует поле СТАТУС при указании фильтрации';
					break;
				}
			} else $PATIENT_LIST[] = $PatientData;
			
		}
		
		if ( !$StopIt )
		{
			$response['debug']['MAIN_PATIENT_LIST'] = $PATIENT_LIST;
			$go_next = false;
			for ($index=0; $index<count($PATIENT_LIST); $index++)
			{
				$PatientName = name_for_query($PATIENT_LIST[$index]['Пациент']);
//				$response['debug']['$PatientName'][$index] = $PatientName;
				
				$birth = $PATIENT_LIST[$index]['Дата рождения'];
				$patid_ident = $PATIENT_LIST[$index]['Номер карты'];
				$patid_insurance_number = $PATIENT_LIST[$index]['Полис ОМС'];
				$patid_passport_serialnumber = $PATIENT_LIST[$index]['Перс. документ'];
				$patid_address = $PATIENT_LIST[$index]['Фактический адрес пациента'];
				$patid_phone = $PATIENT_LIST[$index]['Контакты'];

				$time = $PATIENT_LIST[$index]['Время'];
				list($hours, $mins, $secs) = explode(':', $time); //преобразовываем в секунды
				$_seconds = ($hours * 3600 ) + ($mins * 60 ) + $secs;
				
				
				$querySearch = "SELECT * FROM {$CAOP_PATIENTS} WHERE patid_birth='{$birth}' AND patid_name LIKE '{$PatientName['querySearchPercent']}'";
//				$response['debug']['$querySearch'][$index] = $querySearch;
				$resultSearch = mqc($querySearch);
				$amountSearch = mnr($resultSearch);
				
//				$response['debug']['$amountSearch'][$index] = $amountSearch;
				
				$ds_mkb = '';
				$ds_text = '';
				
				if ( $amountSearch == 1 )
				{
					$PatidData = mfa($resultSearch);
					$NEW_ID = $PatidData['patid_id'];
					$go_next = true;
					
					$LastVisit = getarr(CAOP_JOURNAL, "journal_patid='{$NEW_ID}'", "ORDER BY journal_id DESC LIMIT 1");
					if ( count($LastVisit) == 1 )
					{
						$LastVisit = $LastVisit[0];
						$ds_mkb = $LastVisit['journal_ds'];
						$ds_text = $LastVisit['journal_ds_text'];
					}
					
				} else
				{
					if ( $amountSearch == 0 )
					{
						
						
						$NewPatid = array(
							'patid_name'    =>  NAME_MORMALIZER($PatientName['nameStrToLower']),
							'patid_birth'   =>  $birth,
							'patid_birth_unix'   =>  birthToUnix($birth),
							'patid_ident'   =>  $patid_ident,
							'patid_insurance_number'    =>  $patid_insurance_number,
							'patid_passport_serialnumber' => $patid_passport_serialnumber,
							'patid_address' => $patid_address,
							'patid_phone' => $patid_phone,
						);
						
//						$response['debug']['$NewPatid'][$index] = $NewPatid;
						
						$AppendPatid = appendData(CAOP_PATIENTS, $NewPatid);
						if ( $AppendPatid[ID] > 0 )
						{
							$NEW_ID = $AppendPatid[ID];
							$go_next = true;
						} else
						{
							$response['msg'] = 'Ошибка при добавлении нового пациента в БД';
						}
						
					} else
					{
						$response['msg'] = 'Слишком много пациентов на одну карту';
						$response['debug']['querySearch'] = $querySearch;
						$response['debug']['amountSearch'] = $amountSearch;
						break;
					}
				}
				
				if ( $go_next )
				{
					$DayData = getarr(CAOP_DAYS, "day_id='{$Today_Array['day_id']}'");
					$DayData = $DayData[0];
					
					$newJournal = array(
						'journal_day'   =>  $Today_Array['day_id'],
						'journal_doctor'    =>  $USER_PROFILE['doctor_id'],
						'journal_patid'    =>  $NEW_ID,
						'journal_time'    =>  $time,
						'journal_order'    =>  $_seconds,
						'journal_ds'    =>  $ds_mkb,
						'journal_ds_text'   =>  $ds_text,
						'journal_unix'  =>  $DayData['day_unix']
					);
					
//					$response['debug']['$newJournal'][$index] = $newJournal;
					
					$Append2 = appendData(CAOP_JOURNAL, $newJournal);
					if ( $Append2[ID] > 0 )
					{
						$response['DEBUG'] = 'HERE1';
						$response['result'] = true;
					} else
					{
						$response['msg'] = $Append2;
					}
				}
				
			}
		}
		
		
	}
} else
{
	$response['msg'] = 'Переданы некорректные типы столбцов!';
}