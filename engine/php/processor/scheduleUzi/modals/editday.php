<?php
$response['stage'] = $action;
$response['msg'] = 'begin';

$HTTP = $_POST;
extract($HTTP, EXTR_PREFIX_SAME, '_caop');

$response['result'] = true;

$response['htmlData'] = '';

$CheckDate = getarr(CAOP_SCHEDULE_UZI_DATES, "dates_id='$dates_id'");
if ( count($CheckDate) == 1 )
{
	
	$CheckDate = $CheckDate[0];
	
//	$response['htmlData'] .= debug_ret($CheckDate);
	
	$Shift_query = "SELECT * FROM ".CAOP_SCHEDULE_UZI_SHIFTS." AS shifts
				LEFT JOIN ".CAOP_SCHEDULE_UZI_TIMES." AS times ON shifts.shift_id=times.time_shift_id
				LEFT JOIN ".CAOP_SCHEDULE_UZI_PATIENTS." AS patuzi ON times.time_id=patuzi.patient_time_id AND patuzi.patient_date_id='{$CheckDate['dates_id']}'
				LEFT JOIN ".CAOP_PATIENTS." AS patdata ON patuzi.patient_pat_id=patdata.patid_id
				WHERE shifts.shift_id='{$CheckDate['dates_shift_id']}' ORDER BY times.time_order ASC";
	
	$Shift_result = mqc($Shift_query);
	$Shift_amount = mnr($Shift_result);
	$ShiftData = mr2a($Shift_result);
	
	
	if ($Shift_amount > 0)
	{
		
		$response['htmlData'] .= '<button class="btn btn-sm btn-primary btn-updateShiftList" data-datesid="'.$CheckDate['dates_id'].'">Обновить список</button> ';
		
		$noRecords = true;
		foreach ($ShiftData as $shiftDatum)
		{
			if ( strlen($shiftDatum['patid_name']) > 0 )
			{
				$noRecords = false;
				break;
			}
		}
		if ($noRecords)
		{
			$response['htmlData'] .= '<button class="btn btn-sm btn-warning btn-removeShiftOfDate" data-datesid="'.$CheckDate['dates_id'].'">Удалить смену</button> ';
		} else
		{
			
			$response['htmlData'] .= bt_notice('Чтобы поменять или удалить смену врача УЗИ, необходимо сначала убрать пациентов из расписания!', BT_THEME_WARNING, 1);
		}
		$response['htmlData'] .= ' <button class="btn btn-sm btn-warning btn-printList" data-datesid="'.$CheckDate['dates_id'].'">Распечатать список</button> ';
		foreach ($ShiftData as $shiftDatum)
		{
//						$response['htmlData'] .= debug_ret($shiftDatum);
			if ( strlen($shiftDatum['patid_name']) > 0 )
			{
				// записан пациент, ОКОШКО
				$response['htmlData'] .= '<div>
							<a href="javascript:uziCAOPInfo('.$shiftDatum['patient_journal_id'].', '.$CheckDate['dates_id'].', '.$shiftDatum['time_id'].')">
								'.badge($shiftDatum['time_hour'].':'.$shiftDatum['time_min'], BT_THEME_WARNING, false, 1).' '.wrapper(shorty($shiftDatum['patid_name'])).'
							</a>
							</div>';
			} else
			{
				$response['htmlData'] .= '<div>
							<a href="javascript:uziCAOPInfo(-1, '.$CheckDate['dates_id'].', '.$shiftDatum['time_id'].')">
								'.badge($shiftDatum['time_hour'].':'.$shiftDatum['time_min'], BT_THEME_SUCCESS, false, 1).' Свободно
							</a>
							</div>';
			}
			
		}
		
	} else
	{
		$response['htmlData'] .= bt_notice('Проблема с получением смена врача на выбранный день', BT_THEME_DANGER, 1);
	}
	
} else
{
	$response['htmlData'] .= bt_notice('Такой даты в графике нет', BT_THEME_WARNING, 1);
}