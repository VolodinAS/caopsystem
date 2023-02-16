<?php
$PrintParams = explode('/', $request_params);

if ( $PrintParams[0] > 0 )
{
	$BlankDeclinePrint = getarr(CAOP_BLANK_DECLINE, "decline_id='{$PrintParams[0]}'");
	if ( count($BlankDeclinePrint) > 0 )
	{
		$BlankDeclinePrint = $BlankDeclinePrint[0];
		$PatientData = getPatientDataById($BlankDeclinePrint['decline_patid']);
		if ($PatientData['result'] == true && $PatientData['error'] == false)
		{
			$PatientData = $PatientData['data']['personal'];
			
			$DoctorData = $DoctorsListId['id' . $BlankDeclinePrint['decline_doctor']];
			
			require_once ( "engine/html/title_begin_print.php" );
			require_once ( "engine/html/blankDeclineHtml.php" );
			require_once ( "engine/html/title_end_print.php" );
		} else
		{
			bt_notice('Такого пациента не существует',BT_THEME_DANGER);
		}
		
		exit();
		
	} else bt_notice('Такого бланка не существует',BT_THEME_DANGER);
	
} else bt_notice('Не выбран бланк для распечатки',BT_THEME_DANGER);