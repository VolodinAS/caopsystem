<div class="header">
	<div class="gbuz boldy"><?=$LPU_DOCTOR['lpu_blank_name'];?></div>
	<div class="address boldy"><?=$LPU_DOCTOR['lpu_lpu_address'];?></div>
	<div class="listheader boldy">ЖУРНАЛ ПРИЁМА</div>
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
		<span class="boldy">Принято пациентов:</span> <?=count($Journal)?>
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
				<th class="boldy tablehead" width="1%">Диагноз</th>
				<th class="boldy tablehead">Место карты</th>
			</tr>
			</thead>
			<tbody>
			<?php
			$npp = 1;
			foreach ($Journal as $PatientItem):
				?>
				<?php
				$PatientPersonalData = getarr(CAOP_PATIENTS, "patid_id='{$PatientItem['journal_patid']}'");

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
					<td class="textcenter">
						<?=$PatientItem['journal_ds'];?>
					</td>
					<td>
						<?=$PatientItem['journal_cardplace'];?>
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
?>