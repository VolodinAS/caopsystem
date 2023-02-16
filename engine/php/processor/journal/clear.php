<?php
$response['stage'] = $action;

$Today = createNewDayByUnix(time(), $USER_PROFILE['doctor_id']);

$response['debug']['$Today'] = $Today;

$ClearJournal = deleteitem('caop_journal', "journal_doctor='{$USER_PROFILE['doctor_id']}' AND journal_day='{$Today['day_id']}'");
if ( $ClearJournal['result'] === true )
{
	
	$DayData = getarr(CAOP_DAYS, "day_id='{$Today['day_id']}'");
	$DayData = $DayData[0];
	
	LoggerGlobal(
		LOG_TYPE_CLEAR_JOURNAL,
		$_SERVER['REMOTE_ADDR'],
		$CAT_DATA['cat_id'],
		$USER_PROFILE['doctor_id'],
		'id дня приёма',
		$Today['day_id'],
		'id врача очищенного дня',
		$DayData['day_doctor']
	);
    
	$response['result'] = true;
} else
{
	$response['msg'] = $ClearJournal;
}