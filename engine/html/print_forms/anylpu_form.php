<div class="form-group row">
	<div class="col full-center">
		<?php
		bt_notice('<b>НАПРАВЛЕНИЕ ПО ФОРМЕ 057У</b>', BT_THEME_SUCCESS);
		?>
	</div>
</div>


<?php

$MsktTypes = getarr(CAOP_MSKT_DIR_TYPES, 1, "ORDER BY type_order ASC");
$MsktTypesId = getDoctorsById($MsktTypes, 'type_id');

$a2sSelected = array(
	'value' => $BlankForm['dir_type']
);

$a2sDefault = array(
	'key' => 0,
	'value' => 'Выберите...'
);
$a2sSelector = array2select($MsktTypesId, 'type_id', 'type_title', 'dir_type',
	'class="form-control mysqleditor" data-action="edit"
data-table="'.CAOP_DIR_057.'"
data-assoc="0"
data-fieldid="'.$BLANK_TABLE_FIELD_ID.'"
data-id="'.$BlankForm[$BLANK_TABLE_FIELD_ID].'"
data-field="dir_type"', $a2sDefault, $a2sSelected);
//echo $a2sSelector['result'];

NewFormItem(
	'Тип направления:',
	'dir_type',
	'required-field',
	$BLANK_TABLE_FIELD_ID,
	'dir_type',
	$BLANK_TABLE_LIST,
	$BlankForm[$BLANK_TABLE_FIELD_ID],
	$BLANK_TABLE_FIELD_UPDATE,
	'Выберите тип направления',
	$BlankForm['dir_type'],
	'',
	'select',
	null,
	$a2sSelector['result']
);

NewFormItem(
	'Наименование учреждения:',
	'dir_lpu_name',
	'',
	$BLANK_TABLE_FIELD_ID,
	'dir_lpu_name',
	$BLANK_TABLE_LIST,
	$BlankForm[$BLANK_TABLE_FIELD_ID],
	$BLANK_TABLE_FIELD_UPDATE,
	'Наименование медицинского учреждения, куда направлен пациент',
	$BlankForm['dir_lpu_name']
);

NewFormItem(
	'Код льготы:',
	'dir_benefit_code',
	'required-field',
	$BLANK_TABLE_FIELD_ID,
	'dir_benefit_code',
	$BLANK_TABLE_LIST,
	$BlankForm[$BLANK_TABLE_FIELD_ID],
	$BLANK_TABLE_FIELD_UPDATE,
	'Оставьте пустым, если не знаете, что это',
	$BlankForm['dir_benefit_code'],
	'',
	'input'
);

NewFormItem(
	'Место работы:',
	'dir_job_name',
	'required-field',
	$BLANK_TABLE_FIELD_ID,
	'dir_job_name',
	$BLANK_TABLE_LIST,
	$BlankForm[$BLANK_TABLE_FIELD_ID],
	$BLANK_TABLE_FIELD_UPDATE,
	'Место работы или «не работает»',
	$BlankForm['dir_job_name'],
	null,
	'input'
);

NewFormItem(
	'Должность:',
	'dir_job_duty',
	'required-field',
	$BLANK_TABLE_FIELD_ID,
	'dir_job_duty',
	$BLANK_TABLE_LIST,
	$BlankForm[$BLANK_TABLE_FIELD_ID],
	$BLANK_TABLE_FIELD_UPDATE,
	'Занимаемая должность или «пенсионер»',
	$BlankForm['dir_job_duty'],
	null,
	'input'
);

NewFormItem(
	'Обоснование направления:',
	'dir_reason',
	'',
	$BLANK_TABLE_FIELD_ID,
	'dir_reason',
	$BLANK_TABLE_LIST,
	$BlankForm[$BLANK_TABLE_FIELD_ID],
	$BLANK_TABLE_FIELD_UPDATE,
	'например: консультация онколога с последующей уточняющей диагностикой',
	$BlankForm['dir_reason']
);



NewFormItem(
	'Диагноз МКБ:',
	'dir_mkb_code',
	'required-field mkbDiagnosis',
	$BLANK_TABLE_FIELD_ID,
	'dir_mkb_code',
	$BLANK_TABLE_LIST,
	$BlankForm[$BLANK_TABLE_FIELD_ID],
	$BLANK_TABLE_FIELD_UPDATE,
	'Код диагноза',
	$BlankForm['dir_mkb_code'],
	'data-adequate="MKB" data-return="#dir_mkb_code" data-returntype="input" data-returnfunc="value"',
	'input'
);

NewFormItem(
	'Текст диагноза:',
	'dir_mkb_text',
	'required-field',
	$BLANK_TABLE_FIELD_ID,
	'dir_mkb_text',
	$BLANK_TABLE_LIST,
	$BlankForm[$BLANK_TABLE_FIELD_ID],
	$BLANK_TABLE_FIELD_UPDATE,
	'Текст диагноза',
	$BlankForm['dir_mkb_text']
);

NewFormItem(
	'Дата заполнения:',
	'mskt_date',
	'required-field russianBirth',
	$BLANK_TABLE_FIELD_ID,
	'dir_date',
	$BLANK_TABLE_LIST,
	$BlankForm[$BLANK_TABLE_FIELD_ID],
	$BLANK_TABLE_FIELD_UPDATE,
	'Дата заполнения',
	$BlankForm['dir_date'],
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
