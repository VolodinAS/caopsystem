<?php
$response['stage'] = $action;

$updateArr = array(
	'patid_casestatus'  =>  $_POST['caseid']
);

$Update = updateData(CAOP_PATIENTS, $updateArr, array(), "patid_id='{$_POST['patid']}'");
if ($Update['stat'] == RES_SUCCESS)
{
	$response['result'] = true;
} else
{
	$response['msg'] = $Update;
}