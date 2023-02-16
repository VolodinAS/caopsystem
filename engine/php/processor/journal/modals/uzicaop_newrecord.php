<?php
$response['stage'] = $action;
$response['msg'] = 'begin';

$HTTP = $_POST;
extract($HTTP, EXTR_PREFIX_SAME, '_caop');

$CheckPatUzi = getarr(CAOP_SCHEDULE_UZI_PATIENTS, "patient_date_id='$date_id' AND patient_time_id='$time_id'");
// это новая запись. Проверяем, занята ли она?
if ( count($CheckPatUzi) > 0 )
{
	$response['msg'] = 'Данное время в расписании врача уже занято. Обновите текущую неделю!';
} else
{
	$CheckTime = getarr(CAOP_SCHEDULE_UZI_TIMES, "time_id='$time_id'");
	if ( count($CheckTime) == 1 )
	{
		$CheckTime = $CheckTime[0];
		$response['debug']['$CheckTime'] = $CheckTime;
		$DoctorUzi = getarr(CAOP_SCHEDULE_UZI_DOCTORS, "uzi_id='{$CheckTime['time_uzi_id']}'");
		if ( count($DoctorUzi) == 1 )
		{
			$DoctorUzi = $DoctorUzi[0];
			$response['debug']['$DoctorUzi'] = $DoctorUzi;
			$go_next = false;
			if ( (int)$journal_id === -1 )
			{
				$BY_DOCTOR_ID = -2;
				$PATIENT_ID = $patid_id;
				$go_next = true;
			} else
			{
				$CheckJournal = getarr(CAOP_JOURNAL, "journal_id='$journal_id'");
				if ( count($CheckJournal) == 1 )
				{
					$CheckJournal = $CheckJournal[0];
					
					$BY_DOCTOR_ID = $CheckJournal['journal_doctor'];
					$PATIENT_ID = $CheckJournal['journal_patid'];
					
					$go_next = true;
				} else
				{
					$response['msg'] = 'Такой записи в журнале приёма не существует';
				}
			}
			
			if ( $go_next )
			{
				$go_next = false;
				if ( $PATIENT_ID > 0 )
				{
					
					$CheckDate = getarr(CAOP_SCHEDULE_UZI_DATES, "dates_id='$date_id'");
					if ( count($CheckDate) == 1 )
					{
						$CheckDate = $CheckDate[0];
						
						$response['debug']['$CheckDate'] = $CheckDate;
						
						
						
						$response['debug']['$CheckJournal'] = $CheckJournal;
						
						$CheckArea = getarr(CAOP_SCHEDULE_UZI_RESEARCH_AREA, "area_id='$area_id'");
						if ( count($CheckArea) == 1 )
						{
							$CheckArea = $CheckArea[0];
							
							$response['debug']['$CheckArea'] = $CheckArea;
							$go_next = false;
							if ( strlen($CheckArea['area_descriptionRequire']) > 0 )
							{
								if ( strlen($area_description) > 0 )
								{
									$go_next = true;
								} else
								{
									$response['msg'] = 'Для данного вида УЗИ требуется указать поле "ПРИМЕЧАНИЕ"!';
								}
							} else
							{
								$go_next = true;
							}
							
							if ( $go_next )
							{
								$param_new_record = array(
									'patient_uzi_id' => $CheckTime['time_uzi_id'],
									'patient_doctor_id' => $DoctorUzi['uzi_doctor_id'],
									'patient_pat_id' => $PATIENT_ID,
									'patient_journal_id' => $journal_id,
									'patient_time_id' => $CheckTime['time_id'],
									'patient_date_id' => $CheckDate['dates_id'],
									'patient_prescription_doctor_id' => $BY_DOCTOR_ID,
									'patient_area_id' => $CheckArea['area_id'],
									'patient_area_description' => $area_description
								);
								
								$response['debug']['$param_new_record'] = $param_new_record;
								
								$AppendRecord = appendData(CAOP_SCHEDULE_UZI_PATIENTS, $param_new_record);
								if ( $AppendRecord[ID] > 0 )
								{
									$response['result'] = true;
									$response['msg'] = 'Пациент успешно записан!';
								} else
								{
									$response['msg'] = 'Проблема добавления пациента на УЗИ';
									$response['debug']['$AppendRecord'] = $AppendRecord;
								}
							}
							
						} else
						{
							$response['msg'] = 'Такого обследования нет или Вы его не выбрали';
						}
						
					} else
					{
						$response['msg'] = 'Такой даты в графике врача нет';
					}
					
				} else
				{
					$response['msg'] = 'Не выбран пациент для назначения УЗИ';
				}
			}
		
			
		} else
		{
			$response['msg'] = 'Такого врача УЗИ нет';
		}
	} else
	{
		$response['msg'] = 'Такого времени в расписании врача нет!';
	}
}