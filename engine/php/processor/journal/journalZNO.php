<?php
$response['stage'] = $action;

$patient_id = $_POST['patient_id'];

$Journal = getarr(CAOP_JOURNAL, "journal_id='{$patient_id}'");

if ( count($Journal) == 1 )
{
	$Journal = $Journal[0];

	$response['debug']['$Journal'] = $Journal;

	$PatientPersonalData = getarr(CAOP_PATIENTS, "patid_id='{$Journal['journal_patid']}'");
	
	$CitologyTypes = getarr(CAOP_CITOLOGY_TYPE, 1, "ORDER BY type_id ASC");
	$CitologyTypesId = getDoctorsById($CitologyTypes, 'type_id');

	if ( count($PatientPersonalData) == 1 )
	{
		$PatientPersonalData = $PatientPersonalData[0];

		$response['debug']['$PatientPersonalData'] = $PatientPersonalData;
		
		if (
			ifound($Journal['journal_ds'], 'C') ||
			ifound($Journal['journal_ds'], 'c') ||
			ifound($Journal['journal_ds'], 'D0') ||
			ifound($Journal['journal_ds'], 'd0')
		)
		{
			// Проверяем корректность диагноза
			$ALLGOODCHECKED = true;
			$DS_CORRECTOR = $Journal['journal_ds'];
			
			$DS_CORRECTOR = trim($DS_CORRECTOR);
			$DS_CORRECTOR = nodoublespaces($DS_CORRECTOR);
			$DS_CORRECTOR = nospaces($DS_CORRECTOR);
			$DS_CORRECTOR = str_replace(',', '.', $DS_CORRECTOR);
			$DS_CORRECTOR = mb_strtoupper($DS_CORRECTOR, UTF8);
			$isMatched = preg_match('/\b([A-Z]\d{2})(.\d)?\b/', $DS_CORRECTOR, $matches);
			if ( count($matches) == 0 )
			{
				$ALLGOODCHECKED = false;
				$response['stat'] = 'error';
				$response['msg'] = 'НЕВЕРНО ВВЕДЕН ДИАГНОЗ ПО МКБ-10';
			} else
			{
				$DS_CORRECTOR = $matches[0];
			}
			
			if ( $ALLGOODCHECKED )
			{
				// Проверяем ранее установленные
				$CancerData = getarr(CAOP_CANCER, "cancer_patid='{$PatientPersonalData['patid_id']}' AND cancer_ds='{$DS_CORRECTOR}'");
				if ( count($CancerData) > 0 )
				{
					$CancerData = $CancerData[0];
					$response['msg'] = 'У ДАННОГО ПАЦИЕНТА УЖЕ УСТАНОВЛЕН ДИАГНОЗ ПО ['.$CancerData['cancer_ds'].'] - '.$CancerData['cancer_ds_text'];
				} else
				{
					$newCancer = array(
						'cancer_patid'  =>  $PatientPersonalData['patid_id'],
						'cancer_ds'  =>  $DS_CORRECTOR,
						'cancer_ds_text'  =>  $Journal['journal_ds_text'],
						'cancer_unix'   =>  time(),
						'cancer_doctor_id'  =>  $USER_PROFILE['doctor_id']
					);
					
					// добавляем цитологические данные
					$CitologyData = getarr(CAOP_CITOLOGY, "citology_patid='{$PatientPersonalData[$PK[CAOP_PATIENTS]]}' AND citology_cancer='1'", "ORDER BY patidcard_patient_done DESC LIMIT 0,1");
					if ( count($CitologyData) == 1 )
					{
					    $CitologyData = $CitologyData[0];
						$citology_type = '';
					    if ( $CitologyData['citology_analise_type'] > 0 )
					    {
					        $citology_type = $CitologyTypesId['id' . $CitologyData['citology_analise_type'] ]['type_title'];
					        $citology_type = 'Цитология ('.$citology_type.')';
					    }
						
						$citology_numbers = 'нет';
					    if ( strlen($CitologyData['citology_result_id']) > 0 )
					    {
					        $citology_numbers = $CitologyData['citology_result_id'];
					    }
					    
					    $citology_date = 'неизвестно';
					    if ( strlen($CitologyData['patidcard_patient_done']) > 0 )
					    {
					        $citology_date = $CitologyData['patidcard_patient_done'];
					    } else
					    {
					    	if ( strlen($CitologyData['citology_action_date']) > 0 )
					    	{
							    $citology_date = $CitologyData['citology_action_date'];
					    	} 
					    }
					    
					    $newCancer['cancer_morph_verif'] = 1;
					    $newCancer['cancer_morph_type'] = $citology_type;
					    $newCancer['cancer_morph_number'] = $citology_numbers;
					    $newCancer['cancer_morph_number'] = $citology_numbers;
					    $newCancer['cancer_morph_date'] = $citology_date;
					    $newCancer['cancer_morph_text'] = $CitologyData['citology_result_text'];
					}
					
					
					$AddCancer = appendData(CAOP_CANCER, $newCancer);
					if ( $AddCancer[ID] > 0 )
					{
						
						$param_update_patient_casestatus = array(
						    'patid_casestatus' => 4
						);
						$UpdatePatients = updateData(CAOP_PATIENTS, $param_update_patient_casestatus, $PatientPersonalData, $PK[CAOP_PATIENTS] . "='{$PatientPersonalData[$PK[CAOP_PATIENTS]]}'");
						if ( $UpdatePatients ['stat'] == RES_SUCCESS )
						{
							$response['result'] = true;
						} else $response['msg'] = 'Проблема обновления статуса пациента';
					} else
					{
						$response['msg'] = $AddCancer;
					}
				}
			}
			
			
		} else
		{
			$response['msg'] = "ОШИБКА!!!\n\nВЫ МОЖЕТЕ УСТАНОВИТЬ ДИАГНОЗ ТОЛЬКО СО ЗЛОКАЧЕСТВЕННОЙ ПАТОЛОГИЕЙ (С44, С73 и т.д.)\n\nИсправьте дневник, установив верный диагноз, и повторите попытку";
		}

		
	} else
	{
		$response['msg'] = $PatientPersonalData;
	}


} else
{
	$response['msg'] = $Journal;
}