<?php
$CompanyList = getarr(CAOP_INSURANCE, "insurance_enabled='1'", "ORDER BY insurance_title ASC");
$CompanyListId = getDoctorsById($CompanyList, 'insurance_id');
?>

<?php
$isPatient = false;
if ( strlen($OtherParams) > 0 )
{
	$PatientData = getarr(CAOP_DS_PATIENTS, "patient_id={$OtherParams}");
	if ( count($PatientData) > 0 )
	{
	    $isPatient = true;
		$PatientData = $PatientData[0];
		if ( $PatientData['patient_height'] == 0) $PatientData['patient_height'] = '';
		if ( $PatientData['patient_weight'] == 0) $PatientData['patient_weight'] = '';
	} else
	{
		bt_notice('Выбранного пациента не существует', BT_THEME_WARNING);
	}
}

$defaultArr = array(
	'key' => '0',
	'value' => 'ВЫБЕРИТЕ'
);
$defaultSelect = array(
	'value' => $PatientData['patient_insurance_company_id']
);
$CompanyListSelector = array2select($CompanyList, 'insurance_id', 'insurance_title', 'patient_insurance_company_id', ' aria-describedby="ig_patient_insurance_company_id" aria-label="Название страховой компании" id="patient_insurance_company_id" class="form-control" ', $defaultArr, $defaultSelect);
if ($CompanyListSelector['stat'] == RES_SUCCESS)
{
	$CompanyListSelectorHTML = $CompanyListSelector['result'];
}
?>

<br>
<div class="row">
	<div class="col-auto">
		Импорт из ЦАОП:
	</div>
	<div class="col">
		<input class="form-control form-control-sm" type="text" name="patient_ident_search" id="patient_ident_search" placeholder="Номер амбулаторной карты" value="385411">
	</div>
	<div class="col-auto">
		<button class="btn btn-sm btn-secondary" id="importPatientData">Импорт</button>
	</div>
</div>
<br><br>
<?php
require_once ( "engine/html/dayStac/include/inc_spoiler_newPatient.php" );
require_once ( "engine/html/dayStac/include/inc_spoiler_dirlists.php" );
require_once ( "engine/html/dayStac/include/inc_spoiler_visits.php" );
?>

	
