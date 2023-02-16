<?php
$response['stage'] = $action;
$response['debug']['$_POST'] = $_POST;
$response['msg'] = 'begin';

$HTTP = $_POST;
extract($HTTP, EXTR_PREFIX_SAME, '_caop');

$DirlistPM = RecordManipulation($dirlist_id, CAOP_DS_DIRLIST, 'dirlist_id');
if ( $DirlistPM['result'] )
{
    $DirlistData = $DirlistPM['data'];
    

    $paramValues = array(
		'visreg_dspatid'            =>  $DirlistData['dirlist_dspatid'],
		'visreg_dirlist_id'         =>  $DirlistData['dirlist_id'],
		'visreg_doctor_id'          =>  $USER_PROFILE['doctor_id'],
		'visreg_title'              =>  '',
		'visreg_drug'               =>  '',
		'visreg_dose'               =>  0,
		'visreg_dose_measure_type'  =>  0,
		'visreg_dose_period_type'   =>  0,
		'visreg_dasigna'            =>  '',
		'visreg_freq_amount'        =>  0,
		'visreg_freq_period_amount' =>  0,
		'visreg_freq_period_type'   =>  0
	);

	$createNew = true;

	if ( $createNew )
	{
		$NewRegimen = appendData(CAOP_DS_VISITS_REGIMENS, $paramValues);
		if ( $NewRegimen[ID] > 0 )
		{
			$response['result'] = true;
			$response['patient_id'] = $DirlistData['dirlist_dspatid'];
		}
	}


} else $response['msg'] = $DirlistPM['msg'];