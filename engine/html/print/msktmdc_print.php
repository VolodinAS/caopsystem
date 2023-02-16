<?php
$PrintParams = explode('/', $request_params);

//debug($PrintParams);

if ( $PrintParams[0] > 0 )
{
	$BlankPrint = getarr(CAOP_MSKT_MDC, "mskt_id='{$PrintParams[0]}'");
	if ( count($BlankPrint) > 0 )
	{
		$BlankPrint = $BlankPrint[0];
		$PatientData = getPatientDataById($BlankPrint['mskt_patient_id']);
		if ($PatientData['result'] == true && $PatientData['error'] == false)
		{
			$PatientData = $PatientData['data']['personal'];
			
			$DoctorData = $DoctorsListId['id' . $BlankPrint['mskt_doctor_id']];
			
			require_once ( "engine/html/title_begin_print.php" );
			require_once ( "engine/html/print_html/msktHtml.php" );
			require_once ( "engine/html/title_end_print.php" );
		} else
		{
			bt_notice('Такого пациента не существует',BT_THEME_DANGER);
		}
		
		exit();
		
	} else bt_notice('Такого бланка не существует',BT_THEME_DANGER);
	
} else bt_notice('Не выбран бланк для распечатки',BT_THEME_DANGER);