<?php
//require_once ( "engine/html/journal_menu.php" );

//$PatientsToday = getarr('caop_journal', "journal_day='{$Today_Array['day_id']}' AND journal_doctor='{$CHOSEN_DOCTOR_ID}'", "ORDER BY journal_id DESC");

//debug($Today_Array['day_id']);

$PatientTodayQuery = "	SELECT    
							cj.journal_ds, 
							cj.journal_waiting, 
							cj.journal_time, 
							cj.journal_id, 
							cp.patid_id, 
							cp.patid_ident, 
							cp.patid_name, 
							cp.patid_birth, 
							cj.journal_ds, 
							cj.journal_ds_text,
							cj.journal_ds_recom FROM caop_journal cj
						INNER JOIN caop_patients cp ON cj.journal_patid = cp.patid_id 
						WHERE cj.journal_day='{$Today_Array['day_id']}' ORDER BY cj.journal_time ASC";
$PatientTodayResult = mqc($PatientTodayQuery);
$PatientTodayAmount = mnr($PatientTodayResult);

$JSON = json_encode($PatientsToday);
$JSON_HASH = md5($JSON);

?>
	<script>
        var JSON_HASH = '<?=$JSON_HASH;?>';
        var USE_JSON = 'none';
	</script>
<?php

if ( count($PatientTodayAmount) > 0 )
{

//	$FieldsData = getarr('caop_journal_field', "field_enabled='1'", "ORDER BY field_order ASC");
//        debug($FieldsData);
	?>

	<table class="table tablesorter table-sm" id="patient_journal">
		<thead>
		<tr>
			<th scope="col" width="1%" align="center">Время</th>
			<th scope="col" align="center">Ф.И.О.</th>
			<th scope="col" width="1%" align="center">Дата&nbsp;рождения</th>
		</tr>
		</thead>
		<tbody>
		<?php
		$npp = $PatientTodayAmount;
		while( $Patient = mfa($PatientTodayResult) )
		{
//			debug($Patient);

			$light_bg = ( $light_id == $Patient['journal_id'] ) ? 'table-primary' : '';

			$DeleteButton = '';
			if ( $Today_Array['day_signature_state'] == 0 )
			{
				$DeleteButton = '<a class="dropdown-item" href="javascript:journalRemovePatient('.$Patient['journal_id'].')">Удалить из списка</a><div class="dropdown-divider"></div>';
			}

			$waitingClass = ( $Patient['journal_waiting'] == 1 ) ? ' table-success' : '';

			if ( strlen($Patient['journal_ds_recom']) > 0 ) $waitingClass = ' table-dark';

			?>
			<tr class="<?=$light_bg;?> waiting-patient<?=$waitingClass;?>" data-waiting="<?=$Patient['journal_id'];?>" id="waitingPatient<?=$Patient['journal_id'];?>">
				<td data-title="Время" align="center">
					<?=$Patient['journal_time'];?>
				</td>
				<td data-title="Ф.И.О.">
					<?=mb_ucwords( $Patient['patid_name'] );?>
				</td>
				<td data-title="Дата рождения" align="center">
					<div data-type="fioForSort" style="display: none"><?=strtotime($PatientPersonalData['patid_birth']);?></div>
					<?=$Patient['patid_birth'];?>
				</td>
			</tr>
			<?php
			$npp--;
		}
		?>
		</tbody>
	</table>
	<?php

} else
{
	bt_notice('Список пациентов пока пуст.');
//	    echo 1;
}