<?php

$BLANK_SCRIPT = 'msktmdc'; // название страницы
$BLANK_SCRIPT_PRINT = 'msktmdcPrint'; // название страницы для печати
$BLANK_BUTTON_ADD = 'Добавить направление'; // название кнопки добавления
$BLANK_TABLE_LIST = CAOP_MSKT_MDC; // таблица со списком
$BLANK_TABLE_FIELD_ID = 'mskt_id'; // id столбца индекса
$BLANK_TABLE_FIELD_PATID = 'mskt_patient_id'; // id столбца пациента
$BLANK_TABLE_FIELD_SORT = 'mskt_date'; // id столбца с вводимой датой
$BLANK_TABLE_FIELD_UPDATE = 'mskt_update_unix'; // id столбца с unix-обновлением
$BLANK_LIST_TITLE = 'Список направлений'; // название спойлера
$BLANK_LIST_DESC = 'Направление пациента ID '; // название спойлера итема
$BLANK_LIST_EDIT = 'Редактирование нового направления'; // название спойлера редактирования изначального
$BLANK_LIST_EDIT_DAILY = 'Редактирование направления пациента №'; // название спойлера редактирования постнажатия
$BLANK_LIST_CREATURE = 'Направление составлено'; // название спойлера даты составления
$BLANK_LIST_EMPTY = 'Направлений нет'; // сообщение - нет итемов
$BLANK_LIST_FULLEMPTY = 'Список направлений пуст'; // сообщение - нет вообще итемов
$BLANK_LIST_NOEXIST = 'Такого направления не существует'; // сообщение - не существует итем
$BLANK_LIST_NOCHOOSE = 'Не выбрано направление для редактирования'; // сообщение - не существует итем, если в урле прописано
$BLANK_LIST_REMOVE_CONFIRM = 'Вы действительно хотите удалить это направление?'; // подтверждение удаления
$BLANK_PREFIX_MAIN = 'mskt'; // префикс главного спойлера
$BLANK_PREFIX_ITEM = 'mskt_dir'; // префикс спойлеров элементов
$BLANK_DG_MKB = 'mskt_diagnosis_mkb'; // поле МКБ
$BLANK_DG_TEXT = 'mskt_diagnosis_text'; // поле текст
$BLANK_ARRAY_INIT_VALUES = array();



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

//debug($RequestParamsData);
//exit();

