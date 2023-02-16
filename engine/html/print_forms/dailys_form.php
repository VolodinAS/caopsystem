<?php
//debug($BlankForm);
?>

<div class="form-group row">
    <div class="col full-center">
		<?php
		bt_notice('<b>ДНЕВНИК ПРИЁМА</b>', BT_THEME_SUCCESS);
		?>
    </div>
</div>

<?php
NewFormItem(
	'Тип приёма:',
	'daily_type',
	'required-field',
	$BLANK_TABLE_FIELD_ID,
	'daily_type',
	$BLANK_TABLE_LIST,
	$BlankForm[$BLANK_TABLE_FIELD_ID],
	$BLANK_TABLE_FIELD_UPDATE,
	'Выберите тип приёма',
	$BlankForm['daily_type'],
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
    )
);

NewFormItem(
	'Жалобы:',
	'daily_complains',
	'required-field',
	$BLANK_TABLE_FIELD_ID,
	'daily_complains',
	$BLANK_TABLE_LIST,
	$BlankForm[$BLANK_TABLE_FIELD_ID],
	$BLANK_TABLE_FIELD_UPDATE,
	'на что жалуется пациент',
	$BlankForm['daily_complains']
);

//spoiler_begin('Анамнезы', 'anamnesis');

NewFormItem(
	'Анамнез заболевания:',
	'daily_anam_disease',
	'required-field',
	$BLANK_TABLE_FIELD_ID,
	'daily_anam_disease',
	$BLANK_TABLE_LIST,
	$BlankForm[$BLANK_TABLE_FIELD_ID],
	$BLANK_TABLE_FIELD_UPDATE,
	'Считает себя больным(-ой) ...',
	$BlankForm['daily_anam_disease']
);

NewFormItem(
	'Результаты исследований:',
	'daily_researches',
	'required-field',
	$BLANK_TABLE_FIELD_ID,
	'daily_researches',
	$BLANK_TABLE_LIST,
	$BlankForm[$BLANK_TABLE_FIELD_ID],
	$BLANK_TABLE_FIELD_UPDATE,
	'УЗИ, КТ, МРТ ...',
	$BlankForm['daily_researches']
);

NewFormItem(
	'Анамнез жизни:',
	'daily_anam_life',
	'required-field',
	$BLANK_TABLE_FIELD_ID,
	'daily_anam_life',
	$BLANK_TABLE_LIST,
	$BlankForm[$BLANK_TABLE_FIELD_ID],
	$BLANK_TABLE_FIELD_UPDATE,
	'Рос и развивался ...',
	$BlankForm['daily_anam_life']
);

NewFormItem(
	'Аллергологический анамнез:',
	'daily_anam_allergy',
	'',
	$BLANK_TABLE_FIELD_ID,
	'daily_anam_allergy',
	$BLANK_TABLE_LIST,
	$BlankForm[$BLANK_TABLE_FIELD_ID],
	$BLANK_TABLE_FIELD_UPDATE,
	'',
	$BlankForm['daily_anam_allergy']
);

NewFormItem(
	'Семейный анамнез:',
	'daily_anam_family',
	'required-field',
	$BLANK_TABLE_FIELD_ID,
	'daily_anam_family',
	$BLANK_TABLE_LIST,
	$BlankForm[$BLANK_TABLE_FIELD_ID],
	$BLANK_TABLE_FIELD_UPDATE,
	'',
	$BlankForm['daily_anam_family']
);

//spoiler_end();

NewFormItem(
	'Объективный статус:',
	'daily_presens',
	'required-field',
	$BLANK_TABLE_FIELD_ID,
	'daily_presens',
	$BLANK_TABLE_LIST,
	$BlankForm[$BLANK_TABLE_FIELD_ID],
	$BLANK_TABLE_FIELD_UPDATE,
	'',
	$BlankForm['daily_presens']
);

NewFormItem(
	'Локальный статус:',
	'daily_local',
	'',
	$BLANK_TABLE_FIELD_ID,
	'daily_local',
	$BLANK_TABLE_LIST,
	$BlankForm[$BLANK_TABLE_FIELD_ID],
	$BLANK_TABLE_FIELD_UPDATE,
	'',
	$BlankForm['daily_local']
);

NewFormItem(
	'Диагноз МКБ:',
	'daily_main_dg_mkb',
	'required-field mkbDiagnosis',
	$BLANK_TABLE_FIELD_ID,
	'daily_main_dg_mkb',
	$BLANK_TABLE_LIST,
	$BlankForm[$BLANK_TABLE_FIELD_ID],
	$BLANK_TABLE_FIELD_UPDATE,
	'',
	$BlankForm['daily_main_dg_mkb'],
    'data-adequate="MKB" data-return="#daily_main_dg_mkb" data-returntype="input" data-returnfunc="value"',
    'input'
);

