<?php
$response['stage'] = $action;
$response['msg'] = 'begin';

include("engine/php/processor/include/miasImportConfig.php");

$DispLPU = getarr(CAOP_DISP_LPU, 1, "ORDER BY lpu_order ASC");

$HTTP = $_POST;
extract($HTTP, EXTR_PREFIX_SAME, '_caop');

$miasTextMain = trim($miasTextMain);

$SOME_ONE_ADDED = 0;

// 1 - определяем ТИП импортируемых данных
$isDiary = false;
$isJournal = false;

// 2 - категоризируем тип добавляемых данных
if (ifound($miasTextMain, "записей пациентов"))
{
	$isJournal = true;
	$donePatientsLabel = "казана";
}

if (ifound($miasTextMain, "заболеваний и результаты"))
{
	$isDiary = true;
	$donePatientsLabel = "едактировать";
}

if (strlen($importDate) > 0)
{
	$journalUnix = strtotime($importDate);
	$journalDate = date("d.m.Y", $journalUnix);
}

$importSettings = ($importSettings == "raw" || $importSettings == "ignore" || $importSettings == "new") ? $importSettings : 'raw';


$miasTextMainData = explode("\r\n", $miasTextMain);

$IMPORT_PATIENT_LIST = [];

$VisitTypes = getarr(CAOP_JOURNAL_VISIT_TYPE);
//$VisitTypesId = getDoctorsById($VisitTypes, $PK[CAOP_JOURNAL_VISIT_TYPE]);

$go_next = false;

$enable_last_error = true;

if ($isDiary && $isJournal)
{
	$response['msg'] = "Передан неверный тип данных";
} else $go_next = true;
// Тут присваиваем к используемым массивам поиска - массивы из заданных ранее (для настройки полей) - по ним будут формироваться массивы для добавления в БД пациентов и приёмы
// В ОСНОВНОМ этот механизм нужен для корректного поиска по списку пациентов. Согласно импортируемым данным, будет формироваться массив для добавления в БД. [GO TO 1]

if ($go_next)
{
	$go_next = false;

//	$response['debug']['import_type']['$isDiary'] = $isDiary;
//	$response['debug']['import_type']['$isJournal'] = $isJournal;
	
	if ($isDiary)
	{
		// DEPRECATED
		$response['debug']['$METHOD'] = 'Метод ДНЕВНИК';
		include("engine/php/processor/include/miasImport_diary.php");
	} else
	{
		if ($isJournal)
		{
			$response['debug']['$METHOD'] = 'Метод ЖУРНАЛ';
			include("engine/php/processor/include/miasImport_journal.php");
		} else
		{
			$response['msg'] = "Проблема с распознаванием данных";
			$enable_last_error = false;
		}
	}
}

if ($go_next)
{
	$go_next = false;
	$response['debug']['$IMPORT_PATIENT_LIST'] = $IMPORT_PATIENT_LIST;
	
	if (count($IMPORT_PATIENT_LIST) > 0)
	{
		$go_next = false;
		// 1 - проверяем врача
		$DoctorData = $DoctorsListId['id' . $doctor_id];
		$go_next = true;
// 		if (notnull($DoctorData))
// 		{
// 			$DoctorData = $USER_PROFILE;
// 			$go_next = true;
// 		} else $response['msg'] = 'Не указан врач для импорта!';
	} else
	{
	    $response['msg'] = 'Не получилось получить импортированных пациентов';
	    $enable_last_error = false;
	}
}

if ($go_next)
{
	$go_next = false;
	$DAY_ID = 0;
	// 2 - проверяем в наличии указанный день приема
	$TodayData = getarr(CAOP_DAYS, "day_doctor='{$DoctorData['doctor_id']}' AND day_date='{$journalDate}'");
	if (count($TodayData) == 1)
	{
		$TodayData = $TodayData[0];
		$DAY_ID = $TodayData['day_id'];
		$go_next = true;
	} else
	{
		if (count($TodayData) > 1)
		{
			$response['msg'] = 'Слишком много дней приема на одну дату';
			$enable_last_error = false;
		} else
		{
			$ActualDay = createNewDayByUnix($journalUnix, $DoctorData['doctor_id']);
			if ($ActualDay['result'] === true)
			{
				$go_next = true;
				$DAY_ID = $ActualDay['day_id'];
			}
		}
	}
}

