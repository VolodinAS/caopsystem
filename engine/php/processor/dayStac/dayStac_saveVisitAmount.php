<?php
$response['stage'] = $action;
$response['debug']['$_POST'] = $_POST;
$response['msg'] = 'begin';

$HTTP = $_POST;
extract($HTTP, EXTR_PREFIX_SAME, '_caop');

$VisitsByUnix = getarr(CAOP_DS_VISITS, "visreg_visit_unix='{$weekunix}' AND visreg_dspatid='{$patient_id}'");

$response['debug']['$VisitsByUnix'] = $VisitsByUnix;

if ( count($VisitsByUnix) > 0 )
{
	$VisitsByUnix = $VisitsByUnix[0];

	
	$updateParams = array(
		'visreg_visit_current'  =>  $visreg_visit_current,
		'visreg_visit_total'    =>  $visreg_visit_total,
		'visreg_dispose_date'   =>  $visreg_dispose_date,
		'visreg_dispose_unix'   =>  birthToUnix($visreg_dispose_date)
	);
	
	$UpdateData = updateData(CAOP_DS_VISITS, $updateParams, $VisitsByUnix, "visreg_id='{$VisitsByUnix['visreg_id']}'");
	if ( $UpdateData['stat'] == RES_SUCCESS )
	{
		$response['result'] = true;
	} else
	{
		$response['msg'] = 'updateerror';
		$response['debug']['$UpdateData'] = $UpdateData;
	}
}
