<?php

//$DirectionStac .= spoiler_begin_return(wrapper('Выбор лечебного учреждения при направлении'), 'lpudir' . $PatientData['journal_id'], '');

$JournalInfirst = getarr(CAOP_INFIRST, 1, "ORDER BY infirst_id ASC");

$DispLPU = getarr(CAOP_DISP_LPU, 1, "ORDER BY lpu_order ASC");
$DispLPUId = getDoctorsById($DispLPU, 'lpu_id');

$DirectionStac .= '
<div class="row">
    <div class="col">
        Быстрый выбор исхода (при направлении на лечение в другое учреждение):<br><br>' . $RadioDirStac . '
    </div>
</div>
<br>
<div class="row">
	<div class="col">
        <div class="form-group">
            <label for="journal_dirstac_desc">Название учреждения (если нет в списке выше):</label>
            <input
                type="text" 
                class="mysqleditor form-control form-control-lg indiraaccessable" 
                id="journal_dirstac_desc" 
                aria-describedby="journal_dirstac_desc" 
                placeholder="Название медицинского учреждения"
                value="' . htmlspecialchars($PatientData['journal_dirstac_desc']) . '"
                data-action="edit" 
                data-table="caop_journal" 
                data-assoc="0" 
                data-fieldid="journal_id" 
                data-id="' . $PatientData['journal_id'] . '"
                data-field="journal_dirstac_desc" 
                data-return="0" data-unixfield="journal_update_unix">
        </div>
    </div>
</div>
';
//$DirectionStac .= spoiler_end_return();

$disp_Consult = $disp_Disp = $disp_isReported = $disp_selfdoctor = '';

if ($PatientData['journal_disp_isDisp'] == 1) $disp_Consult = 'checked';
if ($PatientData['journal_disp_isDisp'] == 2) $disp_Disp = 'checked';
if ($PatientData['journal_disp_isReported'] == 1) $disp_isReported = 'checked';
if ($PatientData['journal_disp_self'] == 1) $disp_selfdoctor = 'checked';

$PATIENT_LPU = 0;
if ($PatientPersonalData['patid_pin_lpu_id'] > 0)
{
	$PATIENT_LPU = $PatientPersonalData['patid_pin_lpu_id'];
} else
{
	if ($PatientData['journal_disp_lpu'] > 0)
	{
		$PATIENT_LPU = $PatientData['journal_disp_lpu'];
	}
}

$defaultArr = array(
	'key' => '0',
	'value' => 'ВЫБЕРИТЕ ЛПУ ПРИКРЕПЛЕНИЯ'
);
$defaultSelect = array(
	'value' => $PATIENT_LPU,
);
$DispLPU_select = array2select($DispLPUId, 'lpu_id', 'lpu_showname', "disp_lpu_selector", 'id="journal_disp_lpu" class="form-control mysqleditor" data-action="edit" data-table="' . CAOP_JOURNAL . '" data-assoc="0" data-fieldid="journal_id" data-id="' . $PatientData['journal_id'] . '" data-field="journal_disp_lpu"', $defaultArr, $defaultSelect);

if ( $PATIENT_LPU > 0 )
{
	if ( $PatientData['journal_disp_lpu'] == 0 )
	{
		$param_update_lpu = array(
		    'journal_disp_lpu' => $PATIENT_LPU
		);
		$UpdateLPU = updateData(CAOP_JOURNAL, $param_update_lpu, [], $PK[CAOP_JOURNAL] . "='{$PatientData[$PK[CAOP_JOURNAL]]}'");
	}
}


$OLD_DISPANCER = spoiler_begin_return(wrapper('Если посещение пациента относится к ранее выставленному диспансерному диагнозу, выберите его!'), 'if_old_disp', '');
$OLD_DISPANCER .= '
	<div class="row">
		<div class="col">
			' . $DispancerSelector['result'] . '
		</div>
		<div class="col-auto">
			<button class="btn btn-warning btn-removeDispancer" data-journal="' . $PatientData[$PK[CAOP_JOURNAL]] . '">Удалить диспансерный диагноз</button>
		</div>
	</div>';
$OLD_DISPANCER .= spoiler_end_return();

$NEW_DISPANCER = '<div class="p-3">';
$NEW_DISPANCER .= wrapper('Выберите ЛПУ прикрепления пациента');
$NEW_DISPANCER .= $DispLPU_select['result'];
$NEW_DISPANCER .= '</div>';

