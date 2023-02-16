<?php

$response['stage'] = $action;
$response['msg'] = 'begin';
$response['result'] = true;
$response['htmlData'] = '';

$UNIX = $_POST['unix_time'];

$DOSE_PERIOD_TYPES = getarr(CAOP_DS_DOSE_PERIOD_TYPE, "type_enabled='1'", "ORDER BY type_order ASC");
$DOSE_PERIOD_TYPES_ID = getDoctorsById($DOSE_PERIOD_TYPES, 'type_id');

$DOSE_FREQ_PERIOD_TYPES = getarr(CAOP_DS_FREQ_PERIOD_TYPE, "type_enabled='1'", "ORDER BY type_order ASC");
$DOSE_FREQ_PERIOD_TYPES_ID = getDoctorsById($DOSE_FREQ_PERIOD_TYPES, 'type_id');

$DS_RESEARCH_TYPES = getarr(CAOP_DS_RESEARCH_TYPES, "type_enabled='1'", "ORDER BY type_order ASC");
$DS_RESEARCH_TYPES_ID = getDoctorsById($DS_RESEARCH_TYPES, 'type_id');
$DS_RESEARCH_TYPES_LINEAR = getLinearIds($DS_RESEARCH_TYPES, 'type_id');

$UNIX_PREV_MONTH = strtotime('first day of previous month', $UNIX);
$UNIX_NEXT_MONTH = strtotime('first day of next month', $UNIX);

$UNIX_MAINDATA = getCurrentDay($UNIX);

// debug($UNIX_MAINDATA);
// exit('HERE1');

// 1 - получаем актуальные направления
$ActualDirlist = getarr(CAOP_DS_DIRLIST, "dirlist_isMain='1' AND dirlist_done_unix='0'");

