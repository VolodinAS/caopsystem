<?php
$query_journal = "SELECT * FROM {$CAOP_JOURNAL} INNER JOIN {$CAOP_DAYS} ON {$CAOP_JOURNAL}.journal_day={$CAOP_DAYS}.day_id ORDER BY {$CAOP_JOURNAL}.journal_id ASC";
//debug($query_journal);
$result_journal = mqc($query_journal);
$amount_journal = mnr($result_journal);
$JournalDay = mr2a($result_journal);
//debug($amount_journal);
//debug(count($JournalDay));

//debug($JournalDay[0]);
$npp = 1;
foreach ($JournalDay as $journal_item)
{
	$debug_str = "";
//	debug($journal_item);
	$debug_str .= "#{$npp}";
	$debug_str .= "; ID_{$journal_item['journal_id']}";
	$NORMAL_DATE = $journal_item['day_date'];
	$UNIX_OF_NORMAL_DATE = birthToUnix($NORMAL_DATE);
	$UNIX_TO_DATE = date("d.m.Y", $UNIX_OF_NORMAL_DATE);
	if ( $UNIX_TO_DATE == $NORMAL_DATE )
	{
		$debug_str .= "; date_is_adequate[{$NORMAL_DATE}|{$UNIX_OF_NORMAL_DATE}|{$UNIX_TO_DATE}]";
	} else
	{
		$debug_str .= "; DATE_NOT_ADEQUATE[{$NORMAL_DATE}|{$UNIX_OF_NORMAL_DATE}|{$UNIX_TO_DATE}]";
		debug($debug_str);
		break;
	}
	
	if ( (int)$UNIX_OF_NORMAL_DATE == (int)$journal_item['journal_unix'] )
	{
		$debug_str .= "; unixes_equals[{$UNIX_OF_NORMAL_DATE}|{$journal_item['journal_unix']}]";
	} else
	{
		$debug_str .= "; UNIXES_NOT_EQUALS[{$UNIX_OF_NORMAL_DATE}|{$journal_item['journal_unix']}]";
		$paramValues = array(
		    'journal_unix'  =>  $UNIX_OF_NORMAL_DATE
		);
		$UpdateJournalUnix = updateData(CAOP_JOURNAL, $paramValues, $journal_item, "journal_id='{$journal_item['journal_id']}'");
		if ( $UpdateJournalUnix['stat'] == RES_SUCCESS )
		{
			$debug_str .= "; unixes_was_updated[{$UNIX_OF_NORMAL_DATE}|{$UpdateJournalUnix['journal_unix']}]";
			if ( (int)$UNIX_OF_NORMAL_DATE == (int)$UpdateJournalUnix['journal_unix'] )
			{
				$debug_str .= "; unixes_now_equals[{$UNIX_OF_NORMAL_DATE}|{$UpdateJournalUnix['journal_unix']}]";
			} else
			{
				$debug_str .= "; WTF_IS_GOING_ON???";
			}
		}
//		debug($debug_str);
//		break;
	}
	
//	debug($NORMAL_DATE);
//	debug($UNIX_OF_NORMAL_DATE);
//	debug($UNIX_TO_DATE);
	
//	break;
	debug($debug_str);
	$npp++;
}