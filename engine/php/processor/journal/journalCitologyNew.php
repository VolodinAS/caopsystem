<?php
$response['stage'] = $action;
$response['htmlData'] = '';
$response['result'] = true;

$journal_id = $_POST['patient_id'];
$is_add = $_POST['is_add'];

$queryVisit = "SELECT * FROM ".CAOP_JOURNAL." cj LEFT JOIN ".CAOP_PATIENTS." cp ON cj.journal_patid=cp.patid_id WHERE cj.journal_id='{$journal_id}'";
$resultVisit = mqc($queryVisit);
$amountVisit = mnr($resultVisit);

$CitologyTypes = getarr(CAOP_CITOLOGY_TYPE, 1, "ORDER BY type_id ASC");
$CitologyTypesId = getDoctorsById($CitologyTypes, 'type_id');

$zeroOption_analize = array(
	'key'   =>  0,
	'value'    =>  'ВЫБЕРИТЕ'
);

if ( $amountVisit == 1 )
{
	$JournalPatData = mfa($resultVisit);

	$DS = '['.$JournalPatData['journal_ds'].'] ' . $JournalPatData['journal_ds_text'];
	if ( strlen($DS) <= 1 ) $DS = "НЕ УКАЗАН ДИАГНОЗ!";

//	$response['htmlData'] .= debug($JournalPatData, null, null, 1);
	$response['htmlData'] .= '
	<input type="hidden" id="is_add" name="is_add" value="'.$is_add.'">
	<input type="hidden" id="patvisit" value="'.$JournalPatData['journal_id'].'">
	<input type="hidden" id="patid" value="'.$JournalPatData['patid_id'].'">
	<b>Пациент:</b> '.mb_ucwords($JournalPatData['patid_name']).', '.$JournalPatData['patid_birth'].' г.р.<br><br>
	<b>Диагноз:</b> '.$DS.'<br><br>
	<b>Метод диагностики:</b>';
	$CitologySelectorAnalize = array2select($CitologyTypes, 'type_id', 'type_title', 'citology_analise_type', ' id="citology_analise_type" class="form-control form-control-lg"', $zeroOption_analize);
	if ( $CitologySelectorAnalize['stat'] == RES_SUCCESS )
	{
		$response['htmlData'] .= $CitologySelectorAnalize['result'];
	} else
	{
		$response['htmlData'] .= 'ОШИБКА';
	}
	$response['htmlData'] .= '<br>
	<b>Маркировка материала (каждый элемент с новой строки):</b><br>
<textarea class="form-control form-control-lg autosizer" id="biomatmark" placeholder="Пример:
узел правой доли щитовидной железы
узел левой доли щитовидной железы
узел перешейка"></textarea><br>

	<b>Дата заключения:</b>
	<input type="text" class="form-control form-control-lg russianBirth" name="result_date" id="result_date" placeholder="Дата заключения"><br>
	
	<b>Номер заключения:</b>
	<input type="text" class="form-control form-control-lg" name="result_ident" id="result_ident" placeholder="Номер заключения"><br>
	
	<b>Заключение:</b>
	<input type="text" class="form-control form-control-lg" name="result" id="result" placeholder="Заключение">
	';
	
	$response['htmlData'] .= '
		<div class="dropdown-divider"></div>
		<div class="row bg-danger">
		    <div class="col">
		        <div class="form-group">
		            <span class="font-weight-bolder">В результате выявлено:</span>
		        </div>
		    </div>
		    <div class="col">';
	
	$CitologyCancerTypes = getarr(CAOP_CITOLOGY_CANCER_TYPE, 1, "ORDER BY type_order ASC");
	
	for ($index=0; $index<count($CitologyCancerTypes); $index++)
	{
		$title = $CitologyCancerTypes[$index]['type_title'];
		$response['htmlData'] .= '
			    <div class="form-group">
		            <input
		            	class="form-check-input"
		            	type="radio"
		            	name="research_cancer"
		            	id="research_cancer_'.$index.'"
		            	value="'.$CitologyCancerTypes[$index]['type_id'].'"
		            >
		            <label class="form-check-label box-label" for="research_cancer_'.$index.'"><span></span>'.$title.'</label>
		        </div>
	        ';
	}
	
	$response['htmlData'] .= '
			</div>';
	$response['htmlData'] .= '
		</div>';
	
} else
{
	$response['htmlData'] = 'Такого пациента не существует';
}