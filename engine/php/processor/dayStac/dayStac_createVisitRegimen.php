<?php
$response['stage'] = $action;
$response['debug']['$_POST'] = $_POST;
$response['msg'] = 'begin';

$HTTP = $_POST;
extract($HTTP, EXTR_PREFIX_SAME, '_caop');

if ( strlen($json_data) > 0 )
{
	$VisregList = json_decode( trim($json_data), 1 );
} else
{
	$VisregList[0]['visreg_id'] = $visreg_id;
}

//$response['debug']['$VisregList'] = $VisregList;

$unix = strtotime($current_date);
$normal_date = date("d.m.Y", $unix);

$response['debug']['$unix'] = $unix;
$response['debug']['$normal_date'] = $normal_date;

$VisregListSummary = array();

//

$current_visit = 0;
$total_visit = 0;
$isGetVisits = false;

foreach ($VisregList as $visregData)
{
	$visreg_id = $visregData['visreg_id'];
	$VisregRM = RecordManipulation($visreg_id, CAOP_DS_VISITS_REGIMENS, 'visreg_id');
	if ( $VisregRM['result'] )
	{
		$VisregData = $VisregRM['data'];
		
		$response['debug']['$VisregData'] = $VisregData;
		
		if ( !$isGetVisits )
		{
			$isGetVisits = true;
			$LastVisits = getarr(CAOP_DS_VISITS, "visreg_dspatid='{$VisregData['visreg_dspatid']}' AND visreg_dirlist_id='{$VisregData['visreg_dirlist_id']}' ORDER BY visreg_visit_unix DESC LIMIT 1");
			if ( count($LastVisits) > 0 )
			{
				$LastVisits = $LastVisits[0];
				$current_visit = intval($LastVisits['visreg_visit_current']);
				$current_visit += 1;
				$total_visit = $LastVisits['visreg_visit_total'];
			} else
			{
				$current_visit = 1;
				$total_visit = '';
			}
		}
		
		$VisitPatientData = array(
			'visreg_regimen_id'         =>  $VisregData['visreg_id'],
			'visreg_dspatid'            =>  $VisregData['visreg_dspatid'],
			'visreg_dirlist_id'         =>  $VisregData['visreg_dirlist_id'],
			'visreg_doctor_id'          =>  $USER_PROFILE['doctor_id'],
			'visreg_title'              =>  $VisregData['visreg_title'],
			'visreg_drug'               =>  $VisregData['visreg_drug'],
			'visreg_dose'               =>  $VisregData['visreg_dose'],
			'visreg_dose_measure_type'  =>  $VisregData['visreg_dose_measure_type'],
			'visreg_dose_period_type'   =>  $VisregData['visreg_dose_period_type'],
			'visreg_dasigna'            =>  $VisregData['visreg_dasigna'],
			'visreg_freq_amount'        =>  $VisregData['visreg_freq_amount'],
			'visreg_freq_period_amount' =>  $VisregData['visreg_freq_period_amount'],
			'visreg_freq_period_type'   =>  $VisregData['visreg_freq_period_type'],
			'visreg_visit_date'         =>  $normal_date,
			'visreg_visit_unix'         =>  $unix,
			'visreg_visit_current'      =>  $current_visit,
			'visreg_visit_total'        =>  $total_visit
		);
		$VisregListSummary[] = $VisitPatientData;
		
	} else $response['msg'] = $VisregRM['msg'];
}

$response['debug']['$VisregListSummary'] = $VisregListSummary;

if ( count($VisregListSummary) > 0 )
{
	
	foreach ($VisregListSummary as $visregVisit)
	{
		$AddVisit = appendData(CAOP_DS_VISITS, $visregVisit);
	}
	
	$response['result'] = true;
	$response['msg'] = 'Назначения добавлены в визит пациента';
	$response['unix'] = $unix;
	
} else
{
	$response['msg'] = 'Ошибка добавления визитов';
}

