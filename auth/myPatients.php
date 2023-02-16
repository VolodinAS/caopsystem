<?php

//debug($_SESSION);

$q_AllJournal = "SELECT COUNT(*) AS amount FROM {$CAOP_JOURNAL} WHERE journal_doctor='{$USER_PROFILE['doctor_id']}'";
$r_AllJournal = mqc($q_AllJournal);
$AllJournal = mfa($r_AllJournal);

if ( isset($_SESSION['my_patients']) )
{
    switch ($_SESSION['my_patients']['showme'])
    {
        case "casestatus0":
	        $q_CaseStatus0 = "SELECT *, COUNT(cj.journal_id) as visits FROM {$CAOP_PATIENTS} cp
                            LEFT JOIN {$CAOP_JOURNAL} cj ON cp.patid_id=cj.journal_patid
                        WHERE cj.journal_doctor='{$USER_PROFILE['doctor_id']}'
                        AND cp.patid_casestatus='0'
                        GROUP BY cp.patid_id ORDER BY cp.patid_name ASC";
	        $r_CaseStatus0 = mqc($q_CaseStatus0);
	        $CaseStatus0 = mr2a($r_CaseStatus0);
	        
	        $Vitrina = vitrina(V_MYP, 'casestatus0', count($CaseStatus0));
	
	        $AllPatients = $CaseStatus0;
	        $activeTab = 'casestatus0';
	        $active_casestatus0 = 'active';
        break;
        case "casestatus1":
	        $q_CaseStatus1 = "SELECT *, COUNT(cj.journal_id) as visits FROM {$CAOP_PATIENTS} cp
                            LEFT JOIN {$CAOP_JOURNAL} cj ON cp.patid_id=cj.journal_patid
                        WHERE cj.journal_doctor='{$USER_PROFILE['doctor_id']}'
                        AND cp.patid_casestatus='1'
                        GROUP BY cp.patid_id ORDER BY cp.patid_name ASC";
	        $r_CaseStatus1 = mqc($q_CaseStatus1);
	        $CaseStatus1 = mr2a($r_CaseStatus1);
	
	        $Vitrina = vitrina(V_MYP, 'casestatus1', count($CaseStatus1));
	
	        $AllPatients = $CaseStatus1;
	        $activeTab = 'casestatus1';
	        $active_casestatus1 = 'active';
        break;
        case "casestatus2":
            /*ПРОБЛЕМНЫЙ УЧАСТОК*/
	        $q_CaseStatus2 = "SELECT *, COUNT(cj.journal_id) as visits FROM {$CAOP_PATIENTS} cp
                            LEFT JOIN {$CAOP_JOURNAL} cj ON cp.patid_id=cj.journal_patid
                        WHERE cj.journal_doctor='{$USER_PROFILE['doctor_id']}'
                        AND cp.patid_casestatus='2'
                        GROUP BY cp.patid_id ORDER BY cp.patid_name ASC";
	        $r_CaseStatus2 = mqc($q_CaseStatus2);
	        $CaseStatus2_pre = mr2a($r_CaseStatus2);
        
//            $CaseStatus2_pre = getarr(CAOP_PATIENTS, "journal_doctor='{$USER_PROFILE['doctor_id']}' AND patid_casestatus='2'", "ORDER BY patid_name ASC");

//            debug('$CaseStatus2_pre: ' . count($CaseStatus2_pre));
        
	        $CaseStatus2_post = [];
	        foreach ($CaseStatus2_pre as $patientData)
	        {
	            $GetLastVisit = getarr(CAOP_JOURNAL, "journal_patid='{$patientData['patid_id']}'", "ORDER BY journal_id DESC LIMIT 0, 1");
	            if ( count($GetLastVisit) > 0 )
                {
	                $GetLastVisit = $GetLastVisit[0];
	                if ( $GetLastVisit['journal_doctor'] == $USER_PROFILE['doctor_id'] )
                    {
                        $CaseStatus2_post[] = $patientData;
                    }
                }
//                debug($GetLastVisit);
//                break;
	        }
	        unset($CaseStatus2_pre);
//	        debug('$CaseStatus2_post: ' . count($CaseStatus2_post));
	        
	        $Vitrina = vitrina(V_MYP, 'casestatus2', count($CaseStatus2_post));
	
	        $AllPatients = $CaseStatus2_post;
	        $activeTab = 'casestatus2';
	        $active_casestatus2 = 'active';
	        /*ПРОБЛЕМНЫЙ УЧАСТОК*/
        break;
        case "casestatus3":
	        $q_CaseStatus3 = "SELECT *, COUNT(cj.journal_id) as visits FROM {$CAOP_PATIENTS} cp
                            LEFT JOIN {$CAOP_JOURNAL} cj ON cp.patid_id=cj.journal_patid
                        WHERE cj.journal_doctor='{$USER_PROFILE['doctor_id']}'
                        AND cp.patid_casestatus='3'
                        GROUP BY cp.patid_id ORDER BY cp.patid_name ASC";
	        $r_CaseStatus3 = mqc($q_CaseStatus3);
	        $CaseStatus3 = mr2a($r_CaseStatus3);
	
	        $Vitrina = vitrina(V_MYP, 'casestatus3', count($CaseStatus3));
	
	        $AllPatients = $CaseStatus3;
	        $activeTab = 'casestatus3';
	        $active_casestatus3 = 'active';
        break;
        case "casestatus4":
	        $q_CaseStatus4 = "SELECT *, COUNT(cj.journal_id) as visits FROM {$CAOP_PATIENTS} cp
                            LEFT JOIN {$CAOP_JOURNAL} cj ON cp.patid_id=cj.journal_patid
                        WHERE cj.journal_doctor='{$USER_PROFILE['doctor_id']}'
                        AND cp.patid_casestatus='4'
                        GROUP BY cp.patid_id ORDER BY cp.patid_name ASC";
	        $r_CaseStatus4 = mqc($q_CaseStatus4);
	        $CaseStatus4 = mr2a($r_CaseStatus4);
	
	        $Vitrina = vitrina(V_MYP, 'casestatus4', count($CaseStatus4));
	
	        $AllPatients = $CaseStatus4;
	        $activeTab = 'casestatus4';
	        $active_casestatus4 = 'active';
        break;
    }
} else
{
	$q_AllMyPatients = "SELECT *, COUNT(cj.journal_id) as visits FROM {$CAOP_PATIENTS} cp LEFT JOIN {$CAOP_JOURNAL} cj ON cp.patid_id=cj.journal_patid WHERE cj.journal_doctor='{$USER_PROFILE['doctor_id']}' GROUP BY cp.patid_id ORDER BY cp.patid_name ASC";
	$r_AllMyPatients = mqc($q_AllMyPatients);
	$AllMyPatients = mr2a($r_AllMyPatients);
	
	$Vitrina = vitrina(V_MYP, 'all_patients', count($AllMyPatients));
	
	$AllPatients = $AllMyPatients;
	$activeTab = 'all_patients';
	$active_all_patients = 'active';
}

