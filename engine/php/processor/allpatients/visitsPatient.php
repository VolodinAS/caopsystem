<?php
$response['stage'] = $action;

$patid_id = $_POST['patid_id'];

$SPOReasonTypes = getarr(CAOP_SPO_REASON_TYPES, "reason_enabled='1'", "ORDER BY reason_type_order ASC");
$SPOReasonTypesId = getDoctorsById($SPOReasonTypes, 'reason_type_id');

$ResearchTypes = getarr(CAOP_RESEARCH_TYPES, "type_enabled='1'", "ORDER BY type_order ASC");
$ResearchTypesId = getDoctorsById($ResearchTypes, 'type_id');


$JournalInfirst = getarr(CAOP_INFIRST, 1, "ORDER BY infirst_id ASC");
$JournalInfirstId = getDoctorsById($JournalInfirst, 'infirst_id');

$CaseStatusesList = getarr(CAOP_CASESTATUS, "casestatus_enabled='1'", "ORDER BY casestatus_order ASC");
$CaseStatusesListId = getDoctorsById($CaseStatusesList, 'casestatus_id');

$RegButtons = getarr(CAOP_REG_BUTTONS, 1, "ORDER BY reg_button_id ASC");

$PatientPersonalData = getarr(CAOP_PATIENTS, "patid_id='{$patid_id}'");
if ( count($PatientPersonalData) == 1 ) {
    $PatientPersonalData = $PatientPersonalData[0];

//    $Visits = getarr(CAOP_JOURNAL, "journal_patid='{$PatientPersonalData['patid_id']}'", "ORDER BY journal_id DESC");
	$Visits_query = "
		SELECT * FROM ".CAOP_JOURNAL." journal
		LEFT JOIN ".CAOP_SPO." spo ON spo.".$PK[CAOP_SPO]."=journal.journal_spo_id
		LEFT JOIN ".CAOP_DAYS." days ON days.".$PK[CAOP_DAYS]."=journal.journal_day
		WHERE journal.journal_patid='{$PatientPersonalData[$PK[CAOP_PATIENTS]]}'
		ORDER BY journal.journal_id DESC
	";
	
	$Visits_result = mqc($Visits_query);
	$Visits = mr2a($Visits_result);
	

    if ( count($Visits) > 0 )
    {
    	/*подготовка дат*/
	    for ($i=0; $i<count($Visits); $i++)
	    {
	        $visit = $Visits[$i];
//		    $VisitDay = getarr(CAOP_DAYS, "day_id='{$visit['journal_day']}'");
//		    $VisitDay = $VisitDay[0];
		    $Visits[$i]['patidcard_patient_done'] = $visit['day_date'];
		    $Visits[$i]['visitData'] = extractValueByKey($visit, 'day_');
	    }

	    $PATID = $PatientPersonalData['patid_id'];
	    $ResearchesData = getarr(CAOP_RESEARCH, "research_patid='{$PATID}'");
	    $CitologyData = getarr(CAOP_CITOLOGY, "citology_patid='{$PATID}'");

	    $ResearchesTotal = array_merge($ResearchesData, $CitologyData);
	    $ResearchesTotal = array_merge($ResearchesTotal, $Visits);

	    for ($i=0; $i<count($ResearchesTotal); $i++)
	    {
		    $ResearchesTotal[$i]['patidcard_patient_done_unix'] = strtotime($ResearchesTotal[$i]['patidcard_patient_done']);
	    }

        $ResearchesTotal = array_orderby($ResearchesTotal, 'patidcard_patient_done_unix', SORT_DESC);

        $response['htmlData'] = '';
        $npp = count($ResearchesTotal);
        foreach ($ResearchesTotal as $visit) {

//	        $response['htmlData'] .= debug_ret($visit);

	        if ( isset($visit['journal_id']) )
	        {
		        $VisitDay = $visit['visitData'];
		        include ("engine/html/include/visits/inc_journal.php");
		        
		        $npp--;
	        }
	        if ( isset($visit['research_id']) )
	        {
//		        $response['htmlData'] .= debug_ret($visit);
		        $ResearchTitle = $ResearchTypesId['id'.$visit['research_type']]['type_title'];
		        $ResearchDesc = $ResearchTitle . ' ' . $visit['research_area'];
		        $response['htmlData'] .= spoiler_begin_return('<b>[#'.$npp.']</b> ' . $ResearchDesc . ' от '.$visit['patidcard_patient_done'], 'research'.$visit['research_id'], '');
		        $response['htmlData'] .= $visit['research_result'];
		        if ( strlen($visit['research_morph_text']) > 0 )
		        {
			        $response['htmlData'] .= '<div class="dropdown-divider"></div>';
			        $ident_format = ( strlen($visit['research_morph_ident']) > 0 ) ? $visit['research_morph_ident'] : 'нет';
			        $date_format = ( strlen($visit['research_morph_date']) > 0 ) ? ' от ' . $visit['research_morph_date'] : '';
			        $response['htmlData'] .= '<b>Гистология</b> №' . $ident_format . $date_format . ' - ' . $visit['research_morph_text'];
		        }
		        $response['htmlData'] .= '
		        <br/><a target="_blank" href="/allwaiting/patient'.$PatientPersonalData['patid_id'].'"><b>Просмотреть</b></a>
		        ';
		        $response['htmlData'] .= spoiler_end_return();
		        $npp--;
//		        <br/><a target="_blank" href="/allwaiting/light'.$visit['research_id'].'#respat'.$visit['research_id'].'"><b>Просмотреть</b></a>
	        }
	        if ( isset($visit['citology_id']) )
	        {
//		        $response['htmlData'] .= debug_ret($visit);
		        $response['htmlData'] .= spoiler_begin_return('<b>[#'.$npp.']</b> Цитология №'.$visit['citology_result_id'].' от '.$visit['patidcard_patient_done'], 'citology'.$visit['citology_id'], '');
		        $response['htmlData'] .= $visit['citology_result_text'];
		        $response['htmlData'] .= '
		        <br/><a target="_blank" href="/journalCitology/light'.$visit['citology_id'].'#citopat'.$visit['citology_id'].'"><b>Просмотреть</b></a>
		        ';
		        $response['htmlData'] .= spoiler_end_return();
		        $npp--;
	        }



	        /*$VisitDay = getarr(CAOP_DAYS, "day_id='{$visit['journal_day']}'");
	        if ( count($VisitDay) == 1 )
	        {

	        } else
	        {
		        $response['htmlData'] = 'Нет такой даты посещения';
	        }*/

        }

    } else
    {
        $response['htmlData'] = 'Нет визитов у пациента';
    }

    $response['result'] = true;
}