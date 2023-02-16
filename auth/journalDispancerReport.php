<?php
$date_from = $date_to = '';
if ( isset($_SESSION['dispancer_report']) )
{
	$date_from = $_SESSION['dispancer_report']['date_from'];
	$date_to = $_SESSION['dispancer_report']['date_to'];
	$showRepeats = $_SESSION['dispancer_report']['showRepeats'];
	if ( (int)$showRepeats == 1 )
	{
	    $showRepeats = "checked";
	} else
	{
	    $showRepeats = "";
	}
}
?>
<form action="" method="post" id="dispancer_report">
	<div class="row">
		<div class="col font-weight-bolder">
			Выберите сроки:
		</div>
		
		<div class="col">
			<input type="date" class="form-control form-control-lg" id="dispancer_report_from" name="dispancer_report_from" value="<?=$date_from;?>">
		</div>
		
		<div class="col">
			<input type="date" class="form-control form-control-lg" id="dispancer_report_to" name="dispancer_report_to" value="<?=$date_to;?>">
		</div>
	</div>
	<div class="row">
		<div class="col font-weight-bolder">
			Выберите врача:
		</div>
		<div class="col">
			<?php
			$selectArr = array(
			    'value' => $USER_PROFILE['doctor_id']
			);
			$defaultArr = array(
			    'key'   =>  0,
				'value' =>  'ПО ВСЕМ ВРАЧАМ'
			);
			$SelectDoctor = array2select($DoctorsListId, 'doctor_id', 'doctor_f', 'dispancer_report_doctor_id', 'id="dispancer_report_doctor_id" class="form-control form-control-lg"', $defaultArr, $selectArr);
			echo $SelectDoctor['result'];
			?>
		</div>
	</div>
	<div class="row">
		<div class="col font-weight-bolder">
		    <input <?=$showRepeats?> class="form-check-input" type="checkbox" name="dispancer_report_showRepeats" id="dispancer_report_showRepeats" value="1">
			<label class="form-check-label box-label" for="dispancer_report_showRepeats"><span></span><b>Отобразить диспансерных с повторными приёмами</b></label>
		</div>
	</div>
	<br/>
	<div class="row">
		<div class="col">
			<button type="button" class="btn btn-primary btn-lg btn_dispancer_report">Создать отчёт</button>
		</div>
	</div>
</form>

<script defer src="/engine/js/dispancer/disp_report.js?<?=rand(0,999999);?>" type="text/javascript"></script>