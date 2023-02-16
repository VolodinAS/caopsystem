<?php
$response['stage'] = $action;
$response['debug']['$_POST'] = $_POST;
$response['msg'] = 'begin';
$response['result'] = true;
$response['htmlData'] = '';
extract($_POST);

$CompanyList = getarr(CAOP_INSURANCE, "insurance_enabled='1'", "ORDER BY insurance_title ASC");
$CompanyListId = getDoctorsById($CompanyList, 'insurance_id');

$CaseStatusesList = getarr(CAOP_CASESTATUS, "casestatus_enabled='1'", "ORDER BY casestatus_order ASC");
$CaseStatusesListId = getDoctorsById($CaseStatusesList, 'casestatus_id');

/*
 * ГЛАВНЫЕ ТАБЛИЦЫ С ИНФОРМАЦИЕЙ О ПАЦИЕНТЕ
 *
 * [ГОТОВО]
 *
 * $CAOP_PATIENTS - список пациентов
 *
 * $CAOP_BLANK_DECLINE - бланки отказа
 * $CAOP_CANCER - информация о раках пациента
 * $CAOP_CITOLOGY - информация о цитологии пациента
 * $CAOP_DAILY - дневники
 * $CAOP_DIR_057 - направления 057У
 * $CAOP_JOURNAL - информация о визитах пациента
 * $CAOP_MSKT_MDC - направления на МСКТ в МДЦ
 * $CAOP_NOTICE_F1A - информация об 1А кл. гр.
 * $CAOP_RESEARCH - информация об обследованиях пациентов
 * $CAOP_ROUTE_SHEET - информация о маршрутных листах пациента
 * $CAOP_SCHEDULE_UZI_PATIENTS - информация об УЗИ пациентах
 *
 *
 *
 *
 *
 * [НЕ ГОТОВО]
 *
 * */

$PATIENT_TYPES = array('base', 'target');

// , '' , '' , '' , ''
foreach ($PATIENT_TYPES as $TYPE)
{
	$checked = ($TYPE == 'base') ? ' checked' : '';
	
	$PATIENT_ID = $$TYPE;
	
	$response['patient_' . $TYPE]['debug']['data'] = '';
	$PatientData = getarr(CAOP_PATIENTS, "patid_id='{$PATIENT_ID}'")[0];
	$response['patient_' . $TYPE]['debug']['data'] .= '<div class="patient_info">';
	foreach ($PatientData as $field=>$value)
	{
		if ($field != "patid_id")
		{
			$value_format = $value;
			if ($field == "patid_insurance_company")
			{
				$value_format = $CompanyListId['id' . $value]['insurance_title'];
			}
			if ($field == "patid_casestatus")
			{
				$value_format = $CaseStatusesListId['id' . $value]['casestatus_title'];
			}
			$response['patient_' . $TYPE]['debug']['data'] .= '<div class="field-'.$field.' p-1">';
//		$response['patient_' . $TYPE]['debug']['data'] .= $field . ': ' . $value;
			$response['patient_' . $TYPE]['debug']['data'] .= '<input '.$checked.' class="form-check-input " type="radio" name="'.$field.'" id="'.$field.'_'.$PATIENT_ID.'" value="'.$value.'" >
		<label class="form-check-label box-label" for="'.$field.'_'.$PATIENT_ID.'"><span></span>'.$field.': <b>'.$value_format.'</b></label>';
			$response['patient_' . $TYPE]['debug']['data'] .= '</div><div class="dropdown-divider"></div>';
		}
		
	}
	$response['patient_' . $TYPE]['debug']['data'] .= '</div>';
	
	foreach ($PATIENTS_DATAS as $patientIndex => $patientDatas)
	{
		$TABLE = getarr($patientDatas['table'], "{$patientDatas['field_patid']}='{$PATIENT_ID}'", "ORDER BY {$patientDatas['field_id']} ASC");
		$response['patient_' . $TYPE][$patientIndex]['htmlData'] .= spoiler_begin_return($patientDatas['title'] . ' (' . count($TABLE) . ')', $patientDatas['table']);
		$response['patient_' . $TYPE][$patientIndex]['htmlData'] .= debug_ret($TABLE);
		$response['patient_' . $TYPE][$patientIndex]['htmlData'] .= spoiler_end_return();
	}
	
	/*$queryGlobal = "SELECT *, COUNT({$CAOP_CANCER}.cancer_id) as AMOUNT_cancer,
						 COUNT({$CAOP_CITOLOGY}.citology_id) as AMOUNT_citology,
						 COUNT({$CAOP_JOURNAL}.journal_id)  as AMOUNT_journal,
						 COUNT({$CAOP_RESEARCH}.research_id) as AMOUNT_research,
						 COUNT({$CAOP_ROUTE_SHEET}.rs_id) as AMOUNT_route
						 FROM {$CAOP_PATIENTS}
							 LEFT JOIN {$CAOP_CANCER}
								 ON {$CAOP_CANCER}.cancer_patid = {$CAOP_PATIENTS}.patid_id
							 LEFT JOIN {$CAOP_CITOLOGY}
								 ON {$CAOP_CITOLOGY}.citology_patid = {$CAOP_PATIENTS}.patid_id
							 LEFT JOIN {$CAOP_JOURNAL}
								 ON {$CAOP_JOURNAL}.journal_patid = {$CAOP_PATIENTS}.patid_id
							 LEFT JOIN {$CAOP_RESEARCH}
								 ON {$CAOP_RESEARCH}.research_patient_id = {$CAOP_PATIENTS}.patid_id
							 LEFT JOIN {$CAOP_ROUTE_SHEET}
								 ON {$CAOP_ROUTE_SHEET}.rs_patid = {$CAOP_PATIENTS}.patid_id
						 WHERE {$CAOP_PATIENTS}.patid_id='{$PATIENT_ID}'";
	
	
	$resultGlobal = mqc($queryGlobal);
	$DATA = mr2a($resultGlobal);
	if (count($DATA) == 1)
	{
		$DATA = $DATA[0];
		$response['patient_' . $TYPE]['debug']['data'] = debug_ret($DATA);
		$response['patient_' . $TYPE]['debug']['sql'] =  $queryGlobal;
		$response['patient_' . $TYPE]['debug']['sql_line'] =  btw( $queryGlobal );
		$response['patient_' . $TYPE]['cancer']['htmlData'] = '';
		
		foreach ($PATIENTS_DATAS as $patientIndex => $patientDatas)
		{
			if (intval($DATA['AMOUNT_' . $patientIndex]) > 0)
			{
				$TABLE = getarr($patientDatas['table'], "{$patientDatas['field_patid']}='{$DATA['patid_id']}'", "ORDER BY {$patientDatas['field_id']} ASC", 1);
				$response['patient_' . $TYPE][$patientIndex]['htmlData'] .= spoiler_begin_return($patientDatas['title'] . ' (' . $DATA['AMOUNT_' . $patientIndex] . ')', $patientIndex . '_' . $PATIENT_ID);
				$response['patient_' . $TYPE][$patientIndex]['htmlData'] .= debug_ret($TABLE);
				$response['patient_' . $TYPE][$patientIndex]['htmlData'] .= spoiler_end_return();
			}
		}
	} else
	{
		$response['msg'] = 'Странно, но для данного ID больше, чем 1 пациент';
	}*/
	
}

function btw($b1) {
	$b1 = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $b1);
	$b1 = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $b1);
	return $b1;
}