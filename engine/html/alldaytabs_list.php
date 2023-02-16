<?php
echo '<hr>';
$npp = count($AllDaysDoctor);
foreach ($AllDaysDoctor as $day) {
//	$PatientsNum = getrows('caop_journal', "journal_day='{$day['day_id']}' AND journal_doctor='{$day['day_doctor']}'", 'journal_id', null);
	$signed = ($day['day_signature_state'] == 1) ? 'signature.png' : 'notsignature.png';

	$DayData = getCurrentDay($day['day_unix']);


//		    debug($PatientsNum);
	?>
	<div class="row">
		<div class="col-1 align-right"><img src="/engine/images/icons/<?=$signed;?>" alt=""></div>
		<div class="col-11"><?=$npp;?>) <a href="/journalAlldays/<?=$day['day_id'];?>"><?=date("d.m.Y", $day['day_unix']);?></a> (<?=$DayData['week_long'];?>) [<?=$day['amount'];?>]</div>
	</div>
	<hr>
	<?php
    $npp--;
}