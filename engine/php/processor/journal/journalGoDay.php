<?php
$response['stage'] = $action;
$response['debug']['$_POST'] = $_POST;
$response['msg'] = 'begin';

extract($_POST);

if (strlen($dater))
{

} else
{
	$dater = date("Y-m-d", time());
}

$unix = strtotime($dater);
$GetDay = getarr(CAOP_DAYS, "day_unix='{$unix}' AND day_doctor='{$USER_PROFILE['doctor_id']}'");
if (count($GetDay) == 1)
{
	$GetDay = $GetDay[0];
	$currentUnix = strtotime(date("Y-m-d", time()));
	$response['debug']['$currentUnix'] = $currentUnix;
	$response['debug']['day_unix'] = $GetDay['day_unix'];
	if (intval($currentUnix) == intval($GetDay['day_unix']))
	{
		$response['result'] = true;
		$response['dater'] = '/journalCurrent';
	} else
	{
		$response['result'] = true;
		$response['dater'] = '/journalAlldays/' . $GetDay['day_id'];
	}
} else
{
	if (count($GetDay) == 0)
	{
		$response['msg'] = 'Такого дня приёма еще нет';
	} else $response['msg'] = 'Слишком много дней приёма на эту дату';
}