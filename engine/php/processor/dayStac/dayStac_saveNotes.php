<?php
$response['stage'] = $action;
$response['debug']['$_POST'] = $_POST;
$response['msg'] = 'begin';

$HTTP = $_POST;
extract($HTTP, EXTR_PREFIX_SAME, '_caop');

$VisitRM = RecordManipulation($visit_id, CAOP_DS_VISITS, 'visreg_id');
if ( $VisitRM['result'] )
{
    $VisitData = $VisitRM['data'];
    
    if ( isset($visreg_result) )
    {
	    $updateParams = array(
		    'visreg_result' =>  $visreg_result,
	    );
	    $UpdateVisit = updateData(CAOP_DS_VISITS, $updateParams, $VisitData, "visreg_id='{$visit_id}'");
	    if ( $UpdateVisit['stat'] == RES_SUCCESS )
	    {
		    $response['result'] = true;
	    }
    }
} else $response['msg'] = $VisitRM['msg'];

if ( isset($visreg_note) )
{
	$PatientRM = RecordManipulation($patient_id, CAOP_DS_PATIENTS, 'patient_id');
	if ( $PatientRM['result'] )
	{
	    $PatientData = $PatientRM['data'];

	    $updateParams = array(
			'patient_note'   =>  $visreg_note
		);

		$UpdatePatientNote = updateData(CAOP_DS_PATIENTS, $updateParams, array(), "patient_id='{$PatientData['patient_id']}'");
		if ( $UpdatePatientNote['stat'] == RES_SUCCESS )
		{
			$response['msg'] = 'Заметка обновлена';
			$response['result'] = true;
		}
	} else $response['msg'] = $PatientRM['msg'];
	
}