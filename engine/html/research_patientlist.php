<!--<div class="form-group row">-->

<?php
$table_bg = '';
switch ($Patient['research_status'])
{
    case "4":
        $table_bg = 'table-dark';
        break;
    case "2":
        $table_bg = 'table-primary';
        break;
    case "3":
        $table_bg = 'table-success';
        break;
}

$CITO = '';
if ( $Patient['research_cito'] == 2 ) $CITO = 'table-warning';

$table_bg = ( $light_id == $Patient['research_id'] ) ? 'bg-success' : $table_bg;
?>

<tr data-resid="<?=$Patient['research_id'];?>" class="<?=$CITO;?> patient<?=$Patient['research_id'];?> patient-hidden <?=$table_bg;?>">
    <td class="">
        <b><?=$npp;?>)</b>
    </td>
    <td>
        <button type="button" class="btn btn-primary btn-sm openCard" data-patient="<?=$Patient['research_id'];?>">
	        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pen-fill" viewBox="0 0 16 16">
		        <path d="M13.498.795l.149-.149a1.207 1.207 0 1 1 1.707 1.708l-.149.148a1.5 1.5 0 0 1-.059 2.059L4.854 14.854a.5.5 0 0 1-.233.131l-4 1a.5.5 0 0 1-.606-.606l1-4a.5.5 0 0 1 .131-.232l9.642-9.642a.5.5 0 0 0-.642.056L6.854 4.854a.5.5 0 1 1-.708-.708L9.44.854A1.5 1.5 0 0 1 11.5.796a1.5 1.5 0 0 1 1.998-.001z"/>
	        </svg>
        </button>
    </td>
	<td>
		<?=date('d.m.Y', $Patient['research_unix']);?><br/>
	</td>
	<td>
<!--        <div data-type="fioForSort" style="display: none">--><?//=shorty($PatientPersonalData['patid_name']);?><!--</div>-->
<!--		<input type="text"-->
<!--		       class="mysqleditor form-control form-control input-padding"-->
<!--		       value="--><?//=shorty($PatientPersonalData['patid_name']);?><!--"-->
<!--		       data-action="edit"-->
<!--		       data-table="--><?//=$CAOP_PATIENTS;?><!--"-->
<!--		       data-assoc="0"-->
<!--		       data-fieldid="patid_id"-->
<!--		       data-id="--><?//=$PatientPersonalData['patid_id'];?><!--"-->
<!--		       data-field="patid_name"-->
<!--               --><?//=super_bootstrap_tooltip( shorty($PatientPersonalData['patid_name']) );?>
<!--		       placeholder="Имя пациента">-->

        <?=editPersonalDataLink(shorty($PatientPersonalData['patid_name']), $PatientPersonalData['patid_id']);?>
		<a name="respat<?=$Patient['research_id'];?>"></a>
	</td>
	<td>
