<style>
	@media print{
		@page {size: landscape}
	}
</style>

<?php

$SessionParam = $PrintParams[0];

$date_from_unix = strtotime($_SESSION[$SessionParam]['date_from']);
$date_to_unix = strtotime($_SESSION[$SessionParam]['date_to']);
$date_from = date("d.m.Y", $date_from_unix);
$date_to = date("d.m.Y", $date_to_unix);

$date_from_unix_begin = $date_from_unix;
$date_to_unix_end = $date_to_unix + (86400-1);

$query_Visits = "SELECT * FROM {$CAOP_DS_VISITS}
					LEFT JOIN {$CAOP_DS_PATIENTS} ON {$CAOP_DS_VISITS}.visreg_dspatid={$CAOP_DS_PATIENTS}.patient_id
					LEFT JOIN {$CAOP_DS_DIRLIST} ON {$CAOP_DS_VISITS}.visreg_dirlist_id={$CAOP_DS_DIRLIST}.dirlist_id
					WHERE {$CAOP_DS_VISITS}.visreg_visit_unix>='{$date_from_unix_begin}' AND {$CAOP_DS_VISITS}.visreg_visit_unix<='{$date_to_unix_end}'";
$result_Visits = mqc($query_Visits);
$amount_Visits = mnr($result_Visits);

if ( $amount_Visits > 0 )
{
	$VisitsDS = mr2a($result_Visits);
	
	$PatientsVisits = [];
	
	foreach ($VisitsDS as $visit)
	{
		$PatientsVisits['patient_id_' . $visit['patient_id']][] = $visit;
	}
	/**
	 * ОКТЯБРЬ  - 44 / 44   |   44 +++
     * НОЯБРЬ   - 51 / 95   |   51 +++
     * ДЕКАБРЬ  - 46 / 141  |   46 +++
	 */
	?>
	<hr>
	<table width="100%" border="0">
		<tbody>
		<tr>
			<td align="center"><h1>Сколько было визитов за период</h1></td>
		</tr>
		</tbody>
	</table>
	<hr>
	<h2>Период: <?=$date_from;?> - <?=$date_to;?></h2>
	<h3>Количество визитов за период: <?=$amount_Visits;?></h3>
	<h3>Количество пациентов за период: <?=count($PatientsVisits);?></h3>
	
	<table class="tbc size-10pt" border="1" cellpadding="5">
		<thead>
		<tr>
			<th width="1%" scope="col" class="text-center" data-title="npp">№</th>
			<th scope="col" class="text-center" data-title="patient_fio">
				Ф.И.О.
			</th>
			<th width="1%" scope="col" class="text-center" data-title="patient_birth">Дата рождения</th>
			<th width="1%" scope="col" class="text-center" data-title="dirlist_diag_mkb">МКБ</th>
			<th scope="col" class="text-center" data-title="dirlist_diag_text">Диагноз</th>
			<th width="1%" scope="col" class="text-center" data-title="dirlist_visit_date">Дата первой явки</th>
			<th width="1%" scope="col" class="text-center" data-title="visits">Визитов за период</th>
		</tr>
		</thead>
		<tbody>
		<?php
		$npp = 1;
		foreach ($PatientsVisits as $patientID=>$patientData)
		{
//			debug($PatientInfo);
			$PatientInfo = $patientData[0];
			$Visits = count($patientData);
			?>
			<tr>
				<td align="center" data-cell="npp" class="font-weight-bolder text-center"><?=$npp;?>)</td>
				<td data-cell="patient_fio" class="patient-name">
					<?=mb_ucwords($PatientInfo['patient_fio']);?>
				</td>
				<td align="center" data-cell="patient_birth" class="text-center"><?=$PatientInfo['patient_birth'];?></td>
				<td align="center" data-cell="dirlist_diag_mkb" class="text-center font-weight-bolder"><?=$PatientInfo['dirlist_diag_mkb'];?></td>
				<td data-cell="dirlist_diag_text"><?=$PatientInfo['dirlist_diag_text'];?></td>
				<td align="center" data-cell="dirlist_visit_date" class="text-center"><?=$PatientInfo['dirlist_visit_date'];?></td>
				<td align="center" data-cell="visits" class="text-center"><?=$Visits;?></td>
			
			</tr>
			<?php
			$npp++;
		}		
		?>
		<tr>
			<td colspan="6" align="right"><b>ВСЕГО ВИЗИТОВ:</b></td>
			<td align="center"><?=$amount_Visits;?></td>
		</tr>
		</tbody>
	</table>
	
	<?php
	
//	debug($PatientsVisits);
	
} else
{
	bt_notice('За отчётный период визитов в ДС не было');
}