if ( count($ActualDirlist) > 0 )
{
    // debug($ActualDirlist);
    // die('$ActualDirlist');
	$ActualRegimens = array();
	foreach ($ActualDirlist as $Dirlist)
	{
		$RegimensData = getarr(CAOP_DS_VISITS_REGIMENS, "visreg_dirlist_id='{$Dirlist['dirlist_id']}'");
		if ( count($RegimensData) > 0 )
		{
			foreach ($RegimensData as $Regimen)
			{
				$ActualRegimens[] = $Regimen;
			}
		}
	}
	
	
    // debug($ActualRegimens);
    // die('$ActualRegimens');
	
	if ( count($ActualRegimens) > 0 )
	{
		for ($i=0; $i<count($ActualRegimens); $i++)
		{
			$DirlistData = getarr(CAOP_DS_DIRLIST, "dirlist_id='{$ActualRegimens[$i]['visreg_dirlist_id']}'");
			$PatientData = getarr(CAOP_DS_PATIENTS, "patient_id='{$ActualRegimens[$i]['visreg_dspatid']}'");
			$VisitsData = getarr(CAOP_DS_VISITS, "visreg_dspatid='{$ActualRegimens[$i]['visreg_dspatid']}' AND visreg_dirlist_id='{$ActualRegimens[$i]['visreg_dirlist_id']}'", "ORDER BY visreg_visit_unix DESC");
			if ( count($DirlistData) == 1 ) $ActualRegimens[$i]['dirdata'] = $DirlistData[0];
			if ( count($PatientData) == 1 ) $ActualRegimens[$i]['patdata'] = $PatientData[0];
			if ( count($VisitsData) > 0 ) $ActualRegimens[$i]['visdata'] = $VisitsData;
			
			$ActualRegimens[$i]['plandata'] = array();
			
			$StartVisitTimestamp = $ActualRegimens[$i]['dirdata']['dirlist_visit_unix'];
			if ( count($VisitsData) > 0 ) $StartVisitTimestamp = $ActualRegimens[$i]['visdata'][0]['visreg_visit_unix'];
//			$response['htmlData'] .= debug_ret($VisitsData);
//			$response['htmlData'] .= debug_ret($StartVisitTimestamp);
			$CounterVisitTimestamp = $StartVisitTimestamp;
			$EndOfMonth = $UNIX_MAINDATA['by_timestamp']['ends']['month']['unix'];
//			$response['htmlData'] .= debug_ret($EndOfMonth);

            
            // debug('$EndOfMonth');
            // debug(date(DMYHIS, $EndOfMonth));
            // debug('$CounterVisitTimestamp');
            // debug(date(DMYHIS, $CounterVisitTimestamp));
            // exit();
			
			while ($CounterVisitTimestamp < $EndOfMonth)
			{
			    
                
                
				$goadd = false;
				
				if ( $CounterVisitTimestamp >= $UNIX_MAINDATA['by_timestamp']['begins']['month']['unix'] )
				{
				    
					
					if ( $ActualRegimens[$i]['dirdata']['dirlist_done_unix'] != 0 )
					{
						
						if ( $CounterVisitTimestamp <= $ActualRegimens[$i]['dirdata']['dirlist_done_unix'] )
						{
							$goadd = true;
						}
					} else
					{
						$goadd = true;
					}
				}
				
				
				
				$weekday = date('N', $CounterVisitTimestamp); // 1-7
				if ( $weekday==6 || $weekday==7 )
				{
					if ( $weekday== 6 )
					{
						if ( $ActualRegimens[$i]['visreg_freq_period_amount'] == 1 )
						{
							$CounterVisitTimestamp = strtotime('+2 day', $CounterVisitTimestamp);
						} else
						{
							$CounterVisitTimestamp = strtotime('-1 day', $CounterVisitTimestamp);
						}
						
					}
					if ( $weekday== 7 )
					{
						$CounterVisitTimestamp = strtotime('+1 day', $CounterVisitTimestamp);
					}
					//$goadd = false;
				}
				
				if ( $goadd )
				{
					$Plan = array(
						'date'  =>  date("d.m.Y", $CounterVisitTimestamp),
						'unix'  =>  $CounterVisitTimestamp,
						'type'  =>  'plan',
//						'weekday'   =>  $weekday
					);
					$ActualRegimens[$i]['plandata'][] = $Plan;
				}
				
				
				
				$freq_period_type_id = $ActualRegimens[$i]['visreg_freq_period_type'];
				
				// debug('$DOSE_FREQ_PERIOD_TYPES_ID');
				// debug($DOSE_FREQ_PERIOD_TYPES_ID);
				
				$freq_period_type = $DOSE_FREQ_PERIOD_TYPES_ID['id' . $freq_period_type_id];
				
				$total_period = $ActualRegimens[$i]['visreg_freq_period_amount'] * $freq_period_type['type_addon'];
				
				// debug($ActualRegimens[$i]['visreg_freq_period_amount']);
				// debug($freq_period_type_id);
				// debug($freq_period_type);
				// debug($total_period);
				// die('WHILE');
				
				if ( $ActualRegimens[$i]['visreg_freq_amount'] == 0 )
				{
				    debug( $ActualRegimens[$i]);
				    exit();
				} else
				{
				   $div_period = ceil( $total_period / $ActualRegimens[$i]['visreg_freq_amount'] ); 
				}
				// $div_period = 1;
				
				
				
				if ($div_period <= 0)
				{
				    debug('$total_period');
    				debug($total_period);
				    // debug('$ActualRegimens');
    				// debug($ActualRegim/ens);
				    // debug('$ActualRegimens['.$i.']');
    				// debug($ActualRegimens[$i]);
				    debug('$div_period');
    				debug($div_period);
    				// exit();
				} else
				{
				    $CounterVisitTimestamp += $div_period;
				}
				
				
			}
		}
	} else
	{
		$response['htmlData'] .= bt_notice('Нет актуальных назначений для отображения', BT_THEME_WARNING, 1);
	}
	
} else
{
    
	$response['htmlData'] .= bt_notice('Нет активных направлений для отображения', BT_THEME_WARNING, 1);
}
// die('HERE');
// Вычисляем число дней в текущем месяце
$date_t = date('t', $UNIX);
$date_m = date('m', $UNIX);
$date_Y = date('Y', $UNIX);