if ($go_next)
{
	$go_next = false;
	
	$LpuQueryList = [];
	
	foreach ($IMPORT_PATIENT_LIST as $PatientImportData)
	{
		$Patient_Main_Data = null;
		$need_add = false;
		
		// 1 - проверяем, есть ли поле import_patient_ident
		$card = $PatientImportData['import_patient_ident'];
		$journal_status = $PatientImportData['import_journal_status'];
		$journal_time = $PatientImportData['import_journal_time'];
		
		$record_lpu = $PatientImportData['import_journal_record_lpu'];
		$record_division = $PatientImportData['import_journal_record_division'];
		$record_worker = $PatientImportData['import_journal_record_worker'];
		$record_date = $PatientImportData['import_journal_record_date'];
		$record_time = $PatientImportData['import_journal_record_time'];
		$visit_type = $PatientImportData['import_journal_visit_type'];
		
		list($hours, $mins, $secs) = explode(':', $journal_time); //преобразовываем в секунды
		$_seconds = ($hours * 3600) + ($mins * 60) + $secs;
		
		$PatientImportName = name_for_query($PatientImportData['import_patient_name']);
		
		$FoundedPatientData = array();
		
		if (notnull($card))
		{
			// 1.1 - номер есть, проверяем в БД (ВНИМАНИЕ! НЕКОТОРЫЕ НОМЕРА ОТНОСЯТСЯ К АИС ПОЛИКЛИНИКЕ И НЕ ЯВЛЯЮТСЯ ЧАСТЬЮ МИАС-СИСТЕМЫ)
			$PatientByIdent = getarr(CAOP_PATIENTS, "patid_ident='{$card}'");
			if (count($PatientByIdent) == 1)
			{
				$PatientByIdent = $PatientByIdent[0];
				
				// 1.2 - номер есть, сверяем ФИО и Дату рождения
				
				$PatientByIdentByFIOBirth = getarr(CAOP_PATIENTS, "patid_id='{$PatientByIdent['patid_id']}' AND patid_birth='{$PatientImportData['import_patient_birth']}' AND patid_name LIKE '{$PatientImportName['querySearchPercent']}'", null, 1);
				$request_PatientByIdentByFIOBirth = $PatientByIdentByFIOBirth['request'];
				unset($PatientByIdentByFIOBirth['request']);
				if (count($PatientByIdentByFIOBirth) == 1)
				{
					$Patient_Main_Data = $PatientByIdentByFIOBirth[0];
					// 1.2.1 - пациент с таким номером карты, ФИО и ДР найден
				} else
				{
					// 1.2.2 - номер карты совпал, а ФИО нет?? странно, ошибка
					$response['msg'] = 'Номер карты совпал, а ФИО с ДР - нет: ' . $PatientImportData['import_patient_name'];
					$response['debug']['$PatientByIdentByFIOBirth'][] = $request_PatientByIdentByFIOBirth;
					$enable_last_error = false;
					break;
				}
				
			} else
			{
				if (count($PatientByIdent) > 1)
				{
					$response['msg'] = 'Слишком много пациентов на один номер карты';
					$response['debug']['$PatientByIdent'] = $PatientByIdent;
					$enable_last_error = false;
					break;
				} else
				{
					// 1.3 - по карте НЕТ, проверяем по ФИО
					$PatientByFIOBirth = getarr(CAOP_PATIENTS, "patid_birth='{$PatientImportData['import_patient_birth']}' AND patid_name LIKE '{$PatientImportName['querySearchPercent']}'");
					if (count($PatientByFIOBirth) == 1)
					{
						$Patient_Main_Data = $PatientByFIOBirth[0];
					} else
					{
						// 1.4 - пациента с таким ФИО и ДР нет
						$need_add = true;
					}
				}
			}
		} else
		{
			// 2 - номера карты нет, ищем по фио и
			$PatientByFIOBirth = getarr(CAOP_PATIENTS, "patid_birth='{$PatientImportData['import_patient_birth']}' AND patid_name LIKE '{$PatientImportName['querySearchPercent']}'");
			if (count($PatientByFIOBirth) == 1)
			{
				$Patient_Main_Data = $PatientByFIOBirth[0];
			} else
			{
				// 2.1 - пациента с таким ФИО и ДР нет
				$need_add = true;
			}
		}
		
		$paramValues = array();
		foreach ($MaximalFieldPatientsList as $fieldIndex => $addField)
		{
			$key_field = $addField['field'];
			if (notnull($key_field))
			{
				if (notnull($PatientImportData[$addField['title']]))
				{
					$paramValues[$key_field] = $PatientImportData[$addField['title']];
				}
				
			}
		}
		$paramValues['patid_birth_unix'] = birthToUnix($paramValues['patid_birth']);
		
		if ($need_add)
		{
			$Patient_Main_Data = null;
			$AppendPatid = appendData(CAOP_PATIENTS, $paramValues);
			if ($AppendPatid[ID] > 0)
			{
				
				LoggerGlobal(
					LOG_TYPE_CREATE_PATIENT,
					$_SERVER['REMOTE_ADDR'],
					$CAT_DATA['cat_id'],
					$USER_PROFILE['doctor_id'],
					'через импорт',
					$AppendPatid[ID]
				);
				
				$Patient_Main_Data = $AppendPatid;
				$Patient_Main_Data['patid_id'] = $Patient_Main_Data[ID];
			} else
			{
				$response['msg'] = 'Ошибка при добавлении нового пациента в БД';
				$response['debug']['$paramValues'] = $paramValues;
				$enable_last_error = false;
				break;
			}
			
		}
		
		if (notnull($Patient_Main_Data))
		{
			$response['debug']['PatientDatas'][] = $Patient_Main_Data;
			
			$addJournal = false;
			
			$needUpdatePersonalData = array();
			if (strlen($Patient_Main_Data['patid_phone']) < 3)
			{
				$needUpdatePersonalData['patid_phone'] = $paramValues['patid_phone'];
			}
			if (strlen($Patient_Main_Data['patid_address']) < 3)
			{
				$needUpdatePersonalData['patid_address'] = $paramValues['patid_address'];
			}
			if (strlen($Patient_Main_Data['patid_insurance_number']) < 3)
			{
				$needUpdatePersonalData['patid_insurance_number'] = $paramValues['patid_insurance_number'];
			}
			if (strlen($Patient_Main_Data['patid_passport_serialnumber']) < 3)
			{
				$needUpdatePersonalData['patid_passport_serialnumber'] = $paramValues['patid_passport_serialnumber'];
			}
			if (strlen($Patient_Main_Data['patid_snils']) < 3)
			{
				$needUpdatePersonalData['patid_snils'] = $paramValues['patid_snils'];
			}
			
			if ( strlen($paramValues['patid_pin_lpu_id']) > 0 )
			{
			    // есть ЛПУ
				$lpu_patient = searchArray($DispLPU, 'import_query', $paramValues['patid_pin_lpu_id']);
				if ($lpu_patient['status'] == RES_SUCCESS)
				{
					$paramValues['patid_pin_lpu_id'] = $lpu_patient['data']['lpu_id'];
				} else
				{
					$SOME_ONE_ADDED = 2;
					$response['msg'] = 'У пациента не опознано прикрепление!';
					$response['debug']['patient_non_lpu'] = $paramValues;
					$enable_last_error = false;
					break;
				}
			}
			
			if (strlen($Patient_Main_Data['patid_pin_lpu_id']) == 0 || $Patient_Main_Data['patid_pin_lpu_id'] != $paramValues['patid_pin_lpu_id'])
			{
				$needUpdatePersonalData['patid_pin_lpu_id'] = $paramValues['patid_pin_lpu_id'];
			}
			
			if (count($needUpdatePersonalData) > 0)
			{
				$Patient_Main_Data = updateData(CAOP_PATIENTS, $needUpdatePersonalData, $Patient_Main_Data, "patid_id='{$Patient_Main_Data['patid_id']}'");
			}
			
			switch ($importSettings)
			{
				case "raw":
					// Добавляем ВСЕХ
					$addJournal = true;
					break;
				case "new":
					// Добавляем только тех, кто отсутствует в списке
					$IsVisited = getarr(CAOP_JOURNAL, "journal_patid='{$Patient_Main_Data['patid_id']}' AND journal_day='{$DAY_ID}'");
					if (count($IsVisited) == 0)
					{
						$addJournal = true;
					}
					break;
				case "ignore":
					// Добавляем только тех, кто еще не принят
					if (ifound($journal_status, $donePatientsLabel))
					{
						// был
					} else
					{
						$addJournal = true;
					}
					break;
			}
			
			if ($addJournal)
			{
				$ds_mkb = '';
				$ds_text = '';
				
				$LastVisit = getarr(CAOP_JOURNAL, "journal_patid='{$Patient_Main_Data['patid_id']}'", "ORDER BY journal_id DESC LIMIT 1");
				if (count($LastVisit) == 1)
				{
					$LastVisit = $LastVisit[0];
					$ds_mkb = $LastVisit['journal_ds'];
					$ds_text = $LastVisit['journal_ds_text'];
				}
				
				// DEPRECATED
//				$JournalDataDisp = getarr(CAOP_JOURNAL, "journal_patid='{$Patient_Main_Data['patid_id']}' AND journal_disp_lpu!='0' AND journal_disp_mkb!=''", "ORDER BY journal_id DESC LIMIT 0,1");
//				if (count($JournalDataDisp) > 0)
//				{
//					$JournalDataDisp = $JournalDataDisp[0];
//				}
				
				$DayData = getarr(CAOP_DAYS, "day_id='{$DAY_ID}'");
				$DayData = $DayData[0];
				
				$visit_type_data = searchArray($VisitTypes, 'visit_title', $visit_type);
				if ($visit_type_data['status'] == RES_SUCCESS)
				{
					$go_next = true;
				} else
				{
					if ( strlen($visit_type) > 0 )
					{
						$new_visit_type = array(
							'visit_title'=>$visit_type
						);
						$AddVisitType = appendData(CAOP_JOURNAL_VISIT_TYPE, $new_visit_type);
						if ($AddVisitType[ID] > 0)
						{
							$VisitTypes = getarr(CAOP_JOURNAL_VISIT_TYPE);
							$visit_type_data = searchArray($VisitTypes, 'visit_title', $visit_type);
							if ($visit_type_data['status'] == RES_SUCCESS)
							{
								$go_next = true;
							} else
							{
								$response['msg'] = 'Проблема с добавлением нового типа посещения';
								$response['debug']['new_visit_type'] = $new_visit_type;
								$enable_last_error = false;
								break;
							}
						}
					} else
					{
						$response['msg'] = 'Тип визита ПУСТОЙ! Проверьте импортируемые данные';
						$response['debug']['$visit_type'] = $visit_type;
						$enable_last_error = false;
						break;
					}
					
				}
				
				if ( $go_next )
				{
					$visit_type = $visit_type_data['data'][$PK[CAOP_JOURNAL_VISIT_TYPE]];
					
					$newJournal = array(
						'journal_day' => $DAY_ID,
						'journal_doctor' => $DoctorData['doctor_id'],
						'journal_patid' => $Patient_Main_Data['patid_id'],
						'journal_time' => $journal_time,
						'journal_order' => $_seconds,
						'journal_ds' => $ds_mkb,
						'journal_ds_text' => $ds_text,
						'journal_unix' => $DayData['day_unix'],
						// DEPRECATED
//					'journal_disp_lpu' => $JournalDataDisp['journal_disp_lpu'],
//					'journal_disp_mkb' => $JournalDataDisp['journal_disp_mkb'],
						'journal_record_lpu' => $record_lpu,
						'journal_record_division' => $record_division,
						'journal_record_worker' => $record_worker,
						'journal_record_date' => $record_date,
						'journal_record_time' => $record_time,
						'journal_visit_type' => $visit_type,
					);
					
					$response['debug']['add_visits'][] = $newJournal;
					$Append2 = appendData(CAOP_JOURNAL, $newJournal);
					if ($Append2[ID] > 0)
					{
						$response['result'] = true;
						$SOME_ONE_ADDED++;
					} else
					{
						$response['msg'] = $Append2;
						$enable_last_error = false;
					}
					
					
					
// 					$faker = false;
// 					$FakeImport = admin_param('get', 'fake_import_emias');
// 					if ($FakeImport['stat'])
// 					{
// 						$FakeImport = $FakeImport['data']['param_value'];
// 						if ($FakeImport)
// 						{
// 							$FakeImportDoctor = admin_param('get', 'fake_import_emias_doctor_id');
// 							if ($FakeImportDoctor['stat'])
// 							{
// 								$FakeImportDoctor = $FakeImportDoctor['data']['param_value'];
// 								if ($FakeImportDoctor)
// 								{
// 									# всё указано, делаем фейк
// 									$SOME_ONE_ADDED = 1;
// //											$response['result'] = true;
// 									$faker = true;
// 								}
// 							}
// 						}
// 					}
				
				// 	$response['debug']['faker'] = $faker;
				
				// 	if ($faker)
				// 	{
				// 		$response['msg'] = 'Сработал фейковый импорт для doctor_id=' . $FakeImportDoctor;
				// 	} else
				// 	{
				
				// 	}
				} else
				{
					$response['msg'] = 'Произошла ошибка go_next';
					$enable_last_error = false;
					break;
				}
			}
		}
	}
	
	if ($SOME_ONE_ADDED == 0)
	{
	    if ($enable_last_error)
		    $response['msg'] = "Нет пациентов для добавления.\n\nПерсональная информация обновлена";
	}
}