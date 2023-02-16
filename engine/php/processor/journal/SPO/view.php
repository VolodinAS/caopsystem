<?php
$response['stage'] = $action;
$response['msg'] = 'begin';

$HTTP = $_POST;
extract($HTTP, EXTR_PREFIX_SAME, '_caop');

$SPOReasonTypes = getarr(CAOP_SPO_REASON_TYPES, "reason_enabled='1'", "ORDER BY reason_type_order ASC");
$SPOReasonTypesId = getDoctorsById($SPOReasonTypes, 'reason_type_id');


$JournalInfirst = getarr(CAOP_INFIRST, 1, "ORDER BY infirst_id ASC");
$JournalInfirstId = getDoctorsById($JournalInfirst, 'infirst_id');

$CaseStatusesList = getarr(CAOP_CASESTATUS, "casestatus_enabled='1'", "ORDER BY casestatus_order ASC");
$CaseStatusesListId = getDoctorsById($CaseStatusesList, 'casestatus_id');

$RegButtons = getarr(CAOP_REG_BUTTONS, 1, "ORDER BY reg_button_id ASC");

if ( $spo_id == 0 )
{
	$response['result'] = true;
//	$response['htmlData'] .= 'вывести всё';
	$SPOData = getarr(CAOP_SPO, "spo_patient_id={$patient_id}");
	if ( count($SPOData) > 0 )
	{
//	    $response['htmlData'] .= debug_ret($SPOData);
		$PatientPersonalData = getPatientDataById($patient_id);
		
		if ( $PatientPersonalData ['result'] === true )
		{
			$PatientPersonalData = $PatientPersonalData['data']['personal'];
			
			$response['htmlData'] .= bt_notice(wrapper('Пациент: ') . editPersonalDataLink( mb_ucwords($PatientPersonalData['patid_name']), $PatientPersonalData[$PK[CAOP_PATIENTS]] ), BT_THEME_SUCCESS, 1);
			
			foreach ($SPOData as $spoItem)
			{
				$DoctorData = $DoctorsListId['id' . $spoItem['spo_start_doctor_id']];
				$docname = docNameShort($DoctorData);
				$docname_full = docNameShort($DoctorData, 'famimot');
				$gender_open = 'открыл';
				$gender = getGender($docname_full);
				if ( $gender == 1 || $gender == 0 ) $gender_open = 'открыл';
				elseif ( $gender == 2 ) $gender_open = 'открыла';
				
				$spo_date_set = 'нет даты';
				if ( $spoItem['spo_unix_accounting_set'] != 0 )
				{
					$spo_date_set = date(DMY, $spoItem['spo_unix_accounting_set']);
				}
				
				$response['htmlData'] .= spoiler_begin_return(
					'СПО от ' . date(DMY, $spoItem['spo_start_date_unix']) . ' '.wrapper('['.$spoItem['spo_mkb_directed'].' -> '.$spoItem['spo_mkb_finished'].' ('.$spo_date_set.')]') . ', '.$gender_open.' ' . $docname,
					'spo_main_'. $spoItem[$PK[CAOP_SPO]],
					'');
				{
					$JournalVisits_query = "
						SELECT * FROM ".CAOP_JOURNAL." journal
						LEFT JOIN ".CAOP_DAYS." days ON days.".$PK[CAOP_DAYS]."=journal.journal_day
						WHERE journal.journal_spo_id='{$spoItem[$PK[CAOP_SPO]]}'
						ORDER BY days.day_unix DESC
					";
					$JournalVisits_result = mqc($JournalVisits_query);
					$JournalVisits = mr2a($JournalVisits_result);

//	$response['htmlData'] .= $JournalVisits_query;
					
					$response['htmlData'] .= spoiler_begin_return('Данные СПО', 'spo_'. $spoItem[$PK[CAOP_SPO]]);
					$response['htmlData'] .= debug_ret($spoItem);
					$response['htmlData'] .= spoiler_end_return();
					$response['htmlData'] .= bt_divider(1);
					
					if ( count($JournalVisits) > 0 )
					{
						foreach ($JournalVisits as $visit)
						{
							$VisitDay = extractValueByKey($visit, 'day_');
							$closed = true;
							include ("engine/html/include/visits/inc_journal.php");
						}
					} else $response['htmlData'] .= bt_notice('У данного СПО нет визитов', BT_THEME_WARNING, 1);
				}
				$response['htmlData'] .= spoiler_end_return();
				$response['htmlData'] .= '<br>';
			}
			
		} else $response['htmlData'] .= bt_notice('Пациента нет в системе', BT_THEME_WARNING, 1);
		
	} else $response['htmlData'] .= bt_notice('У пациента нет СПО', BT_THEME_WARNING, 1);
} else
{
	$SPORM = RecordManipulation($spo_id, CAOP_SPO, 'spo_id');
	if ( $SPORM['result'] )
	{
		$SPOData = $SPORM['data'];
		
		$PatientPersonalData = getPatientDataById($SPOData['spo_patient_id']);
		if ( $PatientPersonalData ['result'] === true )
		{
			$PatientPersonalData = $PatientPersonalData['data']['personal'];
			
			$response['result'] = true;

//    $response['htmlData'] .= debug_ret($SPOData);
			$JournalVisits_query = "
				SELECT * FROM ".CAOP_JOURNAL." journal
				LEFT JOIN ".CAOP_DAYS." days ON days.".$PK[CAOP_DAYS]."=journal.journal_day
				WHERE journal.journal_spo_id='{$SPOData[$PK[CAOP_SPO]]}'
				ORDER BY days.day_unix DESC
			";
			$JournalVisits_result = mqc($JournalVisits_query);
			$JournalVisits = mr2a($JournalVisits_result);

//	$response['htmlData'] .= $JournalVisits_query;
			
			$response['htmlData'] .= spoiler_begin_return('Данные СПО', 'spo_'. $SPOData[$PK[CAOP_SPO]]);
			$response['htmlData'] .= debug_ret($SPOData);
			$response['htmlData'] .= spoiler_end_return();
			$response['htmlData'] .= bt_divider(1);
			
			if ( count($JournalVisits) > 0 )
			{
				foreach ($JournalVisits as $visit)
				{
					$VisitDay = extractValueByKey($visit, 'day_');
					include ("engine/html/include/visits/inc_journal.php");
				}
			} else $response['htmlData'] .= bt_notice('У данного СПО нет визитов', BT_THEME_WARNING, 1);
		} else $response['msg'] = $PatientPersonalData['msg'];
		
		
		
	} else $response['msg'] = $SPORM['msg'];
}