$dayofmonth = $date_t;

// Счётчик для дней месяца
$day_count = 1;

// 1. Первая неделя
$num = 0;
for ($i = 0; $i < 7; $i++)
{
	// Вычисляем номер дня недели для числа
	$dayofweek = date('w', mktime(0, 0, 0, $date_m, $day_count, $date_Y));
	
	// Приводим к числа к формату 1 - понедельник, ..., 6 - суббота
	$dayofweek = $dayofweek - 1;
	if ($dayofweek == -1) $dayofweek = 6;
	
	if ($dayofweek == $i)
	{
		// Если дни недели совпадают,
		// заполняем массив $week
		// числами месяца
		$week[$num][$i] = $day_count;
		$day_count++;
	} else
	{
		$week[$num][$i] = "";
	}
}
// 2. Последующие недели месяца
while (true)
{
	$num++;
	for ($i = 0; $i < 7; $i++)
	{
		$week[$num][$i] = $day_count;
		$day_count++;
		
		// Если достигли конца месяца - выходим
		// из цикла
		if ($day_count > $dayofmonth) break;
	}
	// Если достигли конца месяца - выходим
	// из цикла
	if ($day_count > $dayofmonth) break;
}
// 3. Выводим содержимое массива $week
// в виде календаря
// Выводим таблицу


$MonthFullName = $MonthsRusFull[ intval($date_m)-1 ];

