<?php
$DocumentParams = $_SESSION[$PrintParams[0]];
$DATES_ID = $DocumentParams['dates_id'];
$CheckDate = getarr(CAOP_SCHEDULE_UZI_DATES, "dates_id='$DATES_ID'");
if ( count($CheckDate) == 1 )
{
	$CheckDate = $CheckDate[0];
//	debug($CheckDate);
	
	$DoctorsListUzi = getarr(CAOP_DOCTOR, "doctor_isUzi='1'");
	$DoctorsListUziId = getDoctorsById($DoctorsListUzi);
	
	$AreasUZI = getarr(CAOP_SCHEDULE_UZI_RESEARCH_AREA, "1", "ORDER BY area_title ASC");
	$AreasUZIId = getDoctorsById($AreasUZI, 'area_id');
	
	$DoctorData = $DoctorsListUziId['id' . $CheckDate['dates_doctor_id']];
	
	$CheckShift = getarr(CAOP_SCHEDULE_UZI_SHIFTS, "shift_id='{$CheckDate['dates_shift_id']}'");
	if ( count($CheckShift) == 1 )
	{
	    $CheckShift = $CheckShift[0];
//		debug($CheckShift);
		
		$Patients_query = "SELECT * FROM ".CAOP_SCHEDULE_UZI_SHIFTS." AS shifts
				LEFT JOIN ".CAOP_SCHEDULE_UZI_TIMES." AS times ON shifts.shift_id=times.time_shift_id
				LEFT JOIN ".CAOP_SCHEDULE_UZI_PATIENTS." AS patuzi ON times.time_id=patuzi.patient_time_id AND patuzi.patient_date_id='{$CheckDate['dates_id']}'
				LEFT JOIN ".CAOP_PATIENTS." AS patdata ON patuzi.patient_pat_id=patdata.patid_id
				WHERE shifts.shift_id='{$CheckDate['dates_shift_id']}' ORDER BY times.time_order ASC";
		
		$Patients_result = mqc($Patients_query);
		$Patients_amount = mnr($Patients_result);
		if ( $Patients_amount > 0 )
		{
			$PatientData = mr2a($Patients_result);
			
//			debug($PatientData);
			
			?>
			<table width="100%" border="0" class="tbc">
				<tr>
					<td align="center">
						<b>
							<div class="size-16pt"><?=$LPU_DOCTOR['lpu_blank_name'];?></div>
							<div class="size-12pt">
								<?=$DoctorData['doctor_duty'];?> <?=mb_ucwords(docNameShort($DoctorData, "famimot"));?>
							</div>
							<br>
							<div class="size-16pt">СПИСОК ПАЦИЕНТОВ НА УЗИ ЦАОП НА <?=$CheckDate['dates_date'];?></div>
							<div class="size-14pt">Всего: <?=$Patients_amount .' '. wordEnd($Patients_amount, 'пациент', 'пациента', 'пациентов');?></div>
						</b>
					</td>
				</tr>
				<tr>
					<td>
						<table width="100%" border="1" class="tbc size-10pt" cellpadding="3">
							<tr>
								<td align="center" data-title="time" width="1%"><b>Время</b></td>
								<td align="center" data-title="name"><b>Ф.И.О. пациента</b></td>
								<td align="center" data-title="birth" width="1%"><b>Дата рождения</b></td>
								<td align="center" data-title="doctor" width="1%"><b>Врач</b></td>
								<td align="center" data-title="area" width="1%"><b><?=nbsper('Область исследования');?></b></td>
								<td align="center" data-title="visited"><b>Метка явки</b></td>
							</tr>
						<?php
						foreach ($PatientData as $patientDatum)
						{
						    $AreaData = $AreasUZIId['id' . $patientDatum['patient_area_id']];
							$DocData = $DoctorsListUziId['id' . $patientDatum['patient_prescription_doctor_id']];
							$DocName = docNameShort($DocData);
							
							if ( $patientDatum['patid_birth'] )
								$age = ageByBirth($patientDatum['patid_birth']);
							
							$addon = (strlen($patientDatum['patient_area_description']) > 0) ? ' ('.$patientDatum['patient_area_description'].')' : '';
							
							$patdata = '';
							if (strlen($patientDatum['patid_name']) > 0)
                            {
                                $patdata = mb_ucwords($patientDatum['patid_name']) . ', ' . $age . ' ' . wordEnd($age, 'год', 'года', 'лет');
                            }
							
							
							else $age = '';
							?>
							<tr>
								<td data-cell="time">
									<?=$patientDatum['time_hour'];?>:<?=$patientDatum['time_min'];?>
								</td>
								<td data-cell="name">
									<?=$patdata;?>
								</td>
								<td data-cell="birth">
									<?=$patientDatum['patid_birth'];?>
								</td>
								<td data-cell="doctor">
									<?=nbsper($DocName);?>
								</td>
								<td data-cell="area">
									<?=$AreaData['area_title'];?><?=$addon;?>
								</td>
								<td data-cell="visited">
									&nbsp;
								</td>
							</tr>
							<?php
						}
						?>
						</table>
					</td>
				</tr>
			</table>
			<?php
			
//			debug($PatientData);
		} else
		{
			echo 'НА УКАЗАННУЮ ДАТУ ПАЦИЕНТОВ НЕТ';
		}
	 
	} else
	{
		echo 'ТАКОЙ СМЕНЫ НЕ СУЩЕСТВУЕТ';
	}
	
//	debug($CheckDate);
//	debug($DoctorData);
	
	/*$Patients_query = "
	SELECT * FROM ".CAOP_SCHEDULE_UZI_PATIENTS." AS patuzi
	RIGHT OUTER JOIN ".CAOP_SCHEDULE_UZI_TIMES." AS times ON times.time_id=patuzi.patient_time_id
	WHERE patuzi.patient_date_id='{$CheckDate['dates_id']}'
	ORDER BY times.time_order ASC";
	
	debug($Patients_query);
	
	*/
} else
{
	echo 'ТАКОГО ДНЯ НЕ СУЩЕСТВУЕТ В РАСПИСАНИИ УЗИ ЦАОП';
}