<?php
$response['stage'] = $action;
$response['msg'] = 'begin';

$HTTP = $_POST;
extract($HTTP, EXTR_PREFIX_SAME, '_caop');

$response['result'] = true;

$response['htmlData'] = '';

$DoctorUZI = getarr(CAOP_DOCTOR, "doctor_isUzi='1'", "ORDER BY doctor_f, doctor_i, doctor_o ASC");
if ( count($DoctorUZI) > 0 )
{
	$DoctorUZIGranted = [];
	// Проверяем, настроены ли ДАТЫ у врачей
	foreach ($DoctorUZI as $doctorUzi)
	{
//		$response['debug']['$doctorUzi'][] = $doctorUzi;
		$CheckDate = getarr(CAOP_SCHEDULE_UZI_DATES, "dates_doctor_id='{$doctorUzi['doctor_id']}'");
//		$response['debug']['$CheckDate'][] = $CheckDate;
		if ( count($CheckDate) > 0 )
		{
		    $DoctorUZIGranted[] = $doctorUzi;
		}
	}
	
	$defaultArr = array(
	    'key' => 0,
		'value' => 'Выберите...'
	);
	
	$Patient_query = "SELECT patid_name, patid_birth FROM ".CAOP_PATIENTS." AS pats INNER JOIN ".CAOP_JOURNAL." AS journal ON journal.journal_patid=pats.patid_id WHERE journal_id='$journal_id'";
	
	$Patient_result = mqc($Patient_query);
	$PatientData = mr2a($Patient_result)[0];
	$response['debug']['$PatientData'] = $PatientData;
	
	$response['debug']['$DoctorUZIGranted'] = $DoctorUZIGranted;
	$DoctorUZIGranted = docFtoFIO($DoctorUZIGranted, 1);
	$DoctorUZIList = getDoctorsById($DoctorUZIGranted, 'doctor_id');
	$SelectDoctor = array2select($DoctorUZIList, 'doctor_id', 'doctor_fio', 'target_doctor', 'id="choose_doctor_uzicaop" class="form-control"', $defaultArr);
	$response['htmlData'] .= bt_notice('<b>Пациент:</b> ' . shorty($PatientData['patid_name'], 'famimot') . ', ' . $PatientData['patid_birth'] . ' г.р.', BT_THEME_WARNING, 1);
	$response['htmlData'] .= '<div class="dropdown-divider"></div>';
	$response['htmlData'] .= '<form id="copyshift_form" data-journalid="'.$journal_id.'">
					<input type="hidden" name="shift_id" value="'.$CheckShift['shift_id'].'">
					<input type="hidden" name="journal_id" value="'.$journal_id.'">
					<input type="hidden" name="type" value="otherOne">
				    <div class="form-group">
				        <label for="doctor"><b>Выберите врача УЗИ, к которому хотите записать пациента:</b></label>
				        '.$SelectDoctor['result'].'
				    </div>
				</form>';
	
	$response['htmlData'] .= '<div id="uzi-caop_result">Сначала выберите врача...</div>';
} else
{
	$response['htmlData'] .= bt_notice('В списке больше нет врачей УЗИ, которым можно бы было скопировать смену', BT_THEME_WARNING, 1);
}

