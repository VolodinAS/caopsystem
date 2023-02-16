<?php
$response['stage'] = $action;
$response['debug']['$_POST'] = $_POST;
$response['msg'] = 'begin';

$HTTP = $_POST;
extract($HTTP, EXTR_PREFIX_SAME, '_caop');

$VisitRM = RecordManipulation($visreg_id, CAOP_DS_VISITS, 'visreg_id');
if ( $VisitRM['result'] )
{
	$VisitData = $VisitRM['data'];
	
	$DeleteVisit = deleteitem(CAOP_DS_VISITS, "visreg_id='{$visreg_id}'");
	
	if ( $DeleteVisit['result'] )
	{
		$response['unix'] = $VisitData['visreg_visit_unix'];
		$response['result'] = true;
	}
	
} else $response['msg'] = $VisitRM['msg'];