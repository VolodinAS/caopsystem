<?php
$response['stage'] = $action;
$response['msg'] = 'begin';

$HTTP = $_POST;
extract($HTTP, EXTR_PREFIX_SAME, '_caop');

if ( $UNIX == -1 )
{
	$firstFreeTalon = getFirstFreeTalon($doctor_id, time());
//	$response['debug']['$firstFreeTalon'] = $firstFreeTalon;
	$UNIX = $firstFreeTalon['result'];
}

$CURRENT_UNIX = time();

$response['result'] = true;

$CheckUzi = getarr(CAOP_SCHEDULE_UZI_DOCTORS, "uzi_doctor_id='$doctor_id'");
if ( count($CheckUzi) == 1 )
{
    $CheckUzi = $CheckUzi[0];
    
    // 0 - формируем начало и конец недели
	$GetUnixData = getCurrentDay($UNIX);
	$GetUnixDataByTimestamp = $GetUnixData['by_timestamp'];
	$WeekBeginDate = $GetUnixDataByTimestamp['begins']['week']['date'];
	$WeekBeginUnix = $GetUnixDataByTimestamp['begins']['week']['unix'];
	$WeekEndDate = $GetUnixDataByTimestamp['ends']['week']['date'];
	$WeekEndUnix = $GetUnixDataByTimestamp['ends']['week']['unix'];
	
	$response['debug']['$WeekBeginDate'] = $WeekBeginDate;
	$response['debug']['$WeekBeginUnix'] = $WeekBeginUnix;
	$response['debug']['$WeekEndDate'] = $WeekEndDate;
	$response['debug']['$WeekEndUnix'] = $WeekEndUnix;
	
	$PrevWeekUnix = $WeekBeginUnix - TIME_WEEK;
	$NextWeekUnix = $WeekBeginUnix + TIME_WEEK;
	
	$response['htmlData'] .= '
		<table width="100%">
			<tr>
				<td colspan="3" align="center">
					<button class="btn btn-primary btn-sm btn-otherWeek" id="btn-updateAfterRecord" data-doctorid="'.$CheckUzi['uzi_doctor_id'].'" data-unix="'.$UNIX.'">Обновить неделю</button>
					<button class="btn btn-warning btn-sm btn-otherWeek" data-doctorid="'.$CheckUzi['uzi_doctor_id'].'" data-unix="'.$CURRENT_UNIX.'">Текущая неделя</button>
				</td>
			</tr>
			<tr>
				<td width="1%"><button class="btn btn-secondary btn-sm btn-otherWeek" data-doctorid="'.$CheckUzi['uzi_doctor_id'].'" data-unix="'.$PrevWeekUnix.'">'.nbsper('Предыдущая неделя').'</button></td>
				<td align="center">
					<b>'.date(DMY, $WeekBeginUnix).' - '.date(DMY, $WeekEndUnix).'</b>
				</td>
				<td width="1%"><button class="btn btn-secondary btn-sm btn-otherWeek" data-doctorid="'.$CheckUzi['uzi_doctor_id'].'" data-unix="'.$NextWeekUnix.'">'.nbsper('Следующая неделя').'</button></td>
			</tr>
		</table><br>
		';
	$response['htmlData'] .= '<div class="row">';
	$WeekEndUnix -= 2 * TIME_DAY;
	for ($week_day=$WeekBeginUnix; $week_day<$WeekEndUnix; $week_day+=TIME_DAY)
	{
		$WeekDayNpp = date(DATE_WKNM, $week_day);
		$WeekName = getDayRusByIndex($WeekDayNpp);
		$response['htmlData'] .= '<div class="col border border-dark full-center">';
		$response['htmlData'] .= '<b>'.$WeekName.'</b><br/>' . date(DMY, $week_day);
		$response['htmlData'] .= '</div>';
	}
	$response['htmlData'] .= '</div>';
	
	$DatesData = getarr(CAOP_SCHEDULE_UZI_DATES, "dates_uzi_id='{$CheckUzi['uzi_id']}' AND dates_date_unix>='$WeekBeginUnix' AND dates_date_unix<='$WeekEndUnix'");
	if ( count($DatesData) > 0 )
	{
		
		
		$response['htmlData'] .= '<div class="row">';
		for ($week_day=$WeekBeginUnix; $week_day<$WeekEndUnix; $week_day+=TIME_DAY)
		{
			$response['htmlData'] .= '<div class="col border border-dark p-0">';
			$date_week_day = date(DMY, $week_day);
			
			$Search = searchArray($DatesData, 'dates_date', $date_week_day);
			if ( $Search['status'] == RES_SUCCESS )
			{
				$DateItem = $DatesData[$Search['key']];
				$response['debug']['$DateItem'][] = $DateItem;
				
				$Shift_query = "SELECT * FROM ".CAOP_SCHEDULE_UZI_SHIFTS." AS shifts
				LEFT JOIN ".CAOP_SCHEDULE_UZI_TIMES." AS times ON shifts.shift_id=times.time_shift_id
				LEFT JOIN ".CAOP_SCHEDULE_UZI_PATIENTS." AS patuzi ON times.time_id=patuzi.patient_time_id AND patuzi.patient_date_id='{$DateItem['dates_id']}'
				LEFT JOIN ".CAOP_PATIENTS." AS patdata ON patuzi.patient_pat_id=patdata.patid_id
				WHERE shifts.shift_id='{$DateItem['dates_shift_id']}' ORDER BY times.time_order ASC";
				
				/*
				
				LEFT JOIN ".CAOP_SCHEDULE_UZI_DATES." AS dates ON patuzi.patient_date_id=dates.dates_id*/
				
				$Shift_result = mqc($Shift_query);
				$Shift_amount = mnr($Shift_result);
				if ( $Shift_amount > 0 )
				{
					$ShiftData = mr2a($Shift_result);
//					$response['htmlData'] .= debug_ret($ShiftData);
					foreach ($ShiftData as $shiftDatum)
					{
//						$response['htmlData'] .= debug_ret($shiftDatum);
						if ( strlen($shiftDatum['patid_name']) > 0 )
						{
						    // записан пациент, ОКОШКО
							$response['htmlData'] .= '<div>
							<a href="javascript:uziCAOPInfo('.$journal_id.', '.$DateItem['dates_id'].', '.$shiftDatum['time_id'].')">
								'.badge($shiftDatum['time_hour'].':'.$shiftDatum['time_min'], BT_THEME_WARNING, false, 1).' '.wrapper(shorty($shiftDatum['patid_name'])).'
							</a>
							</div>';
						} else
						{
							$response['htmlData'] .= '<div>
							<a href="javascript:uziCAOPInfo('.$journal_id.', '.$DateItem['dates_id'].', '.$shiftDatum['time_id'].')">
								'.badge($shiftDatum['time_hour'].':'.$shiftDatum['time_min'], BT_THEME_SUCCESS, false, 1).' Свободно
							</a>
							</div>';
						}
						
					}
				} else
				{
					$response['htmlData'] .= bt_notice('У врача не установлено расписание!', BT_THEME_WARNING);
				}
			} else
			{
				$response['htmlData'] .= '';
			}
			$response['htmlData'] .= '</div>';
		}
		$response['htmlData'] .= '</div>';
	} else
	{
		$response['htmlData'] .= bt_notice('На выбранную неделю у врача УЗИ нет смен', BT_THEME_WARNING, 1);
	}
	
//	$response['debug']['$GetUnixDataByTimestamp'] = $GetUnixDataByTimestamp;
    
    // 1 - ищем график на ЭТУ неделю
} else
{
	$response['htmlData'] .= bt_notice('Выбранного врача УЗИ не существует', BT_THEME_WARNING, 1);
}