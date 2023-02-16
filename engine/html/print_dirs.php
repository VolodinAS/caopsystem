<?php
$DirStacList = getarr(CAOP_DIRSTAC, "dirstac_enabled='1'", "ORDER BY dirstac_order ASC");
$DirStacListId = getDoctorsById($DirStacList, 'dirstac_id');
?>

<div class="header">
	<div class="gbuz boldy"><?=$LPU_DOCTOR['lpu_blank_name'];?></div>
	<div class="address boldy"><?=$LPU_DOCTOR['lpu_lpu_address'];?></div>
	<div class="listheader boldy">НАПРАВЛЕННЫЕ ПАЦИЕНТЫ</div>
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
		<?php
//		debug($DirStacList);
//		debug($Journal);
		
		for ($dirIndex=0; $dirIndex<count($DirStacList); $dirIndex++)
		{
		    $dirItem = $DirStacList[$dirIndex];
			
			foreach ($Journal as $journalItem)
			{
//				journal_dirstac
				if ( (int)$journalItem['journal_dirstac'] === (int)$dirItem['dirstac_id'] )
				{
					if ( !isset($DirStacList[$dirIndex]['count']) ) $DirStacList[$dirIndex]['count'] = 1;
					else $DirStacList[$dirIndex]['count']++;
					$DirStacList[$dirIndex]['data'][] = $journalItem;
				}
		    }
		}
		
//		debug($DirStacList);
		
		foreach ($DirStacList as $dirItem)
		{
			if ( $dirItem['count'] > 0 )
			{
				$Journal = $dirItem['data'];
				
				?><span style="font-weight: bolder; font-size: 20px">Направленные в <?=$dirItem['dirstac_title'];?> [<?=$dirItem['count'];?>]</span><br/>
				<span><?=$dirItem['dirstac_desc'];?></span><br><br>
				<!--
				<table class="minitable" border="1" cellpadding="2">
					<thead>
					<tr>
						<th class="boldy tablehead" width="1%">№&nbsp;карты</th>
						<th class="boldy tablehead">Ф.И.О.</th>
						<th class="boldy tablehead" width="1%">Дата рождения</th>
						<th class="boldy tablehead" width="1%">Диагноз</th>
						<th class="boldy tablehead">Место карты</th>
						<th class="boldy tablehead">Учреждение</th>
					</tr>
					</thead>
					<tbody>
					<?php
					$npp = 1;
					foreach ($Journal as $PatientItem):
						$journal_dirstac_desc = ( strlen( $PatientItem['journal_dirstac_desc'] ) > 0 ) ? $PatientItem['journal_dirstac_desc'] : $dirItem['dirstac_title'];
						?>
						<?php
						$PatientPersonalData = getarr(CAOP_PATIENTS, "patid_id='{$PatientItem['journal_patid']}'");
						
						if ( count($PatientPersonalData) == 1 ) $PatientPersonalData = $PatientPersonalData[0];
						?>
						<tr>
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
								<?=$PatientItem['journal_ds'];?>
							</td>
							<td>
								<?=$PatientItem['journal_cardplace'];?>
							</td>
							<td>
								<?=$journal_dirstac_desc;?>
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
				-->
				<?php
			}
		}
		?>
	</div>
</div>

<?php
//debug($USER_PROFILE);
?>