<?php
$response['stage'] = $action;
$response['result'] = true;
$DailyByDB = '';
$DiaryHTML = '';
$SPOHTML = '';
$ResearchesHTML = '';
$RadioDirStac = '';
$patient_id = $_POST['patient_id'];
$response['htmlData'] = '';
if ($patient_id > 0)
{
	$PatientData = getarr(CAOP_JOURNAL, "journal_id='{$patient_id}'");
	if (count($PatientData) == 1)
	{
		$PatientData = $PatientData[0];
		
		$CitologyTypes = getarr(CAOP_CITOLOGY_TYPE, 1, "ORDER BY type_id ASC");
		$CitologyTypesId = getDoctorsById($CitologyTypes, 'type_id');
		
		$DirStacList = getarr(CAOP_DIRSTAC, "dirstac_enabled='1'", "ORDER BY dirstac_order ASC");
        $DirStacListId = getDoctorsById($DirStacList, 'dirstac_id');
		
		$PatientPersonalData = getarr(CAOP_PATIENTS, "patid_id='{$PatientData['journal_patid']}'");
		if (count($PatientPersonalData) == 1)
		{
			$response['debug']['$PatientData'] = $PatientData;
			
			if ($PatientData['journal_visit_type'] == 1)
			{
				$PatientPersonalData = $PatientPersonalData[0];
				
				$DispancerData = getarr(CAOP_DISP_PATIENTS, "dispancer_patid='{$PatientPersonalData[$PK[CAOP_PATIENTS]]}'");
				if ( count($DispancerData) > 0 )
				{
//					$response['htmlData'] .= debug_ret($PatientData);
//					$response['htmlData'] .= debug_ret($DispancerData);
					$DispancerDefault = array(
						'key' => 0,
						'value' => 'Данное посещение не относится к диспансерному диагнозу'
					);
					$DispancerSelected = array(
						'value' => $PatientData['journal_disp_self']
					);
					$DispancerSelector = array2select($DispancerData, 'dispancer_id', 'callback.func_dispancer', null,
						'class="form-control mysqleditor" data-action="edit"
						data-table="'.CAOP_JOURNAL.'"
						data-assoc="0"
						data-fieldid="'.$PK[CAOP_JOURNAL].'"
						data-id="'.$PatientData[$PK[CAOP_JOURNAL]].'"
						data-field="journal_disp_self"', $DispancerDefault, $DispancerSelected);
//					$response['htmlData'] .= $DispancerSelector['result'];
//					$response['debug']['$DispancerSelector'] = $DispancerSelector;
				}
				
				
				
				
				
				$PATID = $PatientPersonalData['patid_id'];
				$ResearchesData = getarr(CAOP_RESEARCH, "research_patid='{$PATID}'", "ORDER BY research_id DESC");
				$CitologyData = getarr(CAOP_CITOLOGY, "citology_patid='{$PATID}'");

//			$response['htmlData'] .= debug_ret($CitologyData);
				
				$ResearchesTotal = array_merge($ResearchesData, $CitologyData);
				
				$ResearchesTotalOrder = date_converter_for_array_order_by($ResearchesTotal, 'patidcard_patient_done');
//			$response['htmlData'] .= debug_ret($ResearchesTotalOrder);
				if ( $ResearchesTotalOrder ['result'] === true )
				{
					$ResearchesTotal = $ResearchesTotalOrder['data'];
					
					$ResearchesTotal = array_orderby($ResearchesTotal, 'patidcard_patient_done_index', SORT_DESC);
				} else
				{
					if ( $ResearchesTotalOrder['msg'] != 'Не указан массив' )
					{
						$response['htmlData'] .= debug_ret($ResearchesTotalOrder);
					}
				}
				
				
				
				$ResearchesHTML .= '
			<div class="dropdown-divider"></div>
			<div class="row">
				<div class="col">
					<button class="btn btn-primary col" onclick="javascript:AddQuickResearch(' . $patient_id . ')">Добавить обследование</button>
				</div>
				<div class="col">
					<button class="btn btn-primary col" onclick="javascript:AddQuickCitology(' . $patient_id . ')">Добавить цитологию</button>
				</div>
			</div>
			<div class="dropdown-divider"></div>';
				
				$ResearchTypes = getarr(CAOP_RESEARCH_TYPES, "type_enabled='1'", "ORDER BY type_order ASC");
				$ResearchTypesId = getDoctorsById($ResearchTypes, 'type_id');
				
				foreach ($ResearchesTotal as $researchItem)
				{
					if (isset($researchItem['citology_id']))
					{
						$ResearchesHTML .= spoiler_begin_return('Цитология №' . $researchItem['citology_result_id'] . ' от ' . $researchItem['patidcard_patient_done'], 'citology' . $researchItem['citology_id'], '');
						$ResearchesHTML .= $researchItem['citology_result_text'];
						$ResearchesHTML .= '
					<br/><br/>
					<!--<a target="_blank" href="/myCitology/light' . $researchItem['citology_id'] . '#citopat' . $researchItem['citology_id'] . '"><b>Редактировать</b></a>-->
					<a href="javascript:openCitologyCard(' . $researchItem['citology_id'] . ')"><b>Редактировать</b></a> |
					';
						$citology_type = '';
						if ($researchItem['citology_analise_type'] > 0)
						{
							$citology_type = ' ('.$CitologyTypesId['id' . $researchItem['citology_analise_type']]['type_title'].')';
						}
						$ResearchesHTML .= '<a class="cursor-pointer clickForCopy" style="width: 32px" data-target="citologyCopy'.$researchItem['citology_id'].'"><b>Копировать</b></a>';
						$response['htmlData'] .= '<textarea class="class-for-copy" id="citologyCopy' . $researchItem['citology_id'] . '">Цитология'.$citology_type.' №' . $researchItem['citology_result_id'] . ' от ' . $researchItem['patidcard_patient_done'] . ' - ' . $researchItem['citology_result_text'] . '</textarea>';
						$ResearchesHTML .= spoiler_end_return();
					}
					if (isset($researchItem['research_id']))
					{
						
						$ResearchStatusIcon = $ResearchStatusesIcons['id' . $researchItem['research_status']];
						$response['debug']['$ResearchStatusIcon'] = $ResearchStatusIcon;
						
						$ResearchTitle = $ResearchTypesId['id' . $researchItem['research_type']]['type_title'];
						$ResearchDesc = $ResearchTitle . ' ' . $researchItem['research_area'];
						$ResearchesHTML .= spoiler_begin_return($ResearchStatusIcon . ' ' . $ResearchDesc . ' от ' . $researchItem['patidcard_patient_done'], 'research' . $researchItem['research_id'], '');
						$ResearchesHTML .= $researchItem['research_result'];
						if (strlen($researchItem['research_morph_text']) > 0)
						{
							$ResearchesHTML .= '<div class="dropdown-divider"></div>';
							$ident_format = (strlen($researchItem['research_morph_ident']) > 0) ? $researchItem['research_morph_ident'] : 'нет';
							$date_format = (strlen($researchItem['research_morph_date']) > 0) ? ' от ' . $researchItem['research_morph_date'] : '';
							$ResearchesHTML .= '<b>Гистология</b> №' . $ident_format . $date_format . ' - ' . $researchItem['research_morph_text'];
						}
						$ResearchesHTML .= '
					<div class="dropdown-divider"></div>
					<!--<a target="_blank" href="/myqueue/light' . $researchItem['research_id'] . '#respat' . $researchItem['research_id'] . '"><b>Редактировать</b></a>-->
					<a href="javascript:openResearchCard(' . $researchItem['research_id'] . ')"><b>Редактировать</b></a> |
					';

//					$ResearchTypes = getDoctorsById($ResearchTypes, 'type_id');
						$ResearchType = $ResearchTypesId['id' . $researchItem['research_type']];
						$area = '';
						if ( strlen($researchItem['research_area']) > 1 ) $area = ' ' . $researchItem['research_area'];
						$cito = '';
						if ( $researchItem['research_cito'] == 2 ) $cito = ' (CITO)';
						$resDate = '';
						if ( strlen( $researchItem['patidcard_patient_done'] ) > 0 )
						{
							$resDate = ' от ' . $researchItem['patidcard_patient_done'];
						}
						
						$morph = getMorphOfResearch($researchItem);
						
						$ResearchesHTML .= '<a href="#" class="cursor-pointer clickForCopy" style="width: 32px" data-target="researchCopyArea'.$researchItem['research_id'].'"><b>Копировать</b></a>';
						$ResearchesHTML .= '<textarea class="class-for-copy" id="researchCopyArea'.$researchItem['research_id'].'">'.$ResearchType['type_title'].$area.$resDate.' - '.$researchItem['research_result'].$morph.'</textarea>';
						$ResearchesHTML .= spoiler_end_return();
					}
					
				}


//			$ResearchesHTML .= spoiler_end_return();

//			$DiaryTAData = mysqleditor_textarea_generator(CAOP_JOURNAL, 'journal_id', $PatientData['journal_id'], 'journal_diary', '0', '', ' rows="10"', 'textarea', 'form-control form-control-sm mysqleditor autosizer', 'diary', 'СКОПИРУЙТЕ СЮДА ТЕКСТ ДНЕВНИКА', $PatientData['journal_diary'], 'edit', '0');
//			$DiaryHTML .= spoiler_begin_return('Текст дневника', 'diary' . $PatientData['journal_id']);
//			$DiaryHTML .= $DiaryTAData['textarea'];
//			$DiaryHTML .= spoiler_end_return();

//			$DailyByDB .= spoiler_begin_return('Форма дневника <b>(если ЕМИАС не работает)</b>', 'daily' . $PatientData['journal_id']);
				if ($PatientData['journal_daily_id'] == 0)
				{
					$DailyByDB .= '<button class="btn btn-sm btn-primary" onclick="javascript:addDaily(' . $PatientData['journal_id'] . ')">Добавить дневник</button>';
				} else
				{
					$DailyByDB .= '<button class="btn btn-sm btn-warning" onclick="javascript:deleteDaily(' . $PatientData['journal_id'] . ')">Удалить дневник</button> ';
					$DailyByDB .= '<button class="btn btn-sm btn-secondary" onclick="javascript:updateDaily()">Обновить</button> ';
					$DailyByDB .= '<button class="btn btn-sm btn-primary" onclick="javascript:window.open(\'/dailysPrint/' . $PatientData['journal_daily_id'] . '\')">Печатать</button>';
					
					$DailyData = getarr(CAOP_DAILY, "daily_id='{$PatientData['journal_daily_id']}'");
					if (count($DailyData) > 0)
					{
						$DailyData = $DailyData[0];
						
						$BLANK_TABLE_FIELD_ID = 'daily_id';
						$BLANK_TABLE_LIST = CAOP_DAILY;
						$BLANK_TABLE_FIELD_UPDATE = 'daily_date_update_unix';
						
						$DailyByDB .= NewFormItem(
							'Тип приёма:',
							'daily_type',
							'required-field',
							$BLANK_TABLE_FIELD_ID,
							'daily_type',
							$BLANK_TABLE_LIST,
							$DailyData[$BLANK_TABLE_FIELD_ID],
							$BLANK_TABLE_FIELD_UPDATE,
							'Выберите тип приёма',
							$DailyData['daily_type'],
							'',
							'select',
							array(
								'data' => array(
									array(
										'value' => 'Первичный',
										'title' => 'Первичный'
									),
									array(
										'value' => 'Повторный',
										'title' => 'Повторный'
									)
								)
							),
							null,
							false
						);
						
						$DailyByDB .= NewFormItem(
							'Жалобы:',
							'daily_complains',
							'required-field',
							$BLANK_TABLE_FIELD_ID,
							'daily_complains',
							$BLANK_TABLE_LIST,
							$DailyData[$BLANK_TABLE_FIELD_ID],
							$BLANK_TABLE_FIELD_UPDATE,
							'на что жалуется пациент',
							$DailyData['daily_complains'],
							'',
							'textarea',
							null,
							null,
							false
						);
						
						$DailyByDB .= NewFormItem(
							'Анамнез заболевания:',
							'daily_anam_disease',
							'required-field',
							$BLANK_TABLE_FIELD_ID,
							'daily_anam_disease',
							$BLANK_TABLE_LIST,
							$DailyData[$BLANK_TABLE_FIELD_ID],
							$BLANK_TABLE_FIELD_UPDATE,
							'Считает себя больным(-ой) ...',
							$DailyData['daily_anam_disease'],
							'',
							'textarea',
							null,
							null,
							false
						);
						
						$DailyByDB .= NewFormItem(
							'Результаты исследований:',
							'daily_researches',
							'required-field',
							$BLANK_TABLE_FIELD_ID,
							'daily_researches',
							$BLANK_TABLE_LIST,
							$DailyData[$BLANK_TABLE_FIELD_ID],
							$BLANK_TABLE_FIELD_UPDATE,
							'УЗИ, КТ, МРТ ...',
							$DailyData['daily_researches'],
							'',
							'textarea',
							null,
							null,
							false
						);
						
						$DailyByDB .= NewFormItem(
							'Анамнез жизни:',
							'daily_anam_life',
							'required-field',
							$BLANK_TABLE_FIELD_ID,
							'daily_anam_life',
							$BLANK_TABLE_LIST,
							$DailyData[$BLANK_TABLE_FIELD_ID],
							$BLANK_TABLE_FIELD_UPDATE,
							'Рос и развивался ...',
							$DailyData['daily_anam_life'],
							'',
							'textarea',
							null,
							null,
							false
						);
						
						$DailyByDB .= NewFormItem(
							'Аллергологический анамнез:',
							'daily_anam_allergy',
							'',
							$BLANK_TABLE_FIELD_ID,
							'daily_anam_allergy',
							$BLANK_TABLE_LIST,
							$DailyData[$BLANK_TABLE_FIELD_ID],
							$BLANK_TABLE_FIELD_UPDATE,
							'',
							$DailyData['daily_anam_allergy'],
							'',
							'textarea',
							null,
							null,
							false
						);
						
						$DailyByDB .= NewFormItem(
							'Семейный анамнез:',
							'daily_anam_family',
							'required-field',
							$BLANK_TABLE_FIELD_ID,
							'daily_anam_family',
							$BLANK_TABLE_LIST,
							$DailyData[$BLANK_TABLE_FIELD_ID],
							$BLANK_TABLE_FIELD_UPDATE,
							'',
							$DailyData['daily_anam_family'],
							'',
							'textarea',
							null,
							null,
							false
						);
						
						$DailyByDB .= NewFormItem(
							'Объективный статус:',
							'daily_presens',
							'required-field',
							$BLANK_TABLE_FIELD_ID,
							'daily_presens',
							$BLANK_TABLE_LIST,
							$DailyData[$BLANK_TABLE_FIELD_ID],
							$BLANK_TABLE_FIELD_UPDATE,
							'',
							$DailyData['daily_presens'],
							'',
							'textarea',
							null,
							null,
							false
						);
						
						$DailyByDB .= NewFormItem(
							'Локальный статус:',
							'daily_local',
							'',
							$BLANK_TABLE_FIELD_ID,
							'daily_local',
							$BLANK_TABLE_LIST,
							$DailyData[$BLANK_TABLE_FIELD_ID],
							$BLANK_TABLE_FIELD_UPDATE,
							'',
							$DailyData['daily_local'],
							'',
							'textarea',
							null,
							null,
							false
						);
						
						$DailyByDB .= NewFormItem(
							'Диагноз МКБ:',
							'daily_main_dg_mkb',
							'required-field mkbDiagnosis',
							$BLANK_TABLE_FIELD_ID,
							'daily_main_dg_mkb',
							$BLANK_TABLE_LIST,
							$DailyData[$BLANK_TABLE_FIELD_ID],
							$BLANK_TABLE_FIELD_UPDATE,
							'',
							$DailyData['daily_main_dg_mkb'],
							'data-adequate="MKB" data-return="#daily_main_dg_mkb" data-returntype="input" data-returnfunc="value"',
							'input',
							null,
							null,
							false
						);
						
						$DailyByDB .= NewFormItem(
							'Текст диагноза:',
							'daily_main_dg_text',
							'required-field',
							$BLANK_TABLE_FIELD_ID,
							'daily_main_dg_text',
							$BLANK_TABLE_LIST,
							$DailyData[$BLANK_TABLE_FIELD_ID],
							$BLANK_TABLE_FIELD_UPDATE,
							'',
							$DailyData['daily_main_dg_text'],
							'',
							'textarea',
							null,
							null,
							false
						);
						
						$DailyByDB .= spoiler_begin_return('Сопутствующие диагнозы', 'addon_ds');
						
						$DailyByDB .= NewFormItem(
							'Сопутствующий МКБ №1:',
							'daily_add1_dg_mkb',
							'required-field mkbDiagnosis',
							$BLANK_TABLE_FIELD_ID,
							'daily_add1_dg_mkb',
							$BLANK_TABLE_LIST,
							$DailyData[$BLANK_TABLE_FIELD_ID],
							$BLANK_TABLE_FIELD_UPDATE,
							'',
							$DailyData['daily_add1_dg_mkb'],
							'data-adequate="MKB" data-return="#daily_add1_dg_mkb" data-returntype="input" data-returnfunc="value"',
							'input',
							null,
							null,
							false
						);
						
						$DailyByDB .= NewFormItem(
							'Текст сопутствующего диагноза №1:',
							'daily_add1_dg_text',
							'required-field',
							$BLANK_TABLE_FIELD_ID,
							'daily_add1_dg_text',
							$BLANK_TABLE_LIST,
							$DailyData[$BLANK_TABLE_FIELD_ID],
							$BLANK_TABLE_FIELD_UPDATE,
							'',
							$DailyData['daily_add1_dg_text'],
							'',
							'textarea',
							null,
							null,
							false
						);
						
						$DailyByDB .= NewFormItem(
							'Сопутствующий МКБ №2:',
							'daily_add2_dg_mkb',
							'required-field mkbDiagnosis',
							$BLANK_TABLE_FIELD_ID,
							'daily_add2_dg_mkb',
							$BLANK_TABLE_LIST,
							$DailyData[$BLANK_TABLE_FIELD_ID],
							$BLANK_TABLE_FIELD_UPDATE,
							'',
							$DailyData['daily_add2_dg_mkb'],
							'data-adequate="MKB" data-return="#daily_add2_dg_mkb" data-returntype="input" data-returnfunc="value"',
							'input',
							null,
							null,
							false
						);
						
						$DailyByDB .= NewFormItem(
							'Текст сопутствующего диагноза №2:',
							'daily_add2_dg_text',
							'required-field',
							$BLANK_TABLE_FIELD_ID,
							'daily_add2_dg_text',
							$BLANK_TABLE_LIST,
							$DailyData[$BLANK_TABLE_FIELD_ID],
							$BLANK_TABLE_FIELD_UPDATE,
							'',
							$DailyData['daily_add2_dg_text'],
							'',
							'textarea',
							null,
							null,
							false
						);
						
						$DailyByDB .= spoiler_end_return();
						
						$DailyByDB .= NewFormItem(
							'Рекомендации по диагнозу:',
							'daily_recom',
							'required-field',
							$BLANK_TABLE_FIELD_ID,
							'daily_recom',
							$BLANK_TABLE_LIST,
							$DailyData[$BLANK_TABLE_FIELD_ID],
							$BLANK_TABLE_FIELD_UPDATE,
							'Рекомендации',
							$DailyData['daily_recom'],
							'',
							'textarea',
							null,
							null,
							false
						);
						
						$DailyByDB .= spoiler_begin_return('Дополнительные рекомендации', 'addon_recoms');
						
						$DailyByDB .= NewFormItem(
							'Рекомендации сопутствующему по диагнозу:',
							'daily_recom_follow',
							'required-field',
							$BLANK_TABLE_FIELD_ID,
							'daily_recom_follow',
							$BLANK_TABLE_LIST,
							$DailyData[$BLANK_TABLE_FIELD_ID],
							$BLANK_TABLE_FIELD_UPDATE,
							'Рекомендации (если есть)',
							$DailyData['daily_recom_follow'],
							'',
							'textarea',
							null,
							null,
							false
						);
						
						$DailyByDB .= NewFormItem(
							'Дополнительный текст:',
							'daily_addon',
							'required-field',
							$BLANK_TABLE_FIELD_ID,
							'daily_addon',
							$BLANK_TABLE_LIST,
							$DailyData[$BLANK_TABLE_FIELD_ID],
							$BLANK_TABLE_FIELD_UPDATE,
							'Просто текст, если надо',
							$DailyData['daily_addon'],
							'',
							'textarea',
							null,
							null,
							false
						);
						
						$DailyByDB .= spoiler_end_return();
						
						$DailyByDB .= NewFormItem(
							'Дата/время заполнения:',
							'daily_date_create',
							'required-field',
							$BLANK_TABLE_FIELD_ID,
							'daily_date_create',
							$BLANK_TABLE_LIST,
							$DailyData[$BLANK_TABLE_FIELD_ID],
							$BLANK_TABLE_FIELD_UPDATE,
							'Дата заполнения дневника',
							$DailyData['daily_date_create'],
							'',
							'input',
							null,
							null,
							false
						);
						
					} else
					{
						$DailyByDB .= '<button class="btn btn-sm btn-primary" onclick="javascript:addDaily(' . $PatientData['journal_id'] . ')">Добавить дневник!</button>';
					}
				}
//			$DailyByDB .= spoiler_end_return();
				
				$DayData = getarr(CAOP_DAYS, "day_id='{$PatientData['journal_day']}'");
				if (count($DayData) == 1)
				{
					$DayData = $DayData[0];
					$day_date = $DayData['day_date'];
				}
//			$RadioDirStac .= '<div class="row">';
				$first_flag = false;
				$bl = '';
				
				$prechecked = ($PatientData['journal_dirstac'] == 0) ? ' checked' : '';
				$prebgs = ($PatientData['journal_dirstac'] == 0) ? ' bg-info' : '';
				
				$RadioDirStac .= '
					<div class="row' . $prebgs . ' ">
						<div class="col">
							<input' . $prechecked . '
								class="form-check-input mysqleditor"
								type="radio"
								name="dirstac' . $PatientData['journal_id'] . '"
								id="dirstac_none"
								value="0"
								data-action="edit"
								data-table="caop_journal"
								data-assoc="0"
								data-fieldid="journal_id"
								data-id="' . $PatientData['journal_id'] . '"
								data-field="journal_dirstac"
								data-return="0"
								data-callbackfunc="closeLPUPanel"
								data-callbackparams="' . $PatientData['journal_id'] . '"
								data-callbackcond="success"
								placeholder="">
							<label class="form-check-label box-label" for="dirstac_none"><span></span>Никуда</label>
						</div>
					</div>';
				
				foreach ($DirStacListId as $dirStac)
				{
					$desc = ( strlen($dirStac['dirstac_extended']) > 0 ) ? '<div class="text-muted small">• '.$dirStac['dirstac_extended'].'</div>' : '';
					
					$checked = ($PatientData['journal_dirstac'] == $dirStac['dirstac_id']) ? ' checked' : '';
					$bgs = ($PatientData['journal_dirstac'] == $dirStac['dirstac_id']) ? ' bg-info' : '';
					$RadioDirStac .= '
					<br>
					<div class="row">
						<div class="col border-left' . $bgs . '">
							<input' . $checked . '
								class="form-check-input mysqleditor"
								type="radio"
								name="dirstac' . $PatientData['journal_id'] . '"
								id="dirstac' . $dirStac['dirstac_id'] . '"
								value="' . $dirStac['dirstac_id'] . '"
								data-action="edit"
								data-table="caop_journal"
								data-assoc="0"
								data-fieldid="journal_id"
								data-id="' . $PatientData['journal_id'] . '"
								data-field="journal_dirstac"
								data-return="0"
								data-callbackfunc="closeLPUPanel"
								data-callbackparams="' . $PatientData['journal_id'] . '"
								data-callbackcond="success"
								placeholder="">
							<label class="form-check-label box-label" for="dirstac' . $dirStac['dirstac_id'] . '">
								<span></span><b>'.$dirStac['dirstac_title'].'</b> - <small>' . $dirStac['dirstac_desc'] . '</small>
							</label>
							'.$desc.'
						</div>
					</div>
					
					';
				};
//			$RadioDirStac .= '<div class="row">';
				
				// ВСЕ СПО пациента
				$SPOData = getarr(CAOP_SPO, "spo_patient_id='{$PatientData['journal_patid']}'", "ORDER BY spo_start_date_unix DESC");
				if ( count($SPOData) > 0 )
				{
					$SPOHTML .= '
				<button id="addNewSPO" class="btn btn-sm btn-primary" onclick="javascript:journalSPO_addNew('.$PatientData['journal_id'].')">Создать новое СПО</button>
				<div class="dropdown-divider"></div>
				<script>
					$( document ).ready(function()
					{
					    spo_loader('.$PatientData['journal_spo_id'].', '.$PatientData['journal_id'].')
					});
				</script>
				';
					$SPODefault = array(
						'key' => 0,
						'value' => 'Выберите СПО'
					);
					$SPOSelected = array(
						'value' => $PatientData['journal_spo_id']
					);
					$SPOSelector = array2select($SPOData, 'spo_id', 'callback.func_spo', null,
						'id="journal_spo_id" class="form-control mysqleditor" data-action="edit"
				data-table="'.CAOP_JOURNAL.'"
				data-assoc="0"
				data-fieldid="'.$PK[CAOP_JOURNAL].'"
				data-id="'.$PatientData['journal_id'].'"
				data-field="journal_spo_id"
				data-callbackfunc="spo_loader"
				data-callbackparams="self;#general_journal_id"
				', $SPODefault, $SPOSelected);
					$SPOHTML .= $SPOSelector['result'];
					$SPOHTML .= '<div id="spo_result"></div>';
				} else
				{
					$SPOHTML .= bt_notice('У пациента еще нет ни одного случая посещения. <a href="javascript:journalSPO_addNew('.$PatientData['journal_id'].')">'.wrapper('Добавить').'</a>', BT_THEME_WARNING, 1);
				}
				
				
				if ($DayData['day_signature_state'] == 1)
				{
					require_once("engine/php/processor/include/journalCardSigned.php");
				} else
				{
					require_once("engine/php/processor/include/journalCardUnsigned.php");
				}
			} else
			{
				if ( $PatientData['journal_visit_type'] == 2 )
				{
					$response['htmlData'] .= bt_notice('Данный визит указан, как "Телемедицина"', BT_THEME_WARNING, 1);
					$response['htmlData'] .= bt_divider(1);
					$response['htmlData'] .= '
					<button class="btn btn-primary btn-setVisitType" data-type="1" data-journal="'.$PatientData['journal_id'].'">Преобразовать в "Посещение"</button>
					';
					
				} else
				{
					$response['htmlData'] .= bt_notice('ТИП ВИЗИТА НЕ ОПОЗНАН', BT_THEME_WARNING, 1);
					$response['htmlData'] .= '
					<button class="btn btn-primary btn-setVisitType" data-type="1" data-journal="'.$PatientData['journal_id'].'">Преобразовать в "Посещение"</button>
					';
				}
			}
			
		} else
		{
			$response['htmlData'] = 'Пациент отсутствует в базе данных';
		}
		
	} else
	{
		$response['msg'] = 'Такого пациента нет';
	}
	
} else
{
	$response['msg'] = "ID не указан";
}



