<?php
$response['stage'] = $action;

$JournalPatient = getarr(CAOP_JOURNAL, "journal_id='{$_POST['journal_id']}'");

if ( count($JournalPatient) == 1 )
{
	$JournalPatient = $JournalPatient[0];
	$WAITING = ($JournalPatient['journal_waiting'] == 1) ? 0 : 1;

	$update = array(
		'journal_waiting' =>    $WAITING
	);

	$UpdateJournal = updateData(CAOP_JOURNAL, $update, $JournalPatient, "journal_id='{$JournalPatient['journal_id']}'");
	if ( $UpdateJournal['stat'] == RES_SUCCESS )
	{
		$response['result'] = true;
		$response['waiting'] = $WAITING;
	} else
	{
		$response['msg'] = $UpdateJournal;
	}

} else
{
	$response['msg'] = 'Пациент не найден';
}