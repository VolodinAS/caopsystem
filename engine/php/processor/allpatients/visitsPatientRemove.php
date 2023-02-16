<?php
$response['stage'] = $action;

$patid_id = $_POST['patid_id'];

$CheckUser = getarr(CAOP_PATIENTS, "patid_id='{$patid_id}'");
if ( count($CheckUser) == 1 )
{
	$CheckUser = $CheckUser[0];
//	$response['debug']['$CheckUser'] = $CheckUser;
	$TOTAL = count($PATIENTS_DATAS);
	$CURRENT = 0;
	foreach ($PATIENTS_DATAS as $tableIndex => $tableData)
	{
//		$response['debug'][$tableIndex] = $tableData;
		$DeleteData = deleteitem($tableData['table'], "{$tableData['field_patid']}='{$CheckUser['patid_id']}'");
		if ($DeleteData ['result'] === true)
		{
			$CURRENT++;
		}
	}
	
	if ($CURRENT == $TOTAL)
	{
	    $DeletePatientFromList = deleteitem(CAOP_PATIENTS, "patid_id='{$CheckUser['patid_id']}'");
	    if ( $DeletePatientFromList['result'] == true )
	    {
	    	
	    	LoggerGlobal(
	    		LOG_TYPE_DELETE_PATIENT,
			    $_SERVER['REMOTE_ADDR'],
			    $CAT_DATA['cat_id'],
			    $USER_PROFILE['doctor_id'],
			    'id пациента',
			    $CheckUser['patid_id'],
			    'имя пациента',
			    $CheckUser['patid_name']
		    );
	    	
	        $response['result'] = true;
	    } else
	    {
	        $response['msg'] = $DeletePatientFromList;
	    }
	} else
	{
		$response['msg'] = 'Проблема удаления данных пациента';
	}
	

}