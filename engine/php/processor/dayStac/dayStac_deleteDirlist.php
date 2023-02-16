<?php
$response['stage'] = $action;
$response['debug']['$_POST'] = $_POST;
$response['msg'] = 'begin';

$HTTP = $_POST;
extract($HTTP, EXTR_PREFIX_SAME, '_caop');

$PatientPM = RecordManipulation($patient_id, CAOP_DS_PATIENTS, 'patient_id');
if ( $PatientPM['result'] )
{
	$PatientData = $DirlistPM['data'];
	$DirlistPM = RecordManipulation($dirlist_id, CAOP_DS_DIRLIST, 'dirlist_id');
	if ( $DirlistPM['result'] )
	{
	    $DirlistData = $DirlistPM['data'];
	    
	    $DeleteDirlist = deleteitem(CAOP_DS_DIRLIST, "dirlist_id='{$DirlistData['dirlist_id']}'");
	    if ( $DeleteDirlist['result'] )
	    {
	    	$response['result'] = true;
	    } else
	    {
		    $response['msg'] = 'Ошибка удаления направления';
		    $response['debug']['$DeleteDirlist'] = $DeleteDirlist;
	    }
	
	} else $response['msg'] = $DirlistPM['msg'];
	
} else $response['msg'] = $PatientPM['msg'];