//if ( $USER_PROFILE['doctor_id'] != 1 )
//{
//	$NEW_DISPANCER = '';
//	$OLD_DISPANCER = '';
//}
//'.$DirectionStac.'

/*if ( $USER_PROFILE['doctor_id'] != 1 )
{
	$SPOHTML = bt_notice('Раздел находится в разработке', BT_THEME_WARNING, 1);
}*/

$response['htmlData'] .= '

<div class="row">
    <div class="col-12">
        ' . bt_notice('<div class="align-center"><b>ВНИМАНИЕ! Ф.И.О. ПАЦИЕНТА: </b><br>' . mb_ucwords($PatientPersonalData['patid_name']) . ', ' . $PatientPersonalData['patid_birth'] . ' г.р.</div>', BT_THEME_DANGER, 1) . '
    </div>
</div>
<input type="hidden" name="general_journal_id" id="general_journal_id" value="'.$PatientData['journal_id'].'">

' . tab_menu_begin('journal') . '
	' . tab_menu_item('SPO', wrapper('СПО'), 'small', 'data-saveonclick="SPO"', false, false, 'save-on-click', 'small') . '
	' . tab_menu_item('daily', wrapper('Дневник'), '', 'data-saveonclick="daily"', true, true, 'save-on-click', 'small') . '
	' . tab_menu_item('researches', wrapper('Обследования'), '', 'data-saveonclick="researches"', false, false, 'save-on-click', 'small') . '
	' . tab_menu_item('lpu-pin', wrapper('Прикрепление'), '', 'data-saveonclick="lpu-pin"', false, false, 'save-on-click', 'small') . '
	' . tab_menu_item('dir-lpu', wrapper('Направление в другое ЛПУ'), '', 'data-saveonclick="dir-lpu"', false, false, 'save-on-click', 'small') . '
	' . tab_menu_item('daily-emias', wrapper('ЕМИАС не работает'), '', 'data-saveonclick="daily-emias"', false, false, 'save-on-click', 'small') . '
	' . tab_menu_item('service-type', wrapper('Тип услуги'), '', 'data-saveonclick="service-type"', false, false, 'save-on-click', 'small') . '
' . tab_menu_end() . '

' . tab_content_begin('journal') . '

	' . tab_pane_begin('SPO') . '
		' . $SPOHTML . '
	' . tab_end() . '

	' . tab_pane_begin('researches') . '
		' . $ResearchesHTML . '
	' . tab_end() . '

	' . tab_pane_begin('daily-emias') . '
		' . $DailyByDB . '
	' . tab_end() . '

	' . tab_pane_begin('dir-lpu') . '
		' . $DirectionStac . '
	' . tab_end() . '

	' . tab_pane_begin('lpu-pin') . '
		' . $NEW_DISPANCER . '
	' . tab_end() . '


' . tab_pane_begin('daily', true, true, true) . '
	<div class="row display-none" id="dispancer_status_dom" style="display: none">
	    <div class="col">
	      ' . bt_notice('<div id="dispancer_status"></div>', BT_THEME_WARNING, 1) . '
	    </div>
	</div>


<br>
<div class="row">
    <div class="col-12">
        <b>Дата и время приёма:</b>
    </div>
</div>

<div class="row">
    <div class="col">
        <div class="form-group">
            <label for="nothing_day">Дата приёма:</label>
            <input
                type="text" 
                class="form-control form-control-lg" 
                id="nothing_day" 
                aria-describedby="nothing_day" 
                placeholder="День приёма"
                value="' . $day_date . '" disabled>
        </div>
    </div>
    <div class="col align-right">
        <div class="form-group">
            <label for="journal_time">Время приёма:</label>
            <input
                type="text" 
                class="mysqleditor form-control form-control-lg russianTime" 
                id="journal_time" 
                aria-describedby="journal_time" 
                placeholder="Время приёма"
                value="' . $PatientData['journal_time'] . '"
                data-action="edit" 
                data-table="caop_journal" 
                data-assoc="0" 
                data-fieldid="journal_id" 
                data-id="' . $PatientData['journal_id'] . '"
                data-field="journal_time" 
                data-return="0" data-unixfield="journal_update_unix">
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <b>Основной диагноз:</b> <button class="btn btn-lg btn-success" onclick="javascript:setAccountDiagnosis(' . $PatientData['journal_id'] . ')">Поставить на Д-учет</button>
    </div>
</div>