$response['htmlData'] .= '
<script defer type="text/javascript">
$(\'.otherMonth\').click(function (e)
{
    my_calendar.html(LOADER_calendar);
    var THIS = $(this);
    var TIMESTAMP = THIS.data(\'timestamp\');
    loadCalendar(TIMESTAMP);
});
</script>
<div class="row">
	<div class="col-auto">
		<button class="btn btn-lg btn-secondary otherMonth" data-timestamp="'.$UNIX_PREV_MONTH.'">предыдущий</button>
	</div>
	<div class="col text-center">
		<h1>'.$MonthFullName.', '.$date_Y.' г.</h1>
	</div>
	<div class="col-auto">
		<button class="btn btn-lg btn-secondary otherMonth" data-timestamp="'.$UNIX_NEXT_MONTH.'">следующий</button>
	</div>
</div>
';

$SHOWDAYS = 5;

$paddings =  "p-1";
$style = ' style="height: 150px"';
$response['htmlData'] .= '<table class="table table-bordered table-sm">';

$response['htmlData'] .= '<thead><tr>';
for ($days=1; $days<=$SHOWDAYS; $days++)
{
    $response['htmlData'] .= '
        <th scope="col" class="text-center">'.getDayRusByIndex($days).'</th>
    ';
}
$response['htmlData'] .= '</tr></thead>';
$response['htmlData'] .= '<tbody>';

for ($i = 0; $i < count($week); $i++)
{
	
	$response['htmlData'] .=  '<tr>';
	
	for ($j = 0; $j < $SHOWDAYS; $j++)
	{
		
		if (!empty($week[$i][$j]))
		{
			$WeekUnix = birthToUnix( $week[$i][$j] . '.' . $date_m . '.' . $date_Y );
			
			
			$Keys = '';
			
			$OutputRegimen = array();
			$OutputVisits = array();
			$NamesString = '';
			$npp = 1;
			foreach ($ActualRegimens as $actualRegimen)
			{
				$regimen_visit = false;
				$visit = false;
				$name_index_visit = false;
				$name_index = false;
				
				$PlanData = $actualRegimen['plandata'];
				$VisitData = $actualRegimen['visdata'];
				
				if ( count($VisitData) > 0 )
				{
					$visit = array_search($WeekUnix, array_column($VisitData, 'visreg_visit_unix'));
//					$response['htmlData'] .= debug_ret($visit);
					if ( $visit !== false )
					{
						$VisitPatientData = $VisitData[$visit];
						$text_visit = '<div class=""><a href="#" class="openVisitPatient" data-weekunix="'.$VisitPatientData['visreg_visit_unix'].'" data-dirlist="'.$VisitPatientData['visreg_dirlist_id'].'" data-patient="'.$VisitPatientData['visreg_dspatid'].'" data-visit="'.$VisitPatientData['visreg_id'].'">'.$npp.'. <b>'.shorty($actualRegimen['patdata']['patient_fio']).'</b></a></div>';
						$name_index_visit = array_search($actualRegimen['patdata']['patient_fio'], array_column($OutputVisits, 'name'));
						if ($name_index_visit === false)
						{
							$NamesString .= $text_visit;
							$npp++;
						}
						$PatidVisit = array(
							'name'    =>  $actualRegimen['patdata']['patient_fio'],
							'visreg_id'    =>  $actualRegimen['visreg_id'],
							'patid_id'  =>  $actualRegimen['visreg_dspatid'],
							'dirlist_id' =>  $actualRegimen['visreg_dirlist_id']
						);
						$OutputVisits[] = $PatidVisit;
					}
				}
				
				$key = array_search($WeekUnix, array_column($PlanData, 'unix'));
				if ( $key !== false )
				{
					$regimen_visit = array_search($actualRegimen['visreg_id'], array_column($OutputVisits, 'visreg_id'));
					if ( $regimen_visit === false )
					{
						$name_index = array_search($actualRegimen['patdata']['patient_fio'], array_column($OutputRegimen, 'name'));
						$text_muted = '<div class=""><a href="#" class="openDayPatient text-muted" data-weekunix="'.$WeekUnix.'" data-dirlist="'.$actualRegimen['visreg_dirlist_id'].'" data-patient="'.$actualRegimen['visreg_dspatid'].'">'.$npp.'. '.shorty($actualRegimen['patdata']['patient_fio']).'</a></div>';
						
						if ($name_index === false)
						{
							$NamesString .= $text_muted;
							$npp++;
						}
						
						$PatidGraphic = array(
							'name'    =>  $actualRegimen['patdata']['patient_fio'],
							'visreg_id'    =>  $actualRegimen['visreg_id'],
							'patid_id'  =>  $actualRegimen['visreg_dspatid'],
							'dirlist_id' =>  $actualRegimen['visreg_dirlist_id']
						);
						$OutputRegimen[] = $PatidGraphic;
					}
					
					
				}
				
			}
			
//			$VisitRegimens = count($OutputVisits) . '/' . count($OutputRegimen);
			
			$Output = '<div class="text-center openDayCalendar" data-day="'.$WeekUnix.'"><h3 class="cursor-pointer">'.$week[$i][$j].'</h3></div>' . '
			'.$NamesString.'
			';
			
			$Output .= '<div style="display: none;" class="hiddenJson" id="hiddenJson_'.$WeekUnix.'"  data-dayunix="'.$WeekUnix.'">
				'.json_encode($OutputRegimen).'
			</div>';
			
			
			
			// Если имеем дело с субботой и воскресенья
			// подсвечиваем их
			if ($j == 5 || $j == 6)
				$response['htmlData'] .=  '<td width="10%" class="bg-secondary '.$margins.' '.$paddings.'">' . $Output . '</td>';
			else $response['htmlData'] .=  '<td width="16%" class=" '.$margins.' '.$paddings.'">' . $Output . "</td>";
		} else $response['htmlData'] .=  '<td class=" '.$margins.' '.$paddings.'"></td>';
	}
	$response['htmlData'] .=  "</tr>";
}
$response['htmlData'] .= '</tbody>';
$response['htmlData'] .= '</table>';

//$response['htmlData'] .= debug_ret($UNIX_MAINDATA);
//$response['htmlData'] .= debug_ret($ActualRegimens);