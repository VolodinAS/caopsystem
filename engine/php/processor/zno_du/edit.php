<?php
$response['stage'] = $action;
$response['msg'] = 'begin';

$HTTP = $_POST;
extract($HTTP, EXTR_PREFIX_SAME, '_caop');

$response['result'] = true;

$response['htmlData'] = '';

if ( $zno_id )
{
	$response['htmlData'] .= '<button class="btn btn-sm btn-secondary col btn-refreshZNODU" data-znodu="'.$zno_id.'">
		Обновить
	</button>';
	
	$ZNODURM = RecordManipulation($zno_id, CAOP_ZNO_DU, 'zno_id');
	if ( $ZNODURM['result'] )
	{
	    $ZNODUData = $ZNODURM['data'];
	    
	    $PatientData = getPatientDataById($ZNODUData['zno_patient_id'])['data']['personal'];
	    
//	    $response['htmlData'] .= debug_ret($PatientData);
		
//	    $response['htmlData'] .= debug_ret($ZNODUData);
		
		$response['htmlData'] .= '<b>Поиск пациента для постановки на Д-учет:</b>';
		$display = '';
		if ( $ZNODUData['zno_patient_id'] ) $display = ' style="display: none"';
		
		$methodDefault = array(
		    'key' => -1,
		    'value' => 'Выберите...'
		);
		$methodSelected = array(
		    'value' => $ZNODUData['zno_method_type']
		);
		$methodSelector = array2select($ZNODUMorphMethodId, 'morph_id', 'morph_title', 'zno_method_type',
		'class="form-control mysqleditor required-field" data-action="edit"
		id="zno_method_type"
		data-table="'.CAOP_ZNO_DU.'"
		data-assoc="0"
		data-fieldid="zno_id"
		data-id="'.$zno_id.'"
		data-unixfield="zno_update_at"
		data-field="zno_method_type"', $methodDefault, $methodSelected);
		
		$surveyDefault = array(
		    'key' => 0,
		    'value' => 'Выберите...'
		);
		$surveySelected = array(
		    'value' => $ZNODUData['zno_survey_type']
		);
		$surveySelector = array2select($AllSurveys, 'survey_id', 'survey_title', 'zno_survey_type',
		'class="form-control mysqleditor" data-action="edit"
		id="zno_survey_type"
		data-table="'.CAOP_ZNO_DU.'"
		data-assoc="0"
		data-fieldid="zno_id"
		data-id="'.$zno_id.'"
		data-unixfield="zno_update_at"
		data-field="zno_survey_type"', $surveyDefault, $surveySelected);
		
		$TNM = stnmg_parser();
		$TNM_S = $TNM['s'];
		$TNM_T = $TNM['t'];
		$TNM_N = $TNM['n'];
		$TNM_M = $TNM['m'];
//		$TNM_G = $TNM['g'];
		
		$tDefault = array(
			'key' => -1,
			'value' => 'Выберите T...'
		);
		$tSelected = array(
			'value' => $ZNODUData['zno_tnm_t']
		);
		$tSelector = array2select($TNM_T, 'stnmg_code', 'stnmg_code', "zno_tnm_t",
			'class="form-control form-control-sm mysqleditor required-field" data-action="edit"
        id="zno_tnm_t"
        data-table="'.CAOP_ZNO_DU.'"
        data-assoc="0"
        data-fieldid="zno_id"
        data-id="'.$ZNODUData['zno_id'].'"
        data-field="zno_tnm_t"
        data-unixfield="zno_update_at"', $tDefault, $tSelected);
		
		$nDefault = array(
			'key' => -1,
			'value' => 'Выберите N...'
		);
		$nSelected = array(
			'value' => $ZNODUData['zno_tnm_n']
		);
		$nSelector = array2select($TNM_N, 'stnmg_code', 'stnmg_code', "zno_tnm_n",
			'class="form-control form-control-sm mysqleditor required-field" data-action="edit"
        id="zno_tnm_n"
        data-table="'.CAOP_ZNO_DU.'"
        data-assoc="0"
        data-fieldid="zno_id"
        data-id="'.$ZNODUData['zno_id'].'"
        data-field="zno_tnm_n"
        data-unixfield="zno_update_at"', $nDefault, $nSelected);
		
		$mDefault = array(
			'key' => -1,
			'value' => 'Выберите M...'
		);
		$mSelected = array(
			'value' => $ZNODUData['zno_tnm_m']
		);
		$mSelector = array2select($TNM_M, 'stnmg_code', 'stnmg_code', "zno_tnm_m",
			'class="form-control form-control-sm mysqleditor required-field" data-action="edit"
        id="zno_tnm_m"
        data-table="'.CAOP_ZNO_DU.'"
        data-assoc="0"
        data-fieldid="zno_id"
        data-id="'.$ZNODUData['zno_id'].'"
        data-field="zno_tnm_m"
        data-unixfield="zno_update_at"', $mDefault, $mSelected);
		
		$sDefault = array(
			'key' => -1,
			'value' => 'Выберите стадию...'
		);
		$sSelected = array(
			'value' => $ZNODUData['zno_tnm_s']
		);
		$sSelector = array2select($TNM_S, 'stnmg_code', 'stnmg_code', "zno_tnm_s",
			'class="form-control form-control-sm mysqleditor required-field" data-action="edit"
	        id="zno_tnm_m"
	        data-table="'.CAOP_ZNO_DU.'"
	        data-assoc="0"
	        data-fieldid="zno_id"
	        data-id="'.$ZNODUData['zno_id'].'"
	        data-field="zno_tnm_s"
	        data-unixfield="zno_update_at"', $sDefault, $sSelected);
		
		$doctorDefault = array(
		    'key' => 0,
		    'value' => 'Выберите...'
		);
		$doctorSelected = array(
		    'value' => $ZNODUData['zno_doctor_id']
		);
		$doctorSelector = array2select($DoctorsListId, 'doctor_id', 'callback.func_doctor_name', null,
		'class="form-control form-control-sm mysqleditor required-field" data-action="edit"
		id="zno_doctor_id"
		data-table="'.CAOP_ZNO_DU.'"
		data-assoc="0"
		data-fieldid="zno_id"
		data-id="'.$ZNODUData['zno_id'].'"
		data-field="zno_doctor_id"
        data-unixfield="zno_update_at"', $doctorDefault, $doctorSelected);
		
		$response['htmlData'] .= '<form id="znodu_form"'.$display.'>';
		$response['htmlData'] .= '<div class="row">';
		$response['htmlData'] .= '<div class="col">';
		$response['htmlData'] .= '<input class="form-control form-control-sm" type="text" name="patid_name" placeholder="Ф.И.О. пациента" id="patid_name">';
		$response['htmlData'] .= '</div>';
		$response['htmlData'] .= '<div class="col-auto">';
		$response['htmlData'] .= '<button class="btn btn-primary btn-sm btn-znoduSearch">Искать</button>';
		$response['htmlData'] .= '</div>';
		$response['htmlData'] .= '</form>';
		$response['htmlData'] .= '</div>';
		
		
		$response['htmlData'] .= '<input type="hidden" class="mysqleditor" id="patid_id" name="patid_id" value="'.$ZNODUData['zno_patient_id'].'" data-action="edit"
							data-table="'.CAOP_ZNO_DU.'"
							data-assoc="0"
							data-fieldid="zno_id"
							data-id="'.$zno_id.'"
							data-field="zno_patient_id"
							data-unixfield="zno_update_at"
							>';
		$response['htmlData'] .= '<div id="znoduRecord_result">
		<b>ПАЦИЕНТ:</b> '.mb_ucwords($PatientData['patid_name']).', '.$PatientData['patid_birth'].' г.р.<div class="dropdown-divider"></div>
        <button class="btn btn-sm btn-warning btn-znoduChange">Изменить</button>
        <button class="btn btn-sm btn-primary btn-findRS">Поиск маршрутных листов</button>
        <div class="dropdown-divider"></div>
		</div>';
		
//		<div class="form-group">
//	        <label for="zno_apk2" class="font-weight-bolder">Test field:</label>
//			<select id="zno_apk2" class="form-control demo-default" placeholder="Выберите АПК или создайте новое">
//			<option value="">Выберите АПК</option>
//			<option value="4">Thomas Edison</option>
//			<option value="1">Nikola</option>
//			<option value="3">Nikola Tesla</option>
//	    </div>
		
		$response['htmlData'] .= '<div id="znoduPRS_result">

		</div>';
		$response['htmlData'] .= '
		<div class="dropdown-divider"></div>
		<form id="new_zno_du_form">
		 
		    
			<div class="form-group">
		        <label for="zno_apk" class="font-weight-bolder">АПК:</label>
		        <input
		        	type="text"
			        class="form-control required-field mysqleditor"
			        id="zno_apk"
			        name="zno_apk"
			        placeholder="Введите АПК: 4097, 4037 и т.д."
			        data-action="edit"
			        data-table="'.CAOP_ZNO_DU.'"
			        data-assoc="0"
			        data-fieldid="zno_id"
			        data-id="'.$ZNODUData['zno_id'].'"
			        data-field="zno_apk"
			        data-unixfield="zno_update_at"
			        value="'.$ZNODUData['zno_apk'].'"
		        >
		    </div>
		    
			<div class="form-group">
		        <label for="zno_doctor_id" class="font-weight-bolder">Врач, установивший диагноз:</label>
		        '.$doctorSelector['result'].'
		    </div>
		    
		    <div class="form-group">
		        <label for="zno_date_first_visit_caop" class="font-weight-bolder">Первое обращение в ЦАОП:</label>
		        <input
		            type="text"
		            class="form-control required-field mysqleditor russianBirth"
		            id="zno_date_first_visit_caop"
		            name="zno_date_first_visit_caop"
		            placeholder="Дата"
			        data-action="edit"
			        data-table="'.CAOP_ZNO_DU.'"
			        data-assoc="0"
			        data-fieldid="zno_id"
			        data-id="'.$ZNODUData['zno_id'].'"
			        data-field="zno_date_first_visit_caop"
			        data-unixfield="zno_update_at"
			        data-adequate="DATETOUNIX"
			        value="'.dateme(DMY, $ZNODUData['zno_date_first_visit_caop']).'"
		        >
		    </div>
		    
		    <div class="form-group">
		        <label for="zno_date_set_zno" class="font-weight-bolder">Дата установки диагноза:</label>
		        <input
		            type="text"
		            class="form-control required-field mysqleditor russianBirth"
		            id="zno_date_set_zno"
		            name="zno_date_set_zno"
		            placeholder="Дата"
			        data-action="edit"
			        data-table="'.CAOP_ZNO_DU.'"
			        data-assoc="0"
			        data-fieldid="zno_id"
			        data-id="'.$ZNODUData['zno_id'].'"
			        data-field="zno_date_set_zno"
			        data-unixfield="zno_update_at"
			        data-adequate="DATETOUNIX"
			        value="'.dateme(DMY, $ZNODUData['zno_date_set_zno']).'"
		        >
		    </div>
		    
		    <div class="form-group">
		        <label for="zno_diagnosis_mkb" class="font-weight-bolder">Код по МКБ:</label>
		        <input
					type="text"
					class="form-control required-field mkbDiagnosis mysqleditor"
					id="zno_diagnosis_mkb"
					name="zno_diagnosis_mkb"
					placeholder="Код по МКБ"
					data-action="edit"
					data-table="'.CAOP_ZNO_DU.'"
					data-assoc="0"
					data-fieldid="zno_id"
					data-id="'.$ZNODUData['zno_id'].'"
					data-field="zno_diagnosis_mkb"
					data-unixfield="zno_update_at"
					value="'.$ZNODUData['zno_diagnosis_mkb'].'"
					data-adequate="MKB"
					data-return="#zno_diagnosis_mkb"
					data-returntype="input"
					data-returnfunc="value"
		        >
		    </div>
		    
		    <div class="form-group">
		        <label for="zno_diagnosis_text" class="font-weight-bolder">Текст диагноза:</label>
		        <textarea
		            class="form-control mysqleditor"
		            id="zno_diagnosis_text"
		            name="zno_diagnosis_text"
		            placeholder="Текст диагноза"
			        data-action="edit"
			        data-table="'.CAOP_ZNO_DU.'"
			        data-assoc="0"
			        data-fieldid="zno_id"
			        data-id="'.$ZNODUData['zno_id'].'"
			        data-field="zno_diagnosis_text"
			        data-unixfield="zno_update_at"
		        >'.$ZNODUData['zno_diagnosis_text'].'</textarea>
		    </div>
		    
		    <div class="form-group">
		        <label for="zno_tnm_t" class="font-weight-bolder">Стадия TNM - Выберите T:</label>
		        '.$tSelector['result'].'
		    </div>
		    
		    <div class="form-group">
		        <label for="zno_tnm_n" class="font-weight-bolder">Стадия TNM - Выберите N:</label>
		        '.$nSelector['result'].'
		    </div>
		    
		    <div class="form-group">
		        <label for="zno_tnm_m" class="font-weight-bolder">Стадия TNM - Выберите M:</label>
		        '.$mSelector['result'].'
		    </div>
		    
		    <div class="form-group">
		        <label for="zno_tnm_s" class="font-weight-bolder">Стадия:</label>
		        '.$sSelector['result'].'
		    </div>
		    
		    <div class="form-group">
		        <label for="zno_method_type" class="font-weight-bolder">Метод верификации:</label>
		        '.$methodSelector['result'].'
		    </div>
		    
		    <div class="form-group">
		        <label for="zno_survey_type" class="font-weight-bolder">Обследование:</label>
		        '.$surveySelector['result'].'
		    </div>
		    
		    <div class="form-group">
		        <label for="zno_method_date" class="font-weight-bolder">Дата верификации:</label>
		        <input
		            type="text"
		            class="form-control required-field mysqleditor russianBirth"
		            id="zno_method_date"
		            name="zno_method_date"
		            placeholder="Дата"
			        data-action="edit"
			        data-table="'.CAOP_ZNO_DU.'"
			        data-assoc="0"
			        data-fieldid="zno_id"
			        data-id="'.$ZNODUData['zno_id'].'"
			        data-field="zno_method_date"
			        data-unixfield="zno_update_at"
			        data-adequate="DATETOUNIX"
			        value="'.dateme(DMY, $ZNODUData['zno_method_date']).'"
		        >
		    </div>
		    
		    <div class="form-group">
		        <label for="zno_date_dir_in_gop" class="font-weight-bolder">Дата направления в ГОП:</label>
		        <input
		            type="text"
		            class="form-control required-field mysqleditor russianBirth"
		            id="zno_date_dir_in_gop"
		            name="zno_date_dir_in_gop"
		            placeholder="Дата"
			        data-action="edit"
			        data-table="'.CAOP_ZNO_DU.'"
			        data-assoc="0"
			        data-fieldid="zno_id"
			        data-id="'.$ZNODUData['zno_id'].'"
			        data-field="zno_date_dir_in_gop"
			        data-unixfield="zno_update_at"
			        data-adequate="DATETOUNIX"
			        value="'.dateme(DMY, $ZNODUData['zno_date_dir_in_gop']).'"
		        >
		    </div>
		    
		    <div class="form-group">
		        <label for="zno_date_issue_notice" class="font-weight-bolder">Дата оформления извещения:</label>
		        <input
		            type="text"
		            class="form-control required-field mysqleditor russianBirth"
		            id="zno_date_issue_notice"
		            name="zno_date_issue_notice"
		            placeholder="Дата"
			        data-action="edit"
			        data-table="'.CAOP_ZNO_DU.'"
			        data-assoc="0"
			        data-fieldid="zno_id"
			        data-id="'.$ZNODUData['zno_id'].'"
			        data-field="zno_date_issue_notice"
			        data-unixfield="zno_update_at"
			        data-adequate="DATETOUNIX"
			        value="'.dateme(DMY, $ZNODUData['zno_date_issue_notice']).'"
		        >
		    </div>
		    
		    <div class="form-group">
		        <label for="zno_comment" class="font-weight-bolder">Комментарий:</label>
		        <textarea
		            class="form-control mysqleditor"
		            id="zno_comment"
		            name="zno_comment"
		            placeholder="Ваш комментарий"
			        data-action="edit"
			        data-table="'.CAOP_ZNO_DU.'"
			        data-assoc="0"
			        data-fieldid="zno_id"
			        data-id="'.$ZNODUData['zno_id'].'"
			        data-field="zno_comment"
			        data-unixfield="zno_update_at"
		        >'.$ZNODUData['zno_comment'].'</textarea>
		    </div>
		</form>
		
		<div class="dropdown-divider"></div>
		<button class="btn btn-lg btn-primary col btn-doneZNODU">ГОТОВО</button>
		
		<div class="dropdown-divider"></div>
		<button class="btn btn-lg btn-warning col btn-resetZNODU" data-znodu="'.$zno_id.'">Сбросить данные</button>
		
		<div class="dropdown-divider"></div>
		<button class="btn btn-sm btn-danger col btn-removeZNODU" data-znodu="'.$zno_id.'">Удалить запись</button>
		';
	 
	
	} else $response['msg'] = $ZNODURM['msg'];
} else $response['msg'] = 'Не указан ID записи';