<?php
$response['stage'] = $action;
$response['debug']['$_POST'] = $_POST;
$response['msg'] = 'begin';

$HTTP = $_POST;
extract($HTTP, EXTR_PREFIX_SAME, '_caop');

if ( $patid_id > 0 )
{
	$PatientRM = RecordManipulation($patid_id, CAOP_PATIENTS, 'patid_id');
	if ( $PatientRM['result'] )
	{
	    $PatientData = $PatientRM['data'];
	    
	    $response['debug']['$PatientData'] = $PatientData;
	    
	    $RouteSheetRM = RecordManipulation($PatientData['patid_id'], CAOP_ROUTE_SHEET, 'rs_patid', 1);
	    if ( $RouteSheetRM['result'] )
	    {
	        $RouteSheetData = $RouteSheetRM['data'];
			$response['result'] = true;
			
			if ( $RouteSheetRM['amount'] == 1 )
			{
				extract($RouteSheetData);
				include ("engine/php/processor/include/routeSheetParse.php");
			} else
			{
				foreach ($RouteSheetData as $routeSheetDatum)
				{
					extract($routeSheetDatum);
					include ("engine/php/processor/include/routeSheetParse.php");
				}
			}
			
		    
	        
	        $response['debug']['$RouteSheetData'] = $RouteSheetData;
	    
	    } else $response['msg'] = 'У ПАЦИЕНТА НЕТ МАРШРУТНЫХ ЛИСТОВ';
	
	} else $response['msg'] = $PatientRM['msg'];
} else
{
	$response['msg'] = 'Пустой номер пациента';
}