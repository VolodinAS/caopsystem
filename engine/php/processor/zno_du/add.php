<?php
$response['stage'] = $action;
$response['msg'] = 'begin';

$param_add = array(
    'zno_create_at' => time(),
	'zno_update_at' => time(),
);
$AddZNO = appendData(CAOP_ZNO_DU, $param_add);
if ( $AddZNO[ID] > 0)
{
	$response['result'] = true;
} else
{
	$response['msg'] = 'Невозможно добавить новую запись!';
}

/*$response['result'] = true;

$response['htmlData'] = '';

$response['htmlData'] .= '<b>Поиск пациента для постановки на Д-учет:</b>';
$response['htmlData'] .= '<form id="znodu_form">';
$response['htmlData'] .= '<div class="row">';
$response['htmlData'] .= '<div class="col">';
$response['htmlData'] .= '<input class="form-control form-control-sm" type="text" name="patid_name" placeholder="Ф.И.О. пациента" id="patid_name">';
$response['htmlData'] .= '</div>';
$response['htmlData'] .= '<div class="col-auto">';
$response['htmlData'] .= '<button class="btn btn-primary btn-sm btn-znoduSearch">Искать</button>';
$response['htmlData'] .= '</div>';
$response['htmlData'] .= '</form>';
$response['htmlData'] .= '</div>';
$response['htmlData'] .= '<input type="hidden" id="patid_id" name="patid_id" value="-1">';
$response['htmlData'] .= '<div id="znoduRecord_result"></div>';
$response['htmlData'] .= '<div id="znoduPRS_result"></div>';
$response['htmlData'] .= '
<div class="dropdown-divider"></div>
<form id="new_zno_du_form">
    <div class="form-group">
        <label for="zno_apk" class="font-weight-bolder">АПК:</label>
        <input type="text" class="form-control required-field" id="zno_apk" name="zno_apk" placeholder="Введите АПК: 4097, 4037 и т.д.">
    </div>
    
    <div class="form-group">
        <label for="zno_date_first_visit_caop" class="font-weight-bolder">Первое обращение в ЦАОП:</label>
        <input
        	type="date"
        	class="form-control required-field"
        	id="zno_date_first_visit_caop"
        	name="zno_date_first_visit_caop"
        	placeholder="Дата"
        >
    </div>
    
    <div class="form-group">
        <label for="zno_date_set_zno" class="font-weight-bolder">Дата установки диагноза:</label>
        <input
        	type="date"
        	class="form-control required-field"
        	id="zno_date_set_zno"
        	name="zno_date_set_zno"
        	placeholder="Дата"
        >
    </div>
    
    <div class="form-group">
        <label for="zno_diagnosis_mkb" class="font-weight-bolder">Дата установки диагноза:</label>
        <input
        	type="text"
        	class="form-control required-field"
        	id="zno_diagnosis_mkb"
        	name="zno_diagnosis_mkb"
        	placeholder="Код по МКБ"
        >
    </div>
</form>';*/