$NewVitrina = vitrina(V_MYP);
if ( $NewVitrina['result'] === true )
{
    $NewVitrina = $NewVitrina['data'];
//    debug($NewVitrina);
}

$msg = '
    Вы приняли: <b>'.$NewVitrina[V_MYP]['all_patients'].'</b> ' . pluralForm( $NewVitrina[V_MYP]['all_patients'] , 'пациент', 'пациента', 'пациентов') . '<br/>
    Вами проведено: <b>'.$AllJournal['amount'].'</b> ' . pluralForm($AllJournal['amount'], 'приём', 'приёма', 'приёмов');
bt_notice( $msg );

?>

<ul class="list-group list-group-horizontal">

    <li class="list-group-item cursor-pointer <?=$active_all_patients;?>" onclick="javascript:mypSetSession('reset')">
	    <?=nbsper('Все пациенты');?>
        <? badge($NewVitrina[V_MYP]['all_patients'], BT_THEME_PRIMARY, 1); ?>
    </li>
    <li class="list-group-item cursor-pointer <?=$active_casestatus0;?>" onclick="mypSetSession('set','casestatus0')">
	    <?=nbsper('Не распределены');?>
	    <? badge($NewVitrina[V_MYP]['casestatus0'], BT_THEME_PRIMARY, 1); ?>
    </li>
    <li class="list-group-item cursor-pointer <?=$active_casestatus1;?>" onclick="mypSetSession('set','casestatus1')">
	    <?=nbsper('Случай завершен');?>
	    <? badge($NewVitrina[V_MYP]['casestatus1'], BT_THEME_PRIMARY, 1); ?>
    </li>
    <li class="list-group-item cursor-pointer <?=$active_casestatus2;?>" onclick="mypSetSession('set','casestatus2')">
	    <?=nbsper('Обследуется');?>
	    <? badge($NewVitrina[V_MYP]['casestatus2'], BT_THEME_PRIMARY, 1); ?>
    </li>
    <li class="list-group-item cursor-pointer <?=$active_casestatus3;?>" onclick="mypSetSession('set','casestatus3')">
	    <?=nbsper('Susp c-r');?>
	    <? badge($NewVitrina[V_MYP]['casestatus3'], BT_THEME_PRIMARY, 1); ?>
    </li>
    <li class="list-group-item cursor-pointer <?=$active_casestatus4;?>" onclick="mypSetSession('set','casestatus4')">
	    <?=nbsper('Установлено ЗНО');?>
	    <? badge($NewVitrina[V_MYP]['casestatus4'], BT_THEME_PRIMARY, 1); ?>
    </li>
    
