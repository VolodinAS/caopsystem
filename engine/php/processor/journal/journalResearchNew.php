<?php
$response['stage'] = $action;
$response['htmlData'] = '';
$response['result'] = true;

$journal_id = $_POST['patient_id'];
$is_add = $_POST['is_add'];

$queryVisit = "SELECT * FROM $CAOP_JOURNAL cj LEFT JOIN $CAOP_PATIENTS cp ON cj.journal_patid=cp.patid_id WHERE cj.journal_id='{$journal_id}'";
$resultVisit = mqc($queryVisit);
$amountVisit = mnr($resultVisit);

$zeroOption_analize = array(
	'key'   =>  0,
	'value'    =>  'ВЫБЕРИТЕ'
);

if ( $amountVisit == 1 )
{
	$JournalPatData = mfa($resultVisit);

	$DS = '['.$JournalPatData['journal_ds'].'] ' . $JournalPatData['journal_ds_text'];
	if ( strlen($DS) <= 1 ) $DS = "НЕ УКАЗАН ДИАГНОЗ!";
	
	$ResearchTypesHead = getarr(CAOP_RESEARCH_TYPES, "type_enabled='1' AND type_forresearch='1'", "ORDER BY type_order ASC");

//	$response['htmlData'] .= debug($JournalPatData, null, null, 1);
	$response['htmlData'] .= '

	<form id="form_addPatientResearch" onsubmit="submit_addPatientResearch(event)">
    <input type="hidden" id="is_add" name="is_add" value="'.$is_add.'">
	<input type="hidden" id="patvisit" value="'.$JournalPatData['journal_id'].'">
	<input type="hidden" id="patid" value="'.$JournalPatData['patid_id'].'">
	<b>Пациент:</b> '.mb_ucwords($JournalPatData['patid_name']).', '.$JournalPatData['patid_birth'].' г.р.<br><br>
	<b>Диагноз:</b> '.$DS.'<br><br>
	<b>Обследование:</b>';
	$ResearchSelectorAnalize = array2select($ResearchTypesHead, 'type_id', 'type_title', 'research_type', ' id="research_type" class="form-control form-control-lg"', $zeroOption_analize);
	if ( $ResearchSelectorAnalize['stat'] == RES_SUCCESS )
	{
		$response['htmlData'] .= $ResearchSelectorAnalize['result'];
	} else
	{
		$response['htmlData'] .= 'ОШИБКА';
	}
	$response['htmlData'] .= '<br>
	<b>Область обследования:</b><br>
	<input type="text" class="form-control form-control-lg" id="area" placeholder="Пример: ОБП">
	<br>
	<b>Дата обследования:</b><br>
	<input type="text" class="form-control form-control-lg russianBirth" id="dater" placeholder="Пример: '.date("d.m.Y").'">
	<br>
	<b>Заключение:</b><br>
	<textarea name="result" id="result" class="form-control form-control-lg" rows="5" placeholder="Описание"></textarea>
	<input type="submit" name="sub" value="ENTER" style="display: none">';
	
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
	
	$response['htmlData'] .= '</form>
	';
	
	//
} else
{
	$response['htmlData'] = 'Такого пациента не существует';
}