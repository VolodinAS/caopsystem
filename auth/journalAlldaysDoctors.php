<?php
$showdaylist = false;
if ( strlen($request_params) > 0 )
{
	$RequestData = explode("/", $request_params);
	$request_params = $RequestData[0];

	if ( ifound($RequestData[1], "light") )
	{
		$light_id = str_replace("light", "", $RequestData[1]);
	}
//    debug($RequestData);
//    debug($light_id);

	$ChosenDayDoctor = getarr('caop_days', "day_id='{$request_params}'");
	if ( count($ChosenDayDoctor) == 1 )
	{
		$Today_Array = $ChosenDayDoctor[0];
//	    debug($Today_Array);
		$Doctor = $DoctorsListId['id' . $Today_Array['day_doctor']];
		$doc_name = mb_ucwords($Doctor['doctor_f'] . ' ' . $Doctor['doctor_i'] . ' ' . $Doctor['doctor_o']);
		bt_notice('Просмотр журнала приёма от <b>'.date("d.m.Y", $Today_Array['day_unix']).'</b>, врач: <b>'.$doc_name.'</b>',BT_THEME_SECONDARY);

//	    require_once ( "engine/html/journal_patientlist.php" );
		$CHOSEN_DOCTOR_ID = $Doctor['doctor_id'];
		require_once ( "engine/html/journal_patientlist_cards.php" );

	} else
	{
		bt_notice('Такого дня приёма (от '.date("d.m.Y", $request_params).') не существует', BT_THEME_WARNING);
		$showdaylist = true;
	}
} else
{
	$showdaylist = true;
}

if ( $showdaylist )
{
	$AllJournal = getrows(CAOP_JOURNAL, null, $PK[CAOP_JOURNAL]);

	$msg = 'Врачами проведено: <b>'.$AllJournal['count'].'</b> ' . pluralForm($AllJournal['count'], 'приём', 'приёма', 'приёмов');
	bt_notice( $msg );
	
	$DoctorSelectorDefault = array(
	    'key' => 0,
	    'value' => 'Выберите врача'
	);
	$DoctorSelectorSelected = array(
	    'value' => 0
	);
	$DoctorSelectorSelector = array2select($DoctorsListId, 'doctor_id', 'callback.func_doctor_name', null,
	'class="form-control form-control-lg selector-choose-doctor"', $DoctorSelectorDefault, $DoctorSelectorSelected);
	echo $DoctorSelectorSelector['result'];
 
?>
    <div id="doctor_result"></div>

<?php


}


?>



<?php

include ( "engine/html/modals/journalFastMove.php" );

include ( "engine/html/modals/journalCurrentMessageBox.php" );
include ( "engine/html/modals/newPatient.php" );
include ( "engine/html/modals/movePatients.php" );
include ( "engine/html/modals/moveDocPatients.php" );
include ( "engine/html/modals/movePatientsAll.php" );
include ( "engine/html/modals/journalPatientCard.php" );
?>



<script defer src="/engine/js/journal.js?<?=rand(0,999999);?>" type="text/javascript"></script>