<?php
foreach ($PATIENTS_DATAS as $tableIndex=>$patTable)
{
	$debugger = [];
	$debugger['ТАБЛИЦА'] = $tableIndex;
	$distinct_query = "SELECT {$patTable['field_id']}, {$patTable['field_patid']} FROM {$patTable['table']} GROUP BY {$patTable['field_patid']} ORDER BY {$patTable['field_id']} ASC";
	$debugger['$distinct_query'] = $distinct_query;
	$distinct_result = mqc($distinct_query);
	$DistinctData = mr2a($distinct_result);
	$debugger['$DistinctData'] = [];
	foreach ($DistinctData as $distinctDatum)
	{
		$debugger2 = [];
		$patient_id = $distinctDatum[$patTable['field_patid']];
		$record_id = $distinctDatum[$patTable['field_id']];
		$debugger2['$patient_id'] = $patient_id;
		$PatientData = getarr(CAOP_PATIENTS, "patid_id='$patient_id'");
		$debugger2['amount'] = count($PatientData);
		$debugger2[$tableIndex] = $record_id;
		
		$debugger['$DistinctData'][] = $debugger2;
	}
	
	debug($debugger);
}