<?php
$min_date = birthToUnix('01.01.2023');

$Researches = getarr(CAOP_RESEARCH, "research_unix>='{$min_date}'");
$Citologies = getarr(CAOP_CITOLOGY, "citology_dir_date_unix>='{$min_date}'");

$Total = array_merge($Researches, $Citologies);

//debug($Total[0]);

if ($Total > 0)
{
	$SurveyResultTypes2 = getarr(CAOP_CITOLOGY_CANCER_TYPE, 1, "ORDER BY type_order ASC");
	
	$ResearchTypes = getarr(CAOP_RESEARCH_TYPES, "type_enabled='1'", "ORDER BY type_order ASC");
	$ResearchTypesId = getDoctorsById($ResearchTypes, 'type_id');
	
	$CitologyTypes = getarr(CAOP_CITOLOGY_TYPE, 1, "ORDER BY type_id ASC");
	$CitologyTypesId = getDoctorsById($CitologyTypes, 'type_id');
	
	
	
	bt_notice(wrapper('Всего исследований: ') . count($Total), BT_THEME_SUCCESS);
	bt_divider();
	
	$error_surveys = [];
	$npp = count($Total);
	?>
	<table class="table table-sm small">
	    <thead>
	        <tr>
	            <th scope="col" class="text-center" data-title="npp">№</th>
	            <th scope="col" class="text-center" data-title="patid">patid</th>
	            <th scope="col" class="text-center" data-title="ds">ds</th>
	            <th scope="col" class="text-center" data-title="survey">survey</th>
	            <th scope="col" class="text-center" data-title="type">type</th>
	            <th scope="col" class="text-center" data-title="result">result</th>
	            <th scope="col" class="text-center" data-title="morph">morph</th>
	            <th scope="col" class="text-center" data-title="morph_type">morph_type</th>
	            <th scope="col" class="text-center" data-title="doctor">doctor</th>
	            <th scope="col" class="text-center" data-title="cancer_result">cancer_result</th>
	            <th scope="col" class="text-center" data-title="action" width="10%">action</th>
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
		        $survey_ds_mkb = $survey_item['research_ds'];
		        $survey_ds_text = $survey_item['research_ds_text'];
		        $survey_create_at = $survey_item['research_unix'];
		        $survey_morph = $survey_item['research_morph_text'];
		        $survey_result = $survey_item['research_result'];
		        $survey_doctor = $survey_item['research_doctor_id'];
		        $survey_cancer = $survey_item['research_cancer'];
		        $survey_cancer_field = 'research_cancer';
		        $survey_research_type = $ResearchTypesId['id' . $survey_item['research_type']]['type_morph'];
		        $go_next = true;
	        } elseif ( $survey_item['citology_id'] > 0 )
	        {
		        $survey = "Морфология";
		        $survey_id = $survey_item['citology_id'];
		        $patient_id = $survey_item['citology_patid'];
		        $survey_table = CAOP_CITOLOGY;
		        $survey_type_table = $CitologyTypesId;
		        $survey_type_id = $survey_item['citology_analise_type'];
		        $survey_ds_mkb = $survey_item['citology_ds_mkb'];
		        $survey_ds_text = $survey_item['citology_ds_text'];
		        $survey_create_at = $survey_item['citology_dir_date_unix'];
		        $survey_morph = '---';
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
	        	
		        ?>
		        <tr>
			        <td data-cell="npp"><?=$npp;?></td>
			        <td data-cell="patid"><?=$patient_id;?></td>
			        <td data-cell="ds"><span <?=super_bootstrap_tooltip($survey_ds_text);?>><?=$survey_ds_mkb;?></span></td>
			        <td data-cell="survey"><?=$survey;?></td>
			        <td data-cell="type"><?=$survey_name;?></td>
			        <td data-cell="result"><?=$survey_result;?></td>
			        <td data-cell="morph"><?=$survey_morph;?></td>
			        <td data-cell="morph_type"><?=$survey_research_type;?></td>
			        <td data-cell="doctor"><?=$survey_doctor;?></td>
			        <td data-cell="cancer_result"><?=$survey_cancer;?></td>
			        <td data-cell="action">
			        <?php
			        for ($index=0; $index<count($SurveyResultTypes); $index++)
			        {
				        $checked = ( $survey_cancer === $SurveyResultTypes[$index]['type_id'] ) ? ' checked' : '';
				        $title = $SurveyResultTypes[$index]['type_title'];
				        
				        ?>
				        <div class="d-inline-block">
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