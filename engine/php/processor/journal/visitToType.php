<?php
$response['stage'] = $action;
$response['msg'] = 'begin';

$HTTP = $_POST;
extract($HTTP, EXTR_PREFIX_SAME, '_caop');
//$visit_type_id = 10;
$VisitTypes = getarr(CAOP_JOURNAL_VISIT_TYPE);
$VisitTypesId = getDoctorsById($VisitTypes, $PK[CAOP_JOURNAL_VISIT_TYPE]);

$JournalRM = RecordManipulation($journal_id, CAOP_JOURNAL, 'journal_id');
if ( $JournalRM['result'] )
{
    $JournalData = $JournalRM['data'];
	
	$VisitData = $VisitTypesId['id' . $visit_type_id];
	
	if ($VisitData)
	{
//		$response['debug']['$JournalData'] = $JournalData;
		$journal_update = array(
		    'journal_visit_type' => $visit_type_id
		);
		
		$JournalUpdate = updateData(CAOP_JOURNAL, $journal_update, $JournalData, "{$PK[CAOP_JOURNAL]}='{$JournalData[$PK[CAOP_JOURNAL]]}'");
		if ( $JournalUpdate['stat'] == RES_SUCCESS )
		{
			$response['msg'] = 'Тип услуги успешно изменен!';
			$response['result'] = true;
		} else $response['msg'] = 'Проблема с обновлением данных визита';
		
	} else $response['msg'] = 'Неверно выбран тип услуги!';

} else $response['msg'] = $JournalRM['msg'];

