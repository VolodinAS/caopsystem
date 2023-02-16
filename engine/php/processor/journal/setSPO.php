<?php
$response['stage'] = $action;
$response['msg'] = 'begin';

$HTTP = $_POST;
extract($HTTP, EXTR_PREFIX_SAME, '_caop');


$SPORM = RecordManipulation($spo_id, CAOP_SPO, 'spo_id');
if ( $SPORM['result'] )
{
    $SPOData = $SPORM['data'];
    
    $JournalRM = RecordManipulation($journal_id, CAOP_JOURNAL, 'journal_id');
    if ( $JournalRM['result'] )
    {
        $JournalData = $JournalRM['data'];
        
        $spo_update = array(
            'journal_spo_id'    =>  $SPOData['spo_id']
        );
        
        $UpdateJournal = updateData(CAOP_JOURNAL, $spo_update, $JournalData, "journal_id='{$JournalData['journal_id']}'");
        if ( $UpdateJournal['stat'] == RES_SUCCESS )
        {
        	$response['result'] = true;
        } else
        {
        	$response['msg'] = 'ПРОБЛЕМА С ОБНОВЛЕНИЕМ JOURNAL';
        	$response['debug']['$UpdateJournal'] = $UpdateJournal;
        }
        
    
    } else $response['msg'] = $JournalRM['msg'];

} else $response['msg'] = $SPORM['msg'];