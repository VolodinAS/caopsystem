<?php

if ($USER_PROFILE['access_level'] == 2)
{
	
	$AllTables = mysql_tables(DB_NAME);
	
	// 'caop_journal', 'caop_patients', 'caop_citology', 'caop_research'
	
	
	$access_functions = array(
		'gethashpassword' => 'Показывает новый рандомный пароль для системы из ключевого слова',
		'synchronizeResearch' => 'Синхронизация обследований и имён',
		'synchronizeJournal' => 'Какая-то фигня недоделанная для журнала',
		'birth2unix' => 'Обновляет базу, добавляя unix-дату для дней рождения',
		'getrandomcatmachine' => 'Показывает рандомный CAT',
		'catgenerator' => 'Добавляет рандомный CAT в базу',
		'currentcat' => 'Показывает CAT компьютера',
		'dsnoted' => 'Перенос заметок',
		'journalsearch' => 'Поиск по таблице-полю-фразе',
		'doctorbirth' => 'Настройка дней рождений сотрудников',
        'defectivenames' => 'Показывает пациентов с дефектными именами',
        'distincter' => 'Дистинктер пациентных таблиц по patient_id',
        'dispancer' => 'Просмотр структуры диспансерных пациентов',
        'dispancerReset' => 'Сброс таблицы и откат значений к 0',
        'move_dispancer' => 'Перенос диспансерных пациентов в СПО',
        'myisam2innodb' => 'Конвертация таблиц из MyISAM в InnoDB: /tomyisam, /toinnodb'
    );
	
	$getSpolierAdminParam = doctor_param('get', $USER_PROFILE['doctor_id'], 'admin_spoiler', 'spolier');
	if ($getSpolierAdminParam['stat'] == true)
    {
	    
	    $getSpolierAdminParam = $getSpolierAdminParam['data']['settings_param_value'];
//	    debug($getSpolierAdminParam);
	    $param_arr = explode('tables', $getSpolierAdminParam);
	    unset($param_arr[count($param_arr)-1]);
	    $getSpolierAdminParam = implode('tables', $param_arr);
	    $getSpolierAdminParam = str_replace('accordion_', '', $getSpolierAdminParam);
	    $getSpolierAdminParam .= "_spoiler";
//	    debug($param_arr);
	    ?>
        <script defer type="text/javascript">
        $( document ).ready(function()
        {
            $('[data-target="#<?=$getSpolierAdminParam;?>"]').click();
            //$('#' + '<?//=$getSpolierAdminParam;?>//').collapse('show');
        });
        </script>
	    <?php
    }
//	debug($getSpolierAdminParam);
	
	require_once("engine/html/admin/accordion_tables_variables.php");
//	require_once("engine/html/admin/accordion_tables.php");
//    require_once ("engine/html/admin/accordion_table_editor.php");
	require_once("engine/html/admin/accordion_access_functions.php");
	require_once("engine/html/admin/accordion_mysqli.php");
	require_once("engine/html/admin/accordion_mysqleditor.php");
	require_once("engine/html/admin/accordion_mysqleditor_modalform.php");
	require_once("engine/html/admin/accordion_mysqleditor_filters.php");
	require_once("engine/html/admin/accordion_array2select.php");
	require_once("engine/html/admin/accordion_new_printed_document.php");
	require_once("engine/html/admin/accordion_new_php_blank.php");
	require_once("engine/html/admin/accordion_table_joiner.php");
	require_once("engine/html/admin/accordion_session_print.php");
	require_once("engine/html/admin/accordion_form_jquery.php");
//    require_once ("engine/html/admin/accordion_GeyMachine.php");
//    require_once ("engine/html/admin/accordion_Plan.php");
	
	?>
	
	<?php
	
	if (count($request_params) > 0)
	{
		$RequestData = explode("/", $request_params);
		
		switch ($RequestData[0])
		{
			case "journalsearch":
			    bt_notice('Поиск по таблице-полю-фразе', BT_THEME_PRIMARY);
				require_once ("engine/html/admin/commands/journalsearch.php");
			break;
			case "dsnoted":
			    bt_notice('Перенос заметок', BT_THEME_PRIMARY);
				require_once ("engine/html/admin/commands/dsnoted.php");
            break;
			case "gethashpassword":
			    bt_notice('Новый рандомный пароль для системы из ключевого слова', BT_THEME_PRIMARY);
				debug(getHashPassword($RequestData[1]));
				break;
			case "synchronizeJournal":
				bt_notice('Какая-то фигня недоделанная для журнала', BT_THEME_PRIMARY);
				require_once ("engine/html/admin/commands/synchronizeJournal.php");
				$go_synchronize = true;
				break;
			case "synchronizeResearch":
				bt_notice('Синхронизация обследований и имён', BT_THEME_PRIMARY);
				require_once ("engine/html/admin/commands/synchronizeResearch.php");
				$go_synchronize = true;
				break;
			case "birth2unix":
				bt_notice(wrapper('ОБНОВЛЕНИЕ ДАТЫ РОЖДЕНИЯ'), BT_THEME_PRIMARY);
				require_once ("engine/html/admin/commands/birth2unix.php");
            break;
			case "defectivenames":
				bt_notice(wrapper('ДЕФЕКТНЫЕ ИМЕНА'), BT_THEME_PRIMARY);
				require_once ("engine/html/admin/commands/defectivenames.php");
            break;
			case "getrandomcatmachine":
				bt_notice(wrapper('Рандомный CAT'), BT_THEME_PRIMARY);
				debug(GetRandomCat(512));
				break;
			case "currentcat":
				bt_notice(wrapper('CAT компьютера'), BT_THEME_PRIMARY);
				debug($CAT_DATA);
				break;
			case "catgenerator":
				bt_notice('Добавляет рандомный CAT в базу', BT_THEME_PRIMARY);
				require_once ("engine/html/admin/commands/catgenerator.php");
				break;
            case "doctorbirth":
	            bt_notice(wrapper('Настройка дней рождений сотрудников'), BT_THEME_PRIMARY);
	            require_once ("engine/html/admin/commands/doctorbirth.php");
            break;
			case "distincter":
				bt_notice(wrapper('DISTINCTER'), BT_THEME_PRIMARY);
				require_once ("engine/html/admin/commands/distincter.php");
            break;
            case "dispancer":
	            bt_notice(wrapper('СТРУКТУРА ДИСПАНСЕРНЫХ ПАЦИЕНТОВ'), BT_THEME_PRIMARY);
	            require_once ("engine/html/admin/commands/dispancer.php");
            break;
            case "dispancerReset":
	            bt_notice(wrapper('СБРОС ДИСПАНСЕРНЫХ ПАЦИЕНТОВ'), BT_THEME_PRIMARY);
	            require_once ("engine/html/admin/commands/dispancerReset.php");
            break;
            case "move_dispancer":
	            bt_notice(wrapper('ПЕРЕНОС ДИСПАНСЕРНЫХ ПАЦИЕНТОВ'), BT_THEME_PRIMARY);
	            require_once ("engine/html/admin/commands/move_dispancer.php");
            break;
            case "myisam2innodb":
	            bt_notice(wrapper('Конвертация таблиц из MyISAM в InnoDB: /tomyisam, /toinnodb'), BT_THEME_PRIMARY);
	            require_once ("engine/html/admin/commands/myisam2innodb.php");
            break;
		}
		
		if ($go_synchronize)
		{
			if (count($JournalAll) == 1)
			{
				$JournalPatient = $JournalAll[0];
				
				debug($JournalPatient);
				$PatientNameData = explode(" ", $JournalPatient[$field_patient_name]);
				$PatientNameDataFormatted = array();
				$isFamilia = true;
				for ($i = 0; $i < count($PatientNameData); $i++)
				{
					$data = $PatientNameData[$i];
					if (strlen($data) > 0)
					{
						$data = mb_strtolower($data, UTF8);
						$data = nospaces($data);
						$data = str_replace(".", "", $data);
						$data = str_replace(",", "", $data);
						if (strlen($data) > 0)
						{
							if ($isFamilia) $isFamilia = false;
							else $data = firstLetter($data);
							
							$PatientNameDataFormatted[] = $data;
						}
					}
				}
				debug($PatientNameDataFormatted);

//                exit();
				
				$querySearchPercent = '%' . $PatientNameDataFormatted[0] . '%%' . $PatientNameDataFormatted[1] . '%%' . $PatientNameDataFormatted[2] . '%';
				$patientClearName = $PatientNameDataFormatted[0] . ' ' . $PatientNameDataFormatted[1] . ' ' . $PatientNameDataFormatted[2];
				
				$querySearch = str_replace('[QUERY_NAME]', $querySearchPercent, $querySearch);
				$querySearch = str_replace('[QUERY_BIRTH]', $JournalPatient[$field_patient_birth], $querySearch);
				$querySearch = str_replace('[QUERY_IDENT]', $JournalPatient[$field_patient_ident], $querySearch);
				
				debug($querySearch);
				$resultSearch = mqc($querySearch);
				$amountSearch = mnr($resultSearch);
				if ($amountSearch == 1)
				{
					bt_notice('Пациент уже есть в базе', BT_THEME_WARNING);
					$PatidData = mfa($resultSearch);
					debug($PatidData);
					
					$updateJournalData = array(
						$UPDATED_TABLE_PATID => $PatidData['patid_id']
					);
					
					$UpdateJournal = updateData($UPDATED_TABLE, $updateJournalData, array(), "{$UPDATED_TABLE_FID}='{$JournalPatient[$UPDATED_TABLE_FID]}'");
					debug($UpdateJournal);
					if ($UpdateJournal['stat'] == RES_SUCCESS)
					{
						bt_notice("Запись о пациенте в журнале была обновлена", BT_THEME_SUCCESS);
					} else
					{
						bt_notice('Запись в журнале не обновлена, ошибка',BT_THEME_DANGER);
						debug($UpdateJournal);
					}
					
				} else
				{
					if ($amountSearch == 0)
					{
						bt_notice('Пациента еще нет в базе');
						
						$NewPatid = array(
							'patid_ident' => $JournalPatient[$field_patient_ident],
							'patid_name' => $patientClearName,
							'patid_birth' => $JournalPatient[$field_patient_birth],
							'patid_phone' => $JournalPatient[$field_patient_phone],
							'patid_address' => $JournalPatient['journal_patient_address']
						);
						
						debug($NewPatid);
						
						$AppendPatid = appendData(CAOP_PATIENTS, $NewPatid);
						if ($AppendPatid[ID] > 0)
						{
							bt_notice('Пациент добавлен в базу!', BT_THEME_SUCCESS);
							$updateJournalData = array(
								'journal_patid' => $AppendPatid[ID]
							);
							
							$UpdateJournal = updateData(CAOP_JOURNAL, $updateJournalData, array(), "journal_id='{$JournalPatient['journal_id']}'");
							if ($UpdateJournal['stat'] == RES_SUCCESS)
							{
								bt_notice("Запись о пациенте в журнале была обновлена", BT_THEME_SUCCESS);
							} else
							{
								bt_notice('Запись в журнале не обновлена, ошибка',BT_THEME_DANGER);
								debug($UpdateJournal);
							}
						} else
						{
							bt_notice('Пациент в базу не добавлен, ошибка',BT_THEME_DANGER);
							debug($AppendPatid);
						}
						
					} else
					{
						if ($amountSearch > 1)
						{
							bt_notice('Слишком много совпадений, нам п%%да',BT_THEME_DANGER);
						}
					}
				}
			}
		}
	}
	
} else
{
	bt_notice('У Вас нет доступа к данному разделу',BT_THEME_DANGER);
}

require_once("engine/html/modals/adminTableNewItem.php");

?>

<script defer src="/engine/js/admin/admin.js?<?= rand(0, 999999); ?>" type="text/javascript"></script>
