<?php
$response['stage'] = $action;

$day_id = $_POST['day_id'];

$GetDay = getarr('caop_days', "day_id={$day_id} AND day_doctor='{$USER_PROFILE['doctor_id']}'");
if ( count($GetDay) == 1 )
{
	$GetDay = $GetDay[0];
	$ClearJournal = deleteitem('caop_journal', "journal_doctor='{$USER_PROFILE['doctor_id']}' AND journal_day='{$GetDay['day_id']}'");

	if ( $ClearJournal['result'] === true )
	{
		$DeleteDay = deleteitem('caop_days', "day_doctor='{$USER_PROFILE['doctor_id']}' AND day_id='{$GetDay['day_id']}'", 1);
		$response['debug']['$DeleteDay'] = $DeleteDay;
		if ( $DeleteDay['result'] === true )
		{
			LoggerGlobal(
				LOG_TYPE_REMOVE_JOURNAL,
				$_SERVER['REMOTE_ADDR'],
				$CAT_DATA['cat_id'],
				$USER_PROFILE['doctor_id'],
				'id дня приёма',
				$GetDay['day_id'],
				'id врача удаленного дня',
				$GetDay['day_doctor']
			);
		    
			$response['result'] = true;
		} else
		{
			$response['msg'] = $DeleteDay;
		}
	} else
	{
		$response['msg'] = $ClearJournal;
	}

} else
{
	$response['msg'] = 'Дня не существует или день не принадлежит Вам';
}