<div class="row">
    <div class="col-2">
        <div class="form-group">
            <label for="journal_ds">МКБ:</label>
            <input
                type="text" 
                class="mysqleditor form-control form-control-lg mkbDiagnosis required-field" 
                id="journal_ds" 
                aria-describedby="journal_ds" 
                placeholder="Диагноз"
                value="' . $PatientData['journal_ds'] . '"
                data-action="edit" 
                data-table="caop_journal" 
                data-assoc="0" 
                data-fieldid="journal_id" 
                data-id="' . $PatientData['journal_id'] . '"
                data-field="journal_ds"
                data-adequate="MKB"
                data-return="#journal_ds"
                data-returntype="input"
                data-returnfunc="value"
                data-unixfield="journal_update_unix"
				data-callbackfunc="toggleDispancerStatus"
				data-callbackparams="' . $PatientData['journal_id'] . '">
        </div>
    </div>
    <div class="col-10 align-right">
        <div class="form-group">
            <label for="journal_ds_text">Диагноз (Текст):</label>
            <input
                type="text" 
                class="mysqleditor form-control form-control-lg required-field col-auto"
                id="journal_ds_text" 
                aria-describedby="journal_ds_text" 
                placeholder="Диагноз (текст)"
                value="' . htmlspecialchars($PatientData['journal_ds_text']) . '"
                data-action="edit" 
                data-table="caop_journal" 
                data-assoc="0" 
                data-fieldid="journal_id" 
                data-id="' . $PatientData['journal_id'] . '"
                data-field="journal_ds_text" 
                data-return="0" data-unixfield="journal_update_unix">
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <b>Сопутствующий диагноз:</b>
    </div>
</div>

<div class="row">
    <div class="col-2">
        <div class="form-group">
            <label for="journal_ds_follow">МКБ:</label>
            <input
                type="text" 
                class="mysqleditor form-control form-control-lg mkbDiagnosis" 
                id="journal_ds_follow" 
                aria-describedby="journal_ds_follow" 
                placeholder="МКБ"
                value="' . $PatientData['journal_ds_follow'] . '"
                data-action="edit" 
                data-table="caop_journal" 
                data-assoc="0"
                data-adequate="MKB"
                data-return="#journal_ds_follow"
                data-returntype="input"
                data-returnfunc="value"
                data-fieldid="journal_id" 
                data-id="' . $PatientData['journal_id'] . '"
                data-field="journal_ds_follow"
                data-unixfield="journal_update_unix">
        </div>
    </div>
    <div class="col-10 align-right">
        <div class="form-group">
            <label for="journal_ds_follow_text">Соп. диагноз (Текст):</label>
            <input
                type="text" 
                class="mysqleditor form-control form-control-lg" 
                id="journal_ds_follow_text" 
                aria-describedby="journal_ds_follow_text" 
                placeholder="Соп. диагноз (текст)"
                value="' . htmlspecialchars($PatientData['journal_ds_follow_text']) . '"
                data-action="edit" 
                data-table="caop_journal" 
                data-assoc="0" 
                data-fieldid="journal_id" 
                data-id="' . $PatientData['journal_id'] . '"
                data-field="journal_ds_follow_text" 
                data-return="0" data-unixfield="journal_update_unix">
        </div>
    </div>
</div>


<div class="row">
    <div class="col">
        <div class="form-group">
            <label for="journal_recom">Исход:</label>
            <input
                type="text" 
                class="mysqleditor form-control form-control-lg required-field indiraaccessable" 
                id="journal_recom" 
                aria-describedby="journal_recom" 
                placeholder="Исход"
                value="' . htmlspecialchars($PatientData['journal_recom']) . '"
                data-action="edit" 
                data-table="caop_journal" 
                data-assoc="0" 
                data-fieldid="journal_id" 
                data-id="' . $PatientData['journal_id'] . '"
                data-field="journal_recom" 
                data-return="0" data-unixfield="journal_update_unix">
        </div>
    </div>
    <div class="col align-right">
        <div class="form-group">
            <label for="journal_ds_recom">Рекомендации:</label>
            <input
                type="text" 
                class="mysqleditor form-control form-control-lg required-field" 
                id="journal_ds_recom" 
                aria-describedby="journal_ds_recom" 
                placeholder="Рекомендации"
                value="' . htmlspecialchars($PatientData['journal_ds_recom']) . '"
                data-action="edit" 
                data-table="caop_journal" 
                data-assoc="0" 
                data-fieldid="journal_id" 
                data-id="' . $PatientData['journal_id'] . '"
                data-field="journal_ds_recom" 
                data-return="0" data-unixfield="journal_update_unix">
        </div>
    </div>
