<?php
$response['stage'] = $action;
$response['msg'] = 'begin';

$HTTP = $_POST;
extract($HTTP, EXTR_PREFIX_SAME, '_caop');

$JournalData = getarr(CAOP_JOURNAL, "journal_id='{$journal_id}'");
if ( count($JournalData) == 1 )
{
	
	$response['htmlData'] = '';
	
	$Journal = $JournalData[0];
	
	
	
	$PatientData = getarr(CAOP_PATIENTS, "patid_id='{$Journal['journal_patid']}'");
	
	if ( count($PatientData) == 1 )
	{
		$Patient = $PatientData[0];
		
		$response['result'] = true;
		
		$header = wrapper('Заполните все поля для распечатки формы 1А клинической группы', 'h6');
		
		$response['htmlData'] .= $header;


		$response['htmlData'] .= debug_ret($Journal);
		$response['htmlData'] .= debug_ret($Patient);
	 
	} else
	{
		$response['msg'] = 'Такого пациента не существует';
	}
	
} else
{
	$response['msg'] = 'Записи в журнале не найдено';
}