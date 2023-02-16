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
		<button onclick="location.href=\'/blankDeclined/'.$PatientData['patid_id'].'/add\'" type="button" class="btn btn-primary">Заполнить бланк отказа</button><br><br>
		';
		
		$BlankDeclines = getarr(CAOP_BLANK_DECLINE, "decline_patid='{$PatientData['patid_id']}'", "ORDER BY decline_date_create DESC");
		if ( count($BlankDeclines) > 0 )
		{
			spoiler_begin('Список бланков отказа ('.count($BlankDeclines).')', 'declines_'.$PatientData['patid_id'], '');
			foreach ($BlankDeclines as $declineBlank) {
				spoiler_begin('<b>Основной отказ:</b> ['.$declineBlank['decline_phrase'].']', 'blank_'.$declineBlank['decline_id'], '');
				echo '
				<b>Бланк составлен:</b> '.$declineBlank['decline_date_create'].'<br>
				<button onclick="window.open(\'/blankDeclinedPrint/'.$declineBlank['decline_id'].'\')" type="button" class="btn btn-primary">
					<i class="bi bi-printer-fill"></i>
				</button>
				<button onclick="location.href=\'/blankDeclined/'.$PatientData['patid_id'].'/edit/'.$declineBlank['decline_id'].'\'" type="button" class="btn btn-secondary">Редактировать</button>
				<button onclick="if (confirm(\'Вы действительно хотите удалить этот бланк?\')) window.location.href=\'/blankDeclined/'.$PatientData['patid_id'].'/remove/'.$declineBlank['decline_id'].'\'" type="button" class="btn btn-danger">Удалить</button>
				';
//				debug($routeSheet);
				
				spoiler_end();
				echo '<br>';
			}
			spoiler_end();
			
		} else
		{
			bt_notice('Нет заполненных бланков',BT_THEME_SECONDARY);
		}
		
		if ( $RequestParamsData[1] == "add" )
		{
			$bdEmpty = false;
			if ( count($BlankDeclines) > 0 )
			{
				$BlankDeclineEmpty = getarr(CAOP_BLANK_DECLINE, "decline_patid='{$PatientData['patid_id']}' AND decline_date_create=''", "ORDER BY decline_id DESC LIMIT 1");
				if ( count($BlankDeclineEmpty) == 0 )
				{
					$bdEmpty = true;
				} else
				{
					$BlankDeclineEmpty = $BlankDeclineEmpty[0];
				}
			} else
			{
				$bdEmpty = true;
			}
			
			if ( $bdEmpty )
			{
				
				$paramValues = array(
					'decline_patid'  =>  $PatientData['patid_id'],
					'decline_doctor'  =>  $USER_PROFILE['doctor_id'],
					'decline_update_unix'    =>  time()
				);
				
				/*if ($isJournal)
				{
					$JournalRM = RecordManipulation($journal_id, CAOP_JOURNAL, 'journal_id');
					if ( $JournalRM['result'] )
					{
						$JournalData = $JournalRM['data'];
						$paramValues['f1a_dg_mkb'] = $JournalData['journal_ds'];
						$paramValues['f1a_dg_text'] = $JournalData['journal_ds_text'];
					}
				}*/
				
				$NewBlankDecline = appendData(CAOP_BLANK_DECLINE, $paramValues);
				jsrefresh();
				exit();
			}
			echo '<br>';
			bt_notice('<b>Редактирование нового бланка отказа</b>', BT_THEME_WARNING);
			$BlankDeclineForm = $BlankDeclineEmpty;
			include "engine/html/print_forms/blank_decline_form.php";
		} else
		{
			if ( $RequestParamsData[1] == "edit" )
			{
				if ( $RequestParamsData[2] > 0 )
				{
					$BlankDeclineEdit = getarr(CAOP_BLANK_DECLINE, "decline_id='{$RequestParamsData[2]}'");
					if ( count($BlankDeclineEdit) > 0 )
					{
						$BlankDeclineForm = $BlankDeclineEdit[0];
						bt_notice('<b>Редактирование бланка отказа пациента №'.$BlankDeclineForm['decline_id'].'</b>', BT_THEME_WARNING);
						include "engine/html/print_forms/blank_decline_form.php";
					} else bt_notice('Такого бланка не существует',BT_THEME_DANGER);
					
				} else bt_notice('Не выбран бланк для редактирования',BT_THEME_DANGER);
			} else
			{
				if ( $RequestParamsData[1] == "remove" )
				{
					if ( $RequestParamsData[2] > 0 )
					{
						$BlankDeclineEdit = getarr(CAOP_BLANK_DECLINE, "decline_id='{$RequestParamsData[2]}'");
						if ( count($BlankDeclineEdit) > 0 )
						{
							$BlankDeclineEdit = $BlankDeclineEdit[0];
							$DeleteBD = deleteitem(CAOP_BLANK_DECLINE, "decline_id='{$BlankDeclineEdit['decline_id']}'");
							if ( $DeleteBD['result'] == true )
							{
								jsrefresh('/blankDeclined/'.$PatientData['patid_id']);
								exit();
							}
						} else bt_notice('Такого бланка не существует',BT_THEME_DANGER);
						
					} else bt_notice('Не выбран бланк для удаления',BT_THEME_DANGER);
				}
			}
		}
		
	} else
	{
		bt_notice($PatientData['msg'], BT_THEME_WARNING);
	}
} else
{
	
	$BLANK_SCRIPT = 'blankDeclined';
	$BLANK_LIST_DESC = 'Бланки отказа ID: ';
	$BLANK_TABLE_FIELD_ID = 'decline_id';
	$BLANK_TABLE_FIELD_PATID = 'decline_patid';
	$BLANK_PREFIX_MAIN = 'decline';
	$BLANK_LIST_FULLEMPTY = 'Список бланков отказа пуст';
 
	$Blanks = getarr(CAOP_BLANK_DECLINE, 1, "ORDER BY decline_update_unix DESC");
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
