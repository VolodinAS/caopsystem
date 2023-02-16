<?php
$Journal = $JournalDisp;

//echo "|{$showRepeats}|";

$DispLPU = getarr(CAOP_DISP_LPU, 1, "ORDER BY lpu_order ASC");
$DispLPUId = getDoctorsById($DispLPU, 'lpu_id');

$SPOReasonTypes = getarr(CAOP_SPO_REASON_TYPES, "reason_enabled='1'", "ORDER BY reason_type_order ASC");
$SPOReasonTypesId = getDoctorsById($SPOReasonTypes, 'reason_type_id');

if ( count($Journal) > 0 )
{


    // debug($SPOReasonTypesId);
?>

<div class="header">
    <div class="gbuz boldy"><?=$LPU_DOCTOR['lpu_blank_name'];?></div>
    <div class="address boldy"><?=$LPU_DOCTOR['lpu_lpu_address'];?></div>
	<div class="listheader boldy">СПИСОК ПРИНЯТЫХ ДИСПАНСЕРНЫХ ПАЦИЕНТОВ</div>
</div>
<div>
	<div>
		<span class="boldy">Врач:</span> <?=mb_ucwords($DoctorData['doctor_f'] . ' ' . $DoctorData['doctor_i'] . ' ' . $DoctorData['doctor_o']);?>
	</div>
	<?php
	if ( $DayData['day_nurse'] > 0 )
	{
		$NurseData = getarr(CAOP_DOCTOR, "doctor_id='{$DayData['day_nurse']}'");
		if ( count($NurseData) > 0 )
		{
			$NurseData = $NurseData[0];
			?>
			<div>
				<span class="boldy">Медицинская сестра:</span> <?=mb_ucwords($NurseData['doctor_f'] . ' ' . $NurseData['doctor_i'] . ' ' . $NurseData['doctor_o']);?>
			</div>
			<?php
		}
	}
	?>
	<div>
		<span class="boldy">Кабинет:</span> <?=$DoctorData['doctor_cabinet'];?>
	</div>
	<div>
		<span class="boldy">Дата приёма:</span> <?=date("d.m.Y г.", $DayData['day_unix']);?>
	</div>
	<div>
		<span class="boldy">Диспансерных пациентов:</span> <?=count($Journal)?>
	</div>
	<br>
	<div>
		<table class="minitable" border="1" cellpadding="2">
			<thead>
			<tr>
				<!--<th class="boldy tablehead" width="1%">№&nbsp;п/п</th>-->
				<th class="boldy tablehead" width="1%">Время</th>
				<th class="boldy tablehead" width="1%">№&nbsp;карты</th>
				<th class="boldy tablehead">Ф.И.О.</th>
				<th class="boldy tablehead" width="1%">Дата рождения</th>
				<th class="boldy tablehead" width="1%"><?=str_nbsp('Телефон', 5);?></th>
				<th class="boldy tablehead">Адрес</th>
                <th class="boldy tablehead">Итог случая</th>
				<th class="boldy tablehead" width="1%">Диагноз напра-вительный</th>
				
				<th class="boldy tablehead" width="1%">Диагноз ЦАОП</th>
				<th class="boldy tablehead" width="1%">Дата установки</th>
				<th class="boldy tablehead" width="1%">Прикрепление</th>
			</tr>
			</thead>
			<tbody>
			<?php
			$npp = 1;
			foreach ($Journal as $PatientItem):
				?>
				<?php
                // debug($PatientItem);
//				$PatientPersonalData = getarr(CAOP_PATIENTS, "patid_id='{$PatientItem['journal_patid']}'");
                $PatientPersonalData = extractValueByKey($PatientItem, 'patid_');
                
                $complete = ( $PatientItem['journal_spo_end_reason_type'] > 0 ) ? $SPOReasonTypesId['id' . $PatientItem['journal_spo_end_reason_type']]['reason_type_title'] : 'НЕ УКАЗАН ВРАЧОМ';
				
				$disp_lpu = 'не указано';
				if ( $PatientItem['journal_disp_lpu'] != 0 )
                {
	                $disp_lpu = nbsper($DispLPUId [ 'id' . $PatientItem['journal_disp_lpu'] ]['lpu_shortname']);
                }
				
				if ( count($PatientPersonalData) == 1 ) $PatientPersonalData = $PatientPersonalData[0];
				?>
				<tr>
					<!--<td class="textcenter">-->
					<!--	<?=$npp;?>-->
					<!--</td>-->
					<td class="textcenter">
						<?=$PatientItem['journal_time'];?>
					</td>
					<td class="textcenter">
						<?=$PatientPersonalData['patid_ident'];?>
					</td>
					<td>
						<?=mb_ucwords( $PatientPersonalData['patid_name'] );?>
					</td>
					<td class="textcenter">
						<?=$PatientPersonalData['patid_birth'];?>
					</td>
					<td class="textcenter">
						<?=$PatientPersonalData['patid_phone'];?>
					</td>
					<td>
						<?=$PatientPersonalData['patid_address'];?>
					</td>
                    <td>
                        <?=$complete;?>
                    </td>
					<td class="textcenter">
						<?=$PatientItem['spo_mkb_directed'];?>
					</td>
					<td class="textcenter">
						<?=$PatientItem['spo_mkb_finished'];?>
					</td>
					<td class="textcenter">
						<?=date(DMY, $PatientItem['spo_unix_accounting_set']);?>
					</td>
					<td>
						<?=$disp_lpu;?>
					</td>
				</tr>
				<?php
//		debug($PatientItem);
				?>
				<?php
				$npp++;
			endforeach;
			?>
			</tbody>
		</table>
		<br>
		<div class="boldy address">* Внимание! Список отсортирован в алфавитном порядке имени пациента!</div>
	</div>
</div>

<?php
//debug($USER_PROFILE);
} else
{
    echo 'НА ЭТОТ ДЕНЬ В СПИСКЕ ПРИЁМА НЕ БЫЛО ДИСПАНСЕРНЫХ ПАЦИЕНТОВ';
}
?>