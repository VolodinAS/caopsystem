<?php
$response['stage'] = $action;
$Today_Array = getarr('caop_days', "day_unix='{$CURRENT_DAY['unix']}' AND day_doctor='{$USER_PROFILE['doctor_id']}'");

$DefaultNurse = doctor_param('get', $USER_PROFILE['doctor_id'], 'defaultNurse');
$MyNurseId = 0;
if ( $DefaultNurse['stat'] == RES_SUCCESS )
{
	$MyNurseId = $DefaultNurse['data']['settings_param_value'];
}

if ( count($Today_Array) == 0 )
{
	$newDay = array(
	 'day_doctor'    =>  $USER_PROFILE['doctor_id'],
	 'day_date'      =>  $CURRENT_DAY['format']['dd.mm.yyyy'],
	 'day_unix'      =>  $CURRENT_DAY['unix'],
	 'day_nurse'	 =>  $MyNurseId
	);
	$Append = appendData('caop_days', $newDay);
	if ( $Append[ID] > 0 )
	{
		$response['result'] = true;
		$response['msg'] = 'День приёма успешно создан';
	}
} else
{
	$response['msg'] = 'У Вас уже есть на сегодня созданный день! Обновите страницу...';
}