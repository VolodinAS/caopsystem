<?php
$CheckDate = getarr(CAOP_SCHEDULE_UZI_DATES, "dates_id='$date_id'")[0];

$CheckTime = getarr(CAOP_SCHEDULE_UZI_TIMES, "time_id='$patuzi_id'");
if ( count($CheckTime) == 1 )
{
	$CheckTime = $CheckTime[0];

//	$response['htmlData'] .= debug_ret($CheckTime);
	$DoctorUZI = getarr(CAOP_SCHEDULE_UZI_DOCTORS, "uzi_id='{$CheckTime['time_uzi_id']}'");
//	$response['debug']['$DoctorUZI'] = $DoctorUZI;
	if ( count($DoctorUZI) == 1 )
	{
		$AreasCurrent = [];
		$DoctorUZI = $DoctorUZI[0];
		$AreasDoctor = array2KeyValueArray( json_decode( stripslashes($DoctorUZI['uzi_research_area_ids']) , 1 ) );
		
		$DoctorsListUzi = getarr(CAOP_DOCTOR, "doctor_isUzi='1'");
		$DoctorsListUziId = getDoctorsById($DoctorsListUzi);
		
		foreach ($AreasDoctor as $area=>$isSet)
		{
			$areaId = str_replace(SYS_URA, '', $area);
			if ( $isSet )
			{
				$AreasCurrent[] = $AreasUZIId['id' . $areaId];
			}
		}
		if ( count($AreasCurrent) > 0 )
		{
			$CheckUziPat = getarr(CAOP_SCHEDULE_UZI_PATIENTS, "patient_time_id='{$CheckTime['time_id']}'  AND patient_date_id='$date_id'");
			if ( count($CheckUziPat) == 1 )
			{
				// УЖЕ ЕСТЬ ЗАПИСАННЫЙ ПАЦИЕНТ
				
				$CheckUziPat = $CheckUziPat[0];

//		$response['htmlData'] .= debug_ret($CheckUziPat);
				$response['htmlData'] .= '<div class="d-flex flex-row align-self-center justify-content-around">';
				$response['htmlData'] .= '<div>
					<button class="btn btn-danger btn-removeRecord" data-patuziid="'.$CheckTime['time_id'].'" data-journalid="'.$journal_id.'" data-dateid="'.$date_id.'">Снять талон</button>
				</div>';
				$response['htmlData'] .= '<div>
					<button class="btn btn-warning btn-printRecord" data-patuziid="'.$CheckTime['time_id'].'" data-journalid="'.$journal_id.'" data-dateid="'.$date_id.'">Распечатать талон</button>
				</div>';
				$response['htmlData'] .= '<div>
					<input class="form-check-input check-this-talon-for-print" data-uziid="'.$CheckUziPat['patient_id'].'" type="checkbox" name="uziprint_'.$CheckTime['time_id'].'" id="uziprint_'.$CheckUziPat['patient_id'].'" value="1" >
					<label class="form-check-label box-label" for="uziprint_'.$CheckUziPat['patient_id'].'"><span></span><b>Выбрать талон для распечатки</b></label>
				</div>';
				$response['htmlData'] .= '</div>';
				$response['htmlData'] .= '<div class="dropdown-divider"></div>';
				$PatientData = getarr(CAOP_PATIENTS, "patid_id='{$CheckUziPat['patient_pat_id']}'");


//		$response['htmlData'] .= debug_ret($PatientData);
				if ( count($PatientData) == 1 )
				{
					
					
					
					$a2sDefault = array(
						'key' => 0,
						'value' => 'Выберите...'
					);
					$a2sSelected = array(
						'value' => $CheckUziPat['patient_area_id']
					);
					$a2sSelector = array2select($AreasCurrent, 'area_id', 'area_title', null,
						'class="form-control mysqleditor" data-action="edit"
						data-table="'.CAOP_SCHEDULE_UZI_PATIENTS.'"
						data-assoc="0"
						data-fieldid="patient_id"
						data-id="'.$CheckUziPat['patient_id'].'"
						data-field="patient_area_id"', $a2sDefault, $a2sSelected);
					
					$PatientData = $PatientData[0];
					$age = ageByBirth($PatientData['patid_birth']);
					
//					$response['debug']['$DoctorsListId'] = $DoctorsListId;
//					$response['debug']['$CheckUziPat'] = $CheckUziPat;
					
					$response['htmlData'] .= wrapper('Пациент: ') . mb_ucwords($PatientData['patid_name']) . ', ' . $PatientData['patid_birth'] . ' г.р. ('.$age.' '.wordEnd($age, 'год', 'года', 'лет').')<br/>';
					$response['htmlData'] .= wrapper('Врач УЗИ ЦАОП: ') . docNameShort($DoctorsListUziId['id' . $CheckUziPat['patient_doctor_id']], "famimot");
//					$response['htmlData'] .= debug_ret($CheckUziPat);
					$response['htmlData'] .= '<div class="dropdown-divider"></div>';
					$response['htmlData'] .= wrapper('Исследуемая область: ') . $a2sSelector['result'];
					$response['htmlData'] .= '<div class="dropdown-divider"></div>';
					$response['htmlData'] .= wrapper('Примечание (если надо):');
					$response['htmlData'] .= '<input type="text" class="form-control mysqleditor" data-action="edit"
						data-table="'.CAOP_SCHEDULE_UZI_PATIENTS.'"
						data-assoc="0"
						data-fieldid="patient_id"
						data-id="'.$CheckUziPat['patient_id'].'"
						data-field="patient_area_description"
						placeholder="Пояснение для врача УЗИ: к примеру, если УЗИ мягких тканей, то где конкретно"
						value="'.$CheckUziPat['patient_area_description'].'">';
				} else
				{
					$response['htmlData'] .= bt_notice('Такого пациента не существует', BT_THEME_WARNING, 1);
				}
				
			} else
			{
				// ТАЛОН СВОБОДЕН
//				$response['htmlData'] .= debug_ret($journal_id);
				if ( (int)$journal_id === -1 )
				{
//					$response['htmlData'] .= 'from graphic';
					
					$a2sDefault = array(
						'key' => 0,
						'value' => 'Выберите...'
					);
					$a2sSelector = array2select($AreasCurrent, 'area_id', 'area_title', 'area_id', 'class="form-control"', $a2sDefault);
					
					$response['htmlData'] .= '<b>Поиск пациента для записи на УЗИ ЦАОП:</b>';
					$response['htmlData'] .= '<form id="uziSearch_form">';
					$response['htmlData'] .= '<div class="row">';
//					$response['htmlData'] .= '<div class="col-auto">';
//					$response['htmlData'] .= '<input class="form-control form-control-sm" type="text" name="patid_ident" placeholder="№ карты" id="patid_ident">';
//					$response['htmlData'] .= '</div>';
					$response['htmlData'] .= '<div class="col">';
					$response['htmlData'] .= '<input class="form-control form-control-sm" type="text" name="patid_name" placeholder="Ф.И.О. пациента" id="patid_name">';
					$response['htmlData'] .= '</div>';
					$response['htmlData'] .= '<div class="col-auto">';
					$response['htmlData'] .= '<button class="btn btn-primary btn-sm btn-uziSearch">Искать</button>';
					$response['htmlData'] .= '</div>';
					$response['htmlData'] .= '</div>';
					$response['htmlData'] .= '</form>';
					$response['htmlData'] .= '<div class="dropdown-divider"></div>';
					$response['htmlData'] .= '<div id="uziSearch_form_result"></div>';
					$response['htmlData'] .= '<form id="newUZIRecord_form">';
					$response['htmlData'] .= '<div id="uziRecord_result"></div>';
					$response['htmlData'] .= '<input type="hidden" name="patid_id" id="patid_id" value="0">';
					$response['htmlData'] .= '<input type="hidden" name="journal_id" value="'.$journal_id.'">';
					$response['htmlData'] .= '<input type="hidden" name="date_id" value="'.$date_id.'">';
					$response['htmlData'] .= '<input type="hidden" name="time_id" value="'.$CheckTime['time_id'].'">';
					$response['htmlData'] .= '<h3>' . wrapper('Дата и время записи: ') . $CheckDate['dates_date'] . ' ' . $CheckTime['time_hour'] . ':'  . $CheckTime['time_min'] . '</h3>';
					$response['htmlData'] .= wrapper('Исследуемая область: ') . $a2sSelector['result'];
					$response['htmlData'] .= '<br>';
					$response['htmlData'] .= wrapper('Примечание (если надо):');
					$response['htmlData'] .= '<input type="text" class="form-control" name="area_description" placeholder="Пояснение для врача УЗИ: к примеру, если УЗИ мягких тканей, то где конкретно">';
					$response['htmlData'] .= '<div class="dropdown-divider"></div>';
					$response['htmlData'] .= '<button class="btn btn-primary btn-addNewRecord col">Назначить УЗИ</button>';
					$response['htmlData'] .= '</form>';
					
				} else
				{
					$JournalPatient_query = "SELECT * FROM ".CAOP_JOURNAL." AS journal
					LEFT JOIN ".CAOP_PATIENTS." AS patients ON journal.journal_patid=patients.patid_id
					WHERE journal.journal_id='$journal_id'";
					$JournalPatient_result = mqc($JournalPatient_query);
					$JournalPatient_amount = mnr($JournalPatient_result);
					if ( $JournalPatient_amount == 1 )
					{
						
						$CheckDate = getarr(CAOP_SCHEDULE_UZI_DATES, "dates_id='$date_id'");
						if ( count($CheckDate) == 1 )
						{
							$CheckDate = $CheckDate[0];
//			    $response['htmlData'] .= debug_ret($CheckDate);
							
							
							$a2sDefault = array(
								'key' => 0,
								'value' => 'Выберите...'
							);
							$a2sSelector = array2select($AreasCurrent, 'area_id', 'area_title', 'area_id', 'class="form-control"', $a2sDefault);
							
							
							$JournalPatientData = mr2a($JournalPatient_result);
							$JournalPatientData = $JournalPatientData[0];
//			$response['htmlData'] .= debug_ret($JournalPatientData);
							$response['htmlData'] .= '<button class="btn btn-secondary btn-sm btn-otherPatient" data-dateid="'.$CheckDate['dates_id'].'" data-timeid="'.$CheckTime['time_id'].'">Поиск пациента</button> ';
							$response['htmlData'] .= bt_notice(wrapper('Запись на УЗИ ЦАОП пациента: ') . mb_ucwords($JournalPatientData['patid_name']) . ', ' . $JournalPatientData['patid_birth'] . ' г.р.' , BT_THEME_INFO, 1);
							$response['htmlData'] .= '<div class="dropdown-divider"></div>';
							$response['htmlData'] .= '<form id="newUZIRecord_form">';
							$response['htmlData'] .= '<input type="hidden" name="patid_id" value="'.$JournalPatientData['patid_id'].'">';
							$response['htmlData'] .= '<input type="hidden" name="journal_id" value="'.$JournalPatientData['journal_id'].'">';
							$response['htmlData'] .= '<input type="hidden" name="date_id" value="'.$date_id.'">';
							$response['htmlData'] .= '<input type="hidden" name="time_id" value="'.$CheckTime['time_id'].'">';
							$response['htmlData'] .= '<h3>' . wrapper('Дата и время записи: ') . $CheckDate['dates_date'] . ' ' . $CheckTime['time_hour'] . ':'  . $CheckTime['time_min'] . '</h3>';
							$response['htmlData'] .= wrapper('Исследуемая область: ') . $a2sSelector['result'];
							$response['htmlData'] .= '<br>';
							$response['htmlData'] .= wrapper('Примечание (если надо):');
							$response['htmlData'] .= '<input type="text" class="form-control" name="area_description" placeholder="Пояснение для врача УЗИ: к примеру, если УЗИ мягких тканей, то где конкретно">';
							$response['htmlData'] .= '<div class="dropdown-divider"></div>';
							$response['htmlData'] .= '<button class="btn btn-primary btn-addNewRecord col">Назначить УЗИ</button>';
							$response['htmlData'] .= '</form>';
							
						} else
						{
							$response['htmlData'] .= bt_notice('Выбранной даты нет в графике смен', BT_THEME_WARNING, 1);
						}
						
						
					} else
					{
						$response['htmlData'] .= bt_notice('Нет такого приёма в журнале', BT_THEME_WARNING, 1);
					}
				}
				
			}
		} else
		{
			$response['htmlData'] .= bt_notice('У врача УЗИ не выбраны исследуемые области!', BT_THEME_WARNING, 1);
		}
		
		
		
	} else
	{
		$response['htmlData'] .= bt_notice('Такого врача УЗИ нет', BT_THEME_WARNING, 1);
	}
	
	
	
} else
{
	$response['htmlData'] .= bt_notice('Такого времени в расписании врача нет', BT_THEME_WARNING, 1);
}