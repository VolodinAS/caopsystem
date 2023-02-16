<?php
$response['stage'] = $action;
$response['msg'] = 'begin';

$HTTP = $_POST;
extract($HTTP, EXTR_PREFIX_SAME, '_caop');

$response['result'] = true;

$AreasUZI = getarr(CAOP_SCHEDULE_UZI_RESEARCH_AREA, "1", "ORDER BY area_title ASC");
$AreasUZIId = getDoctorsById($AreasUZI, 'area_id');

if ( strlen($patient_id) > 0 )
{
    $CheckPatient = getarr(CAOP_PATIENTS, "patid_id='{$patient_id}'");
    if ( count($CheckPatient) == 1 )
    {
        $CheckPatient = $CheckPatient[0];
        $search = "patient_pat_id='{$CheckPatient['patid_id']}'";
    } else
    {
    	$search = "1";
    }
} else
{
	// все талоны
	$search = "1";
}

$Talons = getarr(CAOP_SCHEDULE_UZI_PATIENTS, $search);
if ( count($Talons) > 0 )
{
    $response['htmlData'] .= '<button class="btn btn-sm btn-primary btn-printUziMany">Распечатать выбранные талоны на одной бумаге</button>';
    $response['htmlData'] .= '<div class="dropdown-divider"></div>';
	
	foreach ($Talons as $talon)
	{
		$response['htmlData'] .= spoiler_begin_return(wrapper('Талон на УЗИ ЦАОП №'.$talon['patient_id']), 'uzicaoptalon_' . $talon['patient_id'], '');
		$date_id = $talon['patient_date_id'];
		$patuzi_id = $talon['patient_time_id'];
		$journal_id = $talon['patient_journal_id'];
		include "engine/php/processor/journal/modals/inc_uzicaop_record.php";
		$response['htmlData'] .= spoiler_end_return();
    }
    
} else
{
	$response['htmlData'] .= bt_notice('У данного пациента нет талонов на УЗИ ЦАОП', BT_THEME_WARNING, 1);
}