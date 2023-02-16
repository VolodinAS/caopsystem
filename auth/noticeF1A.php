<?php
$isPersonal = false;
$isJournal = false;
if (strlen($request_params) > 0) {
	$RequestParamsData = explode('/', $request_params);
//	debug($RequestParamsData);
	
	if ( $RequestParamsData[0] > 0 )
	{
		$isPersonal = true;
	}
	
	if ( strlen($RequestParamsData[2]) > 0 )
	{
	    $journal_id = $RequestParamsData[2];
		$isJournal = true;
	}
}

if ( $isPersonal )
{
	$PatientData = getPatientDataById($RequestParamsData[0]);
	
	if ( $PatientData['result'] && !$PatientData['error'] )
	{
		$PatientData = $PatientData['data']['personal'];

//		debug($PatientData);
		
		bt_notice('<b>Пациент:</b> ' . editPersonalDataLink(mb_ucwords($PatientData['patid_name']), $PatientData['patid_id']) . ', ' . $PatientData['patid_birth']. ' г.р.',BT_THEME_PRIMARY );
		
		echo '
		<button onclick="location.href=\'/noticeF1A/'.$PatientData['patid_id'].'/add\'" type="button" class="btn btn-primary">Добавить извещение</button><br><br>
		';
		
		$Notices1A = getarr(CAOP_NOTICE_F1A, "f1a_patid='{$PatientData['patid_id']}'", "ORDER BY f1a_date_create DESC");
		if ( count($Notices1A) > 0 )
		{
			spoiler_begin('Список извещений ('.count($Notices1A).')', 'n1a_'.$PatientData['patid_id'], '');
			foreach ($Notices1A as $notice1A) {
				spoiler_begin('<b>Диагноз:</b> ['.$notice1A['f1a_dg_mkb'].'] '.$notice1A['f1a_dg_text'], 'notice'.$notice1A['f1a_id'], '');
				echo '
				<b>Извещение составлено:</b> '.$notice1A['f1a_date_create'].'<br>
				<button onclick="window.open(\'/noticeF1aPrint/'.$notice1A['f1a_id'].'\')" type="button" class="btn btn-primary">
					<i class="bi bi-printer-fill"></i>
				</button>
				<button onclick="location.href=\'/noticeF1A/'.$PatientData['patid_id'].'/edit/'.$notice1A['f1a_id'].'\'" type="button" class="btn btn-secondary">Редактировать</button>
				<button onclick="if (confirm(\'Вы действительно хотите удалить это извещение?\')) window.location.href=\'/noticeF1A/'.$PatientData['patid_id'].'/remove/'.$notice1A['f1a_id'].'\'" type="button" class="btn btn-danger">Удалить</button>
				';
//				debug($routeSheet);
				
				spoiler_end();
				echo '<br>';
			}
			spoiler_end();
			
		} else
		{
			bt_notice('Нет извещений',BT_THEME_SECONDARY);
		}
		
		if ( $RequestParamsData[1] == "add" )
		{
			$ntEmpty = false;
			if ( count($Notices1A) > 0 )
			{
				$NoticeEmpty = getarr(CAOP_NOTICE_F1A, "f1a_patid='{$PatientData['patid_id']}' AND f1a_date_create=''", "ORDER BY f1a_id DESC LIMIT 1");
				if ( count($NoticeEmpty) == 0 )
				{
					$ntEmpty = true;
				} else
				{
					$NoticeEmpty = $NoticeEmpty[0];
				}
			} else
			{
				$ntEmpty = true;
			}
			
			if ( $ntEmpty )
			{
			 
				$paramValues = array(
					'f1a_patid'  =>  $PatientData['patid_id'],
					'f1a_doctor'  =>  $USER_PROFILE['doctor_id'],
					'f1a_date_update_unix'    =>  time(),
                    'f1a_lpu_from'  =>  '('.$LPU_DOCTOR['lpu_code'].') '.$LPU_DOCTOR['lpu_blank_name'].', '.$LPU_DOCTOR['lpu_lpu_address'],
                    'f1a_lpu_to'  =>  '(4039) онкологические отделения ГБУЗ СО "ТГКБ №5", г. Тольятти, б-р Здоровья, 25',
                    'f1a_reason'    =>  'отсутствие морфологической верификации'
				);
				
				if ($isJournal)
				{
					$JournalRM = RecordManipulation($journal_id, CAOP_JOURNAL, 'journal_id');
					if ( $JournalRM['result'] )
					{
						$JournalData = $JournalRM['data'];
						$paramValues['f1a_dg_mkb'] = $JournalData['journal_ds'];
						$paramValues['f1a_dg_text'] = $JournalData['journal_ds_text'];
					}
				}
				
				$NewNotice = appendData(CAOP_NOTICE_F1A, $paramValues);
				jsrefresh();
				exit();
			}
			echo '<br>';
			bt_notice('<b>Редактирование нового извещения пациента</b>', BT_THEME_WARNING);
			$NoticeF1aForm = $NoticeEmpty;
			include "engine/html/print_forms/notice_f1a_form.php";
		} else
		{
			if ( $RequestParamsData[1] == "edit" )
			{
				if ( $RequestParamsData[2] > 0 )
				{
					$NoticeEdit = getarr(CAOP_NOTICE_F1A, "f1a_id='{$RequestParamsData[2]}'");
					if ( count($NoticeEdit) > 0 )
					{
						$NoticeF1aForm = $NoticeEdit[0];
						bt_notice('<b>Редактирование извещения пациента №'.$NoticeF1aForm['f1a_id'].'</b>', BT_THEME_WARNING);
						include "engine/html/print_forms/notice_f1a_form.php";
					} else bt_notice('Такого извещения не существует',BT_THEME_DANGER);
					
				} else bt_notice('Не выбрано извещение для редактирования',BT_THEME_DANGER);
			} else
			{
				if ( $RequestParamsData[1] == "remove" )
				{
					if ( $RequestParamsData[2] > 0 )
					{
						$NoticeEdit = getarr(CAOP_NOTICE_F1A, "f1a_id='{$RequestParamsData[2]}'");
						if ( count($NoticeEdit) > 0 )
						{
							$NoticeEdit = $NoticeEdit[0];
							$DeleteNT = deleteitem(CAOP_NOTICE_F1A, "f1a_id='{$NoticeEdit['f1a_id']}'");
							if ( $DeleteNT['result'] == true )
							{
								jsrefresh('/noticeF1A/'.$PatientData['patid_id']);
								exit();
							}
						} else bt_notice('Такого извещения не существует',BT_THEME_DANGER);
						
					} else bt_notice('Не выбрано извещение для удаления',BT_THEME_DANGER);
				}
			}
		}
		
	} else
	{
		bt_notice($PatientData['msg'], BT_THEME_WARNING);
	}
} else
{
	$Notices = getarr(CAOP_NOTICE_F1A, 1, "ORDER BY f1a_date_update_unix DESC");
	$CountBlanks = count($Notices);
	if ( $CountBlanks > 0 )
	{
		$BLANK_SCRIPT = 'noticeF1A'; // название страницы
		$BLANK_TABLE_FIELD_PATID = 'f1a_patid'; // id столбца пациента
		$BLANK_TABLE_FIELD_ID = 'f1a_id'; // id столбца индекса
		$BLANK_LIST_DESC = 'Извещения на 1А кл. гр. ID'; // название спойлера итема
		$BLANK_PREFIX_MAIN = 'f1a'; // префикс главного спойлера
	 
		bt_notice('Документов: ' . $CountBlanks);
		
		foreach ($Notices as $notice) {
			$patient_id = $notice[$BLANK_TABLE_FIELD_PATID];
			$PatientData = getPatientDataById($patient_id);
			if ( $PatientData ['result'] === true )
			{
				$PatientData = $PatientData['data']['personal'];
				spoiler_begin($BLANK_LIST_DESC . $notice[$BLANK_TABLE_FIELD_ID] . ': ' . mb_ucwords($PatientData['patid_name']) . ', ' . $PatientData['patid_birth'] . ' г.р.', $BLANK_PREFIX_MAIN . 'all_'.$notice[$BLANK_TABLE_FIELD_ID], '');
				debug($notice);
				?>
                <button class="btn btn-sm btn-primary" onclick="window.open('/<?=$BLANK_SCRIPT;?>/<?=$PatientData['patid_id'];?>')" type="button">Весь список</button>
				<?php
				spoiler_end();
			}
		}
		
	} else
	{
		bt_notice('В списке нет ни единого извещения',BT_THEME_SECONDARY);
	}
}
?>

<!--<script defer language="JavaScript" type="text/javascript" src="/engine/js/routeSheet.js?--><?//=rand(0,1000000)?><!--"></script>-->
