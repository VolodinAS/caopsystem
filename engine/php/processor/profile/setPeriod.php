<?php
$response['stage'] = $action;

$response['debug']['USER_PROFILE'] = $USER_PROFILE;

$HTTP = $_POST;
extract($HTTP, EXTR_PREFIX_SAME, '_caop');

$SetPeriod = doctor_param('set', $USER_PROFILE['doctor_id'], $doctor_param, $doctor_value);

if ($SetPeriod['stat'] === true)
{
	$response['stat'] = true;
}
