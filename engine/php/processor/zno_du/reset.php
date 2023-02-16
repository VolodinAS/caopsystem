<?php
$response['stage'] = $action;
$response['msg'] = 'begin';

$HTTP = $_POST;
extract($HTTP, EXTR_PREFIX_SAME, '_caop');

$ZNODURM = RecordManipulation($zno_id, CAOP_ZNO_DU, 'zno_id');
if ( $ZNODURM['result'] )
{
    $ZNODUData = $ZNODURM['data'];
    
    $reset_values = array(
        'zno_apk' => '',
	    'zno_date_first_visit_caop' => null,
	    'zno_date_set_zno' => null,
	    'zno_diagnosis_mkb' => '',
	    'zno_diagnosis_text' => '',
	    'zno_tnm_t' => '',
	    'zno_tnm_n' => '',
	    'zno_tnm_m' => '',
	    'zno_tnm_s' => '',
	    'zno_method_type' => 0,
	    'zno_method_date' => null,
	    'zno_date_dir_in_gop' => null,
	    'zno_date_issue_notice' => null,
	    'zno_comment' => '',
	    'zno_doctor_id' => 0,
	    'zno_update_at' => time()
    );
    
    $ResetZNO = updateData(CAOP_ZNO_DU, $reset_values, $ZNODUData, "{$PK[CAOP_ZNO_DU]}='{$ZNODUData[$PK[CAOP_ZNO_DU]]}'");
    if ($ResetZNO ['stat'] == RES_SUCCESS)
    {
    	$response['result'] = true;
    } else $response['msg'] = 'Проблема при сбросе формы';

} else $response['msg'] = $ZNODURM['msg'];