<?php
$response['stage'] = $action;
$response['debug']['$_POST'] = $_POST;
$response['msg'] = 'begin';
$response['result'] = true;

$HTTP = $_POST;
extract($HTTP, EXTR_PREFIX_SAME, '_caop');

$PatientRM = RecordManipulation($patient_id, CAOP_DS_PATIENTS, 'patient_id');
if ( $PatientRM['result'] )
{
	$DS_RESEARCH_TYPES = getarr(CAOP_DS_RESEARCH_TYPES, "type_enabled='1'", "ORDER BY type_order ASC");
	$DS_RESEARCH_TYPES_ID = getDoctorsById($DS_RESEARCH_TYPES, 'type_id');
	
	$PatientData = $PatientRM['data'];
	$visregnote = $PatientData['patient_note'];
	
	$DirlistPM = RecordManipulation($dirlist_id, CAOP_DS_DIRLIST, 'dirlist_id');
	if ( $DirlistPM['result'] )
	{
	    $DirlistData = $DirlistPM['data'];
		
		$PatientsList = json_decode( trim($_POST['json_data']), 1 );
	    
//	    $response['htmlData'] .= debug_ret($PatientsList);
	    $PatientVisregData = array();
		foreach ($PatientsList as $patvisreg)
		{
			if ( $patvisreg['patid_id'] == $patient_id ) $PatientVisregData[] = $patvisreg;
	    }
		
//		$response['htmlData'] .= debug_ret($PatientVisregData);
		
		$response['htmlData'] .= spoiler_begin_return(mb_ucwords($PatientData['patient_fio']) . ', '.$PatientData['patient_birth'] . ' г.р.', 'patient_visreg_'.$PatientData['patient_id'], '');
		
		$response['htmlData'] .= '<button type="button" class="btn btn-info btn-sm col" onclick="javascript:window.location.href=\'/dayStac/newPatient/'.$PatientData['patient_id'].'\'">КАРТА ПАЦИЕНТА</button><br><br>';
		
		$response['htmlData'] .= '[VISIT_AMOUNT]';
		
		$LastVisits = getarr(CAOP_DS_VISITS, "visreg_dspatid='{$PatientData['patient_id']}' AND visreg_dirlist_id='{$DirlistData['dirlist_id']}' ORDER BY visreg_visit_unix DESC LIMIT 1");
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
		
		$HTML_VISIT_AMOUNT = '
		<div class="row">
			<div class="col text-center">
				'.bt_notice('ПЛАНИРУЕМЫЙ ВИЗИТ: <b>№[VISIT_CURRENT]</b> ИЗ <b>[VISIT_TOTAL]</b>', BT_THEME_SUCCESS, 1).'
			</div>
		</div>
		';
		$HTML_VISIT_AMOUNT_WARNING = '
		<div class="row">
			<div class="col-auto">
				<img src="/engine/images/icons/carefull.gif" height="32" alt="">
			</div>
			<div class="col text-center">
				'.bt_notice('ПЛАНИРУЕМЫЙ ВИЗИТ: <b>№[VISIT_CURRENT]</b> ИЗ <b>[VISIT_TOTAL]</b>', BT_THEME_WARNING, 1).'
			</div>
			<div class="col-auto">
				<img src="/engine/images/icons/carefull.gif" height="32" alt="">
			</div>
		</div>
		';
		
		$HTML_REPLACE = $HTML_VISIT_AMOUNT;
		if ( $current_visit >= $total_visit ) $HTML_REPLACE = $HTML_VISIT_AMOUNT_WARNING;
		
		$HTML_REPLACE = str_replace('[VISIT_CURRENT]', $current_visit, $HTML_REPLACE);
		$HTML_REPLACE = str_replace('[VISIT_TOTAL]', $total_visit, $HTML_REPLACE);
		
		$response['htmlData'] = str_replace('[VISIT_AMOUNT]', $HTML_REPLACE, $response['htmlData']);
		
		$response['htmlData'] .= spoiler_begin_return('Показатели анализов', 'analysis' . $PatientData['patient_id']);
//		$ResearchData = getarr(CAOP_DS_RESEARCH, "research_patid='{$PatientData['patient_id']}' AND research_resid IN {$DS_RESEARCH_TYPES_LINEAR}", "ORDER BY research_unix DESC LIMIT ".count($DS_RESEARCH_TYPES), 1);
		
		
//		$queryResearchData = "SELECT * FROM {$CAOP_DS_RESEARCH} WHERE research_patid='{$PatientData['patient_id']}' GROUP BY research_resid ORDER BY research_unix DESC LIMIT ".count($DS_RESEARCH_TYPES);
		$queryResearchData = "SELECT * FROM {$CAOP_DS_RESEARCH} WHERE research_patid='{$PatientData['patient_id']}' ORDER BY research_id DESC";
		$resultResearchData = mqc($queryResearchData);
		$ResearchData = mr2a($resultResearchData);
		
//		$response['htmlData'] .= debug_ret($queryResearchData);
//		$response['htmlData'] .= debug_ret($ResearchData);

		$response['htmlData'] .= '
		<form id="research_'.$PatientData['patient_id'].'">
			<input type="hidden" name="patient_id" value="'.$PatientData['patient_id'].'">
			<div class="row">
				<div class="col font-weight-bolder text-center border-right">
					Название
				</div>
				<div class="col font-weight-bolder text-center">
					Было
				</div>
				<div class="col font-weight-bolder text-center border-right">
					Дата
				</div>
				<div class="col font-weight-bolder text-center">
					Стало
				</div>
				<div class="col font-weight-bolder text-center border-right">
					Дата
				</div>
				<div class="col font-weight-bolder text-center">
					Примечание
				</div>
			</div>
			';
		
		$COPY_DATA = '';
		foreach ($DS_RESEARCH_TYPES_ID as $resid=>$research)
		{
			$OldResearchData = [];
			$ResearchItem = array_search($research['type_id'], array_column($ResearchData, 'research_resid'));
			
			if ( strlen($ResearchItem) >0 )
			{
				$OldResearchData = $ResearchData[$ResearchItem];
				
				
				if ( $COPY_DATA == '' )
				{
					$COPY_DATA = $research['type_title'] . ' от ' . $OldResearchData['research_date'] . ' - ' . $OldResearchData['research_value'];
				} else
				{
					$COPY_DATA .= "\n" .  $research['type_title'] . ' от ' . $OldResearchData['research_date'] . ' - ' . $OldResearchData['research_value'];
				}
				
			}
			
			
			
//			$response['htmlData'] .= debug_ret('|'.$ResearchItem.'|');
//			$response['htmlData'] .= debug_ret($research);
//			$response['htmlData'] .= debug_ret($OldResearchData);
			$response['htmlData'] .= '
			<div class="row">
				<div class="col border-right">
					'.$research['type_title'].'
				</div>
				<div class="col">
					'.$OldResearchData['research_value'].'
				</div>
				<div class="col border-right">
					'.$OldResearchData['research_date'].'
				</div>
				<div class="col">
					<input type="text" name="field_value_'.$research['type_id'].'" class="form-control form-control-sm" placeholder="Новый результат">
				</div>
				<div class="col border-right">
					<input type="text" name="field_date_'.$research['type_id'].'" class="form-control form-control-sm russianBirth" placeholder="Дата результат">
				</div>
				<div class="col">
					<input type="text" name="field_note_'.$research['type_id'].'" class="form-control form-control-sm" placeholder="Примечание" value="'.$OldResearchData['research_note'].'">
				</div>
			</div>
			';
		}
		
		
		$response['htmlData'] .= '<button type="submit" class="col-6 btn btn-sm btn-primary saveResearch" data-patient="'.$PatientData['patient_id'].'">Сохранить</button>';
		$response['htmlData'] .= '<button type="button" class="col-6 btn btn-sm btn-secondary clickForCopy" data-target="researchCopy'.$PatientData['patient_id'].'" data-patient="'.$PatientData['patient_id'].'">Скопировать</button>';
		$response['htmlData'] .= '<textarea class="class-for-copy" id="researchCopy' . $PatientData['patient_id'] . '">'.$COPY_DATA.'</textarea>';
		$response['htmlData'] .= '</form>';
		
		$response['htmlData'] .= spoiler_end_return();
		
		foreach ($PatientVisregData as $patientVisregDatum)
		{
			$VisregData = getarr(CAOP_DS_VISITS_REGIMENS, "	visreg_id='{$patientVisregDatum['visreg_id']}'");
			if ( count($VisregData) > 0 )
			{
				foreach ($VisregData as $visregDatum)
				{
					// проверить, нет ли визита на эту схему на этот день
					$response['htmlData'] .= spoiler_begin_return($visregDatum['visreg_title'], 'visreg_'.$visregDatum['visreg_id'], '');
					$DoctorData = $DoctorsListId['id' . $visregDatum['visreg_doctor_id']];
					$DoctorName = docNameShort($DoctorData, "famimot");
//				$response['htmlData'] .= debug_ret($visreg_dose_measure_type);
					$visreg_dose_measure_type = $DOSE_MEASURE_TYPES_ID['id' . $visregDatum['visreg_dose_measure_type']]['type_title'];
					$visreg_dose_period_type = $DOSE_PERIOD_TYPES_ID['id' . $visregDatum['visreg_dose_period_type']]['type_title'];
					$visreg_freq_period_type = $DOSE_FREQ_PERIOD_TYPES_ID['id' . $visregDatum['visreg_freq_period_type']]['type_title'];
//				$response['htmlData'] .= debug_ret($visregDatum);
					$response['htmlData'] .= '<b>Название схемы:</b> '.$visregDatum['visreg_title'].'<br>';
					$response['htmlData'] .= '<b>Название препарата:</b> '.$visregDatum['visreg_drug'].'<br>';
					$response['htmlData'] .= '<b>Дозировка препарата:</b> '.$visregDatum['visreg_dose'].' '.$visreg_dose_measure_type.' / '.$visreg_dose_period_type.'<br>';
					$response['htmlData'] .= '<b>Частота процедуры:</b> '.$visregDatum['visreg_freq_amount'].' раз в '.$visregDatum['visreg_freq_period_amount'].' '.$visreg_freq_period_type.'<br>';
					$response['htmlData'] .= '<b>Da. Signa.:</b> '.$visregDatum['visreg_dasigna'].'<br><br>';
					$response['htmlData'] .= '<b>Врач:</b> '.$DoctorName;
					$response['htmlData'] .= '<div class="dropdown-divider"></div><div class="row">
						<div class="col-auto">
						<b>Добавить визит:</b>
						</div>
						<div class="col">
							<input type="date" name="currentDay" id="currentDay_'.$visregDatum['visreg_id'].'" class="form-control form-control-sm" value="'.date("Y-m-d", $weekunix).'">
						</div>
						<div class="col-auto">
							<button class="btn btn-sm btn-primary createVisitRegimen" data-visreg="'.$visregDatum['visreg_id'].'">Создать</button>
						</div>
					</div>';
					$response['htmlData'] .= spoiler_end_return();
				}
			} else
			{
				$response['htmlData'] .= bt_notice('У пациента нет активных схем лечения', BT_THEME_WARNING, 1);
			}
		}
		$response['htmlData'] .= spoiler_end_return();
		
		$response['htmlData'] .= '<br>
		<div class="row">
			<div class="col-auto">
			<b>Добавить визиты НА ВСЕ назначения:</b>
			</div>
			<div class="col">
				<input type="date" name="allDays" id="allDays" class="form-control form-control-sm" value="'.date("Y-m-d", $weekunix).'">
			</div>
			<div class="col-auto">
				<button class="btn btn-sm btn-primary createVisitRegimen" data-status="all" data-visreg="'.$visregDatum['visreg_id'].'">Создать</button>
			</div>
		</div>
		';
		
		$response['htmlData'] .= '
			<br>
			<form id="from_visreg_result_'.$visregDatum['visreg_id'].'">
				<input type="hidden" name="patient_id" value="'.$PatientData['patient_id'].'">
				<div class="row">
					<div class="col">
						<label class="sr-only" for="visreg_note">Примечание</label>
						<div class="input-group mb-2">
							<div class="input-group-prepend align-top">
								<div class="input-group-text align-top" '.super_bootstrap_tooltip('Примечание').'>
									'.BT_ICON_DIAG_TEXT.'
								</div>
							</div>
							<textarea class="form-control autosizer" name="visreg_note" huid="visreg_note" rows="3" placeholder="Примечание">'.$visregnote.'</textarea>
						</div>
					</div>
				</div>
				
				<div class="row">
					<div class="col">
						<button class="btn btn-lg btn-primary col saveNotes" data-visit="'.$visregDatum['visreg_id'].'">Сохранить примечание</button>
					</div>
				</div>
			</form>
			';
		
		/*$response['htmlData'] .= '
		<div style="display: none" id="alldaysvisit">'.json_encode($PatientVisregData).'</div>
		';*/
	
	} else $response['msg'] = $DirlistPM['msg'];
	
} else $response['msg'] = $PatientPM['msg'];