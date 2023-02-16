<?php
$response['stage'] = $action;
$response['msg'] = 'begin';

$HTTP = $_POST;
extract($HTTP, EXTR_PREFIX_SAME, '_caop');

$response['result'] = true;

$response['htmlData'] = '';

switch ($type)
{
//		case "single":
//            $response['htmlData'] .= $type;
//		break;
	case "otherOne":
		$CheckShift = getarr(CAOP_SCHEDULE_UZI_SHIFTS, "shift_id='$shift_id'");
		if ( count($CheckShift) > 0 )
		{
			$CheckShift = $CheckShift[0];
			
			$defaultArr = array(
				'key' => 0,
				'value' => 'Выберите...'
			);
			$DoctorUZI = getarr(CAOP_DOCTOR, "doctor_isUzi='1' AND doctor_id!='{$CheckShift['shift_doctor_id']}'");
			if ( count($DoctorUZI) > 0 )
			{
				$DoctorUZI = docFtoFIO($DoctorUZI, 1);
				$DoctorUZIList = getDoctorsById($DoctorUZI, 'doctor_id');
				$SelectDoctor = array2select($DoctorUZIList, 'doctor_id', 'doctor_fio', 'target_doctor', 'id="search_by_doctor" class="form-control"', $defaultArr);
				$response['htmlData'] .= '<form id="copyshift_form">
					<input type="hidden" name="shift_id" value="'.$CheckShift['shift_id'].'">
					<input type="hidden" name="type" value="otherOne">
				    <div class="form-group">
				        <label for="doctor"><b>Выберите врача, которому надо скопировать смену \''.$CheckShift['shift_title'].'\'</b></label>
				        '.$SelectDoctor['result'].'
				    </div>
				</form>';
			} else
			{
				$response['htmlData'] .= bt_notice('В списке больше нет врачей УЗИ, которым можно бы было скопировать смену', BT_THEME_WARNING, 1);
			}
			
		} else
		{
			$response['htmlData'] .= 'Не указана смена';
		}
		
	break;
	case "otherFull":
		$defaultArr = array(
			'key' => 0,
			'value' => 'Выберите...'
		);
		$DoctorUZI = getarr(CAOP_DOCTOR, "doctor_isUzi='1' AND doctor_id!='$doctor_id'");
		if ( count($DoctorUZI) > 0 )
		{
			$DoctorUZI = docFtoFIO($DoctorUZI, 1);
			$DoctorUZIList = getDoctorsById($DoctorUZI, 'doctor_id');
			$SelectDoctor = array2select($DoctorUZIList, 'doctor_id', 'doctor_fio', 'target_doctor', 'id="search_by_doctor" class="form-control"', $defaultArr);
			$response['htmlData'] .= '<form id="copyshift_form">
					<input type="hidden" name="uzi_id" value="'.$uzi_id.'">
					<input type="hidden" name="doctor_id" value="'.$doctor_id.'">
					<input type="hidden" name="type" value="otherFull">
				    <div class="form-group">
				        <label for="doctor"><b>Выберите врача, которому надо скопировать смены</b></label>
				        '.$SelectDoctor['result'].'
				    </div>
				</form>';
		} else
		{
			$response['htmlData'] .= bt_notice('В списке больше нет врачей УЗИ, которым можно бы было скопировать смену', BT_THEME_WARNING, 1);
		}
	break;
	default:
		$response['htmlData'] .= 'Неверно выбран тип дублирования';
	break;
}



