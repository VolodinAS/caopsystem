<?php
$response['stage'] = $action;
$response['msg'] = 'begin';

$HTTP = $_POST;
extract($HTTP, EXTR_PREFIX_SAME, '_caop');

$JournalDay_data = array(
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
		)
	),
	'fields' => '*',
	'where' => $PK[CAOP_JOURNAL] . "='{$journal_id}'"
);
$JournalDay_query = table_joiner($JournalDay_data);
$JournalData_result = mqc($JournalDay_query);
$JournalData = mr2a($JournalData_result);

//$response['debug']['$JournalDay_query'] = $JournalDay_query;

if ( count($JournalData) > 0 )
{
	$JournalData = $JournalData[0];
	
	$response['debug']['$JournalData'] = $JournalData;
	
	$CheckSPO = getarr(CAOP_SPO, "spo_patient_id='{$JournalData['journal_patid']}' AND spo_mkb_finished='{$JournalData['journal_ds']}'", "ORDER BY spo_start_date_unix DESC");
// 	$CheckSPO = getarr(CAOP_SPO, "spo_patient_id='{$JournalData['journal_patid']}'", "ORDER BY spo_start_date_unix DESC");
	
	$go_next = false;
	
	if ( count($CheckSPO) > 0 )
	{
	    $response['debug']['$CheckSPO'] = $CheckSPO;
	    
	    $CheckSPO = $CheckSPO[0];
		
		$response['debug']['$CheckSPO_start_date'] = date(DMY, $CheckSPO['spo_start_date_unix']);
		
	    if ( $CheckSPO['spo_is_dispancer'] == 1 )
	    {
	    	$response['msg'] = 'У пациента уже есть СПО по данному диагнозу. Диагноз относится к ДИСПАНСЕРНЫМ ДИАГНОЗАМ, которые являются бессрочными!'.n(2).'Выберите диспансерный диагноз из списка!';
	    } else
	    {
		    $unix_expired = strtotime("+3 month", $CheckSPO['spo_start_date_unix']);
		
		    $response['debug']['$unix_expired'] = $unix_expired;
		
		    $expired_date = date(DMYHIS, $unix_expired);
		
		    $response['debug']['$expired_date'] = $expired_date;
		
		    if ( $JournalData['day_unix'] <= $unix_expired)
		    {
			    $response['msg'] = 'По текущей дате визита и диагнозу трёхмесячный срок действия направления в СПО еще не истек!'.n(2).'Вы не можете создать новое СПО! Выберите актуальное СПО!';
		    } else $go_next = true;
	    }
	    
	    
	    
	    
	    
	    
	} else $go_next = true;
	
	if ( $go_next )
	{
		
		if ( $JournalData['journal_disp_lpu'] > 0 )
		{
			
			$is_dispancer = ( CheckMKBDispancer($JournalData['journal_ds'], $MKBDispLinear)['result'] ) ? '1' : '0';
			
			
			$param_new_SPO = array(
				'spo_patient_id' => $JournalData['journal_patid'],
				'spo_start_doctor_id' => $JournalData['journal_doctor'],
				'spo_start_date_unix' => $JournalData['day_unix'],
				'spo_start_day_id' => $JournalData['day_id'],
//		    'spo_mkb_directed' => '', // МКБ направления,
				'spo_mkb_finished' => $JournalData['journal_ds'],
				'spo_is_dispancer' => $is_dispancer,
				'spo_dir_lpu_doctor_fio' => $JournalData['journal_record_worker'],
				'spo_dir_lpu_name' => $JournalData['journal_record_division'],
				'spo_dir_lpu_date_date' => $JournalData['journal_record_date'],
				'spo_dir_lpu_date_time' => $JournalData['journal_record_time'],
			    'spo_lpu_id' => $JournalData['journal_disp_lpu'],
			);
			
			$response['debug']['$param_new_SPO'] = $param_new_SPO;
			
			$AddSPO = appendData(CAOP_SPO, $param_new_SPO);
			if ( $AddSPO[ID] > 0 )
			{
				
				$param_update_journal = array(
					'journal_spo_id' => $AddSPO[ID]
				);
				
				$UpdateJournal = updateData(CAOP_JOURNAL, $param_update_journal, [], $PK[CAOP_JOURNAL] . "='{$JournalData[$PK[CAOP_JOURNAL]]}'");
				if ($UpdateJournal['stat'] == RES_SUCCESS)
				{
					$response['result'] = true;
					
					if ( $JournalData['patid_pin_lpu_id'] == 0 )
					{
						$param_update_patient = array(
						    'patid_pin_lpu_id' => $JournalData['journal_disp_lpu']
						);
						$UpdatePatient = updateData(CAOP_PATIENTS, $param_update_patient, [], $PK[CAOP_PATIENTS] . "='{$JournalData['journal_patid']}'");
					}
					
				} else $response['msg'] = 'Проблема обновления СПО';
				
				
			} else $response['msg'] = 'Проблема с созданием СПО';
			
		} else $response['msg'] = 'Перед тем, как создать СПО, выберите ЛПУ прикрепления пациента в разделе "ЛПУ прикрепления"';
		
		
	}
	
	
	
	
 
} else $response['msg'] = 'Нет такого приёма';
