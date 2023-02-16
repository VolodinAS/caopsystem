<?php
$response['stage'] = $action;

$response['debug']['USER_PROFILE'] = $USER_PROFILE;

$nid = $_POST['nurseId'];

$SetDefaultNurse = doctor_param('set', $USER_PROFILE['doctor_id'], 'defaultNurse', $nid);

if ($SetDefaultNurse['stat'] === true)
{
	$response['debug']['SetDefaultNurse'] = $SetDefaultNurse;
	$response['stat'] = true;
}
