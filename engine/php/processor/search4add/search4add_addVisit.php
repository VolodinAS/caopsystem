<?php
$response['stage'] = $action;
$response['debug']['$_POST'] = $_POST;
$response['msg'] = 'begin';

$HTTP = $_POST;
extract($HTTP, EXTR_PREFIX_SAME, '_caop');

$PatientRM = RecordManipulation($patid_id, CAOP_PATIENTS, 'patid_id');
if ($PatientRM['result'])
{
	$PatientData = $PatientRM['data'];
	
	$PATIENT_ID = $PatientData['patid_id'];
	$Today_Array = getarr(CAOP_DAYS, "day_unix='{$CURRENT_DAY['unix']}' AND day_doctor='{$USER_PROFILE['doctor_id']}'");
	if (count($Today_Array) == 1)
	{
		$journal_ds = '';
		$journal_ds_text = '';
		$JournalData = getarr(CAOP_JOURNAL, "journal_patid='{$PATIENT_ID}' AND journal_ds_text!=''", "ORDER BY journal_id DESC LIMIT 0,1");
		if (count($JournalData) > 0)
		{
			$JournalData = $JournalData[0];
		}
		
		$JournalDataDisp = getarr(CAOP_JOURNAL, "journal_patid='{$PATIENT_ID}' AND journal_disp_lpu!='0' AND journal_disp_mkb!=''", "ORDER BY journal_id DESC LIMIT 0,1");
		if (count($JournalDataDisp) > 0)
		{
			$JournalDataDisp = $JournalDataDisp[0];
		}
		
//		$response['debug']['$JournalDataDisp'] = $JournalDataDisp;
		
		$Today_Array = $Today_Array[0];
		$paramValues_journal = array(
			'journal_day' => $Today_Array['day_id'],
			'journal_doctor' => $USER_PROFILE['doctor_id'],
			'journal_patid' => $PATIENT_ID,
			'journal_unix' => $CURRENT_DAY['unix'],
			'journal_update_unix' => time(),
			'journal_ds' => $JournalData['journal_ds'],
			'journal_ds_text' => $JournalData['journal_ds_text'],
			'journal_disp_lpu'  =>  $JournalDataDisp['journal_disp_lpu'],
			'journal_disp_mkb'  =>  $JournalDataDisp['journal_disp_mkb'],
			'journal_visit_type' => 1,
		);
		$NewJournalVisit = appendData(CAOP_JOURNAL, $paramValues_journal);
		if ($NewJournalVisit[ID] > 0)
		{
			$response['result'] = true;
			$response['msg'] = "Визит добавлен в приём!";
			
			
		} else
		{
			$response['msg'] = 'Проблема с добавлением визита';
			$response['debug']['$paramValues_journal'] = $paramValues_journal;
			$response['debug']['$NewJournalVisit'] = $NewJournalVisit;
		}
		
	} else
	{
		$response['msg'] = 'Вы еще не создали день для добавления визита';
		$response['debug']['$Today_Array'] = $Today_Array;
	}
	
} else $response['msg'] = $PatientRM['msg'];