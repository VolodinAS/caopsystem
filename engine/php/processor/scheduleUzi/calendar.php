<?php
$response['stage'] = $action;
$response['msg'] = 'begin';
$response['result'] = true;
$response['htmlData'] = '';

$HTTP = $_POST;
extract($HTTP, EXTR_PREFIX_SAME, '_caop');

$UNIX = $unix_time;

$UNIX_PREV_MONTH = strtotime('first day of previous month', $UNIX);
$UNIX_NEXT_MONTH = strtotime('first day of next month', $UNIX);

$UNIX_MAINDATA = getCurrentDay($UNIX);
$BeginOfMonth = $UNIX_MAINDATA['by_timestamp']['begins']['month']['unix'];
$EndOfMonth = $UNIX_MAINDATA['by_timestamp']['ends']['month']['unix'];

//$response['debug']['$uzi_id'] = $uzi_id;

$CheckUzi = getarr(CAOP_SCHEDULE_UZI_DOCTORS, "uzi_id='$uzi_id'");
if ( count($CheckUzi) == 1 )
{
    $CheckUzi = $CheckUzi[0];
	
	$WorkDaysOnCurrentMonth = getarr(CAOP_SCHEDULE_UZI_DATES, "dates_date_unix>='$BeginOfMonth' AND dates_date_unix<='$EndOfMonth' AND dates_uzi_id='{$CheckUzi['uzi_id']}'");
	
//	$response['debug']['$WorkDaysOnCurrentMonth'] = $WorkDaysOnCurrentMonth;
//$response['debug']['$WorkDaysOnCurrentMonth'] = $WorkDaysOnCurrentMonth;

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
	
	$response['htmlData'] = '
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
		<button class="btn btn-lg btn-secondary otherMonth" data-timestamp="' . $UNIX_PREV_MONTH . '">предыдущий</button>
	</div>
	<div class="col text-center">
		<h1>' . $MonthFullName . ', ' . $date_Y . ' г.</h1>
	</div>
	<div class="col-auto">
		<button class="btn btn-lg btn-secondary otherMonth" data-timestamp="' . $UNIX_NEXT_MONTH . '">следующий</button>
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
			$odc = '';
			$bg_isWork = 'bg-secondary';
			
			if (!empty($week[$i][$j]))
			{
				$NamesString = '';
				$WeekUnix = birthToUnix( $week[$i][$j] . '.' . $date_m . '.' . $date_Y );
				
				$Search = searchArray($WorkDaysOnCurrentMonth, 'dates_date_unix', $WeekUnix);
				if ( $Search['status'] == RES_SUCCESS )
				{
					$Search = $Search['data'];
//				$response['debug']['$Search'] = $Search;
					$CheckShift = getarr(CAOP_SCHEDULE_UZI_SHIFTS, "shift_id='{$Search['dates_shift_id']}'");
					if ( count($CheckShift) > 0 )
					{
						
						$CheckShift = $CheckShift[0];
//					$response['debug']['$CheckShift'] = $CheckShift;
						$NamesString =  $CheckShift['shift_title'];
						$odc = 'openDayCalendar';
						
						$CheckTimes = getrows(CAOP_SCHEDULE_UZI_TIMES, "time_shift_id='{$CheckShift['shift_id']}'", 'time_id');
						$CheckPatUZI = getrows(CAOP_SCHEDULE_UZI_PATIENTS, "patient_date_id='{$Search['dates_id']}'", 'patient_id');
						
						$NamesString .= '<br>'.wrapper('Запись:').' ' . $CheckPatUZI['count'] . '/' . $CheckTimes['count'];
						
						
					}
					$bg_isWork = '';
//				debug($Search);
				}




//			$NamesString = $WeekUnix;

//			$VisitRegimens = count($OutputVisits) . '/' . count($OutputRegimen);
				//openDayCalendar
				$Output = '<div class="text-center '.$odc.'" data-datesid="'.$Search['dates_id'].'" data-day="'.$WeekUnix.'"><h3 class="cursor-pointer">'.$week[$i][$j].'</h3></div>' . '
			'.$NamesString.'
			';
//
//			$Output .= '<div style="display: none;" class="hiddenJson" id="hiddenJson_'.$WeekUnix.'"  data-dayunix="'.$WeekUnix.'">
//				'.json_encode($OutputRegimen).'
//			</div>';
				
				
				
				// Если имеем дело с субботой и воскресенья
				// подсвечиваем их
				if ($j == 5 || $j == 6)
					$response['htmlData'] .=  '<td width="10%" class="bg-secondary '.$margins.' '.$paddings.'">' . $Output . '</td>';
				else $response['htmlData'] .=  '<td width="16%" class="  '.$margins.' '.$paddings.' '.$bg_isWork.'">' . $Output . "</td>";
			} else $response['htmlData'] .=  '<td class=" '.$margins.' '.$paddings.'"></td>';
		}
		$response['htmlData'] .=  "</tr>";
	}
	$response['htmlData'] .= '</table>';
 
} else
{
	$response['htmlData'] .= bt_notice('Неверно выбран врач УЗИ', BT_THEME_WARNING, 1);
}

