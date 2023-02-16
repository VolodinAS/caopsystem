<?php
$response['stage'] = $action;

$patid_id = $_POST['patid_id'];

$PatientPersonalData = getarr(CAOP_PATIENTS, "patid_id='{$patid_id}'");
if (count($PatientPersonalData) == 1)
{
	$CompanyList = getarr(CAOP_INSURANCE, "insurance_enabled='1'", "ORDER BY insurance_title ASC");
	$CompanyListId = getDoctorsById($CompanyList, 'insurance_id');
	
	$CaseStatusesList = getarr(CAOP_CASESTATUS, "casestatus_enabled='1'", "ORDER BY casestatus_order ASC");
	$CaseStatusesListId = getDoctorsById($CaseStatusesList, 'casestatus_id');
	
	$DispLPU = getarr(CAOP_DISP_LPU, 1, "ORDER BY lpu_order ASC");
	$DispLPUId = getDoctorsById($DispLPU, 'lpu_id');
	
	$PatientPersonalData = $PatientPersonalData[0];
	
	$Cancer = getarr(CAOP_CANCER, "cancer_patid='{$PatientPersonalData['patid_id']}'", "ORDER BY cancer_order_number ASC");
	
	$cancer_notice = '';
	if (count($Cancer) > 0)
	{
		$cancer_notice = bt_notice('У пациента выявлено злокачественное новообразование', BT_THEME_DANGER, 1);
	}
	
	$patid_isDead_checked = ($PatientPersonalData['patid_isDead'] == 1) ? "checked" : "";
	
	$response['debug']['$cancer_notice'] = $cancer_notice;
	
	$LpuDefault = array(
	    'key' => 0,
	    'value' => 'Выберите...'
	);
	$LpuSelected = array(
	    'value' => $PatientPersonalData['patid_pin_lpu_id']
	);
	$LpuSelector = array2select($DispLPUId, 'lpu_id', 'lpu_shortname', null,
	'class="form-control mysqleditor form-control-lg" data-action="edit"
	data-table="'.CAOP_PATIENTS.'"
	data-assoc="0"
	data-fieldid="'.$PK[CAOP_PATIENTS].'"
	data-id="'.$PatientPersonalData['patid_id'].'"
	data-field="patid_pin_lpu_id"', $LpuDefault, $LpuSelected);
//	echo $a2sSelector['result'];
	
	
	$response['result'] = true;
	$response['htmlData'] .= '
' . $cancer_notice . '
<div class="row">
    <div class="col">
        <div class="form-group">
            <div class="input-group">
				<div class="input-group-prepend">
					<span class="input-group-text" id="ig_patid_ident" ' . super_bootstrap_tooltip('Номер карты') . '>
						<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-card-heading" viewBox="0 0 16 16">
							<path d="M14.5 3a.5.5 0 0 1 .5.5v9a.5.5 0 0 1-.5.5h-13a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h13zm-13-1A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2h-13z"/>
							<path d="M3 8.5a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h6a.5.5 0 0 1 0 1h-6a.5.5 0 0 1-.5-.5zm0-5a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-9a.5.5 0 0 1-.5-.5v-1z"/>
						</svg>
					</span>
				</div>
				<input
					aria-label="Номер карты"
					aria-describedby="ig_patid_ident"
	                type="text" 
	                class="mysqleditor form-control form-control-lg required-field" 
	                id="patid_ident"
	                placeholder="Номер карты"
	                value="' . $PatientPersonalData['patid_ident'] . '" 
	                data-action="edit" 
	                data-table="caop_patients" 
	                data-assoc="0" 
	                data-fieldid="patid_id" 
	                data-id="' . $PatientPersonalData['patid_id'] . '" 
	                data-field="patid_ident" 
	                data-return="0">
			</div>    
        </div>
    </div>
    <div class="col align-right">
        <div class="form-group">                
            <div class="input-group">
				<div class="input-group-prepend">
					<span class="input-group-text" id="ig_UNIQUE" ' . super_bootstrap_tooltip('Уникальный ID из базы данных') . '>
						<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-exclamation-square-fill" viewBox="0 0 16 16">
					        <path d="M2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2zm6 4c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 4.995A.905.905 0 0 1 8 4zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
						</svg>
					</span>
				</div>
				<input
					aria-label="UNIQUE"
					aria-describedby="ig_UNIQUE"
		            type="text" 
		            class="form-control form-control-lg" 
		            id="UNIQUE"
		            placeholder="UNIQUE-DB-ID"
		            value="' . $PatientPersonalData['patid_id'] . '" disabled>
			</div>    
        </div>
    </div>
</div>

<div class="row">
    <div class="col">
        <div class="form-group">
            <div class="input-group">
				<div class="input-group-prepend">
					<span class="input-group-text" id="ig_patid_name" ' . super_bootstrap_tooltip('Ф.И.О. пациента') . '>
						<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-circle" viewBox="0 0 16 16">
							<path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"/>
							<path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1z"/>
						</svg>
					</span>
				</div>
				<input
					aria-label="Ф.И.О."
					aria-describedby="ig_patid_name"
	                type="text" 
	                class="mysqleditor form-control form-control-lg" 
	                id="patid_name"
	                placeholder="Ф.И.О. пациента"
	                value="' . htmlspecialchars($PatientPersonalData['patid_name']) . '" 
	                data-action="edit" 
	                data-table="caop_patients" 
	                data-assoc="0" 
	                data-fieldid="patid_id" 
	                data-id="' . htmlspecialchars($PatientPersonalData['patid_id']) . '" 
	                data-field="patid_name" 
	                data-return="0">
			</div>    
        </div>
    </div>
    <div class="col align-right">
        <div class="form-group">
            <div class="input-group">
				<div class="input-group-prepend">
					<span class="input-group-text" id="ig_patid_birth" ' . super_bootstrap_tooltip('Дата рождения') . '>
						<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-calendar-fill" viewBox="0 0 16 16">
							<path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V5h16V4H0V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5z"/>
						</svg>
					</span>
				</div>
				<input
					aria-label="Дата рождения"
					aria-describedby="ig_patid_birth"
	                type="text" 
	                class="mysqleditor form-control form-control-lg russianBirth" 
	                id="patid_birth"
	                placeholder="Дата рождения"
	                value="' . $PatientPersonalData['patid_birth'] . '" 
	                data-action="edit" 
	                data-table="caop_patients" 
	                data-assoc="0" 
	                data-fieldid="patid_id" 
	                data-id="' . htmlspecialchars($PatientPersonalData['patid_id']) . '" 
	                data-field="patid_birth"
	                data-return="0">
			</div>    
                
                
        </div>
    </div>
</div>

<div class="row">
    <div class="col">
        <div class="form-group"> 
            <div class="input-group">
				<div class="input-group-prepend">
					<span class="input-group-text" id="ig_patid_address" ' . super_bootstrap_tooltip('Адрес') . '>
						<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-house-fill" viewBox="0 0 16 16">
							<path fill-rule="evenodd" d="m8 3.293 6 6V13.5a1.5 1.5 0 0 1-1.5 1.5h-9A1.5 1.5 0 0 1 2 13.5V9.293l6-6zm5-.793V6l-2-2V2.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5z"/>
							<path fill-rule="evenodd" d="M7.293 1.5a1 1 0 0 1 1.414 0l6.647 6.646a.5.5 0 0 1-.708.708L8 2.207 1.354 8.854a.5.5 0 1 1-.708-.708L7.293 1.5z"/>
						</svg>
					</span>
				</div>
				<input
					aria-label="Адрес"
					aria-describedby="ig_patid_address"
	                type="text" 
	                class="mysqleditor form-control form-control-lg required-field" 
	                id="patid_address"
	                placeholder="Адрес"
	                value="' . $PatientPersonalData['patid_address'] . '" 
	                data-action="edit" 
	                data-table="caop_patients" 
	                data-assoc="0" 
	                data-fieldid="patid_id" 
	                data-id="' . htmlspecialchars($PatientPersonalData['patid_id']) . '" 
	                data-field="patid_address" 
	                data-return="0">
			</div>    
        </div>
    </div>
    <div class="col align-right">
        <div class="form-group">
            <div class="input-group">
				<div class="input-group-prepend">
					<span class="input-group-text" id="ig_patid_phone" ' . super_bootstrap_tooltip('Телефон') . '>
						<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-telephone-fill" viewBox="0 0 16 16">
							<path fill-rule="evenodd" d="M1.885.511a1.745 1.745 0 0 1 2.61.163L6.29 2.98c.329.423.445.974.315 1.494l-.547 2.19a.678.678 0 0 0 .178.643l2.457 2.457a.678.678 0 0 0 .644.178l2.189-.547a1.745 1.745 0 0 1 1.494.315l2.306 1.794c.829.645.905 1.87.163 2.611l-1.034 1.034c-.74.74-1.846 1.065-2.877.702a18.634 18.634 0 0 1-7.01-4.42 18.634 18.634 0 0 1-4.42-7.009c-.362-1.03-.037-2.137.703-2.877L1.885.511z"/>
						</svg>
					</span>
				</div>
				<input
					aria-label="Телефон"
					aria-describedby="ig_patid_phone"
	                type="text" 
	                class="mysqleditor form-control form-control-lg required-field" 
	                id="patid_phone"
	                placeholder="Телефон"
	                value="' . $PatientPersonalData['patid_phone'] . '" 
	                data-action="edit" 
	                data-table="caop_patients" 
	                data-assoc="0" 
	                data-fieldid="patid_id" 
	                data-id="' . $PatientPersonalData['patid_id'] . '" 
	                data-field="patid_phone" 
	                data-return="0">
			</div>
        </div>
    </div>
</div>		

<div class="row">
    <div class="col">
        <div class="form-group">
            <div class="input-group">
				<div class="input-group-prepend">
					<span class="input-group-text" id="ig_patid_insurance_number" ' . super_bootstrap_tooltip('Номер страхового полиса') . '>
						<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-linkedin" viewBox="0 0 16 16">
							<path d="M0 1.146C0 .513.526 0 1.175 0h13.65C15.474 0 16 .513 16 1.146v13.708c0 .633-.526 1.146-1.175 1.146H1.175C.526 16 0 15.487 0 14.854V1.146zm4.943 12.248V6.169H2.542v7.225h2.401zm-1.2-8.212c.837 0 1.358-.554 1.358-1.248-.015-.709-.52-1.248-1.342-1.248-.822 0-1.359.54-1.359 1.248 0 .694.521 1.248 1.327 1.248h.016zm4.908 8.212V9.359c0-.216.016-.432.08-.586.173-.431.568-.878 1.232-.878.869 0 1.216.662 1.216 1.634v3.865h2.401V9.25c0-2.22-1.184-3.252-2.764-3.252-1.274 0-1.845.7-2.165 1.193v.025h-.016a5.54 5.54 0 0 1 .016-.025V6.169h-2.4c.03.678 0 7.225 0 7.225h2.4z"/>
						</svg>
					</span>
				</div>
				<input
					aria-label="Номер страхового полиса"
					aria-describedby="ig_patid_insurance_number"
	                type="text" 
	                class="mysqleditor form-control form-control-lg required-field" 
	                id="patid_insurance_number"
	                placeholder="Номер страхового полиса"
	                value="' . $PatientPersonalData['patid_insurance_number'] . '" 
	                data-action="edit" 
	                data-table="caop_patients" 
	                data-assoc="0" 
	                data-fieldid="patid_id" 
	                data-id="' . htmlspecialchars($PatientPersonalData['patid_id']) . '" 
	                data-field="patid_insurance_number" 
	                data-return="0">
			</div>
        </div>
    </div>
    <div class="col align-right">
        <div class="form-group">
            <div class="input-group">
				<div class="input-group-prepend">
					<span class="input-group-text" id="ig_patid_insurance_company" ' . super_bootstrap_tooltip('Страховая компания') . '>
						<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-safe-fill" viewBox="0 0 16 16">
							<path d="M9.778 9.414A2 2 0 1 1 6.95 6.586a2 2 0 0 1 2.828 2.828z"/>
							<path d="M2.5 0A1.5 1.5 0 0 0 1 1.5V3H.5a.5.5 0 0 0 0 1H1v3.5H.5a.5.5 0 0 0 0 1H1V12H.5a.5.5 0 0 0 0 1H1v1.5A1.5 1.5 0 0 0 2.5 16h12a1.5 1.5 0 0 0 1.5-1.5v-13A1.5 1.5 0 0 0 14.5 0h-12zm3.036 4.464 1.09 1.09a3.003 3.003 0 0 1 3.476 0l1.09-1.09a.5.5 0 1 1 .707.708l-1.09 1.09c.74 1.037.74 2.44 0 3.476l1.09 1.09a.5.5 0 1 1-.707.708l-1.09-1.09a3.002 3.002 0 0 1-3.476 0l-1.09 1.09a.5.5 0 1 1-.708-.708l1.09-1.09a3.003 3.003 0 0 1 0-3.476l-1.09-1.09a.5.5 0 1 1 .708-.708zM14 6.5v3a.5.5 0 0 1-1 0v-3a.5.5 0 0 1 1 0z"/>
						</svg>
					</span>
				</div>
				[patid_insurance_company]
			</div>';
	$mep = 'data-action="edit" data-table="' . CAOP_PATIENTS . '" data-assoc="0" data-fieldid="patid_id" data-id="' . $PatientPersonalData['patid_id'] . '" data-field="patid_insurance_company"';
	$defaultArr = array(
		'key' => '0',
		'value' => 'ВЫБЕРИТЕ'
	);
	$defaultSelect = array(
		'value' => $PatientPersonalData['patid_insurance_company']
	);
	$CompanyListSelector = array2select($CompanyList, 'insurance_id', 'insurance_title', 'patid_insurance_company', ' aria-describedby="ig_patid_insurance_company" aria-label="Название страховой компании" id="patid_insurance_company" class="mysqleditor form-control form-control-lg" ' . $mep, $defaultArr, $defaultSelect);
	if ($CompanyListSelector['stat'] == RES_SUCCESS)
	{
		$response['htmlData'] = str_replace('[patid_insurance_company]', $CompanyListSelector['result'], $response['htmlData']);
		//		$response['htmlData'] .= $CompanyListSelector['result'];
	}
	
	$response['htmlData'] .= '
        </div>
    </div>
</div>		

<div class="row">
    <div class="col">
        <div class="form-group">
            <div class="input-group">
				<div class="input-group-prepend">
					<span class="input-group-text" id="ig_patid_snils" ' . super_bootstrap_tooltip('СНИЛС') . '>
						'.BT_ICON_SNILS.'
					</span>
				</div>
				<input
					aria-label="СНИЛС"
					aria-describedby="ig_patid_snils"
	                type="text"
	                class="mysqleditor form-control form-control-lg required-field snils"
	                id="patid_snils"
	                placeholder="СНИЛС"
	                value="' . $PatientPersonalData['patid_snils'] . '"
	                data-action="edit"
	                data-table="caop_patients"
	                data-assoc="0"
	                data-fieldid="patid_id"
	                data-id="' . htmlspecialchars($PatientPersonalData['patid_id']) . '"
	                data-field="patid_snils"
	                data-return="0">
			</div>
        </div>
    </div>
    <div class="col">
	   <div class="form-group">
            <div class="input-group">
				<div class="input-group-prepend">
					<span class="input-group-text" id="ig_patid_lpu_pin" ' . super_bootstrap_tooltip('ЛПУ прикрепления') . '>
						'.BT_ICON_BUILD.'
					</span>
				</div>
				'.$LpuSelector['result'].'
			</div>
        </div>
	</div>
</div>

';
	$response['htmlData'] .= '
	<div class="row">
		<div class="col text-right">
			<input ' . $patid_isDead_checked . ' class="form-check-input mysqleditor" type="checkbox" name="ig_patid_isDead" id="ig_patid_isDead" value="1" data-action="edit"
			data-table="' . CAOP_PATIENTS . '"
			data-assoc="0" 
			data-fieldid="patid_id" 
			data-id="' . htmlspecialchars($PatientPersonalData['patid_id']) . '" 
			data-field="patid_isDead">
			<label class="form-check-label box-label" for="ig_patid_isDead"><span></span><b>Умерший пациент</b></label>
		</div>
		<div class="col">
	        <div class="form-group">
	            <div class="input-group">
					<div class="input-group-prepend">
						<span class="input-group-text" id="ig_patid_exitus_date" ' . super_bootstrap_tooltip('Дата смерти пациента') . '>
							' . BT_ICON_PATIENT_DEAD . '
						</span>
					</div>
					<input
						aria-label="Дата смерти пациента"
						aria-describedby="ig_patid_exitus_date"
		                type="text"
		                class="mysqleditor form-control form-control-lg russianBirth"
		                id="patid_exitus_date"
		                placeholder="Дата смерти пациента"
		                value="' . $PatientPersonalData['patid_exitus_date'] . '"
		                data-action="edit"
		                data-table="caop_patients"
		                data-assoc="0"
		                data-fieldid="patid_id"
		                data-id="' . htmlspecialchars($PatientPersonalData['patid_id']) . '"
		                data-field="patid_exitus_date"
		                data-return="0">
				</div>
	        </div>
    	</div>
	</div>
	<div class="row">
	    <div class="col">
	        <div class="form-group">
	            <div class="input-group">
					<div class="input-group-prepend">
						<span class="input-group-text" id="ig_patid_name_maiden" ' . super_bootstrap_tooltip('Девичья фамилия') . '>
							'.BT_ICON_SWAP.'
						</span>
					</div>
					<input
						aria-label="Девичья фамилия"
						aria-describedby="ig_patid_name_maiden"
		                type="text"
		                class="mysqleditor form-control form-control-lg"
		                id="patid_name_maiden"
		                placeholder="Девичья фамилия (только для женщин)"
		                value="' . $PatientPersonalData['patid_name_maiden'] . '"
		                data-action="edit"
		                data-table="'.CAOP_PATIENTS.'"
		                data-assoc="0"
		                data-fieldid="'.$PK[CAOP_PATIENTS].'"
		                data-id="' . htmlspecialchars($PatientPersonalData['patid_id']) . '"
		                data-field="patid_name_maiden"
	                >
				</div>
	        </div>
	    </div>
	    <div class="col">
		   <div class="form-group">
	            <div class="input-group">
					<div class="input-group-prepend">
						<span class="input-group-text" id="ig_patid_mark" ' . super_bootstrap_tooltip('Особая отметка') . '>
							'.BT_ICON_CASE_WARNING.'
						</span>
					</div>
					<input
						aria-label="Особая отметка"
						aria-describedby="ig_patid_mark"
		                type="text"
		                class="mysqleditor form-control form-control-lg"
		                id="patid_mark"
		                placeholder="Текстовая метка, если с пациентом \'\'что-то не так\'\'..."
		                value="' . $PatientPersonalData['patid_mark'] . '"
		                data-action="edit"
		                data-table="'.CAOP_PATIENTS.'"
		                data-assoc="0"
		                data-fieldid="'.$PK[CAOP_PATIENTS].'"
		                data-id="' . htmlspecialchars($PatientPersonalData['patid_id']) . '"
		                data-field="patid_mark"
	                >
				</div>
	        </div>
		</div>
	</div>
	';
	
	$response['htmlData'] .= '
    <div class="row">
    <div class="col-12">
        <div class="form-group">
            <label for="patid_casestatus">Случай пациента:</label>
';
	$mep = 'data-action="edit" data-table="' . CAOP_PATIENTS . '" data-assoc="0" data-fieldid="patid_id" data-id="' . $PatientPersonalData['patid_id'] . '" data-field="patid_casestatus" ';
	$defaultArr = array(
		'key' => '0',
		'value' => 'ВЫБЕРИТЕ...'
	);
	$defaultSelect = array(
		'value' => $PatientPersonalData['patid_casestatus']
	);
	$CaseStatusesListSelector = array2select($CaseStatusesList, 'casestatus_id', 'casestatus_title', 'patid_casestatus', ' id="patid_casestatus" class="mysqleditor form-control form-control-lg" ' . $mep, $defaultArr, $defaultSelect);
	if ($CaseStatusesListSelector['stat'] == RES_SUCCESS)
	{
		$response['htmlData'] .= $CaseStatusesListSelector['result'];
	}
	$response['htmlData'] .= '
        </div>
    </div>
</div>
';
	
	if (count($Cancer) > 0)
	{
		foreach ($Cancer as $diag)
		{
			
			
			$zeroArray = array(
				'key' => 0,
				'value' => 'выберите врача'
			);
			$selectedArray = array(
				'key' => 'doctor_id',
				'value' => $diag['cancer_doctor_id']
			);
			$SelectDoctor = array2select($DoctorsListId, 'doctor_id', 'doctor_f', 'cancer_doctor_id', ' class="form-control form-control-sm mysqleditor" id="cancer_doctor_id" data-action="edit" data-table="' . CAOP_CANCER . '" data-assoc="0" data-fieldid="cancer_id" data-id="' . $diag['cancer_id'] . '" data-field="cancer_doctor_id"', $zeroArray, $selectedArray);
			
			
			$morph = ($diag['cancer_morph_verif'] == 1) ? 'верифицирован' : 'не верифицирован';
			$response['htmlData'] .= spoiler_begin_return('<b>Злокачественное новообразование:</b> ' . $diag['cancer_ds'] . ' [№' . $diag['cancer_order_number'] . '] от ' . $diag['cancer_morph_date'] . ' <b>[' . $morph . ']</b>', 'ds_' . $diag['cancer_id']);
//			$response['htmlData'] .= debug($diag, true, false, 1);
			$response['htmlData'] .= '
			<b>Диагноз по МКБ-10:</b><br/>
			<input type="text" 
					class="mysqleditor form-control mkbDiagnosis" 
					id="diag_ds_' . $diag['cancer_id'] . '" 
					data-action="edit" 
					data-table="' . CAOP_CANCER . '"
					data-assoc="0" 
					data-fieldid="cancer_id" 
					data-id="' . $diag['cancer_id'] . '"
					data-field="cancer_ds"
					data-adequate="MKB"
	                data-return="#diag_ds_' . $diag['cancer_id'] . '"
	                data-returntype="input"
	                data-returnfunc="value"
					placeholder="Диагноз по МКБ-10" 
					value="' . htmlspecialchars($diag['cancer_ds']) . '">
			<b>Текст диагноза:</b><br/>
			<textarea 
				class="mysqleditor form-control"
				id="diag_ds_text_' . $diag['cancer_id'] . '"
				data-action="edit"
				data-table="' . CAOP_CANCER . '"
				data-assoc="0"
				data-fieldid="cancer_id"
				data-id="' . $diag['cancer_id'] . '"
				data-return="0"
				data-field="cancer_ds_text"
				placeholder="Текст диагноза">' . htmlspecialchars($diag['cancer_ds_text']) . '</textarea>
			<div class="row">
				<div class="col"><b>Метод:</b><br/>
					<input type="text" 
						class="mysqleditor form-control" 
						id="diag_morph_type_' . $diag['cancer_id'] . '" 
						data-action="edit" 
						data-table="' . CAOP_CANCER . '"
						data-assoc="0" 
						data-fieldid="cancer_id" 
						data-id="' . $diag['cancer_id'] . '" 
						data-return="0" 
						data-field="cancer_morph_type" 
						placeholder="гистология, цитология, ИГХ, МСКТ, УЗИ, МРТ и прочее (напишите)" 
						value="' . htmlspecialchars($diag['cancer_morph_type']) . '">
				</div>			
				<div class="col"><b>Дата:</b><br/>
					<input type="text" 
						class="mysqleditor form-control" 
						id="diag_morph_date_' . $diag['cancer_id'] . '" 
						data-action="edit" 
						data-table="' . CAOP_CANCER . '"
						data-assoc="0" 
						data-fieldid="cancer_id" 
						data-id="' . $diag['cancer_id'] . '" 
						data-return="0" 
						data-field="cancer_morph_date" 
						placeholder="дата" 
						value="' . htmlspecialchars($diag['cancer_morph_date']) . '">
				</div>			
				<div class="col"><b>Номер:</b><br/>
					<input type="text" 
						class="mysqleditor form-control" 
						id="diag_morph_number_' . $diag['cancer_id'] . '" 
						data-action="edit" 
						data-table="' . CAOP_CANCER . '"
						data-assoc="0" 
						data-fieldid="cancer_id" 
						data-id="' . $diag['cancer_id'] . '" 
						data-return="0" 
						data-field="cancer_morph_number" 
						placeholder="номер" 
						value="' . htmlspecialchars($diag['cancer_morph_number']) . '">
				</div>			
				<div class="col"><b>№ п/п:</b><br/>
					<input type="text" 
						class="mysqleditor form-control" 
						id="diag_order_number_' . $diag['cancer_id'] . '" 
						data-action="edit" 
						data-table="' . CAOP_CANCER . '"
						data-assoc="0" 
						data-fieldid="cancer_id" 
						data-id="' . $diag['cancer_id'] . '" 
						data-return="0" 
						data-field="cancer_order_number" 
						placeholder="текст заключения" 
						value="' . htmlspecialchars($diag['cancer_order_number']) . '">
				</div>			
			</div>			
			<b>Заключение:</b><br/>
			<input type="text" 
					class="mysqleditor form-control" 
					id="diag_morph_text_' . $diag['cancer_id'] . '" 
					data-action="edit" 
					data-table="' . CAOP_CANCER . '"
					data-assoc="0" 
					data-fieldid="cancer_id" 
					data-id="' . $diag['cancer_id'] . '" 
					data-return="0" 
					data-field="cancer_morph_text" 
					placeholder="текст заключения" 
					value="' . htmlspecialchars($diag['cancer_morph_text']) . '">
					
			<b>Врач, установивший диагноз:</b><br/>
			' . $SelectDoctor['result'] . '
			
			
			<b>Дата снятия с Д-учета:</b><br/>
			<input type="text"
					class="mysqleditor form-control russianBirth"
					id="cancer_takeoff_date_' . $diag['cancer_id'] . '"
					data-action="edit"
					data-table="' . CAOP_CANCER . '"
					data-assoc="0"
					data-fieldid="cancer_id"
					data-id="' . $diag['cancer_id'] . '"
					data-return="0"
					data-field="cancer_takeoff_date"
					placeholder="Дата снятия с Д-учета"
					value="' . htmlspecialchars($diag['cancer_takeoff_date']) . '">
			
			<b>Причина снятия с Д-учета:</b><br/>
			<input type="text"
					class="mysqleditor form-control"
					id="cancer_takeoff_reason_' . $diag['cancer_id'] . '"
					data-action="edit"
					data-table="' . CAOP_CANCER . '"
					data-assoc="0"
					data-fieldid="cancer_id"
					data-id="' . $diag['cancer_id'] . '"
					data-return="0"
					data-field="cancer_takeoff_reason"
					placeholder="Причина снятия с Д-учета"
					value="' . htmlspecialchars($diag['cancer_takeoff_reason']) . '">
			
			<b>Морфологически верифицирован:</b><br/>
			<button class="btn btn-sm btn-primary patientDiagVerif" onclick="javascript:cancerMorph(' . $diag['cancer_id'] . ', 1)">Да</button>
			<button class="btn btn-sm btn-secondary patientDiagVerif" onclick="javascript:cancerMorph(' . $diag['cancer_id'] . ', 0)">Нет</button>  
			<button class="btn btn-sm btn-warning" onclick="javascript:cancerRemove(' . $diag['cancer_id'] . ')">Удалить диагноз</button>  
			';


//			$response['htmlData'] .= '<script>reInitVerif();</script>';
			$response['htmlData'] .= spoiler_end_return();
		}
	}
	
	$amountData = [];
	foreach ($PATIENTS_DATAS as $tableKey=>$patientTable)
	{
		if ($patientTable['is_doc'])
		{
			$Data = getarr($patientTable['table'],
				"{$patientTable['field_patid']}='{$PatientPersonalData['patid_id']}'",
				null,
				false,
				"{$patientTable['field_id']}");
			$amountData[$tableKey] = count($Data);
		}
		
	}
	
//	$response['htmlData'] .= debug_ret($amountData);
	$summ = array_sum($amountData);
	
	$response['htmlData'] .= spoiler_begin_return('Документы и посещения пациента ('.$summ.')', 'document_' . $PatientPersonalData['patid_id'], '');
	$response['htmlData'] .= '<ul>';
	$response['htmlData'] .= '<li><a target="_blank" href="/journalSearch/card/' . $PatientPersonalData['patid_ident'] . '"><b>Посещения ('.$amountData['journal'].')</b></a></li>';
	$response['htmlData'] .= '<li><a target="_blank" href="/dailys/' . $PatientPersonalData['patid_id'] . '"><b>Дневники ('.$amountData['dailys'].')</b></a></li>';
	$response['htmlData'] .= '<li><a target="_blank" href="/journalCitology/patient' . $PatientPersonalData['patid_id'] . '"><b>Цитологии ('.$amountData['citology'].')</b></a></li>';
	$response['htmlData'] .= '<li><a target="_blank" href="/allwaiting/patient' . $PatientPersonalData['patid_id'] . '"><b>Обследования ('.$amountData['research'].')</b></a></li>';
	$response['htmlData'] .= '<li><a target="_blank" href="/routeSheet/' . $PatientPersonalData['patid_id'] . '"><b>Маршрутные листы ('.$amountData['route'].')</b></a></li>';
	$response['htmlData'] .= '<li><a target="_blank" href="/noticeF1A/' . $PatientPersonalData['patid_id'] . '"><b>Извещения на 1А кл. гр. ('.$amountData['notice_f1a'].')</b></a></li>';
	$response['htmlData'] .= '<li><a target="_blank" href="/msktmdc/' . $PatientPersonalData['patid_id'] . '"><b>Направления на МСКТ в МДЦ ('.$amountData['mskt_mdc'].')</b></a></li>';
	$response['htmlData'] .= '<li><a target="_blank" href="/anylpu/' . $PatientPersonalData['patid_id'] . '"><b>Направления в другие ЛПУ ('.$amountData['dir_057'].')</b></a></li>';
	$response['htmlData'] .= '<li><a target="_blank" href="/morphology/' . $PatientPersonalData['patid_id'] . '"><b>Направления на гистологию ('.$amountData['morphology'].')</b></a></li>';
	$response['htmlData'] .= '<li><a target="_blank" href="/blankDeclined/' . $PatientPersonalData['patid_id'] . '"><b>Бланки отказа ('.$amountData['blank_decline'].')</b></a></li>';
	$response['htmlData'] .= '<li><a href="javascript:uzicaoplist(' . $PatientPersonalData['patid_id'] . ')"><b>Талоны на УЗИ ЦАОП ('.$amountData['uzi_schedule'].')</b></a></li>';
	$response['htmlData'] .= '<li><a href="javascript:showSPO(0, ' . $PatientPersonalData['patid_id'] . ')"><b>Показать СПО ('.$amountData['spo'].')</b></a></li>';
	$response['htmlData'] .= '</ul>';
	$response['htmlData'] .= spoiler_end_return();
	
	$response['htmlData'] .= '<div class="dropdown-divider"></div>';
	
	
	$Logs_query = "
	SELECT * FROM ".CAOP_LOG."
		LEFT JOIN ".CAOP_CAT_SYSTEM." ON ".CAOP_CAT_SYSTEM.".{$PK[CAOP_CAT_SYSTEM]}=".CAOP_LOG.".log_cat_id
		LEFT JOIN ".CAOP_LOG_TYPE." ON ".CAOP_LOG_TYPE.".{$PK[CAOP_LOG_TYPE]}=".CAOP_LOG.".log_action_id
		LEFT JOIN ".CAOP_DOCTOR." ON ".CAOP_DOCTOR.".{$PK[CAOP_DOCTOR]}=".CAOP_LOG.".log_action_doctor_id
	WHERE log_target_info_2='{$PatientPersonalData['patid_id']}'
	ORDER BY log_date_unix DESC
	";
	
	$Logs_result = mqc($Logs_query);
	$Logs = mr2a($Logs_result);
	
	if ( count($Logs) > 0 )
	{
		$response['htmlData'] .= spoiler_begin_return('Лог создания', 'log_create_' . $PatientPersonalData['patid_id']);
		foreach ($Logs as $log)
		{
//			$response['htmlData'] .= debug_ret($log);
//	$addon_field = '';
//	$addon_value = '';
			$docname = mb_ucwords($log['doctor_f'] . ' ' . $log['doctor_i'] . ' ' . $log['doctor_o']);
			
			$addon1_field = $log['log_target_info_1'];
			$addon1_value = $log['log_target_info_2'];
			
			$addon2_field = $log['log_target_info_3'];
			$addon2_value = $log['log_target_info_4'];
			
			$response['htmlData'] .= '
			<div class="small" id="log_'.$log['log_id'].'">
				<div>
					<span class="font-weight-bolder">Дата:</span> '.date(DMYHIS, $log['log_date_unix']).'
				</div>
				<div>
					<span class="font-weight-bolder">IP:</span> '.$log['log_ip'].'
				</div>
				<div>
					<span class="font-weight-bolder">CAT-ключ:</span> '.$log['cat_desc'].'
				</div>
				<div>
					<span class="font-weight-bolder">Действие:</span> '.$log['type_title'].'
				</div>
				<div>
					<span class="font-weight-bolder">Авторизация:</span> '.$docname.'
				</div>
				<div>
					<span class="font-weight-bolder">'.$addon1_field.':</span> '.$addon1_value.'
				</div>
				<div>
					<span class="font-weight-bolder">'.$addon2_field.':</span>  '.$addon2_value.'
				</div>
			</div>
			<div class="dropdown-divider"></div>
			';
		}
		$response['htmlData'] .= spoiler_end_return();
	}
	
} else
{
	$response['msg'] = 'Пациент в БД не найден';
}