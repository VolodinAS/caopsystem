<?php
if ($Total > 0)
{
	bt_notice(wrapper('Исследований: ') . count($Total), BT_THEME_SUCCESS);
	bt_divider();
	
	$error_surveys = [];
	$npp = count($Total);
	$action_header = ($is_doc_head) ? '' : '<th scope="col" class="text-center" data-title="action" width="10%">Действие</th>';
	$doctor_header = ( $USER_PROFILE['doctor_id'] == 1 ) ? '<th scope="col" class="text-center" data-title="doctor">Врач</th>' : '';
	?>
	<table class="table">
		<thead>
		<tr>
			<th scope="col" class="text-center" data-title="npp">№</th>
			<th scope="col" class="text-center" data-title="patid">Ф.И.О. пациента</th>
			<th scope="col" class="text-center" data-title="ds">МКБ</th>
			<th scope="col" class="text-center" data-title="survey">Тип обследования</th>
			<th scope="col" class="text-center" data-title="type">Назначение</th>
			<th scope="col" class="text-center" data-title="result" width="25%">Заключение</th>
			<th scope="col" class="text-center" data-title="morph" width="25%">Морфология?</th>
<!--			<th scope="col" class="text-center" data-title="morph_type">Тип морфологии</th>-->
<!--			<th scope="col" class="text-center" data-title="cancer_result">Результат морфологии</th>-->
			<?=$action_header;?>
            <?=$doctor_header;?>
		</tr>
		</thead>
		<tbody>
		<?php
		
		foreach ($Total as $survey_item)
		{
			$SurveyResultTypes = $SurveyResultTypes2;
			$go_next = false;
			if ( $survey_item['research_id'] > 0 )
			{
				$survey = "Обследование";
				$survey_id = $survey_item['research_id'];
				$patient_id = $survey_item['research_patid'];
				$survey_table = CAOP_RESEARCH;
				$survey_type_table = $ResearchTypesId;
				$survey_type_id = $survey_item['research_type'];
				$survey_area = $survey_item['research_area'];
				$survey_ds_mkb = $survey_item['research_ds'];
				$survey_ds_text = $survey_item['research_ds_text'];
				$survey_create_at = $survey_item['research_unix'];
				$survey_morph = ( strlen($survey_item['research_morph_text']) ) ? $survey_item['research_morph_text'] : 'пока не указано';
				$survey_result = $survey_item['research_result'];
				$survey_doctor = $survey_item['research_doctor_id'];
				$survey_cancer = $survey_item['research_cancer'];
				$survey_cancer_field = 'research_cancer';
				$survey_research_type = $ResearchTypesId['id' . $survey_item['research_type']]['type_morph'];
				$go_next = true;
				if ( $survey_research_type != 1 )
                {
                    $survey_morph = '<span class="text-muted">см. "Заключение"</span>';
                }
			} elseif ( $survey_item['citology_id'] > 0 )
			{
				$survey = "Морфология";
				$survey_id = $survey_item['citology_id'];
				$patient_id = $survey_item['citology_patid'];
				$survey_table = CAOP_CITOLOGY;
				$survey_type_table = $CitologyTypesId;
				$survey_type_id = $survey_item['citology_analise_type'];
				$survey_area = '';
				$survey_ds_mkb = $survey_item['citology_ds_mkb'];
				$survey_ds_text = $survey_item['citology_ds_text'];
				$survey_create_at = $survey_item['citology_dir_date_unix'];
				$survey_morph = '<span class="text-muted">см. "Заключение"</span>';
				$survey_result = $survey_item['citology_result_text'];
				$survey_doctor = $survey_item['citology_doctor_id'];
				$survey_cancer = $survey_item['citology_cancer'];
				$survey_cancer_field = 'citology_cancer';
				$survey_research_type = 1;
				
				$go_next = true;
			} else $error_surveys[] = $survey_item;
			
			if ( $go_next )
			{
				if ( $survey_research_type != 1 ) unset($SurveyResultTypes[1]);
				$SurveyResultTypes = array_values($SurveyResultTypes);
				
				$survey_name = $survey_type_table['id' . $survey_type_id]['type_title'];
//		        $survey_name = debug_ret($survey_type_table);
				if ( strlen($survey_area) > 0 )
				{
					$survey_name = $survey_name . ' ' . $survey_area;
				}
				$PatientLink = editPersonalDataLink(shorty($survey_item['patid_name']) . ', ' . $survey_item['patid_birth'] . ' г.р.', $patient_id);
				$DoctorData = $DoctorsListId[ 'id' . $survey_doctor ];
				$doctor_td = ( $USER_PROFILE['doctor_id'] == 1 ) ? '<td data-cell="doctor">'.docNameShort($DoctorData).'</td>' : '';
				?>
				<tr id="survey_tr_<?=$survey_id;?>">
					<td data-cell="npp" class="full-center"><?=$npp;?></td>
					<td data-cell="patid"><?=$PatientLink;?></td>
					<td data-cell="ds" class="full-center"><span <?=super_bootstrap_tooltip($survey_ds_text);?>><?=$survey_ds_mkb;?></span></td>
					<td data-cell="survey" class="full-center"><?=$survey;?></td>
					<td data-cell="type"><?=$survey_name;?></td>
					<td data-cell="result"><?=$survey_result;?></td>
					<td data-cell="morph"><?=$survey_morph;?></td>
<!--					<td data-cell="morph_type" class="full-center">--><?//=$survey_research_type;?><!--</td>-->
<!--					<td data-cell="cancer_result" class="full-center">--><?//=$survey_cancer;?><!--</td>-->
                    <?php
                    if (!$is_doc_head)
                    {
                        ?>
                        <td data-cell="action">
		                    <?php
		                    for ($index=0; $index<count($SurveyResultTypes); $index++)
		                    {
			                    $checked = ( $survey_cancer === $SurveyResultTypes[$index]['type_id'] ) ? ' checked' : '';
			                    $title = $SurveyResultTypes[$index]['type_title'];
			
			                    ?>
                                <div class="mb-2 mt-2">
                                    <input <?=$checked;?>
                                            class="form-check-input mysqleditor"
                                            type="radio"
                                            name="survey_<?=$survey_id;?>"
                                            id="survey_<?=$survey_id;?>_<?=$SurveyResultTypes[$index]['type_id'];?>"
                                            value="<?=$SurveyResultTypes[$index]['type_id'];?>"
                                            data-action="edit"
                                            data-table="<?=$survey_table;?>"
                                            data-assoc="0"
                                            data-fieldid="<?=$PK[$survey_table];?>"
                                            data-id="<?=$survey_id;?>"
                                            data-field="<?=$survey_cancer_field;?>"
                                            data-callbackfunc="surveySetSecondary"
                                            data-callbackparams="<?=$survey_id;?>"
                                            data-callbackcond="success"
                                    >
                                    <label class="form-check-label box-label" for="survey_<?=$survey_id;?>_<?=$SurveyResultTypes[$index]['type_id'];?>">
                                        <span></span>
					                    <?=$title;?>
                                    </label>
                                </div>
			                    <?php
			
		                    }
		                    ?>
                        </td>
                        <?php
                    }
                    ?>
					<?=$doctor_td;?>
				</tr>
				<?php
				$npp--;
			}
		}
		
		if ( count($error_surveys) > 0 )
		{
			bt_notice('Имеются ошибочные обследования', BT_THEME_WARNING);
			debug($error_surveys);
		}
		?>
		</tbody>
	</table>
	<?php
	
} else bt_notice('Обследований не найдено');