<br>
<? spoiler_begin('Добавить пациенту быстрый визит', 'fast_visit_dont_form') ;?>
<div class="row">
	<div class="col">
		<label class="sr-only" for="fast_visit_add">ДОБАВИТЬ БЫСТРЫЙ ВИЗИТ</label>
		<div class="input-group mb-2">
			<div class="input-group-prepend">
				<div class="input-group-text" <?=super_bootstrap_tooltip('ДОБАВИТЬ БЫСТРЫЙ ВИЗИТ');?>>
					<?=BT_ICON_REGIMEN_TEMPLATE;?>
				</div>
			</div>
			<input class="form-check-input move-labeler" type="checkbox" name="fast_visit_add_checkbox" id="fast_visit_add_checkbox" value="1">
			<label class="form-check-label box-label" for="fast_visit_add_checkbox"><span></span><b>ДОБАВИТЬ БЫСТРЫЙ ВИЗИТ</b></label>
		</div>
	</div>
    <div class="col">
        <label class="sr-only" for="fast_visit_dater">Дата визита</label>
        <div class="input-group mb-2">
            <div class="input-group-prepend">
                <div class="input-group-text" <?=super_bootstrap_tooltip('Дата визита');?>>
					<?=BT_ICON_BIRTH;?>
                </div>
            </div>
            <input type="date" class="form-control required-field" huid="fast_visit_date" name="fast_visit_date" placeholder="Дата визита" value="<?=date('Y-m-d', time());?>">
        </div>
    </div>
</div>
<?php
echo '
<div class="row">
    <div class="col">
        <label class="sr-only" for="visreg_visit_current">Текущее количество процедур</label>
        <div class="input-group mb-2">
            <div class="input-group-prepend">
                <div class="input-group-text" '.super_bootstrap_tooltip('Текущее количество процедур').'>
                '.BT_ICON_PROCEDURE_AMOUNT_CURRENT.'
                </div>
            </div>
            <input type="text" class="form-control form-control-sm required-field" huid="visreg_visit_current" name="visreg_visit_current" placeholder="Текущее количество процедур" value="">
        </div>
    </div>
    <div class="col">
        <label class="sr-only" for="visreg_visit_total">Максимальное количество процедур</label>
        <div class="input-group mb-2">
            <div class="input-group-prepend">
                <div class="input-group-text" '.super_bootstrap_tooltip('Максимальное количество процедур').'>
                '.BT_ICON_PROCEDURE_AMOUNT.'
                </div>
            </div>
            <input type="text" class="form-control form-control-sm required-field" huid="visreg_visit_total" name="visreg_visit_total" placeholder="Максимальное количество процедур" value="">
        </div>
    </div>
    <div class="col">
        <label class="sr-only" for="visreg_dispose_date">Дата выписки</label>
        <div class="input-group mb-2">
            <div class="input-group-prepend">
                <div class="input-group-text" '.super_bootstrap_tooltip('Дата выписки').'>
                '.BT_ICON_PROCEDURE_AMOUNT.'
                </div>
            </div>
            <input type="date" class="form-control form-control-sm" huid="visreg_dispose_date" name="visreg_dispose_date" placeholder="Дата выписки" value="">
        </div>
    </div>
</div>
';
$ANALISES_HTML = '';
$ANALISES_HTML .= spoiler_begin_return('Показатели анализов', 'analisis_'.$visitRegimen['visreg_id'], '');
//		$ResearchData = getarr(CAOP_DS_RESEARCH, "research_patid='{$PatientData['patient_id']}' AND research_resid IN {$DS_RESEARCH_TYPES_LINEAR}", "ORDER BY research_unix DESC LIMIT ".count($DS_RESEARCH_TYPES), 1);


$queryResearchData = "SELECT * FROM {$CAOP_DS_RESEARCH} WHERE research_patid='{$PatientData['patient_id']}' GROUP BY research_resid ORDER BY research_unix DESC LIMIT ".count($DS_RESEARCH_TYPES);
$resultResearchData = mqc($queryResearchData);
$ResearchData = mr2a($resultResearchData);

//		$ANALISES_HTML .= debug_ret($ResearchData);

$ANALISES_HTML .= '
<div class="row">
    <div class="col font-weight-bolder text-center border-right">
        Название
    </div>
    <div class="col font-weight-bolder text-center">
        Было
    </div>
    <div class="col font-weight-bolder text-center border-right">
        Дата
    </div>
    <div class="col font-weight-bolder text-center">
        Стало
    </div>
    <div class="col font-weight-bolder text-center border-right">
        Дата
    </div>
    <div class="col font-weight-bolder text-center">
        Примечание
    </div>
</div>
';

foreach ($DS_RESEARCH_TYPES_ID as $resid=>$research)
{
	$OldResearchData = [];
	$ResearchItem = array_search($research['type_id'], array_column($ResearchData, 'research_resid'));
	
	if ( strlen($ResearchItem) >0 )
	{
		$OldResearchData = $ResearchData[$ResearchItem];
	}

	$ANALISES_HTML .= '
    <div class="row">
        <div class="col border-right">
            '.$research['type_title'].'
        </div>
        <div class="col">
            '.$OldResearchData['research_value'].'
        </div>
        <div class="col border-right">
            '.$OldResearchData['research_date'].'
        </div>
        <div class="col">
            <input type="text" name="field_value_'.$research['type_id'].'" class="form-control form-control-sm" placeholder="Новый результат">
        </div>
        <div class="col border-right">
            <input type="text" name="field_date_'.$research['type_id'].'" class="form-control form-control-sm russianBirth" placeholder="Дата результат">
        </div>
        <div class="col">
            <input type="text" name="field_note_'.$research['type_id'].'" class="form-control form-control-sm" placeholder="Примечание" value="'.$OldResearchData['research_note'].'">
        </div>
    </div>
    ';
}

$ANALISES_HTML .= spoiler_end_return();

echo $ANALISES_HTML;

?>

<? spoiler_end() ;?>
<br>