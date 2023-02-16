<?php
$response['stage'] = $action;
$response['msg'] = 'begin';

$HTTP = $_POST;
extract($HTTP, EXTR_PREFIX_SAME, '_caop');

switch ($type)
{
	case "otherOne":
		
		if ( $target_doctor > 0 )
		{
			
			$CheckTargetUzi = getarr(CAOP_SCHEDULE_UZI_DOCTORS, "uzi_doctor_id='$target_doctor'");
			if ( count($CheckTargetUzi) == 1 )
			{
				$CheckTargetUzi = $CheckTargetUzi[0];
				
				$CheckSrcShift = getarr(CAOP_SCHEDULE_UZI_SHIFTS, "shift_id='$shift_id'");
				if ( count($CheckSrcShift) == 1 )
				{
					$CheckSrcShift = $CheckSrcShift[0];

// копируем смену
					$param_copy_shift = array(
						'shift_uzi_id' => $CheckTargetUzi['uzi_id'],
						'shift_doctor_id' => $CheckTargetUzi['uzi_doctor_id'],
						'shift_title' => $CheckSrcShift['shift_title'] . ' копия'
					);
					$CopyShift = appendData(CAOP_SCHEDULE_UZI_SHIFTS, $param_copy_shift);
					if ( $CopyShift[ID] > 0 )
					{
						
						$TimesSrcData = getarr(CAOP_SCHEDULE_UZI_TIMES, "time_shift_id='{$CheckSrcShift['shift_id']}'");
						$SUCCESS = 0;
						$TOTAL = 0;
						if ( count($TimesSrcData) > 0 )
						{
							foreach ($TimesSrcData as $timesSrcDatum)
							{
								$TOTAL++;
								$param_copy_times = array(
									'time_uzi_id' => $CheckTargetUzi['uzi_id'],
									'time_shift_id' => $CopyShift[ID],
									'time_hour' => $timesSrcDatum['time_hour'],
									'time_min' => $timesSrcDatum['time_min'],
									'time_order' => $timesSrcDatum['time_order']
								);
								$CopyTime = appendData(CAOP_SCHEDULE_UZI_TIMES, $param_copy_times);
								if ( $CopyTime[ID] > 0 )
								{
									$SUCCESS++;
								}
							}
							
							if ( $SUCCESS == $TOTAL )
							{
								$response['result'] = true;
								$response['msg'] = 'Смена успешно скопирована другому врачу!';
							} else
							{
								$response['msg'] = 'Проблемы с копированием времени ('.$SUCCESS.' / '.$TOTAL.')';
							}
						}
						
					} else
					{
						$response['msg'] = 'Проблема с копированием смены';
						$response['debug']['$CopyShift'] = $CopyShift;
					}
				} else
				{
					$response['msg'] = 'Такой смены не существует';
				}
				
			} else
			{
				$response['msg'] = 'Такого врача УЗИ не существует';
			}
			
		} else
		{
			$response['msg'] = 'Не выбран врач для копирования';
		}
		
	break;
	case "single":
	    $CheckSrcShift = getarr(CAOP_SCHEDULE_UZI_SHIFTS, "shift_id='$shift_id'");
	    if ( count($CheckSrcShift) == 1 )
	    {
		    $CheckSrcShift = $CheckSrcShift[0];

			// копируем смену
		    $param_copy_shift = array(
			    'shift_uzi_id' => $CheckSrcShift['shift_uzi_id'],
			    'shift_doctor_id' => $CheckSrcShift['shift_doctor_id'],
			    'shift_title' => $CheckSrcShift['shift_title'] . ' копия'
		    );
		    $CopyShift = appendData(CAOP_SCHEDULE_UZI_SHIFTS, $param_copy_shift);
		    if ( $CopyShift[ID] > 0 )
		    {
			
			    $TimesSrcData = getarr(CAOP_SCHEDULE_UZI_TIMES, "time_shift_id='{$CheckSrcShift['shift_id']}'");
			    $SUCCESS = 0;
			    $TOTAL = 0;
			    if ( count($TimesSrcData) > 0 )
			    {
				    foreach ($TimesSrcData as $timesSrcDatum)
				    {
					    $TOTAL++;
					    $param_copy_times = array(
						    'time_uzi_id' => $CheckSrcShift['shift_uzi_id'],
						    'time_shift_id' => $CopyShift[ID],
						    'time_hour' => $timesSrcDatum['time_hour'],
						    'time_min' => $timesSrcDatum['time_min'],
						    'time_order' => $timesSrcDatum['time_order']
					    );
					    $CopyTime = appendData(CAOP_SCHEDULE_UZI_TIMES, $param_copy_times);
					    if ( $CopyTime[ID] > 0 )
					    {
						    $SUCCESS++;
					    }
				    }
				
				    if ( $SUCCESS == $TOTAL )
				    {
					    $response['result'] = true;
					    $response['msg'] = 'Смена успешно дублирована!';
				    } else
				    {
					    $response['msg'] = 'Проблемы с копированием времени ('.$SUCCESS.' / '.$TOTAL.')';
				    }
			    }
			
		    } else
		    {
			    $response['msg'] = 'Проблема с копированием смены';
			    $response['debug']['$CopyShift'] = $CopyShift;
		    }
	    } else
	    {
	    	$response['msg'] = 'Выбранной смены не существует';
	    }
	break;
	case "otherFull":
		if ( $target_doctor > 0 )
		{
			$CheckTargetUzi = getarr(CAOP_SCHEDULE_UZI_DOCTORS, "uzi_doctor_id='$target_doctor'");
			if ( count($CheckTargetUzi) == 1 )
			{
				$CheckTargetUzi = $CheckTargetUzi[0];
				
				$response['debug']['$CheckTargetUzi'] = $CheckTargetUzi;
				
				$ShiftData = getarr(CAOP_SCHEDULE_UZI_SHIFTS, "shift_uzi_id='$uzi_id' AND shift_doctor_id='$doctor_id'");
				if ( count($ShiftData) > 0 )
				{
					$SUCCESS_SHIFT = 0;
					$TOTAL_SHIFT = 0;
					foreach ($ShiftData as $shiftDatum)
					{
						$TOTAL_SHIFT++;
						
						$CheckSrcShift = $shiftDatum;

// копируем смену
						$param_copy_shift = array(
							'shift_uzi_id' => $CheckTargetUzi['uzi_id'],
							'shift_doctor_id' => $CheckTargetUzi['uzi_doctor_id'],
							'shift_title' => $CheckSrcShift['shift_title'] . ' копия'
						);
						$CopyShift = appendData(CAOP_SCHEDULE_UZI_SHIFTS, $param_copy_shift);
						if ( $CopyShift[ID] > 0 )
						{
							
							$TimesSrcData = getarr(CAOP_SCHEDULE_UZI_TIMES, "time_shift_id='{$CheckSrcShift['shift_id']}'");
							$SUCCESS = 0;
							$TOTAL = 0;
							if ( count($TimesSrcData) > 0 )
							{
								foreach ($TimesSrcData as $timesSrcDatum)
								{
									$TOTAL++;
									$param_copy_times = array(
										'time_uzi_id' => $CheckTargetUzi['uzi_id'],
										'time_shift_id' => $CopyShift[ID],
										'time_hour' => $timesSrcDatum['time_hour'],
										'time_min' => $timesSrcDatum['time_min'],
										'time_order' => $timesSrcDatum['time_order']
									);
									$CopyTime = appendData(CAOP_SCHEDULE_UZI_TIMES, $param_copy_times);
									if ( $CopyTime[ID] > 0 )
									{
										$SUCCESS++;
									}
								}
								
								if ( $SUCCESS == $TOTAL )
								{
									$SUCCESS_SHIFT++;
								} else
								{
									$response['msg'] = 'Проблемы с копированием времени ('.$SUCCESS.' / '.$TOTAL.')';
								}
							}
							
						} else
						{
							$response['msg'] = 'Проблема с копированием смены';
							$response['debug']['$CopyShift'] = $CopyShift;
						}
				    }
					
					if ( $SUCCESS_SHIFT == $TOTAL_SHIFT )
					{
						$response['result'] = true;
						$response['msg'] = 'Смены успешно скопирована другому врачу!';
					}
				} else
				{
					$response['msg'] = 'Нет смен для копирования';
				}
				/*
				$CheckSrcShift = getarr(CAOP_SCHEDULE_UZI_SHIFTS, "shift_id='$shift_id'");
				if ( count($CheckSrcShift) == 1 )
				{
				
				} else
				{
					$response['msg'] = 'Такой смены не существует';
				}
				*/
			} else
			{
				$response['msg'] = 'Такого врача УЗИ не существует';
			}
			
		} else
		{
			$response['msg'] = 'Не выбран врач для копирования';
		}
    break;
}