</ul>
<div class="dropdown-divider"></div>
<?php


//debug($AllPatients);

//$AllPatients = null;

include ( "engine/php/patient_search.php" );

$notShow = true;
if ( !$notShow )
{
    //НОВАЯ СИСТЕМА ТАБЛИЦЫ
    $q_AllPatients = "SELECT *, COUNT(cj.journal_id) as visits FROM {$CAOP_PATIENTS} cp LEFT JOIN {$CAOP_JOURNAL} cj ON cp.patid_id=cj.journal_patid WHERE cj.journal_doctor='{$USER_PROFILE['doctor_id']}' GROUP BY cp.patid_id ORDER BY cp.patid_name ASC";
    $r_AllPatients = mqc($q_AllPatients);
    $AllPatients = mr2a($r_AllPatients);
    $q_AllJournal = "SELECT COUNT(*) AS amount FROM {$CAOP_JOURNAL} WHERE journal_doctor='{$USER_PROFILE['doctor_id']}'";
    $r_AllJournal = mqc($q_AllJournal);
    $AllJournal = mfa($r_AllJournal);
    $AllPatientsData = array();
    foreach ($AllPatients as $PatientData)
    {
        $AllPatientsData['patid_casestatus' . $PatientData['patid_casestatus']][] = $PatientData;
    }
    $CaseStatus0 = $AllPatientsData['patid_casestatus0'];
    $CaseStatus1 = $AllPatientsData['patid_casestatus1'];
    $CaseStatus2 = $AllPatientsData['patid_casestatus2'];
    $CaseStatus3 = $AllPatientsData['patid_casestatus3'];
    $CaseStatus4 = $AllPatientsData['patid_casestatus4'];
    
    //debug( $CaseStatus2 );
    
    //debug($AllJournal);
    //array (personal, visits, diagnosis)
    $msg = '
    Вы приняли: <b>'.count($AllPatients).'</b> ' . pluralForm(count($AllPatients), 'пациент', 'пациента', 'пациентов') . '<br/>
    Вами проведено: <b>'.$AllJournal['amount'].'</b> ' . pluralForm($AllJournal['amount'], 'приём', 'приёма', 'приёмов');
    bt_notice( $msg );
    
    ?>
    
    <ul class="nav nav-tabs">
        <li class="nav-item">
            <a class="nav-link active" data-toggle="tab" href="#all_patients" data-activetab="all_patients">Все пациенты (<?=count($AllPatients);?>)</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#casestatus0" data-activetab="casestatus0">Не распределены (<?=count($CaseStatus0)?>)</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#casestatus1" data-activetab="casestatus1">Больше не вернется (<?=count($CaseStatus1)?>)</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#casestatus2" data-activetab="casestatus2">Обследуется (<?=count($CaseStatus2)?>)</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#casestatus3" data-activetab="casestatus3">Susp c-r (<?=count($CaseStatus3)?>)</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#casestatus4" data-activetab="casestatus4">Установлено ЗНО (<?=count($CaseStatus4)?>)</a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane fade show active" id="all_patients">
            <?php
            $activeTab = 'all_patients';
            include ( "engine/php/patient_search.php" );
            ?>
        </div>
        <div class="tab-pane fade" id="casestatus0">
            <?php
            $AllPatients = $CaseStatus0;
            $activeTab = 'casestatus0';
            include ( "engine/php/patient_search.php" );
            ?>
        </div>
        <div class="tab-pane fade" id="casestatus1">
            <?php
            $AllPatients = $CaseStatus1;
            $activeTab = 'casestatus1';
            include ( "engine/php/patient_search.php" );
            ?>
        </div>
        <div class="tab-pane fade" id="casestatus2">
            <?php
            $AllPatients = $CaseStatus2;
            $activeTab = 'casestatus2';
            include ( "engine/php/patient_search.php" );
            ?>
        </div>
        <div class="tab-pane fade" id="casestatus3">
            <?php
            $AllPatients = $CaseStatus3;
            $activeTab = 'casestatus3';
            include ( "engine/php/patient_search.php" );
            ?>
        </div>
        <div class="tab-pane fade" id="casestatus4">
            <?php
            $AllPatients = $CaseStatus4;
            $activeTab = 'casestatus4';
            include ( "engine/php/patient_search.php" );
            ?>
        </div>
    </div>
    
    

<?php
}

?>

<?php
require_once ( "engine/html/modals/visitsPatientData.php" );
?>

<script defer language="JavaScript" type="text/javascript" src="/engine/js/allpatients.js?<?=rand(0,1000000);?>"></script>