NewFormItem(
	'Текст диагноза:',
	'daily_main_dg_text',
	'required-field',
	$BLANK_TABLE_FIELD_ID,
	'daily_main_dg_text',
	$BLANK_TABLE_LIST,
	$BlankForm[$BLANK_TABLE_FIELD_ID],
	$BLANK_TABLE_FIELD_UPDATE,
	'',
	$BlankForm['daily_main_dg_text']
);

spoiler_begin('Сопутствующие диагнозы', 'addon_ds');

NewFormItem(
	'Сопутствующий МКБ №1:',
	'daily_add1_dg_mkb',
	'required-field mkbDiagnosis',
	$BLANK_TABLE_FIELD_ID,
	'daily_add1_dg_mkb',
	$BLANK_TABLE_LIST,
	$BlankForm[$BLANK_TABLE_FIELD_ID],
	$BLANK_TABLE_FIELD_UPDATE,
	'',
	$BlankForm['daily_add1_dg_mkb'],
    'data-adequate="MKB" data-return="#daily_add1_dg_mkb" data-returntype="input" data-returnfunc="value"',
	'input'
);

NewFormItem(
	'Текст сопутствующего диагноза №1:',
	'daily_add1_dg_text',
	'required-field',
	$BLANK_TABLE_FIELD_ID,
	'daily_add1_dg_text',
	$BLANK_TABLE_LIST,
	$BlankForm[$BLANK_TABLE_FIELD_ID],
	$BLANK_TABLE_FIELD_UPDATE,
	'',
	$BlankForm['daily_add1_dg_text']
);

NewFormItem(
	'Сопутствующий МКБ №2:',
	'daily_add2_dg_mkb',
	'required-field mkbDiagnosis',
	$BLANK_TABLE_FIELD_ID,
	'daily_add2_dg_mkb',
	$BLANK_TABLE_LIST,
	$BlankForm[$BLANK_TABLE_FIELD_ID],
	$BLANK_TABLE_FIELD_UPDATE,
	'',
	$BlankForm['daily_add2_dg_mkb'],
    'data-adequate="MKB" data-return="#daily_add2_dg_mkb" data-returntype="input" data-returnfunc="value"',
	'input'
);

NewFormItem(
	'Текст сопутствующего диагноза №2:',
	'daily_add2_dg_text',
	'required-field',
	$BLANK_TABLE_FIELD_ID,
	'daily_add2_dg_text',
	$BLANK_TABLE_LIST,
	$BlankForm[$BLANK_TABLE_FIELD_ID],
	$BLANK_TABLE_FIELD_UPDATE,
	'',
	$BlankForm['daily_add2_dg_text']
);

spoiler_end();

NewFormItem(
	'Рекомендации по диагнозу:',
	'daily_recom',
	'required-field',
	$BLANK_TABLE_FIELD_ID,
	'daily_recom',
	$BLANK_TABLE_LIST,
	$BlankForm[$BLANK_TABLE_FIELD_ID],
	$BLANK_TABLE_FIELD_UPDATE,
	'Рекомендации',
	$BlankForm['daily_recom']
);

spoiler_begin('Дополнительные рекомендации', 'addon_recoms');

NewFormItem(
	'Рекомендации сопутствующему по диагнозу:',
	'daily_recom_follow',
	'required-field',
	$BLANK_TABLE_FIELD_ID,
	'daily_recom_follow',
	$BLANK_TABLE_LIST,
	$BlankForm[$BLANK_TABLE_FIELD_ID],
	$BLANK_TABLE_FIELD_UPDATE,
	'Рекомендации (если есть)',
	$BlankForm['daily_recom_follow']
);

NewFormItem(
	'Дополнительный текст:',
	'daily_addon',
	'required-field',
	$BLANK_TABLE_FIELD_ID,
	'daily_addon',
	$BLANK_TABLE_LIST,
	$BlankForm[$BLANK_TABLE_FIELD_ID],
	$BLANK_TABLE_FIELD_UPDATE,
	'Просто текст, если надо',
	$BlankForm['daily_addon']
);

spoiler_end();

NewFormItem(
	'Дата/время заполнения:',
	'daily_date_create',
	'required-field',
	$BLANK_TABLE_FIELD_ID,
	'daily_date_create',
	$BLANK_TABLE_LIST,
	$BlankForm[$BLANK_TABLE_FIELD_ID],
	$BLANK_TABLE_FIELD_UPDATE,
	'Дата заполнения дневника',
	$BlankForm['daily_date_create'],
	'',
	'input'
);
?>
<div class="form-group row">
    <div class="col">
        <button type="button"
                class="btn btn-primary col-12"
                onclick="javascript:window.location.href='/<?=$BLANK_SCRIPT;?>/<?= $PatientData['patid_id'] ?>'">ГОТОВО
        </button>
    </div>
</div>