</div>
<div class="row">
    <div class="col">
        <div class="form-group border border-warning">
            <label for="journal_infirst">Случай:</label>
';
$mysqleditor_params = 'data-action="edit" data-table="caop_journal" data-assoc="0" data-fieldid="journal_id" data-id="' . $PatientData['journal_id'] . '" data-field="journal_infirst" data-unixfield="journal_update_unix"';
$defaultSelect = array(
	'value' => $PatientData['journal_infirst']
);
$defaultArr = array(
	'key' => 0,
	'value' => 'ВЫБЕРИТЕ СЛУЧАЙ'
);
$JournalSelectorInfirst = array2select(
	$JournalInfirst,
	'infirst_id',
	'infirst_title',
	'journal_infirst',
	' class="mysqleditor form-control form-control-lg" ' . $mysqleditor_params,
	$defaultArr,
	$defaultSelect);
if ($JournalSelectorInfirst['stat'] == RES_SUCCESS)
{
	$response['htmlData'] .= $JournalSelectorInfirst['result'];
}

$param_checked = ((int)$PatientData['journal_disp_isReported'] == 1) ? ' checked' : '';
$switcher_checkbox_options = array(
	'mye' => array(
		'table' => CAOP_JOURNAL,
		'field_id' => $PK[CAOP_JOURNAL],
		'id' => $PatientData[$PK[CAOP_JOURNAL]],
		'field' => 'journal_disp_isReported'
	),
	'addon' => $param_checked
);
$switcher_checkbox = checkbox_switcher($switcher_checkbox_options);

$report = NewFormItem(
	'Принудительно добавить в диспансерный отчёт:',
	'journal_disp_isReported',
	'',
	$PK[CAOP_JOURNAL],
	'journal_disp_isReported',
	CAOP_JOURNAL,
	$PatientData[$PK[CAOP_JOURNAL]],
	'',
	'Принудительно добавить в диспансерный отчёт',
	$PatientData['journal_disp_isReported'],
	'',
	'select',
	[],
	$switcher_checkbox,
	false,
	'6'
);

$agreementBlank = '
<div class="dropdown-divider"></div>
<a target="_blank" href="/documentPrint/agree_research/'.$PatientData[$PK[CAOP_JOURNAL]].'">Бланк согласия на возвращение после консультаций/обследований</a>
';
if ( $USER_PROFILE['doctor_id'] != 1 ) $agreementBlank = '';

$response['htmlData'] .= '
        </div>
    </div>
    <div class="col align-right">
        <div class="form-group">
            <label for="journal_cardplace">Место карты:</label>
            <input
                type="text" 
                class="mysqleditor form-control form-control-lg indiraaccessable" 
                id="journal_cardplace" 
                aria-describedby="journal_cardplace" 
                placeholder="Место карты"
                value="' . htmlspecialchars($PatientData['journal_cardplace']) . '"
                data-action="edit" 
                data-table="caop_journal" 
                data-assoc="0" 
                data-fieldid="journal_id" 
                data-id="' . $PatientData['journal_id'] . '"
                data-field="journal_cardplace" 
                data-return="0" data-unixfield="journal_update_unix">
        </div>
    </div>
</div>
<div class="dropdown-divider"></div>
'.$report.'
'.$agreementBlank.'
	' . tab_end() . '

	' . tab_pane_begin('service-type') . '
		<button class="btn btn-primary btn-setVisitType" data-type="2" data-journal="'.$PatientData['journal_id'].'">Преобразовать в "Телемедицина"</button>
	' . tab_end() . '



	' . tab_end();

//<div class="row">
//			<div class="col">
//				&nbsp;
//			</div>
//			<div class="col">
//ИЛИ
//				<input '.$disp_selfdoctor.' class="form-check-input mysqleditor" type="checkbox" name="selfdoctor" id="selfdoctor" value="1" data-action="edit"
//				data-table="'.CAOP_JOURNAL.'"
//				data-assoc="0"
//				data-fieldid="journal_id"
//				data-id="'.$PatientData['journal_id'].'"
//				data-field="journal_disp_self">
//				<label class="form-check-label box-label" for="selfdoctor"><span></span><b>Самообращение</b></label>
//			</div>
//		</div>
?>