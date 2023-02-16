<?php
$response['stage'] = $action;

$response['debug']['$_POST'] = $_POST;

$rid = $_POST['research_id'];

$ResearchData = getarr(CAOP_RESEARCH, "research_id='{$rid}'");

$ResearchCitos = getarr(CAOP_RESEARCH_CITO, "1", "ORDER BY cito_id ASC");

if ( count($ResearchData) > 0 )
{
	$ResearchData = $ResearchData[0];
	
	$ResearchTypes = getarr(CAOP_RESEARCH_TYPES, "type_enabled='1'", "ORDER BY type_order ASC");
	$ResearchTypesId = getDoctorsById($ResearchTypes, 'type_id');
	$ResearchStatuses = getarr(CAOP_RESEARCH_STATUS, "1", "ORDER BY status_id ASC");
	$ResearchTypesHead = getarr(CAOP_RESEARCH_TYPES, "type_enabled='1' AND type_forresearch='1'", "ORDER BY type_order ASC");
	
	$ResearchType = $ResearchTypesId['id' . $ResearchData['research_type']];
	$area = '';
	if ( strlen($ResearchData['research_area']) > 1 ) $area = ' ' . $ResearchData['research_area'];
	$cito = '';
	if ( $ResearchData['research_cito'] == 2 ) $cito = ' (CITO)';
	$resDate = '';
	if ( strlen( $ResearchData['patidcard_patient_done'] ) > 0 )
	{
		$resDate = ' от ' . $ResearchData['patidcard_patient_done'];
	}
	
	$morph = getMorphOfResearch($ResearchData);
	
	$response['htmlData'] = '<button class="btn btn-sm btn-warning btn-removeResearch" data-researchid="'.$ResearchData['research_id'].'">Удалить обследование</button> ';
	
	$response['htmlData'] .= '<img '.super_bootstrap_tooltip('Нажмите, чтобы скопировать данные!').' src="/engine/images/icons/copy.png" class="cursor-pointer clickForCopy" style="width: 32px" data-target="researchCopyAreaInner'.$ResearchData['research_id'].'">';
	$response['htmlData'] .= '<textarea class="class-for-copy" id="researchCopyAreaInner'.$ResearchData['research_id'].'">'.$ResearchType['type_title'].$area.$resDate.' - '.$ResearchData['research_result'].$morph.'</textarea>';
	$response['htmlData'] .= '<hr>';
	
	/*Редактирование:
		МКБ             -   research_ds             +
		Текст диагноза  -   research_ds_text        +
		Тип             -   research_type           +
		Область         -   research_area           +
		CITO            -   research_cito           +
		Дата результата -   patidcard_patient_done  +
		Статус          -   research_status
		Заключение      -   research_result
	*/
	
	$ResearchSelectorModal = 'ОШИБКА ГЕНЕРАЦИИ ВЫБОРА ТИПА ОБСЛЕДОВАНИЯ';
	$ResearchSelectorCitoModal = 'ОШИБКА ГЕНЕРАЦИИ ВЫБОРА CITO-типа';
	$ResearchSelectorStatus = 'ОШИБКА ГЕНЕРАЦИИ ВЫБОРА СТАТУСА';
	
	$zeroOption = array(
		'key'   =>  0,
		'value'    =>  'Выберите...'
	);
	$defaultSelect = array(
		'key'   =>   'type_id',
		'value'   =>  $ResearchData['research_type']
	);
	$mysqleditor_params = 'data-action="edit" data-table="caop_research" data-assoc="0" data-fieldid="research_id" data-id="'.$ResearchData['research_id'].'" data-field="research_type" data-return="#researchCopyArea'.$ResearchData['research_id'].'" data-returntype="html" data-returnfunc="research_string"';
	$ResearchSelector = array2select($ResearchTypesHead, 'type_id', 'type_title', 'research_type', ' class="mysqleditor form-control form-control-lg input-padding research_type_selector required-field" ' . $mysqleditor_params, $zeroOption, $defaultSelect);
	if ( $ResearchSelector['stat'] == RES_SUCCESS )
	{
		$ResearchSelectorModal = $ResearchSelector['result'];
	}
	
	$mysqleditor_params3 = 'data-action="edit" data-table="caop_research" data-assoc="0" data-fieldid="research_id" data-id="'.$ResearchData['research_id'].'" data-field="research_cito" data-return="#researchCopyArea'.$ResearchData['research_id'].'" data-returntype="html" data-returnfunc="research_string"';
	$defaultSelect3 = array(
		'key'   =>   'type_id',
		'value'   =>  $ResearchData['research_cito']
	);
	$ResearchSelectorCito = array2select($ResearchCitos, 'cito_id', 'cito_title', 'research_cito', ' class="mysqleditor form-control form-control-lg input-padding" ' . $mysqleditor_params3, null, $defaultSelect3);
	if ( $ResearchSelectorCito['stat'] == RES_SUCCESS )
	{
		$ResearchSelectorCitoModal = $ResearchSelectorCito['result'];
	}
	
	$mysqleditor_params4 = 'data-action="edit" data-table="caop_research" data-assoc="0" data-fieldid="research_id" data-id="'.$ResearchData['research_id'].'" data-field="research_status" data-return="#researchCopyArea'.$ResearchData['research_id'].'" data-returntype="html" data-returnfunc="research_string"';
	$defaultSelect4 = array(
		'key'   =>   'type_id',
		'value'   =>  $ResearchData['research_status']
	);
	$ResearchSelectorStatus = array2select($ResearchStatuses, 'status_id', 'status_title', 'research_status', ' class="mysqleditor form-control form-control-lg input-padding" ' . $mysqleditor_params4, null, $defaultSelect4);
	if ( $ResearchSelectorStatus['stat'] == RES_SUCCESS )
	{
		$ResearchSelectorStatus = $ResearchSelectorStatus['result'];
	}
	
	$resultTextarea = mysqleditor_textarea_generator(
		$CAOP_RESEARCH,
		'research_id',
		$ResearchData['research_id'],
		'research_result',
		'#researchCopyArea'.$ResearchData['research_id'],
		null,
		'rows="5" data-returntype="html" data-returnfunc="research_string"',
		'textarea',
		'mysqleditor form-control form-control-lg',
		'',
		'Результат обследования',
		$ResearchData['research_result'],
		'edit',
		'0');
	
	
	$response['htmlData'] .= '
	<div class="row">
	    <div class="col-2">
	        <div class="form-group">
	            <label for="research_ds">МКБ:</label>
	            <input
	                type="text"
	                class="mysqleditor form-control form-control-lg mkbDiagnosis required-field"
	                id="research_ds"
	                aria-describedby="research_ds"
	                placeholder="МКБ"
	                value="'.$ResearchData['research_ds'].'"
	                data-action="edit"
	                data-table="'.CAOP_RESEARCH.'"
	                data-assoc="0"
	                data-fieldid="research_id"
	                data-id="'.$ResearchData['research_id'].'"
	                data-field="research_ds"
	                data-return="#researchCopyArea'.$ResearchData['research_id'].'"
                 	data-returntype="html"
                 	data-returnfunc="research_string">
	        </div>
	    </div>
	    <div class="col">
	        <div class="form-group">
	            <label for="research_ds_text">Текст диагноза:</label>
	            <input
	                type="text"
	                class="mysqleditor form-control form-control-lg required-field"
	                id="research_ds_text"
	                aria-describedby="research_ds_text"
	                placeholder="Текст диагноза"
	                value="'.$ResearchData['research_ds_text'].'"
	                data-action="edit"
	                data-table="'.CAOP_RESEARCH.'"
	                data-assoc="0"
	                data-fieldid="research_id"
	                data-id="'.$ResearchData['research_id'].'"
	                data-field="research_ds_text"
	                data-return="#researchCopyArea'.$ResearchData['research_id'].'"
                 	data-returntype="html"
                 	data-returnfunc="research_string">
	        </div>
	    </div>
	</div>
	<div class="row">
		<div class="col">
			<div class="form-group">
	            <label for="research_type">Обследование:</label>
	            '.$ResearchSelectorModal.'
	        </div>
		</div>
		<div class="col">
			<div class="form-group">
	            <label for="research_area">Область:</label>
	            <input
	                type="text"
	                class="mysqleditor form-control form-control-lg"
	                id="research_area"
	                aria-describedby="research_area"
	                placeholder="Область"
	                value="'.$ResearchData['research_area'].'"
	                data-action="edit"
	                data-table="'.CAOP_RESEARCH.'"
	                data-assoc="0"
	                data-fieldid="research_id"
	                data-id="'.$ResearchData['research_id'].'"
	                data-field="research_area"
	                data-return="#researchCopyArea'.$ResearchData['research_id'].'"
                 	data-returntype="html"
                 	data-returnfunc="research_string">
	        </div>
		</div>
		<div class="col">
			<div class="form-group">
	            <label for="research_type">CITO:</label>
	            '.$ResearchSelectorCitoModal.'
	        </div>
		</div>
	</div>
	<div class="row">
		<div class="col">
			<div class="form-group">
	            <label for="patidcard_patient_done">Дата результата:</label>
	            <input
	                type="text"
	                class="mysqleditor form-control form-control-lg russianBirth required-field"
	                id="patidcard_patient_done"
	                aria-describedby="patidcard_patient_done"
	                placeholder="Дата результата"
	                value="'.$ResearchData['patidcard_patient_done'].'"
	                data-action="edit"
	                data-table="'.CAOP_RESEARCH.'"
	                data-assoc="0"
	                data-fieldid="research_id"
	                data-id="'.$ResearchData['research_id'].'"
	                data-field="patidcard_patient_done"
	                data-return="#researchCopyArea'.$ResearchData['research_id'].'"
                 	data-returntype="html"
                 	data-returnfunc="research_string">
	        </div>
		</div>
		<div class="col">
			<div class="form-group">
	            <label for="research_type">Статус:</label>
	            '.$ResearchSelectorStatus.'
	        </div>
		</div>
	</div>
	<div class="row">
		<div class="col">
			<div class="form-group">
	            <label for="patidcard_patient_done">Заключение:</label>
	            '.$resultTextarea['textarea'].'
	        </div>
		</div>
	</div>
	';
	
	$CitologyCancerTypes = getarr(CAOP_CITOLOGY_CANCER_TYPE, 1, "ORDER BY type_order ASC");
	
//	$response['htmlData'] .= debug_ret($CitologyCancerTypes);
	$hint = 'НЕэндоскопические методы НЕ МОГУТ поставить РАК, только ПРЕДРАК!';
	if ( $ResearchType['type_morph'] == 1 )
	{
		$hint = '';
		$response['htmlData'] .= '<b>Морфология (если есть):</b><br/>';
		$response['htmlData'] .= '<div class="row">';
		$response['htmlData'] .= '<div class="col"><b>Дата гистологии:</b>';
		$response['htmlData'] .= '<input type="text" class="mysqleditor form-control form-control-lg russianBirth" name="morph_date" id="morph_date" data-action="edit"
		        data-table="'.CAOP_RESEARCH.'"
		        data-assoc="0"
		        data-fieldid="research_id"
		        data-id="'.$ResearchData['research_id'].'"
		        data-field="research_morph_date"
		        placeholder="Дата"
		        data-return="#researchCopyArea'.$ResearchData['research_id'].'"
                data-returntype="html"
                data-returnfunc="research_string"
		        value="'.$ResearchData['research_morph_date'].'">';
		$response['htmlData'] .= '</div>';
		$response['htmlData'] .= '<div class="col"><b>Номер гистологии:</b>';
		$response['htmlData'] .= '<input type="text" class="mysqleditor form-control form-control-lg" name="morph_ident" id="morph_ident" data-action="edit"
		        data-table="'.CAOP_RESEARCH.'"
		        data-assoc="0"
		        data-fieldid="research_id"
		        data-id="'.$ResearchData['research_id'].'"
		        data-field="research_morph_ident"
		        data-return="#researchCopyArea'.$ResearchData['research_id'].'"
                data-returntype="html"
                data-returnfunc="research_string"
		        placeholder="Номера"
		        value="'.$ResearchData['research_morph_ident'].'">';
		$response['htmlData'] .= '</div>';
		$response['htmlData'] .= '</div>';
		$response['htmlData'] .= '<div class="row">';
		$response['htmlData'] .= '<div class="col"><b>Текст гистологии:</b>';
		$morphTextarea = mysqleditor_textarea_generator(
			CAOP_RESEARCH,
			'research_id',
			$ResearchData['research_id'],
			'research_morph_text',
			'#researchCopyArea'.$ResearchData['research_id'],
			null,
			'rows="5" data-returntype="html" data-returnfunc="research_string"',
			'textarea',
			'mysqleditor form-control form-control-lg',
			'',
			'Заключение гистолога',
			$ResearchData['research_morph_text'],
			'edit',
			'0');
		$response['htmlData'] .= $morphTextarea['textarea'];
		$response['htmlData'] .= '</div>';
		$response['htmlData'] .= '</div>';
	} else unset($CitologyCancerTypes[1]);
	
	$response['htmlData'] .= bt_divider(1);
	
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
		$checked = ( $ResearchData['research_cancer'] === $CitologyCancerTypes[$index]['type_id'] ) ? ' checked' : '';
		$title = $CitologyCancerTypes[$index]['type_title'];
		$response['htmlData'] .= '
			    <div class="form-group">
		            <input '.$checked.' class="form-check-input mysqleditor" type="radio" name="research_cancer" id="research_cancer_'.$index.'" value="'.$CitologyCancerTypes[$index]['type_id'].'"
		                data-action="edit"
		                data-table="'.CAOP_RESEARCH.'"
		                data-assoc="0"
		                data-fieldid="'.$PK[CAOP_RESEARCH].'"
		                data-id="'.$ResearchData[$PK[CAOP_RESEARCH]].'"
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
	$response['result'] = true;
	
} else
{
	$response['msg'] = 'Такой цитологии не существует';
}