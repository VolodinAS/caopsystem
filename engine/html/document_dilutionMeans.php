<?php
$SelectedData = $_SESSION[$PrintParams[0]];

$NurseData = $DoctorsNurseId['id' . $SelectedData['nurse_id']];
$NurseName = docNameShort($NurseData);

$Holidays = getarr(CAOP_HOLIDAYS, "holiday_enabled='1'", "ORDER BY holiday_sbboy_from ASC");
?>

<table width="100%" class="tbc">
	<tr>
		<td width="50%">&nbsp;</td>
		<td align="center">
			Приложение №18<br>
			к Приказу №56 от 12.01.2022 г.
		</td>
	</tr>
</table>
<br>
<table width="100%" class="tbc size-14pt" border="1" cellpadding="5">
	<tr class="size-10pt">
		<td align="center" class="boldy">
			Дата разведения дезин-фицирующего средства
		</td>
		<td align="center" class="boldy">
			Наименование дефин-фицирующего средства
		</td>
		<td align="center" class="boldy">
			Концентрация дезин-фицирующего средства
		</td>
		<td align="center" class="boldy">
			Ф.И.О., должность сотрудника, проводившего разведение дезин-фицирующего средства
		</td>
		<td align="center" class="boldy">
			Подпись сотрудника, проводившего разведение дезин-фицирующего средства
		</td>
		<td align="center" class="boldy">
			Ф.И.О., подпись старшей медицинской сестры, осуществляющей контроль разведения дезин-фицирующего средства
		</td>
	</tr>
	<tr>
		<?php
		for ($td=0; $td<6; $td++)
		{
		    ?>
			<td align="center" class="boldy"><?=($td+1);?></td>
		    <?php
		}
		
		?>
	</tr>
	<?php
	$date_from_unix = strtotime($SelectedData['date_from']);
	$amount_on_page = 0;
	for ($day=0; $day<100; $day++)
	{
	    if ($amount_on_page == 25)
        {
            break;
        }
		$day_week_index = date(DATE_WKNM, $date_from_unix);
//		debug($day_week_index);
		$go_next = false;
		if ( $SelectedData['skip_weekends'] )
		{
			if ( $day_week_index == 6 || $day_week_index == 7 )
			{
				$date_from_unix += TIME_DAY;
			} else $go_next = true;
		} else $go_next = true;
		
		if ( $go_next )
        {
            $go_next = false;
	        $is_found = false;
	        foreach ($Holidays as $holiday)
	        {
                $holiday_begin = ssboy($holiday['holiday_sbboy_from'], false, false);
                $holiday_end = ssboy($holiday['holiday_sbboy_to'], false, false);
		        if ($date_from_unix >= $holiday_begin && $date_from_unix <= $holiday_end)
		        {
			        $go_next = false;
			        $date_from_unix += TIME_DAY;
			        break;
		        } else
		        {
			        $go_next = true;
		        }
            }
        }
		
		if ($go_next)
		{
			$amount_on_page += 1;
			?>
			<tr>
				<td align="center">
					<?=date(DMY, $date_from_unix);?><br>
<!--                    --><?//=date(DMY, $holiday_begin);?><!-- - --><?//=date(DMY, $holiday_end);?>
				</td>
				<td align="center">
					Поликлин
				</td>
				<td align="center">
					2%
				</td>
				<td align="center">
					<?=nbsper($NurseName);?>
				</td>
				<td>
					&nbsp;
				</td>
				<td>
					&nbsp;
				</td>
			</tr>
			<?php
			$date_from_unix += TIME_DAY;
		}
	}
	
	?>
</table>
<?php
//print_r($_SESSION);
?>