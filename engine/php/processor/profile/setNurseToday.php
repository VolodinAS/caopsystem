<?php
$response['stage'] = $action;

$response['debug']['USER_PROFILE'] = $USER_PROFILE;

$nid = $_POST['nurseId'];

$DAY_ID = $_POST['day_id'];

$paramValues = array(
	'day_nurse' => $nid
);

$NurseDay = updateData(CAOP_DAYS, $paramValues, array(), "day_id='{$DAY_ID}'");

$response['result'] = true;