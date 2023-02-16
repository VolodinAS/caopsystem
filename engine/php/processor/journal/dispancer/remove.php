<?php
$response['stage'] = $action;
$response['msg'] = 'begin';

$HTTP = $_POST;
extract($HTTP, EXTR_PREFIX_SAME, '_caop');

$JournalRM = RecordManipulation($journal_id, CAOP_JOURNAL, 'journal_id');
if ( $JournalRM['result'] )
{
    $JournalData = $JournalRM['data'];

    if ( strlen($JournalData['journal_disp_self']) > 0 )
    {
        $DispancerRM = RecordManipulation($JournalData['journal_disp_self'], CAOP_DISP_PATIENTS, 'dispancer_id');
        if ( $DispancerRM['result'] )
        {
            $DispancerData = $DispancerRM['data'];
            
            $DeleteDispancer = deleteitem(CAOP_DISP_PATIENTS, $PK[CAOP_DISP_PATIENTS] . "='{$DispancerData[$PK[CAOP_DISP_PATIENTS]]}'");
            if ( $DeleteDispancer ['result'] === true )
            {
            	
            	$param_journal_update = array(
            	    'journal_disp_self' => 0
            	);
            	
            	$UpdateJournal = updateData(CAOP_JOURNAL, $param_journal_update, $JournalData, $PK[CAOP_JOURNAL] . "='{$JournalData[$PK[CAOP_JOURNAL]]}'");
            	if ( $UpdateJournal['stat'] == RES_SUCCESS )
	            {
	            	$response['result'] = true;
	            	
	            } else $response['msg'] = 'Проблема с обновлением журнала';
            	
            } else $response['msg'] = 'Проблема с удалением диспансерного диагноза!';
            
        } else $response['msg'] = $DispancerRM['msg'];
    } 

} else $response['msg'] = $JournalRM['msg'];