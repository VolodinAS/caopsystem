<?php
$UziDoctor = getarr(CAOP_SCHEDULE_UZI_DOCTORS, "uzi_doctor_id='$ParamPage'");
if ( count($UziDoctor) == 1 )
{
	$UziDoctor = $UziDoctor[0];
	$DoctorData = $DoctorsListId['id' . $UziDoctor['uzi_doctor_id']];
	?>
	<table>
		<tr>
			<td width="1%">
				<button class="btn btn-secondary btn-sm" onclick="location.href='/uziSchedule'">Вернуться</button>
			</td>
			<td><h5>Настройка шаблонов графика УЗИ для врача <?=docNameShort($DoctorData);?>:</h5></td>
		</tr>
	</table>
	
	
	<button class="btn btn-sm btn-primary btn-addTemp" data-uziid="<?=$UziDoctor['uzi_id'];?>" data-doctorid="<?=$UziDoctor['uzi_doctor_id'];?>">Создать шаблон</button>
	
	<div class="dropdown-divider"></div>
	<h6>Доступные шаблоны:</h6>
	<div class="dropdown-divider"></div>
	<?php
	$CheckTemps = getarr(CAOP_SCHEDULE_UZI_DATES_SHIFTS_TEMP, "temp_uzi_id='{$UziDoctor['uzi_id']}' AND temp_subid='0'", "ORDER BY temp_title ASC");
	if ( count($CheckTemps) > 0 )
	{
		$ShiftsData = getarr(CAOP_SCHEDULE_UZI_SHIFTS, "shift_doctor_id='{$UziDoctor['uzi_doctor_id']}'", "ORDER BY shift_title ASC");
		$ShiftsDataById = getDoctorsById($ShiftsData, 'shift_id');
		?>
		<table>
		<?php
		$selectArrDefault = array(
			'key' => 0,
			'value' => 'Выберите...'
		);
		
		$isFirst = true;
		foreach ($CheckTemps as $checkTemp)
		{
			if ( $isFirst )
			{
				$isFirst = false;
			} else
			{
				?>
				<tr>
					<td colspan="10">&nbsp;</td>
				</tr>
				<?php
			}
			?>
			<tr>
				<td width="1%">
					<button class="btn btn-warning btn-removeTemp" data-tempid="<?=$checkTemp['temp_id'];?>">
						<?=BT_ICON_CLOSE;?>
					</button>
				</td>
				<td>
					<input type="text" class="form-control mysqleditor" placeholder="Введите название шаблона'" value="<?=$checkTemp['temp_title'];?>" data-action="edit"
					       data-table="<?=CAOP_SCHEDULE_UZI_DATES_SHIFTS_TEMP;?>"
					       data-assoc="0"
					       data-fieldid="temp_id"
					       data-id="<?=$checkTemp['temp_id'];?>"
					       data-field="temp_title">
				</td>
				<td width="1%">
					&nbsp;
				</td>
			</tr>
			<tr>
				<td colspan="3">
					<b>Укажите дням недели соответствующие созданные смены, чтобы начать включать их в график:</b>
					
					<table width="100%">
						<tr>
							<?php
							$GetDaysTemp = getarr(CAOP_SCHEDULE_UZI_DATES_SHIFTS_TEMP, "temp_subid='{$checkTemp['temp_id']}'", "ORDER BY temp_id ASC");
							$GetDaysTempById = getDoctorsById($GetDaysTemp, 'temp_week_day');
							
							for ($week_day=0; $week_day<7; $week_day++)
							{
								$ShiftTemp = $GetDaysTempById['id' . $week_day];
//								debug($ShiftTemp);
								$selectArrSelected = null;
								if ( $ShiftTemp['temp_day_shift'] > 0 )
								{
									$selectArrSelected = array(
										'value' => $ShiftTemp['temp_day_shift']
									);
								}
								$ShiftsSelector = array2select($ShiftsData, 'shift_id', 'shift_title', null,
									'class="form-control mysqleditor" data-action="edit"
								data-table="'.CAOP_SCHEDULE_UZI_DATES_SHIFTS_TEMP.'"
								data-assoc="0"
								data-fieldid="temp_id"
								data-id="'.$ShiftTemp['temp_id'].'"
								data-field="temp_day_shift"', $selectArrDefault, $selectArrSelected);
								?>
								<td align="center">
									<b><?=getDayRusShortByIndex($week_day+1);?>:</b><br>
									<?=$ShiftsSelector['result'];?>
								</td>
								<?php
							}
							
							?>
						</tr>
					</table>
				</td>
			</tr>
			<?php
		}
		?>
		</table>
		<?php
	} else
	{
		bt_notice('Нет шаблонов', BT_THEME_WARNING);
	}
}

/**
 * <button class="btn btn-sm btn-warning btn-copyFullTemps" data-uziid="<?=$UziDoctor['uzi_id'];?>" data-doctorid="<?=$UziDoctor['uzi_doctor_id'];?>">Скопировать шаблоны другому врачу</button>
 * <button class="btn btn-warning btn-copyTemp" data-tempid="<?=$checkTemp['temp_id'];?>"">Copy</button>
 */
?>