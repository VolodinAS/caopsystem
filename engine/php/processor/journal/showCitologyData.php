<?php
$response['stage'] = $action;

$response['debug']['$_POST'] = $_POST;

$cid = $_POST['citology_id'];

$CitologyData = getarr(CAOP_CITOLOGY, "citology_id='{$cid}'");
$CitologyTypes = getarr(CAOP_CITOLOGY_TYPE, 1, "ORDER BY type_id ASC");
$CitologyTypesId = getDoctorsById($CitologyTypes, 'type_id');

if (count($CitologyData) > 0)
{
	$CitologyData = $CitologyData[0];
	$response['htmlData'] = '';
	
	
	/*Редактирование:
		МКБ             -   citology_ds_mkb         +
		Тип             -   citology_analise_type   +
		Дата проведения -   citology_action_date    +
		Дата результата -   patidcard_patient_done
		Заключение      -
	*/
	
	$PatientRM = RecordManipulation($CitologyData['citology_patid'], CAOP_PATIENTS, 'patid_id');
	if ($PatientRM['result'])
	{
		$PatientData = $PatientRM['data'];
		
		$response['debug']['$PatientData'] = $PatientData;
		
		$response['htmlData'] = '<button class="btn btn-sm btn-warning btn-removeCitology" data-citologyid="'.$CitologyData['citology_id'].'">Удалить цитологию</button> ';
		
		$citology_type = '';
		if ($CitologyData['citology_analise_type'] > 0)
		{
			$citology_type = ' ('.$CitologyTypesId['id' . $CitologyData['citology_analise_type']]['type_title'].')';
		}
		
		$response['htmlData'] .= '<img ' . super_bootstrap_tooltip('Нажмите, чтобы скопировать данные!') . ' src="/engine/images/icons/copy.png" class="cursor-pointer clickForCopy" style="width: 32px" data-target="citologyCopy' . $CitologyData['citology_id'] . '">';
		$response['htmlData'] .= '<textarea class="class-for-copy" id="citologyCopy' . $CitologyData['citology_id'] . '">Цитология'.$citology_type.' №' . $CitologyData['citology_result_id'] . ' от ' . $CitologyData['patidcard_patient_done'] . ' - ' . $CitologyData['citology_result_text'] . '</textarea>';
		$response['htmlData'] .= '<hr>';
		
		$CitologySelectorModal = 'ОШИБКА ГЕНЕРАЦИИ ВЫБОРА ТИПА ЦИТОЛОГИИ';
		$zeroOption_analize = array(
			'key' => 0,
			'value' => 'ВЫБЕРИТЕ'
		);
		$defaultSelect_analize = array(
			'key' => 'type_id',
			'value' => $CitologyData['citology_analise_type']
		);
		$mysqleditor_params_analize = 'data-action="edit" data-table="' . CAOP_CITOLOGY . '" data-assoc="0" data-fieldid="citology_id" data-id="' . $CitologyData['citology_id'] . '" data-field="citology_analise_type" data-return="#citologyCopy' . $CitologyData['citology_id'] . '" data-returntype="html" data-returnfunc="citology_string"';
		$CitologySelectorAnalize = array2select($CitologyTypes, 'type_id', 'type_title', 'citology_analise_type', ' class="mysqleditor form-control form-control-lg" ' . $mysqleditor_params_analize, $zeroOption_analize, $defaultSelect_analize);
		
		if ($CitologySelectorAnalize['stat'] == RES_SUCCESS)
			$CitologySelectorModal = $CitologySelectorAnalize['result'];
		
		$response['htmlData'] .= '
			<div class="row">
				<div class="col">
				'.bt_notice(wrapper(mb_ucwords($PatientData['patid_name'])).', '.$PatientData['patid_birth'], BT_THEME_SUCCESS, 1).'
				</div>
			</div>
			<div class="row">
			    <div class="col-2">
			        
			        <div class="form-group">
			            <label for="citology_ds_mkb">МКБ:</label>
			            <input
			                type="text"
			                class="mysqleditor form-control form-control-lg mkbDiagnosis required-field"
			                id="citology_ds_mkb"
			                aria-describedby="citology_ds_mkb"
			                placeholder="Диагноз"
			                value="' . $CitologyData['citology_ds_mkb'] . '"
			                data-action="edit"
			                data-table="' . CAOP_CITOLOGY . '"
			                data-assoc="0"
			                data-fieldid="citology_id"
			                data-id="' . $CitologyData['citology_id'] . '"
			                data-field="citology_ds_mkb"
			                data-return="#citologyCopy' . $CitologyData['citology_id'] . '"
			                data-returntype="html"
			                data-returnfunc="citology_string">
			        </div>
			    </div>
			    <div class="col">
			        <div class="form-group">
			            <label for="citology_ds_text">Текст диагноза:</label>
			            <input
			                type="text"
			                class="mysqleditor form-control form-control-lg required-field"
			                id="citology_ds_text"
			                aria-describedby="citology_ds_text"
			                placeholder="Текст диагноза"
			                value="' . $CitologyData['citology_ds_text'] . '"
			                data-action="edit"
			                data-table="' . CAOP_CITOLOGY . '"
			                data-assoc="0"
			                data-fieldid="citology_id"
			                data-id="' . $CitologyData['citology_id'] . '"
			                data-field="citology_ds_text"
			                data-return="#citologyCopy' . $CitologyData['citology_id'] . '"
			                data-returntype="html"
			                data-returnfunc="citology_string">
			        </div>
			    </div>
			</div>
			';
		
		$response['htmlData'] .= '
			<div class="row">
			    <div class="col">
			        <div class="form-group">
			            <label for="citology_analise_type">Тип цитологии:</label>
						' . $CitologySelectorModal . '
			        </div>
			    </div>
			</div>
			';
		
		
		$citology_action_date = mysqleditor_field_generator(
			$CAOP_CITOLOGY,
			'citology_id',
			$CitologyData['citology_id'],
			'citology_action_date',
			0,
			'',
			'',
			'input',
			'text',
			'mysqleditor form-control form-control-lg russianBirth',
			'',
			'Дата проведения',
			$CitologyData['citology_action_date'],
			'edit',
			'0');

		$citology_number = mysqleditor_field_generator(
			$CAOP_CITOLOGY,
			'citology_id',
			$CitologyData['citology_id'],
			'citology_result_id',
			0,
			'',
			'',
			'input',
			'text',
			'mysqleditor form-control form-control-lg',
			'',
			'Номер стёкол',
			$CitologyData['citology_result_id'],
			'edit',
			'0');
		
		$citology_result_date = mysqleditor_field_generator(
			$CAOP_CITOLOGY,
			'citology_id',
			$CitologyData['citology_id'],
			'patidcard_patient_done',
			0,
			'',
			'',
			'input',
			'text',
			'mysqleditor form-control form-control-lg russianBirth',
			'',
			'Дата результата',
			$CitologyData['patidcard_patient_done'],
			'edit',
			'0');
		
		$response['htmlData'] .= '
			<div class="row">
			    <div class="col">
			        <div class="form-group">
			            <label for="journal_ds">Дата проведения:</label>
			            ' . $citology_action_date['input'] . '
			        </div>
			    </div>
			    <div class="col">
			        <div class="form-group">
			            <label for="journal_ds">Номер стёкол:</label>
			            ' . $citology_number['input'] . '
			        </div>
			    </div>
			    <div class="col">
			        <div class="form-group">
			            <label for="journal_ds">Дата результата:</label>
			            ' . $citology_result_date['input'] . '
			        </div>
			    </div>
			</div>
			';
		
		$response['htmlData'] .= '
			<div class="row">
			    <div class="col">
			        <div class="form-group">
			            <label for="citology_result_text">Заключение:</label>
			            <input
			                type="text"
			                class="mysqleditor form-control form-control-lg required-field"
			                id="citology_result_text"
			                aria-describedby="citology_result_text"
			                placeholder="Заключение"
			                value="' . $CitologyData['citology_result_text'] . '"
			                data-action="edit"
			                data-table="' . CAOP_CITOLOGY . '"
			                data-assoc="0"
			                data-fieldid="citology_id"
			                data-id="' . $CitologyData['citology_id'] . '"
			                data-field="citology_result_text"
			                data-return="#citologyCopy' . $CitologyData['citology_id'] . '"
			                data-returntype="html"
			                data-returnfunc="citology_string">
			        </div>
			    </div>
			</div>
			';
		
		$CitologyCancerTypes = getarr(CAOP_CITOLOGY_CANCER_TYPE, 1, "ORDER BY type_order ASC");
		
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
			$checked = ( $CitologyData['citology_cancer'] === $CitologyCancerTypes[$index]['type_id'] ) ? ' checked' : '';
		    $title = $CitologyCancerTypes[$index]['type_title'];
		    $response['htmlData'] .= '
			    <div class="form-group">
		            <input '.$checked.' class="form-check-input mysqleditor" type="radio" name="citolory_cancer" id="citolory_cancer_'.$index.'" value="'.$CitologyCancerTypes[$index]['type_id'].'"
		                data-action="edit"
		                data-table="'.CAOP_CITOLOGY.'"
		                data-assoc="0"
		                data-fieldid="'.$PK[CAOP_CITOLOGY].'"
		                data-id="'.$CitologyData[$PK[CAOP_CITOLOGY]].'"
		                data-field="citology_cancer"
		            >
		            <label class="form-check-label box-label" for="citolory_cancer_'.$index.'"><span></span>'.$title.'</label>
		        </div>
	        ';
		}
		
		$response['htmlData'] .= '
			</div>';
		$response['htmlData'] .= '
		</div>';


//	$response['htmlData'] .= debug_ret($CitologyData);
		
		$response['result'] = true;
		
	} else $response['msg'] = $PatientRM['msg'];
	
	
} else
{
	$response['msg'] = 'Такой цитологии не существует';
}