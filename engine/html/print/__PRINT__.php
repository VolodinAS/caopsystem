<?php
$PrintParams = explode('/', $request_params);

$BLANK_FIELD_ID = ''; // название поля индекса
$BLANK_FIELD_PATID = ''; // название поля id пациента
$BLANK_FIELD_DOCID = ''; // название поля id врача
$BLANK_HTML = ''; // название скрипта HTML

if ( $PrintParams[0] > 0 )
{
	$BlankPrint = getarr(CAOP_MORPHOLOGY, "{$BLANK_FIELD_ID}='{$PrintParams[0]}'");
	if ( count($BlankPrint) > 0 )
	{
		$BlankPrint = $BlankPrint[0];
		$PatientData = getPatientDataById($BlankPrint[$BLANK_FIELD_PATID]);
		if ($PatientData['result'] == true && $PatientData['error'] == false)
		{
			$PatientData = $PatientData['data']['personal'];
			
			$DoctorData = $DoctorsListId['id' . $BlankPrint[$BLANK_FIELD_DOCID]];
			
			require_once ( "engine/html/title_begin_print.php" );
			require_once ( "engine/html/print_html/{$BLANK_HTML}Html.php" );
			require_once ( "engine/html/title_end_print.php" );
		} else
		{
			bt_notice('Такого пациента не существует',BT_THEME_DANGER);
		}
		
		exit();
		
	} else bt_notice('Такого бланка не существует',BT_THEME_DANGER);
	
} else bt_notice('Не выбран бланк для распечатки',BT_THEME_DANGER);