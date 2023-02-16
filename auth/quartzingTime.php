<?php

$SelectedData = $_SESSION['quartzingTime'];
$NurseDefault = array(
	'key' => 0,
	'value' => 'Выберите...'
);
$NurseSelected = array(
	'value' => $SelectedData['nurse_id']
);
$NurseNames = docFtoFIO($DoctorsNurseId, 1);


$NurseOneSelector = array2select($NurseNames, 'doctor_id', 'doctor_fio', 'nurse_one_id',
	'class="form-control" id="nurse_one_id"', $NurseDefault, $NurseSelected);

$NurseTwoSelector = array2select($NurseNames, 'doctor_id', 'doctor_fio', 'nurse_two_id',
	'class="form-control" id="nurse_two_id"', $NurseDefault, $NurseDefault);

$skip_weekend_checked = ( $SelectedData['skip_weekends'] == 1 ) ? ' checked' : '';
if (!notnull($SelectedData))
{
	$skip_weekend_checked = ' checked';
}

require_once ("engine/html/include/nurseJournals/holidays.php");
?>

<form id="quartzingTime_form">
    <div class="row">
        <div class="col">
            <div class="form-group">
                <label for="nurse_one_id"><b>Выбор первой медсестры:</b></label>
		        <?=$NurseOneSelector['result'];?>
            </div>
        </div>
        <div class="col">
            <div class="form-group">
                <label for="nurse_one_id"><b>Выбор второй медсестры (если есть):</b></label>
		        <?=$NurseTwoSelector['result'];?>
            </div>
        </div>
    </div>
	
	
	<div class="form-group">
		<label for="date_from"><b>Выберите дату начала:</b></label>
		<input type="date" class="form-control" id="date_from" name="date_from" aria-describedby="end_date" value="<?=$SelectedData['date_from'];?>">
		<small id="end_date" class="form-text text-muted">
			Конечную дату попытается расчитать программа, чтобы распечатка не выходила за пределы страницы
		</small>
	</div>
	
	
	<div class="form-group">
		<label><b>Дни генеральных уборок:</b></label>
		<?php
        for ($index=0; $index<5; $index++)
        {
            ?>
            <input type="date" class="form-control" name="gen_cleans[]" value="<?=$SelectedData['gen_cleans'][$index];?>">
            <?php
        }
		?>
	</div>
	
	<div class="form-group">
		<input class="form-check-input " type="checkbox" name="skip_weekends" id="skip_weekends" value="1"<?=$skip_weekend_checked;?>>
		<label class="form-check-label box-label" for="skip_weekends"><span></span><b>Пропускать выходные дни</b></label>
	</div>
	
	<div class="form-group">
		<button class="btn btn-primary btn-getJournalQuartzTime col-12">Сгенерировать журнал</button>
	</div>
</form>

<script defer type="text/javascript" src="/engine/js/nurseJournals.js?<?=rand(1000, 9999);?>"></script>