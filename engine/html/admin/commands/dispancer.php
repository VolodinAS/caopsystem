<?php
$data = array(
	'tables' => array(
		array(
			'table_name' => CAOP_JOURNAL,
			'table_field_id' => 'journal_day'
		),
		array(
			'table_name' => CAOP_DAYS,
			'table_join' => 'LEFT JOIN',
			'table_main_field' => 'journal_day',
			'table_field_id' => $PK[CAOP_DAYS]
		),
		array(
			'table_name' => CAOP_PATIENTS,
			'table_join' => 'LEFT JOIN',
			'table_main_field' => 'journal_patid',
			'table_field_id' => $PK[CAOP_PATIENTS]
		),
		array(
			'table_name' => CAOP_DISP_PATIENTS,
			'table_join' => 'LEFT JOIN',
			'table_main_field' => 'journal_disp_self',
			'table_field_id' => $PK[CAOP_DISP_PATIENTS]
		)
	),
	'fields' => '*',
	'where' => CAOP_JOURNAL . ".journal_disp_isDisp=2 AND ".CAOP_JOURNAL.".journal_disp_self=0",
	'addon' => 'ORDER BY '.CAOP_DAYS.'.day_unix ASC'
);
$Dispancer_query = table_joiner($data);
debug($Dispancer_query);
$Dispancer_result = mqc($Dispancer_query);
$Dispancer = mr2a($Dispancer_result);
if ( count($Dispancer) > 0 )
{
	$JournalVisits = table_record_divider($Dispancer, 'journal_patid');
//	                debug($JournalVisits);
	
	foreach ($JournalVisits as $patient_id=>$visits)
	{
		$Patient = extractValueByKey($visits[0], 'patid_');

//			            $DispancerVisits = table_record_divider($visits, 'dispancer_id');
		
		spoiler_begin('Пациент ' . mb_ucwords($Patient['patid_name']) . ', ' . $Patient['patid_birth'], 'patient_' . $patient_id, '');
		{
			echo editPersonalDataLink('Просмотреть информацию', $Patient['patid_id']);
			
			foreach ($visits as $visit)
			{
				$Journal = extractValueByKey($visit, 'journal_');
				$Day = extractValueByKey($visit, 'day_');
				unset($visit);
				spoiler_begin('Посещение от ' . $Day['day_date'], 'visit' . $Journal['journal_id'], '');
				{
					
					bt_notice(wrapper('Диспансерный диагноз (МКБ):') . ' ' . $Journal['journal_disp_mkb']);
					bt_notice(wrapper('Диагноз визита (МКБ):') . ' ' . $Journal['journal_ds']);
					bt_notice(wrapper('Диагноз визита (Текст):') . ' ' . $Journal['journal_ds_text']);
					
					$CheckDispancer = getarr(CAOP_DISP_PATIENTS, "dispancer_patid='{$Patient['patid_id']}' AND dispancer_ds_mkb='{$Journal['journal_ds']}'");
					$CheckDispancer_count = count($CheckDispancer);
					if ( $CheckDispancer_count > 0 )
					{
						if ( $CheckDispancer_count == 1 )
						{
							$CheckDispancer = $CheckDispancer[0];
							
							if ( $Journal['journal_disp_self'] > 0 )
							{
								bt_notice('Обновление Диспансерного диагноза не требуется', BT_THEME_WARNING);
							} else
							{
								$param_disp_self = array(
									'journal_disp_self' => $CheckDispancer[$PK[CAOP_DISP_PATIENTS]]
								);
								
								$UpdateJournal = updateData(CAOP_JOURNAL, $param_disp_self, $Journal, $PK[CAOP_JOURNAL] . "='{$Journal[$PK[CAOP_JOURNAL]]}'");
								if ( $UpdateJournal['stat'] == RES_SUCCESS )
								{
									bt_notice('ДИСПАНСЕРНЫЙ СТАТУС УСПЕШНО ОБНОВЛЁН', BT_THEME_PRIMARY);
								} else
								{
									bt_notice('ПРОБЛЕМА ОБНОВЛЕНИЯ ДИСПАНСЕРНОГО ДИАГНОЗА', BT_THEME_DANGER);
								}
							}
							
							
							
						} else
						{
							bt_notice('СЛИШКОМ МНОГО ПОВТОРЯЮЩИХСЯ ДИСПАНСЕРНЫХ ДИАГНОЗОВ', BT_THEME_DANGER);
							debug($CheckDispancer);
						}
					} else
					{
						bt_notice('ДИСПАНСЕРНЫЙ ДИАГНОЗ ОТСУТСТВУЕТ', BT_THEME_WARNING);
						$param_disp = array(
							'dispancer_patid' => $Patient[$PK[CAOP_PATIENTS]],
							'dispancer_journal_id' => $Journal[$PK[CAOP_JOURNAL]],
							'dispancer_ds_mkb' => $Journal['journal_disp_mkb'],
							'dispancer_lpu_id' => $Journal['journal_disp_lpu'],
							'dispancer_accounting_begin_unix' => $Day['day_unix'],
							'dispancer_doctor_id' => $Journal['journal_doctor']
						);
						if ( $Journal['journal_ds'] == $Journal['journal_disp_mkb'] )
						{
							$param_disp['dispancer_ds_text'] = $Journal['journal_ds_text'];
						}
						debug($param_disp);
						
						$AddDispancer = appendData(CAOP_DISP_PATIENTS, $param_disp);
						if ( $AddDispancer[ID] > 0 )
						{
							bt_notice('Диспансерный диагноз добавлен!', BT_THEME_SUCCESS);

//                                            debug($AddDispancer);
							
							$param_journal_update = array(
								'journal_disp_self' => $AddDispancer[ID]
							);
//                                            debug($param_journal_update);
							$UpdateJournal = updateData(CAOP_JOURNAL, $param_journal_update, $Journal, $PK[CAOP_JOURNAL] . "='{$Journal[$PK[CAOP_JOURNAL]]}'");
							if ( $UpdateJournal['stat'] == RES_SUCCESS )
							{
								bt_notice('ДАННЫЕ ПОЛНОСТЬЮ ОБНОВЛЕНЫ', BT_THEME_PRIMARY);
							} else
							{
								bt_notice('Невозможно обновить журнал', BT_THEME_WARNING);
							}
							
						} else
						{
							bt_notice('Невозможно добавить диспансерный диагноз', BT_THEME_WARNING);
						}
						
					}
					
				}
				spoiler_end();
			}
		}
		spoiler_end();
		bt_divider();
//		                break;
	}
	
} else bt_notice('Нет диспансерных пациентов');
/*$Journal_count = count($Journal);
if ( $Journal_count > 0 )
{
	$JournalVisits = table_record_divider($Journal, 'journal_patid');
	debug('Диспансерных визитов: ' . count($JournalVisits));
	debug($JournalVisits);
} else
{
	bt_notice('Нет диспансерных пациентов');
}*/