<?php
//	            $DispancerData = getarr(CAOP_DISP_PATIENTS, "1", "ORDER BY {$PK[CAOP_DISP_PATIENTS]} ASC");
$DispancerData_query = "
                    SELECT * FROM ".CAOP_DISP_PATIENTS." disp
                    LEFT JOIN ".CAOP_JOURNAL." journal ON journal.".$PK[CAOP_JOURNAL]."=disp.dispancer_journal_id
                    LEFT JOIN ".$CAOP_PATIENTS." patients ON patients.patid_id=journal.journal_patid
                    LEFT JOIN ".CAOP_DAYS." days ON days.day_unix=disp.dispancer_accounting_begin_unix AND days.day_doctor=disp.dispancer_doctor_id
                ";

//                $DispancerData_query .= " LIMIT 0, 1";

//                debug($DispancerData_query);
$DispancerData_result = mqc($DispancerData_query);
$DispancerData = mr2a($DispancerData_result);

//                var_dump( str_starts_with('dispancer_journal_id', 'journal_') );
//                var_dump( str_starts_with('dispancer_journal_id', 'dispancer_') );
foreach ($DispancerData as $dispancer)
{
	$DispData = extractValueByKey($dispancer, 'dispancer_');
	$PatientData = extractValueByKey($dispancer, 'patid_');
	$DayData = extractValueByKey($dispancer, 'day_');
	$JournalData = extractValueByKey($dispancer, 'journal_');
	unset($dispancer);
	spoiler_begin('DISPANCER#'.$DispData['dispancer_id'].'', 'disp'. $DispData['dispancer_id'], '');
	{
		spoiler_begin('Debugger data', 'debug'. $DispData['dispancer_id']);
		{
			debug($DispData);
			debug($PatientData);
			debug($DayData);
			debug($JournalData);
		}
		spoiler_end();
		
		
		$CheckSPO = getarr(CAOP_SPO, "spo_patient_id='{$PatientData['patid_id']}' AND spo_mkb_finished='{$JournalData['journal_ds']}'", "ORDER BY spo_start_date_unix DESC");
		$is_create_spo = false;
		$is_update_journal = false;
		if ( count($CheckSPO) > 0 )
		{
			bt_notice('СПО УЖЕ СУЩЕСТВУЕТ', BT_THEME_SUCCESS);
			$CheckSPO = $CheckSPO[0];
			$SPO_ID = $CheckSPO['spo_id'];
			$is_update_journal = true;
		} else
		{
			bt_notice('СПО НЕ СУЩЕСТВУЕТ', BT_THEME_INFO);
			$is_create_spo = true;
		}
		
		if ( $is_create_spo )
		{
			// Создаем СПО
			
			$is_dispancer = ( CheckMKBDispancer($JournalData['journal_ds'], $MKBDispLinear)['result'] ) ? '1' : '0';
			
			$param_spo = array(
				'spo_patient_id' => $JournalData['journal_patid'],
				'spo_start_doctor_id' => $JournalData['journal_doctor'],
				'spo_start_date_unix' => $DayData['day_unix'],
				'spo_start_day_id' => $DayData['day_id'],
				'spo_mkb_finished' => $JournalData['journal_ds'],
				'spo_is_dispancer' => $is_dispancer,
				'spo_dir_lpu_doctor_fio' => $JournalData['journal_record_worker'],
				'spo_dir_lpu_name' => $JournalData['journal_record_division'],
				'spo_dir_lpu_date_date' => $JournalData['journal_record_date'],
				'spo_dir_lpu_date_time' => $JournalData['journal_record_time'],
				'spo_lpu_id' => $JournalData['journal_disp_lpu'],
			);
			
			debug($param_spo);
			
			$NewSPO = appendData(CAOP_SPO, $param_spo);
			if ( $NewSPO[ID] > 0 )
			{
				bt_notice('СПО СОЗДАНО', BT_THEME_SUCCESS);
				$SPO_ID = $NewSPO[ID];
				$is_update_journal = true;
			} else
			{
				bt_notice('НЕ УДАЛОСЬ СОЗДАТЬ СПО', BT_THEME_WARNING);
			}
			
			
		}
		
		$go_next = false;
		if ( $is_update_journal )
		{
			// обновляем журнал, удаляем dispancer
			if ( $JournalData['journal_spo_id'] == 0 )
			{
				$param_update_journal = array(
					'journal_spo_id' => $SPO_ID
				);
				$UpdateJournal = updateData(CAOP_JOURNAL, $param_update_journal, $JournalData, $PK[CAOP_JOURNAL] . "='{$JournalData[$PK[CAOP_JOURNAL]]}'");
				if ( $UpdateJournal['stat'] == RES_SUCCESS )
				{
					$go_next = true;
					bt_notice('ЖУРНАЛ ОБНОВЛЁН', BT_THEME_SUCCESS);
				} else
				{
					bt_notice('НЕ УДАЛОСЬ ОБНОВИТЬ ЖУРНАЛ', BT_THEME_DANGER);
				}
			} else $go_next = true;
			
			if ( $go_next )
			{
				$go_next = false;
				
				if ( $PatientData['patid_pin_lpu_id'] == 0 )
				{
					$param_update_patient = array(
						'patid_pin_lpu_id' => $DispData['dispancer_lpu_id']
					);
					$PatientUpdate = updateData(CAOP_PATIENTS, $param_update_patient, $PatientData, $PK[CAOP_PATIENTS] . "='{$PatientData[$PK[CAOP_PATIENTS]]}'");
					if ( $PatientUpdate['stat'] == RES_SUCCESS )
					{
						$go_next = true;
						bt_notice('ПАЦИЕНТ ОБНОВЛЁН', BT_THEME_SUCCESS);
					} else
					{
						bt_notice('НЕ УДАЛОСЬ ОБНОВИТЬ ПАЦИЕНТА', BT_THEME_DANGER);
					}
				} else $go_next = true;
			}
			
			if ( $go_next )
			{
				$DeleteDisp = deleteitem(CAOP_DISP_PATIENTS, $PK[CAOP_DISP_PATIENTS] . "='{$DispData[$PK[CAOP_DISP_PATIENTS]]}'");
				if ( $DeleteDisp ['result'] === true )
				{
					bt_notice('ДИСПАНСЕР #'.$DispData[$PK[CAOP_DISP_PATIENTS]].' УСПЕШНО ПЕРЕВЕДЁН В СПО!', BT_THEME_SUCCESS);
				} else
				{
					bt_notice('ОШИБКА ПРИ УДАЛЕНИИ ДИСПАНСЕРА', BT_THEME_DANGER);
				}
			}
			
			
		}
		
	}
	spoiler_end();

//	                break;
}
unset($DispancerData);
//	            debug($DispancerData);