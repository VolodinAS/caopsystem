<?php
//debug($BlankForm);
?>

<div class="form-group row">
	<div class="col full-center">
		<?php
		bt_notice('<b>НАПРАВЛЕНИЕ НА МСКТ</b>', BT_THEME_SUCCESS);
		?>
	</div>
</div>

<?php

$MsktTypes = getarr(CAOP_MSKT_DIR_TYPES, 1, "ORDER BY type_order ASC");
$MsktTypesId = getDoctorsById($MsktTypes, 'type_id');

$a2sSelected = array(
    'value' => $BlankForm['mskt_dir_type']
);

$a2sDefault = array(
    'key' => 0,
    'value' => 'Выберите...'
);
$a2sSelector = array2select($MsktTypesId, 'type_id', 'type_title', 'mskt_dir_type',
'class="form-control mysqleditor" data-action="edit"
data-table="'.CAOP_MSKT_MDC.'"
data-assoc="0"
data-fieldid="'.$BLANK_TABLE_FIELD_ID.'"
data-id="'.$BlankForm[$BLANK_TABLE_FIELD_ID].'"
data-field="mskt_dir_type"', $a2sDefault, $a2sSelected);
//echo $a2sSelector['result'];

NewFormItem(
	'Тип направления:',
	'mskt_dir_type',
	'required-field',
	$BLANK_TABLE_FIELD_ID,
	'daily_type',
	$BLANK_TABLE_LIST,
	$BlankForm[$BLANK_TABLE_FIELD_ID],
	$BLANK_TABLE_FIELD_UPDATE,
	'Выберите тип направления',
	$BlankForm['mskt_dir_type'],
	'',
	'select',
	null,
	$a2sSelector['result']
);

NewFormItem(
	'Код льготы:',
	'mskt_benefit_code',
	'required-field',
	$BLANK_TABLE_FIELD_ID,
	'mskt_benefit_code',
	$BLANK_TABLE_LIST,
	$BlankForm[$BLANK_TABLE_FIELD_ID],
	$BLANK_TABLE_FIELD_UPDATE,
	'Оставьте пустым, если не знаете, что это',
	$BlankForm['mskt_benefit_code'],
	'',
	'input'
);

NewFormItem(
	'Место работы:',
	'mskt_job_place',
	'required-field',
	$BLANK_TABLE_FIELD_ID,
	'mskt_job_place',
	$BLANK_TABLE_LIST,
	$BlankForm[$BLANK_TABLE_FIELD_ID],
	$BLANK_TABLE_FIELD_UPDATE,
	'Место работы или «не работает»',
	$BlankForm['mskt_job_place'],
	null,
	'input'
);

NewFormItem(
	'Должность:',
	'mskt_job_duty',
	'required-field',
	$BLANK_TABLE_FIELD_ID,
	'mskt_job_duty',
	$BLANK_TABLE_LIST,
	$BlankForm[$BLANK_TABLE_FIELD_ID],
	$BLANK_TABLE_FIELD_UPDATE,
	'Занимаемая должность или «пенсионер»',
	$BlankForm['mskt_job_duty'],
	null,
	'input'
);

NewFormItem(
	'Обоснование направления:',
	'mskt_dir_reason',
	'',
	$BLANK_TABLE_FIELD_ID,
	'mskt_dir_reason',
	$BLANK_TABLE_LIST,
	$BlankForm[$BLANK_TABLE_FIELD_ID],
	$BLANK_TABLE_FIELD_UPDATE,
	'Например, «МСКТ ОГК с КУ»',
	$BlankForm['mskt_dir_reason']
);



NewFormItem(
	'Диагноз МКБ:',
	'mskt_diagnosis_mkb',
	'required-field mkbDiagnosis',
	$BLANK_TABLE_FIELD_ID,
	'mskt_diagnosis_mkb',
	$BLANK_TABLE_LIST,
	$BlankForm[$BLANK_TABLE_FIELD_ID],
	$BLANK_TABLE_FIELD_UPDATE,
	'Код диагноза',
	$BlankForm['mskt_diagnosis_mkb'],
	'data-adequate="MKB" data-return="#mskt_diagnosis_mkb" data-returntype="input" data-returnfunc="value"',
	'input'
);

NewFormItem(
	'Текст диагноза:',
	'mskt_diagnosis_text',
	'required-field',
	$BLANK_TABLE_FIELD_ID,
	'mskt_diagnosis_text',
	$BLANK_TABLE_LIST,
	$BlankForm[$BLANK_TABLE_FIELD_ID],
	$BLANK_TABLE_FIELD_UPDATE,
	'Текст диагноза',
	$BlankForm['mskt_diagnosis_text']
);

NewFormItem(
	'Дата заполнения:',
	'mskt_date',
	'required-field russianBirth',
	$BLANK_TABLE_FIELD_ID,
	'mskt_date',
	$BLANK_TABLE_LIST,
	$BlankForm[$BLANK_TABLE_FIELD_ID],
	$BLANK_TABLE_FIELD_UPDATE,
	'Дата заполнения',
	$BlankForm['mskt_date'],
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
