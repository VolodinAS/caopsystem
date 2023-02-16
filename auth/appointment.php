<?php

$Today_Array = getarr('caop_days', "day_unix='{$CURRENT_DAY['unix']}' AND day_doctor='{$USER_PROFILE['doctor_id']}'");
if ( count($Today_Array) == 1 )
{
	$Today_Array = $Today_Array[0];
//    require_once ( "engine/html/journal_patientlist.php" );
	$CHOSEN_DOCTOR_ID = $USER_PROFILE['doctor_id'];
	if ( isMobile() )
	{
//		debug('Вывод для мобильного устройства');
		require_once ( "engine/html/journal_patientlist_cards_mobile.php" );
	} else
	{
		require_once ( "engine/html/journal_patientlist_cards_spo.php" );
	}
	
} else
{
	bt_notice('У Вас еще нет на сегодня созданных приёмов. <button class="btn btn-primary btn-sm" id="addNewDay">Создать?</button>', BT_THEME_WARNING);
}

?>





<?php

include ( "engine/html/modals/suspicio.php" );

include ( "engine/html/modals/journalCurrentMessageBox.php" );

include ( "engine/html/modals/newPatient.php" );
include ( "engine/html/modals/search4add.php" );

include ( "engine/html/modals/movePatients.php" );
include ( "engine/html/modals/moveDocPatients.php" );
include ( "engine/html/modals/movePatientsAll.php" );
include ( "engine/html/modals/journalPatientCard.php" );
include ( "engine/html/modals/miasImportWindow.php" );
include ( "engine/html/modals/miasImportWindow2.php" );
include ( "engine/html/modals/miasImportWindowMain.php" );
include ( "engine/html/modals/addPatientResearch.php" );
include ( "engine/html/modals/addPatientCitology.php" );
require_once("engine/html/modals/visitsPatientData.php");

include ( "engine/html/modals/editResearch.php" );
?>



<script defer src="/engine/js/journal.js?<?=rand(0,999999);?>" type="text/javascript"></script>