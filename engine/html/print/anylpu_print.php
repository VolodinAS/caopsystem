<?php
$PrintParams = explode('/', $request_params);

//debug($PrintParams);

$PRINT_SETTINGS = array(
	'blank' => 'anylpu',
    'table' => CAOP_DIR_057,
	'field_id' => 'dir_id',
	'patient_field_id' => 'dir_patient_id',
	'doctor_field_id' => 'dir_doctor_id'
);

if ( $PrintParams[0] > 0 )
{
	$BlankPrint = getarr($PRINT_SETTINGS['table'], "{$PRINT_SETTINGS['field_id']}='{$PrintParams[0]}'");
	if ( count($BlankPrint) > 0 )
	{
		$BlankPrint = $BlankPrint[0];
		$PatientData = getPatientDataById($BlankPrint[$PRINT_SETTINGS['patient_field_id']]);
		if ($PatientData['result'] == true && $PatientData['error'] == false)
		{
			$PatientData = $PatientData['data']['personal'];
			
			$DoctorData = $DoctorsListId['id' . $BlankPrint[$PRINT_SETTINGS['doctor_field_id']]];
			
			require_once ( "engine/html/title_begin_print.php" );
			require_once ( "engine/html/print_html/{$PRINT_SETTINGS['blank']}Html.php" );
			require_once ( "engine/html/title_end_print.php" );
		} else
		{
			bt_notice('Такого пациента не существует',BT_THEME_DANGER);
		}
		
		exit();
		
	} else bt_notice('Такого бланка не существует',BT_THEME_DANGER);
	
} else bt_notice('Не выбран бланк для распечатки',BT_THEME_DANGER);