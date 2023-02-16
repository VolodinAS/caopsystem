<?php
$response['stage'] = $action;
$response['msg'] = 'begin';

$HTTP = $_POST;
extract($HTTP, EXTR_PREFIX_SAME, '_caop');

$JournalRM = RecordManipulation($journal_id, CAOP_JOURNAL, 'journal_id');
if ($JournalRM['result'])
{
	$JournalData = $JournalRM['data'];
	
	$response['debug']['$JournalData'] = $JournalData;
	
	if (strlen($JournalData['journal_ds']) > 0)
	{
		
		if (strlen($JournalData['journal_ds_text']) > 0)
		{
			
			$CheckDispancer = CheckMKBDispancer($JournalData['journal_ds'], $MKBDispLinear);
			
			if (($CheckDispancer ['result'] === true) || (ifound($JournalData['journal_ds'], "C")))
			{
				
				$PatientData = getPatientDataById($JournalData['journal_patid']);
				if ($PatientData['result'] && !$PatientData['error'])
				{
					$PatientData = $PatientData['data']['personal'];
					$response['debug']['$PatientData'] = $PatientData;
					
					$PATIENT_LPU = 0;
					if ( $PatientData['patid_pin_lpu_id'] > 0 )
					{
						$PATIENT_LPU = $PatientData['patid_pin_lpu_id'];
					} else
					{
						if ($JournalData['journal_disp_lpu'] > 0)
						{
							$PATIENT_LPU = $JournalData['journal_disp_lpu'];
						}
					}
					
					if ( $PATIENT_LPU > 0 )
					{
						
						$DayData = getarr(CAOP_DAYS, $PK[CAOP_DAYS] . "='{$JournalData['journal_day']}'");
						if (count($DayData) > 0)
						{
							$DayData = $DayData[0];
							$response['debug']['$DayData'] = $DayData;
							
							$DispancerData = getarr(CAOP_DISP_PATIENTS, "dispancer_patid='{$PatientData[$PK[CAOP_PATIENTS]]}' AND dispancer_ds_mkb='{$JournalData['journal_ds']}'");
							if ( count($DispancerData) == 0 )
							{
								
								$param_add_dispancer = array(
									'dispancer_patid' => $PatientData[$PK[CAOP_PATIENTS]],
									'dispancer_journal_id' => $JournalData[$PK[CAOP_JOURNAL]],
									'dispancer_ds_mkb' => $JournalData['journal_ds'],
									'dispancer_ds_text' => $JournalData['journal_ds_text'],
									'dispancer_lpu_id' => $JournalData['journal_disp_lpu'],
									'dispancer_accounting_begin_unix' => $DayData['day_unix'],
									'dispancer_doctor_id' => $JournalData['journal_doctor']
								);
								
								$response['debug']['$param_add_dispancer'] = $param_add_dispancer;
								
								$AddDispancer = appendData(CAOP_DISP_PATIENTS, $param_add_dispancer);
								if ( $AddDispancer[ID] > 0 )
								{
									$param_journal_update = array(
										'journal_disp_self' => $AddDispancer[ID]
									);
									$UpdateJournal = updateData(CAOP_JOURNAL, $param_journal_update, $JournalData, $PK[CAOP_JOURNAL] . "='{$JournalData[$PK[CAOP_JOURNAL]]}'");
									if ( $UpdateJournal['stat'] == RES_SUCCESS )
									{
										
										$response['result'] = true;
										
										if ( $PatientData['patid_pin_lpu_id'] == 0 )
										{
											$param_patient_lpu_update = array(
												'patid_pin_lpu_id' => $JournalData['journal_disp_lpu']
											);
											$UpdatePatient = updateData(CAOP_PATIENTS, $param_patient_lpu_update, $PatientData, $PK[CAOP_PATIENTS] . "='{$PatientData[$PK[CAOP_PATIENTS]]}'");
										}
									} else $response['msg'] = 'Невозможно добавить диспансерный учет:' . n(2) . 'Проблема обновления журнала приёма!';
									
								} else $response['msg'] = 'Невозможно добавить диспансерный учет:' . n(2) . 'Проблема с добавлением в диспансер!';
								
							} else $response['msg'] = 'Невозможно добавить диспансерный учет:' . n(2) . 'Пациент с таким диспансерным диагнозом уже состоит на учете!';
							
						} else $response['msg'] = 'Невозможно добавить диспансерный учет:' . n(2) . 'Такого дня приёма не существует!';
						
					} else $response['msg'] = 'Невозможно добавить диспансерный учет:' . n(2) . 'Выберите ЛПУ прикрепления пациента!';
					
				} else $response['msg'] = 'Невозможно добавить диспансерный учет: Пациента не существует!';
				
			} else $response['msg'] = 'Невозможно добавить диспансерный учет:' . n(2) . 'Диагноз не относится к диспансерному!';
			
		} else $response['msg'] = 'Невозможно добавить диспансерный учет:' . n(2) . 'Укажите текст диагноза!';
		
	} else $response['msg'] = 'Невозможно добавить диспансерный учет:' . n(2) . 'Укажите код диагноза по МКБ!';
	
} else $response['msg'] = $JournalRM['msg'];