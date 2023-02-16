<?php
$PrintParams = explode('/', $request_params);

$CompanyList = getarr(CAOP_INSURANCE, "insurance_enabled='1'", "ORDER BY insurance_title ASC");
$CompanyListId = getDoctorsById($CompanyList, 'insurance_id');

if ( ifound($PrintParams[0], "?") )
{
	$PrintParamsData = explode("?", $PrintParams[0]);
//		debug($PrintParamsData);
	if ( $PrintParamsData[0] == 'cancerList' )
	{
		require_once("engine/html/title_begin_print.php");
		require_once ( "engine/html/cancerList_print.php" );
		require_once("engine/html/title_end_print.php");
		exit();
	} else
	{
		$go_print_error = true;
		$go_print_msg = 'Не опознан тип документа';
	}
}

if ( count( $PrintParams ) > 0 )
{

	$go_journal = true;

	switch ($PrintParams[0])
	{

		case 'stat_howmany_phys_face_cured':
		case 'stat_howmany_visits':
		case 'dispancer_report':
		case 'nosology_report':
		case 'cancerNosology':
		case 'uzi_single_day':
		case 'uzicaop_talon':
		case 'dilutionMeans':
		case 'quartzingTime':
		
			$go_next = true;
			$go_journal = false;
			break;
		case 'reference_simple':
		case 'fgds':
		case 'fks':
		case 'ekg':
		case 'rentgen':
		case 'vich':
		case 'agree_research':
			$go_next = true;
			break;
		default:
			$go_print_error = true;
			$go_print_msg = 'Невозможно распечатать, так как неопознан тип документа';
			break;
	}

	if ( $go_journal )
	{
		$journal_id = $PrintParams[1];

		$PatientJournal = getarr(CAOP_JOURNAL, "journal_id='{$journal_id}'");
		if ( count($PatientJournal) == 1 )
		{
			$PatientJournal = $PatientJournal[0];


			$PatientPersonalData = getarr(CAOP_PATIENTS, "patid_id='{$PatientJournal['journal_patid']}'");

			if ( count($PatientPersonalData) == 1 )
			{
				$PatientPersonalData = $PatientPersonalData[0];

//            	debug($PatientPersonalData);
//            	debug($CompanyListId);
//            	debug('id'.$PatientPersonalData['patid_insurance_company']);
				$Insurance = $CompanyListId[ 'id'.$PatientPersonalData['patid_insurance_company'] ];
//	            debug($Insurance);
				$Insurance = $Insurance['insurance_title'];



				$Doctor = $DoctorsListId['id' . $PatientJournal['journal_doctor']];
				$doc_name = $Doctor['doctor_f'] . ' ' . $Doctor['doctor_i'] . ' ' . $Doctor['doctor_o'];
				$doc_name_sh = shorty($doc_name);

			} else
			{
				bt_notice('ПАЦИЕНТА НЕТ В БАЗЕ ДАННЫХ',BT_THEME_DANGER);
			}


		} else
		{
			$go_print_error = true;
			$go_print_msg = 'Невозможно распечатать, так как не найдена запись в журнале';
		}
	}

	if ( $go_next )
	{
		$document = "engine/html/document_" . $PrintParams[0] . ".php";
		//debug($document);
		require_once("engine/html/title_begin_print.php");
		require_once($document);
		require_once("engine/html/title_end_print.php");
		exit();

	}


}