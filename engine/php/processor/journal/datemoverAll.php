<?php
$response['stage'] = $action;
$response['msg'] = 'begin';

$HTTP = $_POST;
extract($HTTP, EXTR_PREFIX_SAME, '_caop');

$PatientForMove = getarr(CAOP_JOURNAL, "journal_day='{$day_id}' AND journal_doctor='{$doctor_id}' AND LENGTH(journal_need_move)>1");
if ( count($PatientForMove) > 0 )
{
	$Success = 0;
	foreach ($PatientForMove as $journal)
	{
		$response['debug']['$journal'][] = $journal;
		$unix = strtotime($journal['journal_need_move']);
		if ( $journal['journal_need_move'] == date("Y-m-d", $unix) )
		{
			$ActualDay = createNewDayByUnix($unix, $doctor_id);
			$response['debug']['$ActualDay'] = $ActualDay;
			if ( $ActualDay['result'] === true )
			{
				$updateParams = array(
					'journal_day'   =>  $ActualDay['day_id'],
					'journal_doctor'   =>  $doctor_id,
					'journal_need_move' =>  0,
					'journal_unix'  =>  $ActualDay['day_unix']
				);
				$UpdateData = updateData(CAOP_JOURNAL, $updateParams, array(), "journal_id='{$journal['journal_id']}'");
				if ($UpdateData['stat'] == RES_SUCCESS)
				{
					$Success++;
				}
				
			} else
			{
				$response['msg'] = $ActualDay['msg'];
				break;
			}
			
		} else
		{
			$response['msg'] = 'Дата не совпадает с UNIX';
			break;
		}
	}
	
	if ($Success == count($PatientForMove))
	{
		$response['debug']['moved'] = $Success . '/'. count($PatientForMove);
		$response['result'] = true;
	} else
	{
		$response['msg'] = 'Некоторые пациенты не перемещены!';
	}
 
} else
{
	$response['msg'] = 'Нет пациентов для переноса';
}