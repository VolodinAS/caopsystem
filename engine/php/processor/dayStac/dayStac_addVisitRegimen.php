<?php
$response['stage'] = $action;
$response['debug']['$_POST'] = $_POST;
$response['msg'] = 'begin';

$HTTP = $_POST;
extract($HTTP, EXTR_PREFIX_SAME, '_caop');

$PatientPM = RecordManipulation($patient_id, CAOP_DS_PATIENTS, 'patient_id');
if ( $PatientPM['result'] )
{
	$PatientData = $PatientPM['data'];
	
	$paramValues = array(
	
	);
	
} else $response['msg'] = $PatientPM['msg'];