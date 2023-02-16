<?php
$response['stage'] = $action;

$updateDiag = array(
	'cancer_morph_verif'    =>  $_POST['morph']
);

$UpdateCancer = updateData(CAOP_CANCER, $updateDiag, array(), "cancer_id={$_POST['diag_id']}");
if ( $UpdateCancer['stat'] == RES_SUCCESS )
{
	$response['result'] = true;
} else
{
	$response['msg'] = $UpdateCancer;
}