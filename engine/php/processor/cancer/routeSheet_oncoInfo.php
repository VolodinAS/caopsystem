<?php
$response['stage'] = $action;
//$response['debug']['$_POST'] = $_POST;
$response['msg'] = 'begin';
//$response['result'] = true;

$HTTP = $_POST;
extract($HTTP, EXTR_PREFIX_SAME, '_caop');

$DataFields = array(
	'Дата заполнения:' => array(
		'field_name' => 'rs_fill_date',
		'field_place'	=> 'rs'
	),
	'Первое обращение:' => array(
		'field_name' => 'rs_stage_vr_u4_pol_date',
		'field_place'	=> 'rs'
	),
	'Обстоят-ва выявления:' => array(
		'field_name' => 'rs_ds_cond',
		'field_place'	=> 'rs'
	),
	'Дата устан. диагноза:' => array(
		'field_name' => 'rs_ds_set_date',
		'field_place'	=> 'rs'
	),
	'Дата выяв. п. признаков:' => array(
		'field_name' => 'rs_stage_po_pe_pr_zno_date',
		'field_place'	=> 'rs'
	),
	// 'Клин. группа:' => array(
	// 	'field_name' => '',
	// 	'field_place'	=> 'rs'
	// ),
	'Метод подтверждения' => array(
		'field_name' => '',
		'field_place'	=> 'cancer'
	),
	// 'Дата направл. на гист-ю:' => array(
	// 	'field_name' => '',
	// 	'field_place'	=> 'rs'
	// ),
	'Морфологический тип:' => array(
		'field_name' => 'rs_morphology',
		'field_place'	=> 'rs'
	),
	'Гист. дифференцировка:' => array(
		'field_name' => 'rs_tnm_g',
		'field_place'	=> 'rs',
		'field_postprocess'	=> 'shortyG'
	),
	'Порядковый № опухоли:' => array(
		'field_name' => '',
		'field_place'	=> 'cancer'
	),
	'T:' => array(
		'field_name' => 'rs_tnm_t',
		'field_place'	=> 'rs',
		'field_postprocess'	=> 'replaceT'
	),
	'N:' => array(
		'field_name' => 'rs_tnm_n',
		'field_place'	=> 'rs',
		'field_postprocess'	=> 'replaceN'
	),
	'M:' => array(
		'field_name' => 'rs_tnm_m',
		'field_place'	=> 'rs',
		'field_postprocess'	=> 'replaceM'
	),
	'Стадия опух. процесса:' => array(
		'field_name' => 'rs_stadia',
		'field_place'	=> 'rs',
		'field_postprocess'	=> 'rome_to_arabic'
	),
	'Дата постановки на Д-учет (дата визита с ЗНО):' => array(
		'field_name' => 'rs_ds_du_date',
		'field_place'	=> 'rs'
	)
);

$ONCOINFO_TEXT = trim($oncoInfo);
$ONCOINFO_DATA = explode("\n", $ONCOINFO_TEXT);
$ONCOINFO_DATA_NOSPACES = [];
foreach ($ONCOINFO_DATA as $ONCOINFO_STRING)
{
	if ( strlen($ONCOINFO_STRING) > 0 )
	{
		$ONCOINFO_STRING = trim($ONCOINFO_STRING);
		if ( strlen($ONCOINFO_STRING) > 0 )
		{
			$ONCOINFO_DATA_NOSPACES[] = $ONCOINFO_STRING;
		}

	}
}
unset($ONCOINFO_DATA);


$PATIENT_DIAGNOSIS_DATA = array();
for ($i=0; $i < count($ONCOINFO_DATA_NOSPACES); $i++) {
	$DS = [];
	$DS = $DataFields[ $ONCOINFO_DATA_NOSPACES[$i] ];
	if ( notnull($DS) )
	{
		$DS['value'] = $ONCOINFO_DATA_NOSPACES[$i+1];
		if ( notnull($DS['field_postprocess']) )
		{
			$func = $DS['field_postprocess'];
//			$DS['value'] = $func($DS['value']);
			$DS['pre_value'] = $DS['value'];
			$DS['value'] = call_user_func($func, $DS['value']);
			$DS['func'] = $func;
			$DS['func_value'] = $DS['value'];
		}

		$DS['title'] = $ONCOINFO_DATA_NOSPACES[$i];
		$PATIENT_DIAGNOSIS_DATA[] = $DS;
	}
}
$DS = [];
$DS = $DataFields[ 'Дата постановки на Д-учет (дата визита с ЗНО):' ];
$DS['title'] = 'Дата постановки на Д-учет (дата визита с ЗНО):';
$DS['value'] = processRussianDate( $ONCOINFO_DATA_NOSPACES[0] );
$PATIENT_DIAGNOSIS_DATA[] = $DS;

//$response['debug']['$DataFields'] = $DataFields;
//$response['debug']['$ONCOINFO_TEXT'] = $ONCOINFO_TEXT;
//$response['debug']['$ONCOINFO_DATA_NOSPACES'] = $ONCOINFO_DATA_NOSPACES;
$response['patient_diagnosis_data'] = $PATIENT_DIAGNOSIS_DATA;
$response['result'] = true;

function shortyG($str)
{
	$check = md5('Степень дифференцировки не может быть установлена');
	$str_md5 = md5($str);
	if ( $str_md5 == $check )
	{
		// НАШЕЛ
		return mb_strtolower('X', UTF8);
	} else
	{
		// НЕ НАШЕЛ
		$strData = explode(" ", $str);
		return mb_strtolower(str_replace('G', '', $strData[0]), UTF8);
	}
}

function rome_to_arabic($rome)
{
	$rome = mb_strtolower($rome, UTF8);
    $rome = str_replace('iv', '4', $rome);
    $rome = str_replace('iii', '3', $rome);
    $rome = str_replace('ii', '2', $rome);
    $rome = str_replace('i', '1', $rome);
    return $rome;
}

function replaceT($str)
{
	return str_replace('T', '', $str);
}

function replaceN($str)
{
	return str_replace('N', '', $str);
}

function replaceM($str)
{
	return str_replace('M', '', $str);
}

