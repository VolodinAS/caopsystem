<?php

//НОВАЯ СИСТЕМА ТАБЛИЦЫ
$q_AllPatients = "SELECT *, COUNT(cj.journal_id) as visits
                    FROM {$CAOP_PATIENTS} cp
                        LEFT JOIN {$CAOP_JOURNAL} cj
                            ON cp.patid_id=cj.journal_patid
                    GROUP BY cp.patid_id
                    ORDER BY cp.patid_name ASC";
$r_AllPatients = mqc($q_AllPatients);
$AllPatients = mr2a($r_AllPatients);
$q_AllJournal = "SELECT COUNT(*) AS amount FROM {$CAOP_JOURNAL}";
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

//debug($AllJournal);
//array (personal, visits, diagnosis)
$msg = '
Всего в списке: <b>'.count($AllPatients).'</b> ' . pluralForm(count($AllPatients), 'пациент', 'пациента', 'пациентов') . '<br/>
Врачами проведено: <b>'.$AllJournal['amount'].'</b> ' . pluralForm($AllJournal['amount'], 'приём', 'приёма', 'приёмов');
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
	    <script>
	    var ACTIVE_TAB = 'all_patients';
	    </script>
		<?php
		$activeTab = 'all_patients';
		include ( "engine/php/patient_search.php" );
		?>
	</div>
	<div class="tab-pane fade" id="casestatus0">
	    <script>
	    var ACTIVE_TAB = 'casestatus0';
	    </script>
		<?php
		$AllPatients = $CaseStatus0;
		$activeTab = 'casestatus0';
		include ( "engine/php/patient_search.php" );
		?>
	</div>
	<div class="tab-pane fade" id="casestatus1">
	    <script>
	    var ACTIVE_TAB = 'casestatus1';
	    </script>
		<?php
		$AllPatients = $CaseStatus1;
		$activeTab = 'casestatus1';
		include ( "engine/php/patient_search.php" );
		?>
	</div>
	<div class="tab-pane fade" id="casestatus2">
	    <script>
	    var ACTIVE_TAB = 'casestatus2';
	    </script>
		<?php
		$AllPatients = $CaseStatus2;
		$activeTab = 'casestatus2';
		include ( "engine/php/patient_search.php" );
		?>
	</div>
	<div class="tab-pane fade" id="casestatus3">
	    <script>
	    var ACTIVE_TAB = 'casestatus3';
	    </script>
		<?php
		$AllPatients = $CaseStatus3;
		$activeTab = 'casestatus3';
		include ( "engine/php/patient_search.php" );
		?>
	</div>
	<div class="tab-pane fade" id="casestatus4">
	    <script>
	    var ACTIVE_TAB = 'casestatus4';
	    </script>
		<?php
		$AllPatients = $CaseStatus4;
		$activeTab = 'casestatus4';
		include ( "engine/php/patient_search.php" );
		?>
	</div>
</div>

<?php
require_once ( "engine/html/modals/visitsPatientData.php" );
?>

<script defer language="JavaScript" type="text/javascript" src="/engine/js/allpatients.js"></script>