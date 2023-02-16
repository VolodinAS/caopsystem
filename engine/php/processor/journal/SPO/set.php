<?php
$response['stage'] = $action;
$response['msg'] = 'begin';

$HTTP = $_POST;
extract($HTTP, EXTR_PREFIX_SAME, '_caop');

// LEFT JOIN ".CAOP_PATIENTS." patients ON patients.".$PK[CAOP_PATIENTS]."=journal.journal_patid
$JournalSPO_query = "
						SELECT * FROM ".CAOP_JOURNAL." journal
						LEFT JOIN ".CAOP_DAYS." days ON days.".$PK[CAOP_DAYS]."=journal.journal_day
						LEFT JOIN ".CAOP_SPO." spo ON spo.".$PK[CAOP_SPO]."=journal.journal_spo_id
						WHERE journal_id='{$journal_id}'
					";
$JournalSPO_result = mqc($JournalSPO_query);
$JournalSPO = mr2a($JournalSPO_result);

if ( count($JournalSPO) == 1 )
{
	$JournalSPO = $JournalSPO[0];
	$DayData = extractValueByKey($JournalSPO, 'day_');
	$JournalData = extractValueByKey($JournalSPO, 'journal_');
	$SPOData = extractValueByKey($JournalSPO, 'spo_');
	unset($JournalSPO);
	
	if ( $DayData[$PK[CAOP_DAYS]] > 0 )
	{
		if ( $SPOData[$PK[CAOP_SPO]] > 0 )
		{
			
			if ( $SPOData['spo_unix_accounting_set'] == 0 )
			{
				$response['debug']['$DayData'] = $DayData;
				$response['debug']['$SPOData'] = $SPOData;
				
				$is_cancer = DiagnosisCancer($JournalData['journal_ds']);
				$is_dispancer = CheckMKBDispancer($JournalData['journal_ds'], $MKBDispLinear);
				
				if ( $is_cancer || $is_dispancer )
				{
					
					$param_spo_update = array(
						'spo_unix_accounting_set' => $DayData['day_unix'],
						'spo_mkb_finished' => $JournalData['journal_ds'],
						'spo_is_dispancer' => 1
					);
					
					$UpdateSPO = updateData(CAOP_SPO, $param_spo_update, [], $PK[CAOP_SPO] . "='{$SPOData[$PK[CAOP_SPO]]}'");
					if ( $UpdateSPO['stat'] == RES_SUCCESS )
					{
						$response['msg'] = 'Пациент успешно поставлен на Д-учет!';
						$response['result'] = true;
					} else
					{
						$response['msg'] = 'Проблема в обновлении диагноза!';
						$response['focus'] = 'journal_ds';
						$response['tab'] = 'daily';
					}
				} else
				{
					$response['msg'] = 'Вы не можете поставить на Д-учет диагноз, который не относится к диспансерным или злокачественным!';
					$response['focus'] = 'journal_ds';
					$response['tab'] = 'daily';
				}
			} else
			{
				$response['msg'] = 'Пациент уже состоит на Д-учете! Сначала сотрите предыдущие данные!';
				$response['focus'] = 'spo_unix_accounting_set';
				$response['tab'] = 'SPO';
			}
			
			
			
		} else
		{
			$response['msg'] = 'Вы не можете поставить на Д-учет, не выбрав СПО!';
			$response['focus'] = 'addNewSPO';
			$response['tab'] = 'SPO';
		}
	} else $response['msg'] = 'Такого дня не существует';
} else $response['msg'] = 'Такого визита нет';

