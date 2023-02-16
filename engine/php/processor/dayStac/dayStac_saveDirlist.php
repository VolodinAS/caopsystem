<?php
$response['stage'] = $action;
$response['debug']['$_POST'] = $_POST;
$response['msg'] = 'begin';

$HTTP = $_POST;
extract($HTTP, EXTR_PREFIX_SAME, '_caop');

$PatientPM = RecordManipulation($patient_id, CAOP_DS_PATIENTS, 'patient_id');
if ( $PatientPM['result'] )
{
	
	$DirlistPM = RecordManipulation($dirlist_id, CAOP_DS_DIRLIST, 'dirlist_id');
	if ( $DirlistPM['result'] )
	{
		$DirlistData = $DirlistPM['data'];
		
		$response['debug']['$DirlistData'] = $DirlistData;
		
		if ( $dirlist_isMain == '1' )
		{
			$paramValuesUpdateMain = array(
				'dirlist_isMain' => 0
			);
			$UpdateOtherDirlists = updateData(CAOP_DS_DIRLIST, $paramValuesUpdateMain, array(), "dirlist_dspatid='{$patient_id}'");
		}
		
		$paramValues = array(
			'dirlist_dspatid'   =>  $patient_id,
			'dirlist_doctor_id' =>  $USER_PROFILE['doctor_id'],
			'dirlist_doc_date'  =>  $dirlist_doc_date,
			'dirlist_doc_unix'  =>  birthToUnix($dirlist_doc_date),
			'dirlist_done_date'  =>  $dirlist_done_date,
			'dirlist_done_unix'  =>  birthToUnix($dirlist_done_date),
			'dirlist_diag_mkb'  =>  $dirlist_diag_mkb,
			'dirlist_diag_text' =>  $dirlist_diag_text,
			'dirlist_visit_date'    =>  $dirlist_visit_date,
			'dirlist_visit_unix'    =>  birthToUnix($dirlist_visit_date),
			'dirlist_dirdoc_name'   =>  $dirlist_dirdoc_name,
			'dirlist_isMain'    =>  ( $dirlist_isMain=='1' ) ? '1' : '0',
			'dirlist_found_title'   =>  $dirlist_found_title
		);
		
		$UpdateDirlist = updateData(CAOP_DS_DIRLIST, $paramValues, $DirlistData, "dirlist_id='{$dirlist_id}'");
		if ( $UpdateDirlist['stat'] == RES_SUCCESS )
		{
			
			
			$response['result'] = true;
		}
	
	} else $response['msg'] = $DirlistPM['msg'];

} else $response['msg'] = $PatientPM['msg'];