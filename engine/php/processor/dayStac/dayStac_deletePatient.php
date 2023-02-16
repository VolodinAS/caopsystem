<?php
$response['stage'] = $action;
$response['debug']['$_POST'] = $_POST;
$response['msg'] = 'begin';

$patient_id = $_POST['patient_id'];

$PatientPM = RecordManipulation($patient_id, CAOP_DS_PATIENTS, 'patient_id');
if ( $PatientPM['result'] )
{
	
	$DeletePatient = deleteitem(CAOP_DS_PATIENTS, "patient_id='{$patient_id}'");
	if ( $DeletePatient['result'] === TRUE )
	{
		$response['result'] = true;
	} else
	{
		$response['msg'] = 'Ошибка удаления пациента';
		$response['debug']['$DeletePatient'] = $DeletePatient;
	}
	
} else $response['msg'] = $PatientPM['msg'];