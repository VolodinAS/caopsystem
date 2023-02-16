<?php
$response['stage'] = $action;
$response['msg'] = 'begin';

$HTTP = $_POST;
extract($HTTP, EXTR_PREFIX_SAME, '_caop');

$JournalRM = RecordManipulation($journal_id, CAOP_JOURNAL, 'journal_id');
if ( $JournalRM['result'] )
{
	$JournalData = $JournalRM['data'];
	
	if ( $JournalData['journal_daily_id'] > 0 )
	{
		$zerroes = false;
		$CheckDaily = getarr(CAOP_DAILY, "daily_id='{$JournalData['journal_daily_id']}'");
		if ( count($CheckDaily) > 0 )
		{
		    $CheckDaily = $CheckDaily[0];
		    
		    $DeleteDaily = deleteitem(CAOP_DAILY, "daily_id='{$CheckDaily['daily_id']}'");
		    if ($DeleteDaily ['result'] === true)
		    {
			    $zerroes = true;
		    } else
		    {
		    	$response['msg'] = 'Проблема удаления дневника';
		    }
		    
		} else
		{
			$zerroes = true;
			$response['msg'] = 'Указанный дневник не найден';
		}
		
		if ( $zerroes )
		{
			$param_update = array(
				'journal_daily_id' => '0'
			);
			$UpdateJournalDaily = updateData(CAOP_JOURNAL, $param_update, $JournalData, "journal_id='{$JournalData['journal_id']}'");
			if ( $UpdateJournalDaily['stat'] == RES_SUCCESS )
			{
				$response['result'] = true;
			} else
			{
				$response['msg'] = 'Проблема обнуления дневника в журнале';
			}
		}
		
	} else
	{
		$response['msg'] = 'Нет соответствующего дневника для удаления';
	}
	
} else $response['msg'] = $JournalRM['msg'];