<br>
<form action="/report_visits" method="post">
	<div class="form-group row">
		<div class="col"><b>Выберите период отчётности:</b> от</div>
		<div class="col"><input type="date" class="form-control form-control-lg" id="report_dir_date_from" name="report_dir_date_from" value="<?=$_POST['report_dir_date_from']?>" required></div>
		<div class="col">до</div>
		<div class="col"><input type="date" class="form-control form-control-lg" id="report_dir_date_to" name="report_dir_date_to" value="<?=$_POST['report_dir_date_to']?>" required></div>
	</div>
	<br>
	<div class="form-group row">
		<button type="submit" class="btn btn-primary col-12">Результат</button>
	</div>
</form>

<?php
if ( count($_POST) > 0 ) {

	$unix_from = strtotime($_POST['report_dir_date_from']);
	$unix_to = strtotime($_POST['report_dir_date_to']);
// 	debug($unix_from);
// 	debug($unix_to);

	$UnixFromData = getCurrentDay($unix_from);
	$UnixToData = getCurrentDay($unix_to);

	$unix_from_accu = $UnixFromData['by_timestamp']['begins']['day']['unix'];
	$unix_to_accu = $UnixToData['by_timestamp']['ends']['day']['unix'];

	$DoctorsVisitsAmount = array();

	$queryVisits = "SELECT * FROM {$CAOP_JOURNAL} WHERE journal_day IN (SELECT day_id FROM {$CAOP_DAYS} WHERE day_unix>='{$unix_from_accu}' AND day_unix<='{$unix_to_accu}' ORDER BY day_id DESC) ORDER BY journal_id DESC";
// 	debug($queryVisits);
	$resultVisits = mqc($queryVisits);
	$amountVisits = mnr($resultVisits);
	$ReportVisits = mr2a($resultVisits);

    // debug($amountVisits);

	foreach ($ReportVisits as $reportVisit) {
		$doc_id = 'id' . $reportVisit['journal_doctor'];
		if ( array_key_exists($doc_id, $DoctorsVisitsAmount) )
		{
			$DoctorsVisitsAmount[$doc_id] += 1;
		} else
		{
			$DoctorsVisitsAmount[$doc_id] = 1;
		}
	}

?>
	<hr>
	<div class="print-selected" id="print-selected">
		<div class="row">
			<div class="col full-center font-weight-bolder size-20pt">
				<?=$LPU_DOCTOR['lpu_blank_name'];?>
            </div>
        </div>
        <div class="row">
            <div class="col full-center font-weight-bolder size-10pt">
				<?=$LPU_DOCTOR['lpu_lpu_address'];?>
			</div>
		</div>
		<br>
		<div class="row">
			<div class="col full-center font-weight-bolder size-16pt">
				ОТЧЁТ
			</div>
		</div>
		<div class="row">
			<div class="col full-center font-weight-bolder size-16pt">
				по количеству приёмов<br>
				в период с <u> <?=date("d.m.Y", $unix_from)?> </u> по <u> <?=date("d.m.Y", $unix_to)?> </u>
			</div>
		</div>
		<hr>
		<div class="row">
			<div class="col">
				<b>ВСЕГО ПРИЁМОВ:</b> <?=$amountVisits?><br><br>
				<b>Из них приёмов врачей:</b><br>
				<?php
				foreach ($DoctorsVisitsAmount as $doc_id=>$amount) {
					$DoctorData = $DoctorsListId[$doc_id];
					echo '<u>' . docNameShort($DoctorData, "famimot") . ':</u> ' . $amount . '<br>';
				}
				?>
			</div>
		</div>


	</div>
	<br>
<?php
	bt_notice('<b>ВНИМАНИЕ! С учетом того, что не все врачи пользуются системой, отчёт несет приблизительный характер</b>', BT_THEME_WARNING);
//	debug($DoctorsVisitsAmount);
}

?>
