<?php
$checker_query = "SELECT journal_id, journal_day, journal_ds, journal_ds_text FROM {$CAOP_JOURNAL} WHERE 1 ORDER BY journal_id ASC";
$checker_result = mqc($checker_query);
$checker_rows = mnr($checker_result);

$TOTAL = $checker_rows;
$CORRECT = 0;
$INCORRECT = 0;
$SUMMARY = 0;

if ($checker_rows > 0)
{
	$checker_data = mr2a($checker_result);
	
	foreach ($checker_data as $journal_data)
	{
		$diagnosis = $journal_data['journal_ds'];
		
		$MKB_DATA = CheckMKBCode($diagnosis);
		$correctMKB = $MKB_DATA['value'];
		
		if ($correctMKB !== false)
		{
			$CORRECT++;
		} else
		{
			$INCORRECT++;
			
			$mkbEditor = mysqleditor_field_generator(
				$CAOP_JOURNAL,
				'journal_id',
				$journal_data['journal_id'],
				'journal_ds',
				0,
				'',
				'data-adequate="MKB" id="mkb_checker'.$journal_data['journal_id'].'" data-return="#mkb_checker'.$journal_data['journal_id'].'" data-returntype="input" data-returnfunc="value"',
				'input',
				'text',
				'form-control mysqleditor col-1 mkbDiagnosis',
				'',
				'МКБ-код',
				$journal_data['journal_ds']
			);
			
			?>
            <label class="sr-only"
                   for="labby_<?=$journal_data['journal_id'];?>">ID <?=$journal_data['journal_id'];?></label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <div class="input-group-text">
                        <a href="/journalAlldays/<?=$journal_data['journal_day'];?>/light<?=$journal_data['journal_id'];?>" target="_blank"><?=$journal_data['journal_id'];?></a>
                    </div>
                </div>
                <?=$mkbEditor['input'];?>


                <div class="input-group-prepend">
		            <?=$journal_data['journal_ds_text'];?>
                </div>
            </div>
            <br>
			<?php
		}
	}
	
	$SUMMARY = $CORRECT + $INCORRECT;
	
	debug('$CORRECT: ' . $CORRECT);
	debug('$INCORRECT: ' . $INCORRECT);
	debug('$SUMMARY: ' . $SUMMARY);
	
//	debug( CheckMKBCode('D13/6') );
}