<?php
$response['stage'] = $action;
$response['msg'] = 'begin';

$HTTP = $_POST;
extract($HTTP, EXTR_PREFIX_SAME, '_caop');

$SPORM = RecordManipulation($spo_id, CAOP_SPO, 'spo_id');
if ( $SPORM['result'] )
{
    $SPOData = $SPORM['data'];
    
    $param_reset_spo = array(
        'journal_spo_id' => 0
    );
    
    $ResetSPOJournal = updateData(CAOP_JOURNAL, $param_reset_spo, [], "journal_spo_id='{$SPOData['journal_spo_id']}'");
    if ( $ResetSPOJournal['stat'] == RES_SUCCESS )
    {
    	$DeleteSPO = deleteitem(CAOP_SPO, $PK[CAOP_SPO] . "='{$SPOData[$PK[CAOP_SPO]]}'");
    	if ( $DeleteSPO ['result'] === true )
	    {
	    	$response['result'] = true;
	    }
    } else $response['msg'] = 'Проблема сброса СПО с визитов';

} else $response['msg'] = $SPORM['msg'];