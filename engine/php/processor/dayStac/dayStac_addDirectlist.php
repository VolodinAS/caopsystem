<?php
$response['stage'] = $action;
$response['debug']['$_POST'] = $_POST;
$response['msg'] = 'begin';
$patient_id = $_POST['patient_id'];

$PatientPM = RecordManipulation($patient_id, CAOP_DS_PATIENTS, 'patient_id');
if ( $PatientPM['result'] )
{
	$PatientData = $PatientPM['data'];
	$response['debug']['$PatientData'] = $PatientData;
	
	$paramValues = array(
		'dirlist_dspatid'   =>  $patient_id,
		'dirlist_doctor_id' =>  $USER_PROFILE['doctor_id'],
		'dirlist_doc_date'  =>  '',
		'dirlist_doc_unix'  =>  0,
		'dirlist_done_date'  =>  '',
		'dirlist_done_unix'  =>  0,
		'dirlist_diag_mkb'  =>  '',
		'dirlist_diag_text' =>  '',
		'dirlist_visit_date'    =>  date('d.m.Y'),
		'dirlist_visit_unix'    =>  time(),
		'dirlist_dirdoc_name'   =>  '',
		'dirlist_isMain'    =>  '0'
	);
	
	$NewDirlist = appendData(CAOP_DS_DIRLIST, $paramValues);
	
	if ( $NewDirlist[ID] > 0 )
	{
		$response['result'] = true;
	} else
	{
		$response['msg'] = 'Ошибка добавления направления';
		$response['debug']['$NewDirlist'] = $NewDirlist;
	}
	
} else $response['msg'] = $PatientPM['msg'];