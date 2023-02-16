<?php
$response['stage'] = $action;
$response['msg'] = 'begin';

$HTTP = $_POST;
extract($HTTP, EXTR_PREFIX_SAME, '_caop');

$CheckJournalSPO = getarr(CAOP_JOURNAL, "journal_id='{$spo_journal_id}'");
$go_next = false;
if (count($CheckJournalSPO) > 0)
{
	$go_next = true;
	$JournalData = $CheckJournalSPO[0];
	$response['debug']['$JournalData'] = $JournalData;
} else
{
	$response['msg'] = 'НЕТ ID ЗАПИСИ В ЖУРНАЛЕ ПРИЁМА';
	$response['debug']['$CheckJournalSPO'] = $CheckJournalSPO;
}

if ($go_next)
{
	if (strlen($spo_start_date_unix) > 0)
	{
		$spo_start_date_unix = strtotime($spo_start_date_unix);
		
		if ($spo_start_doctor_id > 0)
		{
			
			$MKB_DATA = CheckMKBCode($spo_mkb_directed);
			$spo_mkb_directed = $MKB_DATA['value'];
			if ($spo_mkb_directed === false)
			{
				$response['msg'] = 'НЕВЕРНО УКАЗАН КОД НАПРАВИТЕЛЬНОГО ДИАГНОЗА ПО МКБ-10';
			} else
			{
				
				$spo_values = array(
					'spo_patient_id' => $spo_patient_id,
					'spo_start_doctor_id' => $spo_start_doctor_id,
					'spo_start_date_unix' => $spo_start_date_unix,
					'spo_mkb_directed' => $spo_mkb_directed,
					'spo_start_day_id' => $JournalData['journal_day']
				);
				
				$go_next = true;
				
				if (strlen($spo_mkb_finished) > 0)
				{
					$MKB_DATA = CheckMKBCode($spo_mkb_finished);
					$spo_mkb_finished = $MKB_DATA['value'];
					if ($spo_mkb_finished === false)
					{
						$go_next = false;
						$response['msg'] = 'НЕВЕРНО УКАЗАН КОД ЗАКЛЮЧИТЕЛЬНОГО ДИАГНОЗА ПО МКБ-10';
					} else
					{
						$spo_values['spo_mkb_finished'] = $spo_mkb_finished;
					}
				}
				if (count($spo_end_date_unix) > 0)
				{
					$spo_end_date_unix = strtotime($spo_end_date_unix);
					$spo_values['spo_end_date_unix'] = $spo_end_date_unix;
				}
				if ($spo_end_doctor_id > 0)
				{
					$spo_values['spo_end_doctor_id'] = $spo_end_doctor_id;
				}
				if ($spo_end_reason_type > 0)
				{
					$spo_values['spo_end_reason_type'] = $spo_end_reason_type;
				}
				
				if ($go_next)
				{
					$CreateSPO = appendData(CAOP_SPO, $spo_values);
					if ($CreateSPO[ID] > 0)
					{
						// TODO УДАЛИТЬ ЭТУ СТРОКУ
//						$TRUNCATE = mqc("TRUNCATE caop_spo");
						// TODO УДАЛИТЬ ЭТУ СТРОКУ
						
						if ($JournalData['journal_spo_id'] == 0)
						{
							$journal_update = array(
								'journal_spo_id' => $CreateSPO[ID]
							);
							$UpdateJournalSPO = updateData(CAOP_JOURNAL, $journal_update, [], "journal_id='{$spo_journal_id}'");
							if ($UpdateJournalSPO['stat'] == RES_SUCCESS)
							{
								$response['result'] = true;
								$response['journal_id'] = $spo_journal_id;
							} else
							{
								$response['msg'] = 'ПРОБЛЕМА С ОБНОВЛЕНИЕМ ЖУРНАЛА';
								$response['debug']['$UpdateJournalSPO'] = $UpdateJournalSPO;
							}
						} else
						{
							$response['result'] = true;
							$response['journal_id'] = $spo_journal_id;
						}
						
					} else
					{
						$response['msg'] = 'ПРОБЛЕМА С ДОБАВЛЕНИЕМ СПО';
						$response['debug']['$CreateSPO'] = $CreateSPO;
					}
				}
			}
		} else
		{
			$response['msg'] = 'НЕ ВЫБРАН ВРАЧ, ОТКРЫВШИЙ СПО';
		}
	} else
	{
		$response['msg'] = 'НЕВЕРНО УКАЗАНА ДАТА ОТКРЫТИЯ СПО';
	}
}

