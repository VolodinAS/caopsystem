<?php
$response['stage'] = $action;
$response['msg'] = 'begin';

$HTTP = $_POST;
extract($HTTP, EXTR_PREFIX_SAME, '_caop');

$response['notify'] = '';

$JournalData = getarr(CAOP_JOURNAL, "journal_id='{$journal_id}'");
$response['debug']['$JournalData'] = $JournalData;
if ( count($JournalData) == 1 )
{
    $response['result'] = true;
    $JournalData = $JournalData[0];
    
//    $response['debug']['$JournalData'] = $JournalData;
//    $response['debug']['$MKBDispLinear'] = $MKBDispLinear;
    $journal_mkb_disp = $JournalData['journal_disp_mkb'];
    $journal_mkb_main = $JournalData['journal_ds'];
    $MKB = "";
    $PLACE = "";
    $go_next = false;
    if ( strlen($journal_mkb_disp) > 0 )
    {
        $MKB = $journal_mkb_disp;
        $PLACE = "НАПРАВИТЕЛЬНЫЙ диагноз";
        $NOTIFY = "Не забудьте отметить пациента, как диспансерного и выбрать ЛПУ прикрепления!";
        $go_next = true;
    } else
    {
        if ( strlen($journal_mkb_main) > 0 ) 
        {
            $MKB = $journal_mkb_main;
            $PLACE = "ОСНОВНОЙ диагноз";
            $NOTIFY = "Возможно, Вам необходимо будет добавить его в отчёт!";
            $go_next = true;
        }
    }
    
    $response['debug']['$MKB'] = $MKB;
    $response['debug']['$PLACE'] = $PLACE;
    
    if ( $go_next )
    {
        $go_next = false;
        
        $MKBDispancer = CheckMKBDispancer($MKB, $MKBDispLinear);
        
//        $response['debug']['$MKBDispancer'] = $MKBDispancer;
        
//        if ( in_array($MKB, $MKBDispLinear) )
        if ( $MKBDispancer ['result'] === true)
        {
            $response['notify'] = wrapper("ВНИМАНИЕ!") . ' Указанный Вами '.$PLACE.' подходит под список диспансерных диагнозов! ' . $NOTIFY;
        } else
        {
	        $response['notify'] = '';
        }
    }
    
} else $response['msg'] = 'Визита с таким ID не существует';