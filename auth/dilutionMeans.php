<?php

$SelectedData = $_SESSION['dilutionMeans'];

//debug($SelectedData);

$a2sDefault = array(
    'key' => 0,
    'value' => 'Выберите...'
);
$a2sSelected = array(
    'value' => $SelectedData['nurse_id']
);

$NurseNames = docFtoFIO($DoctorsNurseId, 1);



$a2sSelector = array2select($NurseNames, 'doctor_id', 'doctor_fio', 'nurse_id',
'class="form-control" id="nurse_id"', $a2sDefault, $a2sSelected);

$skip_weekend_checked = ( $SelectedData['skip_weekends'] == 1 ) ? ' checked' : '';
if (!notnull($SelectedData))
{
    $skip_weekend_checked = ' checked';
}

require_once ("engine/html/include/nurseJournals/holidays.php");

?>

<form id="dilutionMeans_form">
	<div class="form-group">
		<label for="nurse_id"><b>Выбор медсестры:</b></label>
		<?=$a2sSelector['result'];?>
	</div>
	
	<div class="form-group">
	    <label for="date_from"><b>Выберите дату начала:</b></label>
	    <input type="date" class="form-control" id="date_from" name="date_from" aria-describedby="end_date" value="<?=$SelectedData['date_from'];?>">
		<small id="end_date" class="form-text text-muted">
			Конечную дату попытается расчитать программа, чтобы распечатка не выходила за пределы страницы
		</small>
	</div>
	
	<div class="form-group">
	    <input class="form-check-input " type="checkbox" name="skip_weekends" id="skip_weekends" value="1"<?=$skip_weekend_checked;?>>
	    <label class="form-check-label box-label" for="skip_weekends"><span></span><b>Пропускать выходные дни</b></label>
	</div>
	
	<div class="form-group">
	    <button class="btn btn-primary btn-getJournalDilutionMeans col-12">Сгенерировать журнал</button>
	</div>
</form>

<script defer type="text/javascript" src="/engine/js/nurseJournals.js?<?=rand(1000, 9999);?>"></script>