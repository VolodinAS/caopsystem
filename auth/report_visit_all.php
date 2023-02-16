<?php

$queryMinUnix = "SELECT MIN(day_unix) as unix_from FROM {$CAOP_DAYS}";
$resultMinUnix = mqc($queryMinUnix);
$MinUnixData = mr2a($resultMinUnix);
$unix_from = $MinUnixData[0]['unix_from'];

$queryMaxUnix = "SELECT MAX(day_unix) as unix_to FROM {$CAOP_DAYS}";
$resultMaxUnix = mqc($queryMaxUnix);
$MaxUnixData = mr2a($resultMaxUnix);
$unix_to = intval( $MaxUnixData[0]['unix_to'] ) + 86399 ;

$DoctorsVisitsAmount = array();

$queryVisits = "SELECT * FROM {$CAOP_JOURNAL} WHERE journal_day IN (SELECT day_id FROM {$CAOP_DAYS} WHERE 1 ORDER BY day_id DESC) ORDER BY journal_id DESC";
$resultVisits = mqc($queryVisits);
$amountVisits = mnr($resultVisits);
$ReportVisits = mr2a($resultVisits);

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
			по количеству приёмов (ВСЕГО)<br>
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

?>