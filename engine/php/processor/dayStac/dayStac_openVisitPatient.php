<?php
$response['stage'] = $action;
$response['debug']['$_POST'] = $_POST;
$response['msg'] = 'begin';
$response['result'] = true;
$response['htmlData'] = '';

$HTTP = $_POST;
extract($HTTP, EXTR_PREFIX_SAME, '_caop');

$VisregsData = getarr(CAOP_DS_VISITS, "visreg_visit_unix='{$weekunix}' AND visreg_dspatid='{$patient_id}'");
if ( count( $VisregsData ) > 0 )
{
	$DS_RESEARCH_TYPES = getarr(CAOP_DS_RESEARCH_TYPES, "type_enabled='1'", "ORDER BY type_order ASC");
	$DS_RESEARCH_TYPES_ID = getDoctorsById($DS_RESEARCH_TYPES, 'type_id');
	
	$PatientRM = RecordManipulation($patient_id, CAOP_DS_PATIENTS, 'patient_id');
	if ( $PatientRM['result'] )
	{
		$PatientData = $PatientRM['data'];
		
		$response['htmlData'] .= spoiler_begin_return(mb_ucwords($PatientData['patient_fio']), 'patient_visreg_'.$patient_id, '');
		$response['htmlData'] .= '<button type="button" class="btn btn-info btn-sm col" onclick="javascript:window.location.href=\'/dayStac/newPatient/'.$PatientData['patient_id'].'\'">КАРТА ПАЦИЕНТА</button><br><br>';
		$response['htmlData'] .= '<b>ВИЗИТ ПАЦИЕНТА:</b> от ' . date("d.m.Y" , $weekunix);
		
		$response['htmlData'] .= '[VISIT_AMOUNT]';
		$response['htmlData'] .= '[VISIT_AMOUNT_FORM]';
		
		$ANALISES_HTML = '';
		$ANALISES_HTML .= spoiler_begin_return('Показатели анализов', 'analisis_'.$patient_id);
		
		//LIMIT ".count($DS_RESEARCH_TYPES)
		// ORDER BY research_unix DESC
		// GROUP BY research_resid
		//  AND research_unix=MAX(research_unix)
//		$queryResearchData = "SELECT * FROM {$CAOP_DS_RESEARCH} WHERE research_patid='{$PatientData['patient_id']}'  ORDER BY research_unix DESC";
		$queryResearchData = "SELECT * FROM {$CAOP_DS_RESEARCH} WHERE research_patid='{$PatientData['patient_id']}' ORDER BY research_id DESC";
		$resultResearchData = mqc($queryResearchData);
		$ResearchData = mr2a($resultResearchData);

//		$ANALISES_HTML .= debug_ret($queryResearchData);
//		$ANALISES_HTML .= debug_ret($ResearchData);
		
		$ANALISES_HTML .= '
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
			
			$ANALISES_HTML .= '
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
            '.$OldResearchData['research_note'].'
        </div>
    </div>
    ';
		}
		
		$ANALISES_HTML .= '<button type="button" class="col btn btn-sm btn-secondary clickForCopy" data-target="researchCopy'.$PatientData['patient_id'].'" data-patient="'.$PatientData['patient_id'].'">Скопировать</button>';
		$ANALISES_HTML .= '<textarea class="class-for-copy" id="researchCopy' . $PatientData['patient_id'] . '">'.$COPY_DATA.'</textarea>';
		
		$ANALISES_HTML .= spoiler_end_return();
		
		$VISIT_AMOUNT_FORM = '
		<form id="visit_amount_'.$weekunix.'">
			<input type="hidden" name="weekunix" value="'.$weekunix.'">
			<input type="hidden" name="patient_id" value="'.$patient_id.'">
			<div class="row">
	            <div class="col">
	                <label class="sr-only" for="visreg_visit_current">Текущее количество процедур</label>
	                <div class="input-group mb-2">
	                    <div class="input-group-prepend">
	                        <div class="input-group-text" '.super_bootstrap_tooltip('Текущее количество процедур').'>
								'.BT_ICON_PROCEDURE_AMOUNT_CURRENT.'
	                        </div>
	                    </div>
	                    <input type="text" class="form-control form-control-sm" huid="visreg_visit_current" name="visreg_visit_current" placeholder="Текущее количество процедур" value="[VISIT_CURRENT]">
	                </div>
	            </div>
	            <div class="col">
	                <label class="sr-only" for="visreg_visit_total">Максимальное количество процедур</label>
	                <div class="input-group mb-2">
	                    <div class="input-group-prepend">
	                        <div class="input-group-text" '.super_bootstrap_tooltip('Максимальное количество процедур').'>
								'.BT_ICON_PROCEDURE_AMOUNT.'
	                        </div>
	                    </div>
	                    <input type="text" class="form-control form-control-sm" huid="visreg_visit_total" name="visreg_visit_total" placeholder="Максимальное количество процедур" value="[VISIT_TOTAL]">
	                </div>
	            </div>
	            <div class="col">
	                <label class="sr-only" for="visreg_dispose_date">Дата выписки</label>
	                <div class="input-group mb-2">
	                    <div class="input-group-prepend">
	                        <div class="input-group-text" '.super_bootstrap_tooltip('Дата выписки').'>
								'.BT_ICON_PROCEDURE_AMOUNT.'
	                        </div>
	                    </div>
	                    <input type="date" class="form-control form-control-sm" huid="visreg_dispose_date" name="visreg_dispose_date" placeholder="Дата выписки" value="[VISIT_DISPOSE]">
	                </div>
	            </div>
	            <div class="col-auto">
					<button type="submit" class="btn btn-sm btn-primary saveVisitAmount" data-weekunix="'.$weekunix.'">Сохранить</button>
				</div>
	        </div>
        </form>
		
		';
		
		$response['htmlData'] .= $ANALISES_HTML;
		
		$visreg_visit_current = 0;
		$visreg_visit_total = 0;
		$visreg_dispose_date = '';
		foreach ($VisregsData as $visregVisit)
		{
			/*$VisRegNote = getarr(CAOP_DS_VISITS, "visreg_dspatid='{$patient_id}' AND visreg_note!=''", "ORDER BY visreg_id DESC LIMIT 1");
			if ( count($VisRegNote) > 0 )
			{
				$VisRegNote = $VisRegNote[0];
				$visregnote = $VisRegNote['visreg_note'];
			} else
			{
				$visregnote = $visregVisit['visreg_note'];
			}*/
			
			$visregnote = $PatientData['patient_note'];
			
			$visreg_visit_current = $visregVisit['visreg_visit_current'];
			$visreg_visit_total = $visregVisit['visreg_visit_total'];
			$visreg_dispose_date = $visregVisit['visreg_dispose_date'];
			
			$visreg_dispose_date_input = ( strlen($visreg_dispose_date) > 0 ) ? date( 'Y-m-d', birthToUnix($visreg_dispose_date) ) : '';
			
			//$VisregData = getarr(CAOP_DS_VISITS_REGIMENS, "visreg_id='{$visregRegimen['visreg_id']}'");
			
			$response['htmlData'] .= spoiler_begin_return($visregVisit['visreg_title'], 'visreg_'.$visregVisit['visreg_id'], '');
			$DoctorData = $DoctorsListId['id' . $visregVisit['visreg_doctor_id']];
			$DoctorName = docNameShort($DoctorData, "famimot");
			
			//$response['debug']['$DoctorsListId'] = $DoctorsListId;
			
			$visreg_dose_measure_type = $DOSE_MEASURE_TYPES_ID['id' . $visregVisit['visreg_dose_measure_type']]['type_title'];
			$visreg_dose_period_type = $DOSE_PERIOD_TYPES_ID['id' . $visregVisit['visreg_dose_period_type']]['type_title'];
			$visreg_freq_period_type = $DOSE_FREQ_PERIOD_TYPES_ID['id' . $visregVisit['visreg_freq_period_type']]['type_title'];
			$response['htmlData'] .= '<b>Название препарата:</b> '.$visregVisit['visreg_drug'].'<br>';
			$response['htmlData'] .= '<b>Дозировка препарата:</b> '.$visregVisit['visreg_dose'].' '.$visreg_dose_measure_type.' / '.$visreg_dose_period_type.'<br>';
			$response['htmlData'] .= '<b>Частота процедуры:</b> '.$visregVisit['visreg_freq_amount'].' раз в '.$visregVisit['visreg_freq_period_amount'].' '.$visreg_freq_period_type.'<br>';
			$response['htmlData'] .= '<b>Da. Signa.:</b> '.$visregVisit['visreg_dasigna'].'<br><br>';
			$response['htmlData'] .= '<b>Врач:</b> '.$DoctorName;
			
			$response['htmlData'] .= '
			
			<form id="from_visreg_result_'.$visregVisit['visreg_id'].'">
				<input type="hidden" name="patient_id" value="'.$PatientData['patient_id'].'">
				<input type="hidden" name="visit_id" value="'.$visregVisit['visreg_id'].'">
				<div class="row">
					<div class="col">
						<label class="sr-only" for="visreg_result">Результат лечения</label>
						<div class="input-group mb-2">
							<div class="input-group-prepend align-top">
								<div class="input-group-text align-top" '.super_bootstrap_tooltip('Результат лечения').'>
									'.BT_ICON_DIAG_TEXT.'
								</div>
							</div>
							<textarea class="form-control autosizer" name="visreg_result" huid="visreg_result" rows="3" placeholder="Результат лечения">'.$visregVisit['visreg_result'].'</textarea>
						</div>
					</div>
				</div>
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
						<button class="btn btn-lg btn-primary col saveNotes" data-visit="'.$visregVisit['visreg_id'].'">Сохранить заметки</button>
					</div>
				</div>
			</form>
			';
			
			$response['htmlData'] .= '
			<div class="row">
				<div class="col">
					<button class="btn btn-sm btn-danger col mt-1 deleteVisit" data-visit="'.$visregVisit['visreg_id'].'">УДАЛИТЬ ПРОЦЕДУРУ</button>
				</div>
			</div>
			';
			
			/*<div class="row">
					<div class="col">
						<label class="sr-only" for="visreg_visit_total">Максимальное количество процедур</label>
						<div class="input-group mb-2">
							<div class="input-group-prepend align-top">
								<div class="input-group-text align-top" '.super_bootstrap_tooltip('Максимальное количество процедур').'>
		'.BT_ICON_PROCEDURE_AMOUNT.'
								</div>
							</div>
							<input class="form-control" type="text" name="visreg_visit_total" huid="visreg_visit_total" placeholder="Максимальное количество визитов" value="'.$visregVisit['visreg_visit_total'].'">
						</div>
					</div>
				</div>*/
			
			$response['htmlData'] .= spoiler_end_return();
			
			
			
		}
		$response['htmlData'] .= spoiler_end_return();
		
		$HTML_VISIT_AMOUNT = '
		<div class="row">
			<div class="col text-center">
				'.bt_notice('ПОРЯДКОВЫЙ НОМЕР ВИЗИТА: <b>№[VISIT_CURRENT]</b> ИЗ <b>[VISIT_TOTAL]</b>', BT_THEME_SUCCESS, 1).'
			</div>
		</div>
		';
		$HTML_VISIT_AMOUNT_WARNING = '
		<div class="row">
			<div class="col-auto">
				<img src="/engine/images/icons/carefull.gif" height="32" alt="">
			</div>
			<div class="col text-center">
				'.bt_notice('ПОРЯДКОВЫЙ НОМЕР ВИЗИТА: <b>№[VISIT_CURRENT]</b> ИЗ <b>[VISIT_TOTAL]</b>', BT_THEME_WARNING, 1).'
			</div>
			<div class="col-auto">
				<img src="/engine/images/icons/carefull.gif" height="32" alt="">
			</div>
		</div>
		';
		
		$HTML_REPLACE = $HTML_VISIT_AMOUNT;
		if ( $visreg_visit_current >= $visreg_visit_total ) $HTML_REPLACE = $HTML_VISIT_AMOUNT_WARNING;
		
		$HTML_REPLACE = str_replace('[VISIT_CURRENT]', $visreg_visit_current, $HTML_REPLACE);
		$HTML_REPLACE = str_replace('[VISIT_TOTAL]', $visreg_visit_total, $HTML_REPLACE);
		
		$VISIT_AMOUNT_FORM = str_replace('[VISIT_CURRENT]', $visreg_visit_current, $VISIT_AMOUNT_FORM);
		$VISIT_AMOUNT_FORM = str_replace('[VISIT_TOTAL]', $visreg_visit_total, $VISIT_AMOUNT_FORM);
		$VISIT_AMOUNT_FORM = str_replace('[VISIT_DISPOSE]', $visreg_dispose_date_input, $VISIT_AMOUNT_FORM);
		
		$response['htmlData'] = str_replace('[VISIT_AMOUNT]', $HTML_REPLACE, $response['htmlData']);
		$response['htmlData'] = str_replace('[VISIT_AMOUNT_FORM]', $VISIT_AMOUNT_FORM, $response['htmlData']);
		
		
	} else $response['msg'] = $PatientPM['msg'];

} else
{
	$response['htmlData'] .= 'Нет назначений для этого пациента';
}