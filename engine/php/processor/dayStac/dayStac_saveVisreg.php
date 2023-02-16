<?php
$response['stage'] = $action;
$response['debug']['$_POST'] = $_POST;
$response['msg'] = 'begin';

$HTTP = $_POST;
extract($HTTP, EXTR_PREFIX_SAME, '_caop');

$DS_RESEARCH_TYPES = getarr(CAOP_DS_RESEARCH_TYPES, "type_enabled='1'", "ORDER BY type_order ASC");
$DS_RESEARCH_TYPES_ID = getDoctorsById($DS_RESEARCH_TYPES, 'type_id');

$excludeVars = array('visreg_isTemplate', 'visreg_visit_current', 'visreg_visit_total', 'visreg_dispose_date');

$varName = array(
	'visreg_dose'                       =>  'Дозировка препарата',
	'visreg_dose_measure_type'          =>  'Измерение дозировки',
	'visreg_dose_period_type'           =>  'Время дозировки',
	'visreg_drug'                       =>  'Название препарата',
	'visreg_freq_amount'                =>  'Количество введений',
	'visreg_freq_period_amount'         =>  'Периодичность',
	'visreg_freq_period_type'           =>  'Временной промежуток',
	'visreg_title'                      =>  'Название схемы лечения'
);

$VisregRM = RecordManipulation($visreg_id, CAOP_DS_VISITS_REGIMENS, 'visreg_id');
if ( $VisregRM['result'] )
{
	$VisregData = $VisregRM['data'];
	
	$youAreNotFillText = '';
	$isEmptyFound = false;
	foreach ($HTTP as $key=>$value)
	{
		if ( !in_array($key, $excludeVars) && !ifound($key, "field_") )
		{
			$isEmpty = false;
			if ( strlen($value) > 0 )
			{
				if ( ctype_digit($value) )
				{
					if($value==0)
					{
						$isEmpty = true;
					}
				} else
				{
					if ( $value == '0' )
					{
						$isEmpty = true;
					}
				}
			} else
			{
				$isEmpty = true;
			}
			
			if ($isEmpty)
			{
				$isEmptyFound = true;
				if ( strlen($youAreNotFillText) == '' )
				{
					$youAreNotFillText .= 'Вы не заполнили поля: ' . $varName[$key];
				} else
				{
					$youAreNotFillText .= ', ' . $varName[$key];
				}
			}
		}
		
	}
	
	if ( !$isEmptyFound )
	{
		
		$RegimenData = array(
			'visreg_dspatid'                =>  $VisregData['visreg_dspatid'],
			'visreg_dirlist_id'             =>  $VisregData['visreg_dirlist_id'],
			'visreg_doctor_id'              =>  $VisregData['visreg_doctor_id'],
			'visreg_title'                  =>  $visreg_title,
			'visreg_drug'                   =>  $visreg_drug,
			'visreg_dose'                   =>  $visreg_dose,
			'visreg_dose_measure_type'      =>  $visreg_dose_measure_type,
			'visreg_dose_period_type'       =>  $visreg_dose_period_type,
			'visreg_freq_amount'            =>  $visreg_freq_amount,
			'visreg_freq_period_amount'     =>  $visreg_freq_period_amount,
			'visreg_freq_period_type'       =>  $visreg_freq_period_type,
			'visreg_dasigna'                =>  $visreg_dasigna
		);
		
		$response['debug']['$RegimenData'] = $RegimenData;
		
		$UpdateRegiment = updateData(CAOP_DS_VISITS_REGIMENS, $RegimenData, $VisregData, "visreg_id='{$VisregData['visreg_id']}'");
		if ( $UpdateRegiment['stat'] == RES_SUCCESS )
		{
//			$response['result'] = true;
			$response['patient_id'] = $VisregData['visreg_dspatid'];
			$response['msg'] = 'Назначение сохранено';
			if ($visreg_isTemplate == 1)
			{
				unset($RegimenData['visreg_dspatid']);
				unset($RegimenData['visreg_dirlist_id']);
				$TemplateData = array();
				foreach ($RegimenData as $key=>$value)
				{
					$key = str_replace('visreg_', 'regimen_', $key);
					$TemplateData[$key] = $value;
				}
				
				$NewRegimenTemplate = appendData(CAOP_DS_REGIMENS, $TemplateData);
				$response['msg'] .= "\n\nШаблон \"{$visreg_title}\" успешно создан!";
				$response['patient_id'] = $VisregData['visreg_dspatid'];
				
				$response['debug']['$fast_visit_add_checkbox'] = $fast_visit_add_checkbox;
			}
			
			if ( intval($fast_visit_add_checkbox) == 1 )
			{
				// 1 - ВИЗИТ - Обследования
				foreach ($DS_RESEARCH_TYPES_ID as $researchId=>$researchType)
				{
					$researchId = str_replace('id', '', $researchId);
					
					$resvar_value = 'field_value_' . $researchId;
					$resvar_date = 'field_date_' . $researchId;
					$resvar_note = 'field_note_' . $researchId;
					
					if ( strlen($HTTP[$resvar_value])>0 && strlen($HTTP[$resvar_date])>0 )
					{
						$paramValues = array(
							'research_patid'    =>  $VisregData['visreg_dspatid'],
							'research_resid'    =>  $researchId,
							'research_value'    =>  $HTTP[$resvar_value],
							'research_note'    =>  $HTTP[$resvar_note],
							'research_date'    =>  $HTTP[$resvar_date],
							'research_unix'    =>  birthToUnix($HTTP[$resvar_date])
						);
						
						$response['debug']['$paramValues_'.$researchId][] = $paramValues;

						$InsertResearch = appendData(CAOP_DS_RESEARCH, $paramValues);
					
					} else
					{
						if ( strlen($HTTP[$resvar_note]) > 0 )
						{
							// обновить примечание
							$ResearchNote = getarr(CAOP_DS_RESEARCH, "research_patid='{$VisregData['visreg_dspatid']}' AND research_resid='{$researchId}' ORDER BY research_unix DESC LIMIT 1");
							$response['debug']['$ResearchNote_' . $researchId] = $ResearchNote;
							if ( count($ResearchNote) == 1 )
							{
								$ResearchNote = $ResearchNote[0];
								$updateValues = array(
									'research_note' =>  $HTTP[$resvar_note]
								);
								$UpdateNote = updateData(CAOP_DS_RESEARCH, $updateValues, $ResearchNote, "research_id='{$ResearchNote['research_id']}'");
								if ( $UpdateNote['stat'] == RES_SUCCESS )
								{
								
								}
							}
						}
					}
					
				}
				
				// 2 - ВИЗИТ - Визит
				$visreg_id = $VisregData['visreg_id'];
				$VisregRM = RecordManipulation($visreg_id, CAOP_DS_VISITS_REGIMENS, 'visreg_id');
				if ( $VisregRM['result'] )
				{
					$VisregData = $VisregRM['data'];
					
					$response['debug']['$VisregData'] = $VisregData;
					
					if ( !$isGetVisits )
					{
						$isGetVisits = true;
						$LastVisits = getarr(CAOP_DS_VISITS, "visreg_dspatid='{$VisregData['visreg_dspatid']}' AND visreg_dirlist_id='{$VisregData['visreg_dirlist_id']}' ORDER BY visreg_visit_unix DESC LIMIT 1");
						if ( count($LastVisits) > 0 )
						{
							$LastVisits = $LastVisits[0];
							$current_visit = intval($LastVisits['visreg_visit_current']);
							$current_visit += 1;
							$total_visit = $LastVisits['visreg_visit_total'];
						} else
						{
							$current_visit = 1;
							$total_visit = '';
						}
					}
					
					$fast_visit_date_unix = strtotime( $fast_visit_date );
					$fast_visit_date = date( "d.m.Y", $fast_visit_date_unix );
					
					if ( strlen($visreg_dispose_date) > 0 )
					{
						$visreg_dispose_date_unix = strtotime( $visreg_dispose_date );
						$visreg_dispose_date = date( "d.m.Y", $visreg_dispose_date_unix );
					}
					
					
					
					$VisitPatientData = array(
						'visreg_regimen_id'         =>  $VisregData['visreg_id'],
						'visreg_dspatid'            =>  $VisregData['visreg_dspatid'],
						'visreg_dirlist_id'         =>  $VisregData['visreg_dirlist_id'],
						'visreg_doctor_id'          =>  $USER_PROFILE['doctor_id'],
						'visreg_title'              =>  $VisregData['visreg_title'],
						'visreg_drug'               =>  $VisregData['visreg_drug'],
						'visreg_dose'               =>  $VisregData['visreg_dose'],
						'visreg_dose_measure_type'  =>  $VisregData['visreg_dose_measure_type'],
						'visreg_dose_period_type'   =>  $VisregData['visreg_dose_period_type'],
						'visreg_dasigna'            =>  $VisregData['visreg_dasigna'],
						'visreg_freq_amount'        =>  $VisregData['visreg_freq_amount'],
						'visreg_freq_period_amount' =>  $VisregData['visreg_freq_period_amount'],
						'visreg_freq_period_type'   =>  $VisregData['visreg_freq_period_type'],
						'visreg_visit_date'         =>  $fast_visit_date,
						'visreg_visit_unix'         =>  $fast_visit_date_unix
					);
					
					if ( strlen($visreg_visit_current) > 0 ) $VisitPatientData['visreg_visit_current'] = $visreg_visit_current;
					if ( strlen($visreg_visit_total) > 0 ) $VisitPatientData['visreg_visit_total'] = $visreg_visit_total;
					if ( strlen($visreg_dispose_date) > 0 )
					{
						$VisitPatientData['visreg_dispose_date'] = $visreg_dispose_date;
						$VisitPatientData['visreg_dispose_unix'] = $visreg_dispose_date_unix;
					}
					
					$VisregListSummary[] = $VisitPatientData;
					
					if ( count($VisregListSummary) > 0 )
					{
						$response['debug']['$VisregListSummary'] = $VisregListSummary;
						foreach ($VisregListSummary as $visregVisit)
						{
							$AddVisit = appendData(CAOP_DS_VISITS, $visregVisit);
						}
						
					} else
					{
						$response['msg'] = 'Ошибка добавления визитов';
					}
					
				} else $response['msg'] = $VisregRM['msg'];
			}
		}
		
	} else $response['msg'] = $youAreNotFillText;
	
	
} else $response['msg'] = $VisregRM['msg'];

