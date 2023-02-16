<?php

$SelectedData = $_SESSION[$PrintParams[0]];

//debug($SelectedData);

$NurseOneData = $DoctorsNurseId['id' . $SelectedData['nurse_one_id']];
$NurseOneName = docNameShort($NurseOneData);

$NurseTwoData = $DoctorsNurseId['id' . $SelectedData['nurse_two_id']];
$NurseTwoName = docNameShort($NurseTwoData);

//$NurseName = $NurseOneName;

$Holidays = getarr(CAOP_HOLIDAYS, "holiday_enabled='1'", "ORDER BY holiday_sbboy_from ASC");

$periods = array(
    array(
        'from' => '7<sup><u>45</u></sup>',
	    'to'    =>  '8<sup><u>00</u></sup>'
    ),
	array(
		'from' => '13<sup><u>45</u></sup>',
		'to'    =>  '14<sup><u>00</u></sup>'
	),
	array(
		'from' => '19<sup><u>45</u></sup>',
		'to'    =>  '20<sup><u>00</u></sup>'
	)
);

$gen_clean_time = array(
	'from' => '15<sup><u>45</u></sup>',
	'to'    =>  '16<sup><u>00</u></sup>'
);

?>

<table width="100%" class="tbc size-14pt" border="1" cellpadding="4">
	<thead class="size-10pt">
		<tr>
			<th scope="col" class="text-center boldy" data-title="date">Дата</th>
			<th scope="col" class="text-center boldy" data-title="time" colspan="2">Время квар-цевания</th>
			<th scope="col" class="text-center boldy" data-title="nurse">Ф.И.О. медицин-ской сестры</th>
			<th scope="col" class="text-center boldy" data-title="hours">Количество отработан-ных часов</th>
			<th scope="col" class="text-center boldy" data-title="sign">Подпись медицин-ской сестры</th>
			<th scope="col" class="text-center boldy" data-title="add">Примечание</th>
		</tr>
	</thead>
	<tbody>
		<?php
        $is_one_first = true;
		$date_from_unix = strtotime($SelectedData['date_from']);
		$amount_on_page = 0;
		for ($day=0; $day<100; $day++)
		{
		    
		    if ( $is_one_first )
            {
                $is_one_first = false;
                $Nurse1 = $NurseOneName;
                $Nurse2 = $NurseTwoName;
            } else
            {
	            $is_one_first = true;
	            $Nurse2 = $NurseOneName;
	            $Nurse1 = $NurseTwoName;
            }
		    
			$is_gen_clean = false;
			
			$gen_clean_count = count($SelectedData['gen_cleans']);
			if ( $gen_clean_count > 0 )
			{
				foreach ($SelectedData['gen_cleans'] as $gen_clean)
				{
					$gen_clean_unix = strtotime($gen_clean);
					if ($gen_clean_unix == $date_from_unix)
                    {
	                    $is_gen_clean = true;
	                    break;
                    }
					else $is_gen_clean = false;
				}
				
			}
		 
			if ($amount_on_page == 9)
			{
				break;
			}
			
			$day_week_index = date(DATE_WKNM, $date_from_unix);
			$go_next = false;
			if ( $SelectedData['skip_weekends'] )
			{
				if ( $day_week_index == 6 || $day_week_index == 7 )
				{
					$date_from_unix += TIME_DAY;
				} else $go_next = true;
			} else $go_next = true;
			
			// БЛОК ИСКЛЮЧЕНИЯ ПРАЗДНИКОВ
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
				$is_first = true;
				for ($time=0; $time<3; $time++)
				{
				    $NurseName = 'NONE';
				    $time_from = $periods[$time]['from'];
				    $time_to = $periods[$time]['to'];
					if ($time == 1)
					{
						if ( $is_gen_clean )
                        {
	                        $time_from = $gen_clean_time['from'];
	                        $time_to = $gen_clean_time['to'];
                        }
					}
				 
					$dater = '';
					if ( $is_first )
					{
						$is_first = false;
						$dater = date(DMY, $date_from_unix);
						
						if ( $is_gen_clean )
                        {
                            $dater = wrapper($dater);
                        }
					}
					
					if ($NurseTwoName)
                    {
	                    if ($time == 0 || $time == 1)
	                    {
		                    $NurseName = $Nurse1;
	                    }
	                    if ( $time == 2 )
	                    {
		                    $NurseName = $Nurse2;
	                    }
                    } else
                    {
	                    $NurseName = $NurseOneName;
                    }
					
				    ?>
					<tr>
						<td align="center" data-gcunix="<?=$gen_clean_unix;?>" data-gcdate="<?=date(DMY, $gen_clean_unix);?>"><?=$dater;?></td>
						<td align="center"><?=$time_from;?></td>
						<td align="center"><?=$time_to;?></td>
						<td align="center"><?=nbsper($NurseName);?></td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
				    <?php
				}
				
				
				$date_from_unix += TIME_DAY;
			}
		}
				
		?>
	</tbody>
</table>