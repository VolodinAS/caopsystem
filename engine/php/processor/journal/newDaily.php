<?php
$response['stage'] = $action;
$response['msg'] = 'begin';

$HTTP = $_POST;
extract($HTTP, EXTR_PREFIX_SAME, '_caop');

$JournalRM = RecordManipulation($journal_id, CAOP_JOURNAL, 'journal_id');
if ( $JournalRM['result'] )
{
    $JournalData = $JournalRM['data'];
    
    $PatientData = getarr(CAOP_PATIENTS, "patid_id='{$JournalData['journal_patid']}'");
    if ( count($PatientData) == 1 )
    {
        $PatientData = $PatientData[0];
	
	    $BLANK_ARRAY_INIT_VALUES['daily_patid'] = $PatientData['patid_id'];
	    $BLANK_ARRAY_INIT_VALUES['daily_journal_id'] = $journal_id;
	    $BLANK_ARRAY_INIT_VALUES['daily_doctor_id'] = $JournalData['journal_doctor'];
	    $BLANK_ARRAY_INIT_VALUES['daily_main_dg_mkb'] = $JournalData['journal_ds'];
	    $BLANK_ARRAY_INIT_VALUES['daily_main_dg_text'] = $JournalData['journal_ds_text'];
	    $BLANK_ARRAY_INIT_VALUES['daily_add1_dg_mkb'] = $JournalData['journal_ds_follow'];
	    $BLANK_ARRAY_INIT_VALUES['daily_add1_dg_text'] = $JournalData['journal_ds_follow_text'];
	    $BLANK_ARRAY_INIT_VALUES['daily_anam_allergy'] = 'без особенностей.';
	    $BLANK_ARRAY_INIT_VALUES['daily_anam_family'] = 'без особенностей.';
	    $BLANK_ARRAY_INIT_VALUES['daily_recom'] = $JournalData['journal_recom'];
	    $BLANK_ARRAY_INIT_VALUES['daily_date_update_unix'] = time();
	    $BLANK_ARRAY_INIT_VALUES['daily_date_create'] = date("d.m.Y H:i", time());
	    
	    $paramValues = $BLANK_ARRAY_INIT_VALUES;
	
	    $NewBlank = appendData(CAOP_DAILY, $paramValues);
	    
	    if ( $NewBlank[ID] > 0 )
	    {
	    	
	    	$param_journal = array(
	    	    'journal_daily_id' => $NewBlank[ID]
	    	);
	    	$UpdateJournalDaily = updateData(CAOP_JOURNAL, $param_journal, $JournalData, "journal_id='{$JournalData['journal_id']}'");
	    	if ( $UpdateJournalDaily ['stat'] == RES_SUCCESS )
		    {
		    	$response['result'] = true;
		    } else
		    {
		    	$response['msg'] = 'Проблема добавления дневника в журнал';
		    }
	    	
	    } else
	    {
	    	$response['msg'] = 'Проблема добавления дневника';
	    }
    } else
    {
    	$response['msg'] = 'Такого пациента нет';
    }
	
	
	
    
//    $param_daily = array(
//        'journal_daily_id' => $JournalData['']
//    );

} else $response['msg'] = $JournalRM['msg'];