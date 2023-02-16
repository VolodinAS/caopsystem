<?php
$showdaylist = false;
if ( strlen($request_params) > 0 )
{
    $RequestData = explode("/", $request_params);
    $request_params = $RequestData[0];

    if ( ifound($RequestData[1], "light") )
    {
        $light_id = str_replace("light", "", $RequestData[1]);
        ?>
        <script>
            $( document ).ready(function()
            {
                let journal_id = <?=$light_id;?>;
                let btn = $('button[data-patient="'+journal_id+'"]');
                if (btn.length)
                {
                    setTimeout(function ()
                    {
                        btn.click();
                    }, 1000)
                }
            });
        </script>
        <?php
    }
//    debug($RequestData);
//    debug($light_id);

    $ChosenDayDoctor = getarr('caop_days', "day_id='{$request_params}'");
    if ( count($ChosenDayDoctor) == 1 )
    {
	    $Today_Array = $ChosenDayDoctor[0];
//	    debug($Today_Array);
        bt_notice('Просмотр журнала приёма от <b>'.date("d.m.Y", $Today_Array['day_unix']).'</b>',BT_THEME_SECONDARY);

//	    require_once ( "engine/html/journal_patientlist.php" );
	    $CHOSEN_DOCTOR_ID = $Today_Array['day_doctor'];
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
	require_once ( "engine/html/alldaytabs.php" );
}


?>

<?php
include ("engine/html/modals/uziSchedule/modal_uziList.php");
include ("engine/html/modals/uziSchedule/modal_uziTimeEditor.php");
include ("engine/html/modals/uzi_caop.php");

include ( "engine/html/modals/journalFastMove.php" );

include ( "engine/html/modals/journalCurrentMessageBox.php" );
include ( "engine/html/modals/newPatient.php" );
include ( "engine/html/modals/movePatients.php" );
include ( "engine/html/modals/moveDocPatients.php" );
include ( "engine/html/modals/movePatientsAll.php" );
include ( "engine/html/modals/journalPatientCard.php" );
include ( "engine/html/modals/visitsPatientData.php" );
include ( "engine/html/modals/dispancerPatient.php" );

include ( "engine/html/modals/addPatientResearch.php" );
include ( "engine/html/modals/addPatientCitology.php" );

include ( "engine/html/modals/editResearch.php" );
include ( "engine/html/modals/editCitology.php" );


?>



<script defer src="/engine/js/journal.js?<?=rand(0,999999);?>" type="text/javascript"></script>