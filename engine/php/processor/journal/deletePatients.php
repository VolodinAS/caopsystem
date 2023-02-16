<?php
$response['stage'] = $action;

$patient_ids = $_POST['patientsList'];

$patient_ids_str = implode(',', $patient_ids);

$response['debug']['$patient_ids_str'] = $patient_ids_str;

$DeleteQuery = "DELETE FROM caop_journal WHERE journal_id IN ({$patient_ids_str})";
$DeleteResult = mqc($DeleteQuery);
if ( $DeleteResult )
{
	LoggerGlobal(
		LOG_TYPE_REMOVE_PATIENTS,
		$_SERVER['REMOTE_ADDR'],
		$CAT_DATA['cat_id'],
		$USER_PROFILE['doctor_id'],
		'idы записей в журнале',
		$patient_ids_str
	);
	
	$response['result'] = true;
} else
{
	$response['msg'] = '[MYSQL with request {'.$DeleteQuery.'}]: ' . mysqlierror();
}