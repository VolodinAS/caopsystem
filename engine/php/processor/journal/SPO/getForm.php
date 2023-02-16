<?php
$response['stage'] = $action;
$response['msg'] = 'begin';

$CaopSPOReasons = getarr(CAOP_SPO_REASON_TYPES, 1, "ORDER BY reason_type_order ASC");
$CaopSPOReasonsId = getDoctorsById($CaopSPOReasons, 'reason_type_id');

$CaopSPOUnsetReasons = getarr(CAOP_SPO_ACCOUNTING_UNSET_REASON_TYPES, 1, "ORDER BY type_order ASC");
$CaopSPOUnsetReasonsIds = getDoctorsById($CaopSPOUnsetReasons, 'type_id');

$HTTP = $_POST;
extract($HTTP, EXTR_PREFIX_SAME, '_caop');

$data = explode(", ", $spo_id);
if ( count($data) == 2 )
{
	$spo_id = $data[0];
	$journal_id = $data[1];
}

$SPORM = RecordManipulation($spo_id, CAOP_SPO, 'spo_id');
if ( $SPORM['result'] )
{
	$response['result'] = true;
	
    $SPOData = $SPORM['data'];
    
    $response['htmlData'] .= '<div class="dropdown-divider"></div>';
    
    $response['htmlData'] .= NewFormItem(
    	'Направительный диагноз:',
	    'spo_mkb_directed',
	    'mkbDiagnosis',
	    $PK[CAOP_SPO],
	    'spo_mkb_directed',
	    CAOP_SPO,
	    $SPOData[$PK[CAOP_SPO]],
	    '',
	    'Направительный диагноз',
	    $SPOData['spo_mkb_directed'],
	    'data-adequate="MKB" data-return="#spo_mkb_directed" data-returntype="input" data-returnfunc="value"',
	    'input',
	    [],
	    null,
	    false,
	    '7'
    );
    
    $response['htmlData'] .= bt_divider(1);
    
    $response['htmlData'] .= NewFormItem(
    	'Диагноз после визита (заключительный):',
	    'spo_mkb_finished',
	    'mkbDiagnosis',
	    $PK[CAOP_SPO],
	    'spo_mkb_finished',
	    CAOP_SPO,
	    $SPOData[$PK[CAOP_SPO]],
	    '',
	    'Диагноз после визита',
	    $SPOData['spo_mkb_finished'],
	    'data-adequate="MKB" data-return="#spo_mkb_finished" data-returntype="input" data-returnfunc="value"',
	    'input',
	    [],
	    null,
	    false,
	    '7'
    );
    
    $date_set = ($SPOData['spo_unix_accounting_set'] > 0) ? date(DMY, $SPOData['spo_unix_accounting_set']) : '';
    
    $response['htmlData'] .= NewFormItem(
    	'Дата установки диагноза (если ЗНО или диспансеризация):',
	    'spo_unix_accounting_set',
	    'russianBirth',
	    $PK[CAOP_SPO],
	    'spo_unix_accounting_set',
	    CAOP_SPO,
	    $SPOData[$PK[CAOP_SPO]],
	    '',
	    'Дата установки диагноза',
	    $date_set,
	    'data-adequate="DATETOUNIX"',
	    'input',
	    [],
	    null,
	    false,
	    '8'
    );
	
	$response['htmlData'] .= bt_divider(1);
    
    $response['htmlData'] .= NewFormItem(
    	'Дата открытия СПО:',
	    'spo_start_date_unix',
	    'russianBirth',
	    $PK[CAOP_SPO],
	    'spo_start_date_unix',
	    CAOP_SPO,
	    $SPOData[$PK[CAOP_SPO]],
	    '',
	    'Дата открытия СПО',
	    date(DMY, $SPOData['spo_start_date_unix']),
	    'data-adequate="DATETOUNIX"',
	    'input',
	    [],
	    null,
	    false,
	    '8'
    );
	
	$DoctorSelector = make_selector(
		$DoctorsListId,
		$SPOData['spo_start_doctor_id'],
	'id="spo_start_doctor_id" data-action="edit"
		    data-table="'.CAOP_SPO.'"
		    data-assoc="0"
		    data-fieldid="'.$PK[CAOP_SPO].'"
		    data-id="'.$SPOData[$PK[CAOP_SPO]].'"
		    data-field="spo_start_doctor_id"',
		'callback.func_doctor_name',
		'Выберите врача...',
		'doctor_id',
		true,
		null,
		'form-control mysqleditor'
	);
    
    $response['htmlData'] .= NewFormItem(
    	'Врач, открывший СПО:',
	    'spo_start_doctor_id',
	    '',
	    $PK[CAOP_SPO],
	    'spo_start_doctor_id',
	    CAOP_SPO,
	    $SPOData[$PK[CAOP_SPO]],
	    '',
	    'Врач, открывший СПО',
	    '',
	    '',
	    'select',
	    [],
	    $DoctorSelector,
	    false,
	    '4'
    );
    
	$LPUSelector = make_selector(
		$DispLPUId,
		$SPOData['spo_lpu_id'],
		'id="spo_lpu_id" data-action="edit"
		    data-table="'.CAOP_SPO.'"
		    data-assoc="0"
		    data-fieldid="'.$PK[CAOP_SPO].'"
		    data-id="'.$SPOData[$PK[CAOP_SPO]].'"
		    data-field="spo_lpu_id"',
		'lpu_shortname',
		'Выберите ЛПУ...',
		'lpu_id',
		true,
		null,
		'form-control mysqleditor'
	);
    
    $response['htmlData'] .= NewFormItem(
    	'ЛПУ прикрепления:',
	    'spo_lpu_id',
	    '',
	    $PK[CAOP_SPO],
	    'spo_lpu_id',
	    CAOP_SPO,
	    $SPOData[$PK[CAOP_SPO]],
	    '',
	    'ЛПУ прикрепления:',
	    '',
	    '',
	    'select',
	    [],
	    $LPUSelector,
	    false,
	    '4'
    );
	
	$response['htmlData'] .= NewFormItem(
		'Направивший врач:',
		'spo_dir_lpu_doctor_fio',
		'',
		$PK[CAOP_SPO],
		'spo_dir_lpu_doctor_fio',
		CAOP_SPO,
		$SPOData[$PK[CAOP_SPO]],
		'',
		'Направивший врач',
		$SPOData['spo_dir_lpu_doctor_fio'],
		'',
		'input',
		[],
		null,
		false,
		'4'
	);
	
	$response['htmlData'] .= NewFormItem(
		'Направившее ЛПУ:',
		'spo_dir_lpu_name',
		'',
		$PK[CAOP_SPO],
		'spo_dir_lpu_name',
		CAOP_SPO,
		$SPOData[$PK[CAOP_SPO]],
		'',
		'Направившее ЛПУ',
		$SPOData['spo_dir_lpu_name'],
		'',
		'input',
		[],
		null,
		false,
		'4'
	);

	$response['htmlData'] .= NewFormItem(
		'Дата направления:',
		'spo_dir_lpu_date_date',
		'russianBirth',
		$PK[CAOP_SPO],
		'spo_dir_lpu_date_date',
		CAOP_SPO,
		$SPOData[$PK[CAOP_SPO]],
		'',
		'Дата направления',
		$SPOData['spo_dir_lpu_date_date'],
		'',
		'input',
		[],
		null,
		false,
		'4'
	);

	$response['htmlData'] .= NewFormItem(
		'Время направления:',
		'spo_dir_lpu_date_time',
		'russianBirth',
		$PK[CAOP_SPO],
		'spo_dir_lpu_date_time',
		CAOP_SPO,
		$SPOData[$PK[CAOP_SPO]],
		'',
		'Время направления',
		$SPOData['spo_dir_lpu_date_time'],
		'',
		'input',
		[],
		null,
		false,
		'4'
	);
	
	$param_checked = ((int)$SPOData['spo_is_dispancer'] == 1) ? ' checked' : '';
	
	$switcher_checkbox_options = array(
		'mye' => array(
			'table' => CAOP_SPO,
			'field_id' => 'spo_id',
			'id' => $SPOData[$PK[CAOP_SPO]],
			'field' => 'spo_is_dispancer'
		),
		'addon' => $param_checked
	);
	$switcher_checkbox = checkbox_switcher($switcher_checkbox_options);
	
	$response['htmlData'] .= NewFormItem(
		'Случай является диспансерным:',
		'spo_is_dispancer',
		'',
		$PK[CAOP_SPO],
		'spo_is_dispancer',
		CAOP_SPO,
		$SPOData[$PK[CAOP_SPO]],
		'',
		'Случай является диспансерным',
		$SPOData['spo_is_dispancer'],
		'',
		'select',
		[],
		$switcher_checkbox,
		false,
		'4'
	);
	
	$response['htmlData'] .= bt_divider(1);
	
	$JournalData = getarr(CAOP_JOURNAL, $PK[CAOP_JOURNAL] . "='{$journal_id}'");
	if ( count($JournalData) > 0 )
	{
	    $JournalData = $JournalData[0];
		
		$case_selector = make_selector(
			$CaopSPOReasonsId,
			$JournalData['journal_spo_end_reason_type'],
			'id="spo_end_reason_type" data-action="edit"
		    data-table="'.CAOP_JOURNAL.'"
		    data-assoc="0"
		    data-fieldid="'.$PK[CAOP_JOURNAL].'"
		    data-id="'.$JournalData[$PK[CAOP_JOURNAL]].'"
		    data-field="journal_spo_end_reason_type"',
			'reason_type_title',
			'Выберите, чем закончилось посещение...',
			'reason_type_id',
			true,
			null,
			'form-control mysqleditor'
		);
		
		$response['htmlData'] .= NewFormItem(
			'Выберите, чем закончилось посещение:',
			'spo_end_reason_type',
			'',
			$PK[CAOP_SPO],
			'spo_end_reason_type',
			CAOP_SPO,
			$SPOData[$PK[CAOP_SPO]],
			'',
			'Выберите, чем закончилось посещение:',
			'',
			'',
			'select',
			[],
			$case_selector,
			false,
			'7'
		);
		
		$response['htmlData'] .= bt_divider(1);
	}
	
//	$response['debug']['$JournalData'] = $JournalData;
	
	$unset_selector = make_selector(
		$CaopSPOUnsetReasonsIds,
		$SPOData['spo_accounting_unset_reason'],
		'id="spo_accounting_unset_reason" data-action="edit"
		    data-table="'.CAOP_SPO.'"
		    data-assoc="0"
		    data-fieldid="'.$PK[CAOP_SPO].'"
		    data-id="'.$SPOData[$PK[CAOP_SPO]].'"
		    data-field="spo_accounting_unset_reason"',
		'type_title',
		'Выберите причину...',
		'type_id',
		true,
		null,
		'form-control mysqleditor'
	);
	
	$response['htmlData'] .= NewFormItem(
		'Причина снятия с Д-учета (если надо снять):',
		'spo_accounting_unset_reason',
		'',
		$PK[CAOP_SPO],
		'spo_accounting_unset_reason',
		CAOP_SPO,
		$SPOData[$PK[CAOP_SPO]],
		'',
		'Выберите причину...',
		'',
		'',
		'select',
		[],
		$unset_selector,
		false,
		'7'
	);
	
	$date_unset = ($SPOData['spo_unix_accounting_unset'] > 0) ? date(DMY, $SPOData['spo_unix_accounting_unset']) : '';
	
	$response['htmlData'] .= NewFormItem(
		'Дата снятия с Диспансерного учета:',
		'spo_unix_accounting_unset',
		'russianBirth',
		$PK[CAOP_SPO],
		'spo_unix_accounting_unset',
		CAOP_SPO,
		$SPOData[$PK[CAOP_SPO]],
		'',
		'Дата снятия с Диспансерного учета',
		$date_unset,
		'data-adequate="DATETOUNIX"',
		'input',
		[],
		null,
		false,
		'8'
	);
	
	$response['htmlData'] .= bt_divider(1);
	
	
	$response['htmlData'] .= '<button class="btn btn-lg btn-danger col" onclick="javascript:removeSPO('.$SPOData[$PK[CAOP_SPO]].')">Удалить данное СПО</button>';
	
//	$response['htmlData'] .= debug_ret($SPOData);
 

} else $response['msg'] = $SPORM['msg'];