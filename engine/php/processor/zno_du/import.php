<?php
$response['stage'] = $action;
$response['msg'] = 'begin';

$HTTP = $_POST;
extract($HTTP, EXTR_PREFIX_SAME, '_caop');

$PatientRM = RecordManipulation($patid, CAOP_PATIENTS, $PK[CAOP_PATIENTS]);
if ( $PatientRM['result'] )
{
    $PatientData = $PatientRM['data'];
	
	$response['debug']['$PatientData'] = $PatientData;
    
    $ZNODURM = RecordManipulation($zno_id, CAOP_ZNO_DU, $PK[CAOP_ZNO_DU]);
    if ( $ZNODURM['result'] )
    {
        $ZNODUData = $ZNODURM['data'];
        
        $response['debug']['$ZNODUData'] = $ZNODUData;
        
        $RouteSheetRM = RecordManipulation($rs, CAOP_ROUTE_SHEET, $PK[CAOP_ROUTE_SHEET]);
        if ( $RouteSheetRM['result'] )
        {
            $RouteSheetData = $RouteSheetRM['data'];
            
            $response['debug']['$RouteSheetData'] = $RouteSheetData;
            
            $param_znodu_update = array(
                'zno_date_first_visit_caop' => birthToUnix($RouteSheetData['rs_stage_caop_date']),
	            'zno_date_set_zno' => birthToUnix($RouteSheetData['rs_ds_set_date']),
	            'zno_diagnosis_mkb' => $RouteSheetData['rs_ds_mkb'],
	            'zno_diagnosis_text' => $RouteSheetData['rs_ds_text'],
	            'zno_tnm_t' => $RouteSheetData['rs_tnm_t'],
	            'zno_tnm_n' => $RouteSheetData['rs_tnm_n'],
	            'zno_tnm_m' => $RouteSheetData['rs_tnm_m'],
	            'zno_tnm_s' => $RouteSheetData['rs_stadia'],
	            'zno_date_dir_in_gop' => birthToUnix($RouteSheetData['rs_fill_date']),
	            'zno_date_issue_notice' => birthToUnix($RouteSheetData['rs_fill_date']),
	            'zno_method_date' => birthToUnix($RouteSheetData['rs_ds_set_date']),
	            'zno_doctor_id' => $RouteSheetData['rs_doctor_id'],
            );
            
            $UpdateZNODU = updateData(CAOP_ZNO_DU, $param_znodu_update, $ZNODUData, "{$PK[CAOP_ZNO_DU]}='{$ZNODUData[$PK[CAOP_ZNO_DU]]}'");
            if ( $UpdateZNODU ['stat'] == RES_SUCCESS )
            {
            	$response['result'] = true;
            } else
            {
            	$response['msg'] = 'Ошибка обновления ДУ ЗНО';
            }
        
        } else $response['msg'] = $RouteSheetRM['msg'];
    
    } else $response['msg'] = $ZNODURM['msg'];
    
    

} else $response['msg'] = $PatientRM['msg'];