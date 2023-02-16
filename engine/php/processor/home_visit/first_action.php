<?php
$response['stage'] = $action;
$response['msg'] = 'begin';

$HTTP = $_POST;
extract($HTTP, EXTR_PREFIX_SAME, '_caop');

$param_home_action = array(
    'action_home_id' => $visit_id,
	'action_type_id' => 1,
	'action_personal_id' => $USER_PROFILE['doctor_id'],
	'action_unix' => time()
);

$AddAction = appendData(CAOP_HOME_VISIT_ACTIONS, $param_home_action);
if ($AddAction[ID] > 0)
{
	$response['result'] = true;
} else
{
	$response['msg'] = 'Проблема при добавлении действия';
	$response['debug']['$AddAction'] = $AddAction;
}