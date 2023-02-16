<?php
$response['stage'] = $action;
$response['debug']['$_POST'] = $_POST;

$patient_ident = $_POST['patient_ident'];

$PatientData = getarr(CAOP_PATIENTS, "patid_ident={$patient_ident}");

if( count($PatientData) == 1 )
{
	$response['result'] = true;
	$response['data'] = $PatientData[0];
} else
{
	if ( count($PatientData) > 1 )
	{
		$response['msg'] = 'Для данного номера амбулаторной найдено больше одного пациента ('.count($PatientData).')';
	} else
	{
		$response['msg'] = 'Нет пациентов с таким номером амбулаторной карты';
	}
}