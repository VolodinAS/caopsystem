<?php
//debug($_SESSION);
?>
<div class="row">
	<div class="col-2">
		Выберите период:
	</div>
	<div class="col-1">С:</div>
	<div class="col">
		<input type="date" class="form-control form-control-sm" id="fj_from" value="<?=$_SESSION['formalJournal']['date_from'];?>">
	</div>
	<div class="col-1">00:00:00</div>
	
	<div class="col-1">ПО:</div>
	<div class="col">
		<input type="date" class="form-control form-control-sm" id="fj_to" value="<?=$_SESSION['formalJournal']['date_to'];?>">
	</div>
	<div class="col-1">23:59:59</div>
	<div class="col-1 text-right">
		<button type="button" class="btn btn-primary btn-sm" id="btn-formalJournal-go">Показать</button>
	</div>
	<div class="col-1 text-right">
		<button type="button" class="btn btn-warning btn-sm" id="btn-formalJournal-reset">Сбросить</button>
	</div>
</div>

<?php

if ( count( $_SESSION['formalJournal'] ) > 0 )
{
	$HTTP = $_SESSION['formalJournal'];
	extract($HTTP, EXTR_PREFIX_SAME, '_caop');
	
	$date_from_unix = strtotime($date_from);
	$date_to_unix = strtotime($date_to);
	
	$CurrentDayFrom = getCurrentDay($date_from_unix);
	$CurrentDayTo = getCurrentDay($date_to_unix);
	
	$begin_unix = $CurrentDayFrom['by_timestamp']['begins']['day']['unix'];
	$end_unix = $CurrentDayFrom['by_timestamp']['ends']['day']['unix'];
	
//	debug( date("d.m.Y H:i:s", $begin_unix) . ' - ' . date("d.m.Y H:i:s", $end_unix) . ' ( '. ($end_unix - $begin_unix) . ')' );
	
	$query_FormalJournal = "SELECT * FROM {$CAOP_JOURNAL} LEFT JOIN {$CAOP_PATIENTS} ON {$CAOP_JOURNAL}.journal_patid = {$CAOP_PATIENTS}.patid_id WHERE {$CAOP_JOURNAL}.journal_doctor='{$USER_PROFILE['doctor_id']}' AND ( {$CAOP_JOURNAL}.journal_update_unix >= '{$begin_unix}' AND {$CAOP_JOURNAL}.journal_update_unix <= '{$end_unix}' )";
	$result_FormalJournal = mqc($query_FormalJournal);
	$amount_FormalJournal = mnr($result_FormalJournal);
	
	if ( $amount_FormalJournal > 0 )
	{
		$npp = 1;
		$AllPatients = mr2a($result_FormalJournal);
		include ( "engine/php/patient_search.php" );
	} else
	{
		bt_notice('Нет принятых пациентов за указанный период', BT_THEME_WARNING);
	}
	
}

?>

<script defer type="text/javascript" src="/engine/js/journalFormal.js?<?=rand(0, 99999);;?>"></script>