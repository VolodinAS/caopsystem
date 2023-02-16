<?php
$response['stage'] = $action;
$response['debug']['$_POST'] = $_POST;
$response['msg'] = 'begin';
$response['result'] = true;


$PatientsList = json_decode( trim($_POST['json_data']), 1 );

//$response['htmlData'] .= debug_ret($PatientsList);

if ( count($PatientsList) > 0 )
{
	$PatientsIDS = array();
	foreach ($PatientsList as $Patient)
	{
		$PatientsIDS['patid_'.$Patient['patid_id']][] = $Patient;
	}
	
//	$response['htmlData'] .= debug_ret($PatientsIDS);
	
	foreach ($PatientsIDS as $patid_id=>$patid_regimen)
	{
//		$response['htmlData'] .= debug_ret($patid_regimen[0]);
		$patid_name = $patid_regimen[0]['name'];
		$patient_id = str_replace('patid_', '', $patid_id);
		$response['htmlData'] .= spoiler_begin_return(mb_ucwords($patid_name), 'patient_visreg_'.$patient_id, '');
		foreach ($patid_regimen as $visregRegimen)
		{
			$VisregData = getarr(CAOP_DS_VISITS_REGIMENS, "visreg_id='{$visregRegimen['visreg_id']}'");
			$visregDatum = $VisregData[0];
			
			$response['htmlData'] .= spoiler_begin_return($visregDatum['visreg_title'], 'visreg_'.$visregDatum['visreg_id'], '');
			$DoctorData = $DoctorsListId['id' . $visregDatum['visreg_doctor_id']];
			$DoctorName = docNameShort($DoctorData, "famimot");
			$visreg_dose_measure_type = $DOSE_MEASURE_TYPES_ID['id' . $visregDatum['visreg_dose_measure_type']]['type_title'];
			$visreg_dose_period_type = $DOSE_PERIOD_TYPES_ID['id' . $visregDatum['visreg_dose_period_type']]['type_title'];
			$visreg_freq_period_type = $DOSE_FREQ_PERIOD_TYPES_ID['id' . $visregDatum['visreg_freq_period_type']]['type_title'];
			$response['htmlData'] .= '<b>Название схемы:</b> '.$visregDatum['visreg_title'].'<br>';
			$response['htmlData'] .= '<b>Название препарата:</b> '.$visregDatum['visreg_drug'].'<br>';
			$response['htmlData'] .= '<b>Дозировка препарата:</b> '.$visregDatum['visreg_dose'].' '.$visreg_dose_measure_type.' / '.$visreg_dose_period_type.'<br>';
			$response['htmlData'] .= '<b>Частота процедуры:</b> '.$visregDatum['visreg_freq_amount'].' раз в '.$visregDatum['visreg_freq_period_amount'].' '.$visreg_freq_period_type.'<br>';
			$response['htmlData'] .= '<b>Da. Signa.:</b> '.$visregDatum['visreg_dasigna'].'<br><br>';
			$response['htmlData'] .= '<b>Врач:</b> '.$DoctorName;
			$response['htmlData'] .= spoiler_end_return();
			
		}
		$response['htmlData'] .= spoiler_end_return();
	}
} else
{
	$response['htmlData'] .= bt_notice('На сегодня нет плановых пациентов', BT_THEME_WARNING, 1);
}