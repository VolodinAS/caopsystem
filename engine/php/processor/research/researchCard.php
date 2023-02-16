<?php
$response['stage'] = $action;

$patient_id = $_POST['patient_id'];

$ResearchTypes = getarr(CAOP_RESEARCH_TYPES, "type_enabled='1'", "ORDER BY type_order ASC");
$ResearchTypesId = getDoctorsById($ResearchTypes, 'type_id');

if ( $patient_id > 0 )
{

	$ResearchPatient = getarr('caop_research', "research_id='{$patient_id}'");
	if ( count($ResearchPatient) == 1 )
	{

		$ResearchPatient = $ResearchPatient[0];

        $PatientPersonalData = getarr(CAOP_PATIENTS, "patid_id='{$ResearchPatient['research_patid']}'");
        if ( count($PatientPersonalData) == 1 ) {
            $PatientPersonalData = $PatientPersonalData[0];

            $Doctors = getDoctorsById($DoctorsList);
//            $ResearchTypes = getDoctorsById($ResearchTypes, 'type_id');
            $Doctor = $Doctors['id' . $ResearchPatient['research_doctor_id']];
            $ResearchType = $ResearchTypesId['id' . $ResearchPatient['research_type']];

            $area = '';
            if ( strlen($ResearchPatient['research_area']) > 1 ) $area = ' ' . $ResearchPatient['research_area'];

            $cito = '';
            if ( $ResearchPatient['research_cito'] == 2 ) $cito = ' (CITO)';

            $doctor_name = mb_ucwords($Doctor['doctor_f'] . ' ' . $Doctor['doctor_i'] . ' ' . $Doctor['doctor_o']);

            $resDate = '';
            if ( strlen( $ResearchPatient['patidcard_patient_done'] ) > 0 )
            {
            	$resDate = ' от ' . $ResearchPatient['patidcard_patient_done'];
            }

            $response['result'] = true;
            $response['htmlData'] = '
            <b>Ф.И.О.:</b> '.shorty($PatientPersonalData['patid_name']).', '.$PatientPersonalData['patid_birth'].' г.р.<br/>
            <b>Врач:</b> '.$doctor_name.'<br/>
            <b>Диагноз:</b> ['.$ResearchPatient['research_ds'].'] '.$ResearchPatient['research_ds_text'].'<br/>
            <b>Обследование:</b> '.$ResearchType['type_title'].$area.$cito.$resDate.'<br/>
            <b>Контакты:</b> '.$PatientPersonalData['patid_phone'].'<br/>
            <b>Результат (при наличии) <img src="/engine/images/icons/copy.png" class="cursor-pointer clickForCopy" style="width: 32px" data-target="researchCopyArea'.$ResearchPatient['research_id'].'">:</b><br/>
            ';
            $response['htmlData'] .= '<input type="hidden" id="research_id" value="'.$ResearchPatient['research_id'].'">';
            $resultTextarea = mysqleditor_textarea_generator(
                                                            CAOP_RESEARCH,
                                                            'research_id',
                                                            $ResearchPatient['research_id'],
                                                            'research_result',
                                                            '0',
                                                            null,
                                                            'rows="5"',
                                                            'textarea',
                                                            'mysqleditor form-control form-control-lg',
                                                            '',
                                                            'Результат обследования',
                                                            $ResearchPatient['research_result'],
                                                            'edit',
                                                            '0');
            $response['htmlData'] .= $resultTextarea['textarea'];
            
            $morph = getMorphOfResearch($ResearchPatient);
            
            $response['htmlData'] .= '
            <textarea class="class-for-copy" id="researchCopyArea'.$ResearchPatient['research_id'].'">'.$ResearchType['type_title'].$area.$resDate.' - '.$ResearchPatient['research_result'].$morph.'</textarea>
            ';
	
	        $CitologyCancerTypes = getarr(CAOP_CITOLOGY_CANCER_TYPE, 1, "ORDER BY type_order ASC");
	        $hint = 'НЕэндоскопические методы НЕ МОГУТ поставить РАК, только ПРЕДРАК!';
            if ( $ResearchType['type_morph'] == 1 )
            {
	            $hint = '';
	            $response['htmlData'] .= '<br/><b>Морфология (если есть):</b><br/>';
	            $response['htmlData'] .= '<div class="row">';
	            $response['htmlData'] .= '<div class="col"><b>Дата гистологии:</b>';
	            $response['htmlData'] .= '<input type="text" class="mysqleditor form-control form-control-lg russianBirth" name="morph_date" id="morph_date" data-action="edit"
			        data-table="'.CAOP_RESEARCH.'"
			        data-assoc="0"
			        data-fieldid="research_id"
			        data-id="'.$ResearchPatient['research_id'].'"
			        data-field="research_morph_date"
	                data-return="#researchCopyArea'.$ResearchPatient['research_id'].'"
                 	data-returntype="html"
                 	data-returnfunc="research_string"
			        placeholder="Дата"
			        value="'.$ResearchPatient['research_morph_date'].'">';
	            $response['htmlData'] .= '</div>';
	            $response['htmlData'] .= '<div class="col"><b>Номер гистологии:</b>';
	            $response['htmlData'] .= '<input type="text" class="mysqleditor form-control form-control-lg" name="morph_ident" id="morph_ident" data-action="edit"
			        data-table="'.CAOP_RESEARCH.'"
			        data-assoc="0"
			        data-fieldid="research_id"
			        data-id="'.$ResearchPatient['research_id'].'"
			        data-field="research_morph_ident"
	                data-return="#researchCopyArea'.$ResearchPatient['research_id'].'"
                 	data-returntype="html"
                 	data-returnfunc="research_string"
			        placeholder="Номера"
			        value="'.$ResearchPatient['research_morph_ident'].'">';
	            $response['htmlData'] .= '</div>';
	            $response['htmlData'] .= '</div>';
	            $response['htmlData'] .= '<div class="row">';
	            $response['htmlData'] .= '<div class="col"><b>Текст гистологии:</b>';
	            $morphTextarea = mysqleditor_textarea_generator(
		            CAOP_RESEARCH,
		            'research_id',
		            $ResearchPatient['research_id'],
		            'research_morph_text',
		            '#researchCopyArea'.$ResearchPatient['research_id'],
		            null,
		            'rows="5" data-returntype="html" data-returnfunc="research_string"',
		            'textarea',
		            'mysqleditor form-control form-control-lg',
		            '',
		            'Заключение гистолога',
		            $ResearchPatient['research_morph_text'],
		            'edit',
		            '0');
	            $response['htmlData'] .= $morphTextarea['textarea'];
	            $response['htmlData'] .= '</div>';
	            $response['htmlData'] .= '</div>';
            } else unset($CitologyCancerTypes[1]);
	
	        $CitologyCancerTypes = array_values($CitologyCancerTypes);
	
	        $response['htmlData'] .= '
		<div class="dropdown-divider"></div>
		<div class="row bg-danger">
		    <div class="col">
		        <div class="form-group">
		            <span class="font-weight-bolder">В результате выявлено:</span>
		        </div>
		    </div>
		    <div class="col">';
	
	        for ($index=0; $index<count($CitologyCancerTypes); $index++)
	        {
		        $checked = ( $ResearchPatient['research_cancer'] === $CitologyCancerTypes[$index]['type_id'] ) ? ' checked' : '';
		        $title = $CitologyCancerTypes[$index]['type_title'];
		        $response['htmlData'] .= '
			    <div class="form-group">
		            <input '.$checked.' class="form-check-input mysqleditor" type="radio" name="research_cancer" id="research_cancer_'.$index.'" value="'.$CitologyCancerTypes[$index]['type_id'].'"
		                data-action="edit"
		                data-table="'.CAOP_RESEARCH.'"
		                data-assoc="0"
		                data-fieldid="'.$PK[CAOP_RESEARCH].'"
		                data-id="'.$ResearchPatient[$PK[CAOP_RESEARCH]].'"
		                data-field="research_cancer"
		            >
		            <label class="form-check-label box-label" for="research_cancer_'.$index.'"><span></span>'.$title.'</label>
		        </div>
	        ';
	        }
	
	        $response['htmlData'] .= '
			</div>';
	        $response['htmlData'] .= '
		</div>';
	        $response['htmlData'] .= '<span class="text-muted font-weight-bold">'.$hint.'</span>';
            
        } else
        {
            $response['htmlData'] = 'АШИПКА';
        }



	} else
	{
		$response['msg'] = 'Пациент не найден';
	}

}