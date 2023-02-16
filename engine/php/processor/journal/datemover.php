<?php
$response['stage'] = $action;
$NewDate = $_POST['dater'];
$Patients = $_POST['patientsList'];

$response['debug']['$NewDate'] = $NewDate;
$response['debug']['$Patients'] = $Patients;

if ( strlen($NewDate) )
{
	if (count($Patients) > 0)
	{
		$DOCID = $USER_PROFILE['doctor_id'];
		if ( $_POST['docid'] > 0 )
		{
			$DOCID = 0;
			$docider = $_POST['docid'];
			$DocData = $DoctorsListId['id' . $docider];
			if ( count($DocData) > 0 )
			{
				$DOCID = $DocData['doctor_id'];
			}
		}
		$NewUnix = strtotime($NewDate);
		$response['debug']['$DOCID'] = $DOCID;
		if ( $DOCID > 0 )
		{

			$ActualDay = createNewDayByUnix($NewUnix, $DOCID);
			if ( $ActualDay['result'] === true )
			{

				$Success = 0;
				foreach ($Patients as $patient_id) {
					$updateParams = array(
						'journal_day'   =>  $ActualDay['day_id'],
						'journal_doctor'   =>  $DOCID,
						'journal_need_move' =>  0,
						'journal_unix'  =>  $ActualDay['day_unix']
					);
					$UpdateData = updateData('caop_journal', $updateParams, array(), "journal_id='{$patient_id}'");
					if ($UpdateData['stat'] == RES_SUCCESS)
					{
						$Success++;
					}
				}

				if ($Success == count($Patients))
				{
					$response['result'] = true;
				} else
				{
					$response['msg'] = 'Некоторые пациенты не перемещены!';
				}

			} else
			{
				$response['msg'] = $ActualDay['msg'];
			}
		} else
		{
			$response['msg'] = 'Неверно выбран врач';
		}
	} else
	{
		$response['msg'] = 'Не выбраны пациенты для переноса';
	}
} else
{
	$response['msg'] = 'Не выбрана дата переноса';
}