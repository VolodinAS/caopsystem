<?php
$response['stage'] = $action;
$patient_id = $_POST['patient'];
$response['debug']['$patient_id'] = $patient_id;

$DeletePatient = deleteitem('caop_journal', "journal_id='{$patient_id}' AND journal_doctor='{$USER_PROFILE['doctor_id']}'");
if ( $DeletePatient['result'] === true )
{
	$response['result'] = true;
} else
{
	$response['msg'] = $DeletePatient;
}