<!--        <div data-type="fioForSort" style="display: none">--><?//=strtotime($PatientPersonalData['patid_birth']);?><!--</div>-->
<!--		<input type="text"-->
<!--		       class="mysqleditor form-control form-control input-padding russianBirth"-->
<!--		       value="--><?//=$PatientPersonalData['patid_birth'];?><!--"-->
<!--		       data-action="edit"-->
<!--		       data-table="--><?//=$CAOP_PATIENTS;?><!--"-->
<!--		       data-assoc="0"-->
<!--		       data-fieldid="patid_id"-->
<!--		       data-id="--><?//=$PatientPersonalData['patid_id'];?><!--"-->
<!--		       data-field="patid_birth"-->
<!--		       placeholder="Дата рождения">-->
        <?=$PatientPersonalData['patid_birth'];?>
	</td>
	<td>
		<div class="text-hide"><?=$Patient['research_ds'];?></div>
		<input type="text"
		       class="mysqleditor form-control form-control input-padding mkbDiagnosis required-field"
		       value="<?=$Patient['research_ds'];?>"
		       data-action="edit"
		       data-table="caop_research"
		       data-assoc="0"
		       data-fieldid="research_id"
		       data-id="<?=$Patient['research_id'];?>"
		       data-field="research_ds"
		       placeholder="Диагноз">
	</td>
	<td>
		<input type="text"
		       class="mysqleditor form-control form-control input-padding required-field"
		       value="<?=$Patient['research_ds_text'];?>"
		       data-action="edit"
		       data-table="caop_research"
		       data-assoc="0"
		       data-fieldid="research_id"
		       data-id="<?=$Patient['research_id'];?>"
		       data-field="research_ds_text"
               <?=super_bootstrap_tooltip($Patient['research_ds_text']);?>
		       placeholder="Текст&nbsp;диагноза">
	</td>
	<td align="center">
		<?php
		if ( count($ResearchTypes) > 0 )
		{
			if ( $Patient['research_type'] == 0 )
			{
				echo '<i class="bi bi-arrow-down-square-fill"></i>';
			}
			$zeroOption = array(
			 'key'   =>  0,
			 'value'    =>  'Выберите...'
			);
			$defaultSelect = array(
			 'key'   =>   'type_id',
			 'value'   =>  $Patient['research_type']
			);
			$mysqleditor_params = 'data-action="edit" data-table="caop_research" data-assoc="0" data-fieldid="research_id" data-id="'.$Patient['research_id'].'" data-field="research_type"';
			$ResearchSelector = array2select($ResearchTypesHead, 'type_id', 'type_title', 'research_type', ' class="mysqleditor form-control form-control input-padding research_type_selector required-field" ' . $mysqleditor_params, $zeroOption, $defaultSelect);
			if ( $ResearchSelector['stat'] == RES_SUCCESS )
			{
				echo $ResearchSelector['result'];
			}
			if ( $Patient['research_type'] == 0 )
			{
				echo '<i class="bi bi-arrow-up-square-fill"></i>';
			}
		}
		?>
	</td>
	<td>
		<input type="text"
		       class="mysqleditor form-control form-control input-padding"
		       value="<?=$Patient['research_area'];?>"
		       data-action="edit"
		       data-table="caop_research"
		       data-assoc="0"
		       data-fieldid="research_id"
		       data-id="<?=$Patient['research_id'];?>"
		       data-field="research_area"
               <?=super_bootstrap_tooltip($Patient['research_area']);?>
		       placeholder="Область обследования">
	</td>
    <td>
		<?php
		$mysqleditor_params3 = 'data-action="edit" data-table="caop_research" data-assoc="0" data-fieldid="research_id" data-id="'.$Patient['research_id'].'" data-field="research_cito"';
		$defaultSelect3 = array(
		 'key'   =>   'type_id',
		 'value'   =>  $Patient['research_cito']
		);
		$ResearchSelectorCito = array2select($ResearchCitos, 'cito_id', 'cito_title', 'research_cito', ' class="mysqleditor form-control form-control input-padding" ' . $mysqleditor_params3, null, $defaultSelect3);
		if ( $ResearchSelectorCito['stat'] == RES_SUCCESS )
		{
			echo $ResearchSelectorCito['result'];
		}
		?>
    </td>
	<td>
		<input type="text"
		       class="mysqleditor form-control form-control input-padding russianBirth"
		       value="<?=$Patient['research_patient_talon'];?>"
		       data-action="edit"
		       data-table="caop_research"
		       data-assoc="0"
		       data-fieldid="research_id"
		       data-id="<?=$Patient['research_id'];?>"
		       data-field="research_patient_talon"
		       placeholder="Дата проведения">
	</td>
	<td>
		<input type="text"
		       class="mysqleditor form-control form-control input-padding russianBirth"
		       value="<?=$Patient['patidcard_patient_done'];?>"
		       data-action="edit"
		       data-table="caop_research"
		       data-assoc="0"
		       data-fieldid="research_id"
		       data-id="<?=$Patient['research_id'];?>"
		       data-field="patidcard_patient_done"
		       placeholder="Дата результата">
	</td>
    <td>
		<?php
		$mysqleditor_params4 = 'data-action="edit" data-table="caop_research" data-assoc="0" data-fieldid="research_id" data-id="'.$Patient['research_id'].'" data-field="research_status"';
		$defaultSelect4 = array(
		 'key'   =>   'type_id',
		 'value'   =>  $Patient['research_status']
		);
		$ResearchSelectorStatus = array2select($ResearchStatuses, 'status_id', 'status_title', 'research_status', ' class="mysqleditor form-control form-control input-padding" ' . $mysqleditor_params4, null, $defaultSelect4);
		if ( $ResearchSelectorStatus['stat'] == RES_SUCCESS )
		{
			echo $ResearchSelectorStatus['result'];
		}
		?>
    </td>
    <td>
        <button type="button" class="btn btn-info deletebutton" data-patient="<?=$Patient['research_id'];?>">
	        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x" viewBox="0 0 16 16">
		        <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
	        </svg>
        </button>
    </td>
<!--</div>-->
</tr>