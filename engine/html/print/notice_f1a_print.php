<?php
$PrintParams = explode('/', $request_params);

if ( $PrintParams[0] > 0 )
{
	$NoticePrint = getarr(CAOP_NOTICE_F1A, "f1a_id='{$PrintParams[0]}'");
	if ( count($NoticePrint) > 0 )
	{
		$NoticePrint = $NoticePrint[0];
		$PatientData = getPatientDataById($NoticePrint['f1a_patid']);
		if ($PatientData['result'] == true && $PatientData['error'] == false)
		{
			$PatientData = $PatientData['data']['personal'];
			
			$DoctorData = $DoctorsListId['id' . $NoticePrint['f1a_doctor']];
			
			require_once ( "engine/html/title_begin_print.php" );
			require_once ( "engine/html/noticeF1aHtml.php" );
			require_once ( "engine/html/title_end_print.php" );
		} else
		{
			bt_notice('Такого пациента не существует',BT_THEME_DANGER);
		}
		
		exit();
		
	} else bt_notice('Такого бланка не существует',BT_THEME_DANGER);
	
} else bt_notice('Не выбран бланк для распечатки',BT_THEME_DANGER);