if ( $isPersonal )
{
	$PatientData = getPatientDataById($RequestParamsData[0]);
	
	if ( $PatientData['result'] && !$PatientData['error'] )
	{
		$PatientData = $PatientData['data']['personal'];



//		debug($PatientData);
		
		bt_notice('<b>Пациент:</b> ' . editPersonalDataLink(mb_ucwords($PatientData['patid_name']), $PatientData['patid_id']) . ', ' . $PatientData['patid_birth']. ' г.р.',BT_THEME_PRIMARY );
		
		echo '
		<button onclick="location.href=\'/'.$BLANK_SCRIPT.'/'.$PatientData['patid_id'].'/add\'" type="button" class="btn btn-primary">'.$BLANK_BUTTON_ADD.'</button><br><br>
		';
		
		$DataArray = getarr($BLANK_TABLE_LIST, "{$BLANK_TABLE_FIELD_PATID}='{$PatientData['patid_id']}'", "ORDER BY {$BLANK_TABLE_FIELD_SORT} DESC");
//		debug($DataArray);
		if ( count($DataArray) > 0 )
		{
			spoiler_begin($BLANK_LIST_TITLE . ' ('.count($DataArray).')', $BLANK_PREFIX_MAIN . '_'.$PatientData['patid_id'], '');
			foreach ($DataArray as $itemData) {
				spoiler_begin('<b>Диагноз:</b> ['.$itemData[$BLANK_DG_MKB].'] '.$itemData[$BLANK_DG_TEXT], $BLANK_PREFIX_ITEM.$itemData[$BLANK_TABLE_FIELD_ID], '');
				echo '
				<b>'.$BLANK_LIST_CREATURE.':</b> '.$itemData[$BLANK_TABLE_FIELD_SORT].'<br>
				<button onclick="window.open(\'/'.$BLANK_SCRIPT_PRINT.'/'.$itemData[$BLANK_TABLE_FIELD_ID].'\')" type="button" class="btn btn-primary">
					<i class="bi bi-printer-fill"></i>
				</button>
				<button onclick="location.href=\'/'.$BLANK_SCRIPT.'/'.$PatientData['patid_id'].'/edit/'.$itemData[$BLANK_TABLE_FIELD_ID].'\'" type="button" class="btn btn-secondary">Редактировать</button>
				<button onclick="if (confirm(\''.$BLANK_LIST_REMOVE_CONFIRM.'\')) window.location.href=\'/'.$BLANK_SCRIPT.'/'.$PatientData['patid_id'].'/remove/'.$itemData[$BLANK_TABLE_FIELD_ID].'\'" type="button" class="btn btn-danger">Удалить</button>
				';
//				debug($routeSheet);
				
				spoiler_end();
				echo '<br>';
			}
			spoiler_end();
			
		} else
		{
			bt_notice($BLANK_LIST_EMPTY,BT_THEME_SECONDARY);
		}
		
		if ( $RequestParamsData[1] == "add" )
		{
			$triggerEmpty = false;
			if ( count($DataArray) > 0 )
			{
				$BlankEmpty = getarr($BLANK_TABLE_LIST, "{$BLANK_TABLE_FIELD_PATID}='{$PatientData['patid_id']}' AND {$BLANK_TABLE_FIELD_SORT}=''", "ORDER BY {$BLANK_TABLE_FIELD_ID} DESC LIMIT 1");
				if ( count($BlankEmpty) == 0 )
				{
					$triggerEmpty = true;
				} else
				{
					$BlankEmpty = $BlankEmpty[0];
				}
			} else
			{
				$triggerEmpty = true;
			}
			
			if ( $triggerEmpty )
			{
				
				
				
				if ($isJournal)
				{
					$JournalRM = RecordManipulation($journal_id, CAOP_JOURNAL, 'journal_id');
					if ( $JournalRM['result'] )
					{
						$JournalData = $JournalRM['data'];
					}
				}
				
				$BLANK_ARRAY_INIT_VALUES['mskt_patient_id'] = $PatientData['patid_id'];
				$BLANK_ARRAY_INIT_VALUES['mskt_journal_id'] = $journal_id;
				$BLANK_ARRAY_INIT_VALUES['mskt_doctor_id'] = $USER_PROFILE['doctor_id'];
				$BLANK_ARRAY_INIT_VALUES['mskt_diagnosis_mkb'] = $JournalData['journal_ds'];
				$BLANK_ARRAY_INIT_VALUES['mskt_diagnosis_text'] = $JournalData['journal_ds_text'];
				$BLANK_ARRAY_INIT_VALUES['mskt_update_unix'] = time();
				$BLANK_ARRAY_INIT_VALUES['mskt_date'] = date("d.m.Y", time());
				$BLANK_ARRAY_INIT_VALUES['mskt_create_unix'] = time();
				
				$paramValues = $BLANK_ARRAY_INIT_VALUES;
				
				$NewBlank = appendData($BLANK_TABLE_LIST, $paramValues);
				jsrefresh('/' . $BLANK_SCRIPT . '/' . $PatientData['patid_id'] . '/edit/' . $NewBlank[ID]);
				exit();
			}
			echo '<br>';
			bt_notice('<b>'.$BLANK_LIST_EDIT.'</b>', BT_THEME_WARNING);
			$BlankForm = $NoticeEmpty;
			include "engine/html/print_forms/{$BLANK_SCRIPT}_form.php";
		} else
		{
			if ( $RequestParamsData[1] == "edit" )
			{
				if ( $RequestParamsData[2] > 0 )
				{
					$BlankEdit = getarr($BLANK_TABLE_LIST, "{$BLANK_TABLE_FIELD_ID}='{$RequestParamsData[2]}'");
					if ( count($BlankEdit) > 0 )
					{
						$BlankForm = $BlankEdit[0];
						bt_notice('<b>' . $BLANK_LIST_EDIT_DAILY .$BlankForm[$BLANK_TABLE_FIELD_ID].'</b>', BT_THEME_WARNING);
						include "engine/html/print_forms/{$BLANK_SCRIPT}_form.php";
					} else bt_notice($BLANK_LIST_NOEXIST,BT_THEME_DANGER);
					
				} else bt_notice($BLANK_LIST_NOCHOOSE,BT_THEME_DANGER);
			} else
			{
				if ( $RequestParamsData[1] == "remove" )
				{
					if ( $RequestParamsData[2] > 0 )
					{
						$BlankEdit = getarr($BLANK_TABLE_LIST, "{$BLANK_TABLE_FIELD_ID}='{$RequestParamsData[2]}'");
						if ( count($BlankEdit) > 0 )
						{
							$BlankEdit = $BlankEdit[0];
							$DeleteBlank = deleteitem($BLANK_TABLE_LIST, "{$BLANK_TABLE_FIELD_ID}='{$BlankEdit[$BLANK_TABLE_FIELD_ID]}'");
							if ( $DeleteBlank['result'] == true )
							{
								jsrefresh('/'.$BLANK_SCRIPT.'/'.$PatientData['patid_id']);
								exit();
							}
						} else bt_notice($BLANK_LIST_NOEXIST,BT_THEME_DANGER);
						
					} else bt_notice($BLANK_LIST_REMOVE_CONFIRM,BT_THEME_DANGER);
				}
			}
		}
		
	} else
	{
		bt_notice($PatientData['msg'], BT_THEME_WARNING);
	}
} else
{
	$Blanks = getarr($BLANK_TABLE_LIST, 1, "ORDER BY {$BLANK_TABLE_FIELD_UPDATE} DESC");
	$CountBlanks = count($Blanks);
	if ( $CountBlanks > 0 )
	{
		bt_notice('Документов: ' . $CountBlanks);
		foreach ($Blanks as $blank) {
			$patient_id = $blank[$BLANK_TABLE_FIELD_PATID];
			$PatientData = getPatientDataById($patient_id);
			if ( $PatientData ['result'] === true )
			{
				$PatientData = $PatientData['data']['personal'];
				spoiler_begin($BLANK_LIST_DESC . $blank[$BLANK_TABLE_FIELD_ID] . ': ' . mb_ucwords($PatientData['patid_name']) . ', ' . $PatientData['patid_birth'] . ' г.р.', $BLANK_PREFIX_MAIN . 'all_'.$blank[$BLANK_TABLE_FIELD_ID], '');
				debug($blank);
				?>
                <button class="btn btn-sm btn-primary" onclick="window.open('/<?=$BLANK_SCRIPT;?>/<?=$PatientData['patid_id'];?>')" type="button">Весь список</button>
				<?php
				spoiler_end();
			}
		}
	} else
	{
		bt_notice($BLANK_LIST_FULLEMPTY ,BT_THEME_SECONDARY);
	}
}
?>

<!--<script defer language="JavaScript" type="text/javascript" src="/engine/js/routeSheet.js?--><?//=rand(0,1000000)?><!--"></script>-->
