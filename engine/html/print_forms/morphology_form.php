<?php
//debug($BlankForm);
?>

<div class="form-group row">
    <div class="col full-center">
		<?php
		bt_notice('<b>НАПРАВЛЕНИЕ НА ГИСТОЛОГИЮ</b>', BT_THEME_SUCCESS);
		?>
    </div>
</div>

<?php

$SexArray = getarr(CAOP_SEX, "1", "ORDER BY sex_id ASC");
$SexTypesId = getDoctorsById($SexArray, 'sex_id');

$CountySideArea = getarr(CAOP_COUNTRYSIDE_AREA, 1, "ORDER BY area_id ASC");
$CountySideAreaTypesId = getDoctorsById($CountySideArea, 'area_id');

$MorphTypes = getarr(CAOP_MORPH_TYPE, 1, "ORDER BY morph_type_id ASC");
$MorphTypesId = getDoctorsById($MorphTypes, 'morph_type_id');

NewFormItem(
	'<b>6. СНИЛС:</b>',
	'morph_patient_snils',
	'required-field snils',
	$BLANK_TABLE_FIELD_ID,
	'morph_patient_snils',
	$BLANK_TABLE_LIST,
	$BlankForm[$BLANK_TABLE_FIELD_ID],
	$BLANK_TABLE_FIELD_UPDATE,
	'СНИЛС пациента',
	$BlankForm['morph_patient_snils'],
	'',
	'input'
);

$a2sSelected_morph_sex = array(
	'value' => $BlankForm['morph_sex']
);

$a2sDefault_morph_sex = array(
	'key' => 0,
	'value' => 'Выберите...'
);
$a2sSelector_morph_sex = array2select($SexTypesId, 'sex_id', 'sex_title', 'morph_sex',
	'class="form-control mysqleditor" data-action="edit"
data-table="' . CAOP_MORPHOLOGY . '"
data-assoc="0"
data-fieldid="' . $BLANK_TABLE_FIELD_ID . '"
data-id="' . $BlankForm[$BLANK_TABLE_FIELD_ID] . '"
data-field="morph_sex"', $a2sDefault_morph_sex, $a2sSelected_morph_sex);
//echo $a2sSelector['result'];

NewFormItem(
	'<b>3. Пол:</b>',
	'morph_sex',
	'required-field',
	$BLANK_TABLE_FIELD_ID,
	'morph_sex',
	$BLANK_TABLE_LIST,
	$BlankForm[$BLANK_TABLE_FIELD_ID],
	$BLANK_TABLE_FIELD_UPDATE,
	'Выберите пол',
	$BlankForm['morph_sex'],
	'',
	'select',
	null,
	$a2sSelector_morph_sex['result']
);

$a2sSelected_morph_area = array(
	'value' => $BlankForm['morph_area']
);

$a2sSelector_morph_area = array2select($CountySideAreaTypesId, 'area_id', 'area_title', 'morph_area',
	'class="form-control mysqleditor" data-action="edit"
data-table="' . CAOP_MORPHOLOGY . '"
data-assoc="0"
data-fieldid="' . $BLANK_TABLE_FIELD_ID . '"
data-id="' . $BlankForm[$BLANK_TABLE_FIELD_ID] . '"
data-field="morph_area"', $a2sDefault_morph_sex, $a2sSelected_morph_area);
//echo $a2sSelector['result'];

NewFormItem(
	'<b>8. Местность:</b>',
	'morph_area',
	'required-field',
	$BLANK_TABLE_FIELD_ID,
	'morph_area',
	$BLANK_TABLE_LIST,
	$BlankForm[$BLANK_TABLE_FIELD_ID],
	$BLANK_TABLE_FIELD_UPDATE,
	'Выберите местность',
	$BlankForm['morph_area'],
	'',
	'select',
	null,
	$a2sSelector_morph_area['result']
);

NewFormItem(
	'<b>10. Диагноз МКБ:</b>',
	'morph_dg_mkb',
	'required-field mkbDiagnosis',
	$BLANK_TABLE_FIELD_ID,
	'morph_dg_mkb',
	$BLANK_TABLE_LIST,
	$BlankForm[$BLANK_TABLE_FIELD_ID],
	$BLANK_TABLE_FIELD_UPDATE,
	'Код диагноза',
	$BlankForm['morph_dg_mkb'],
	'data-adequate="MKB" data-return="#morph_dg_mkb" data-returntype="input" data-returnfunc="value"',
	'input'
);

NewFormItem(
	'<b>9. Текст диагноза:</b>',
	'morph_dg_text',
	'required-field',
	$BLANK_TABLE_FIELD_ID,
	'morph_dg_text',
	$BLANK_TABLE_LIST,
	$BlankForm[$BLANK_TABLE_FIELD_ID],
	$BLANK_TABLE_FIELD_UPDATE,
	'Текст диагноза',
	$BlankForm['morph_dg_text']
);

$a2sSelected_morph_method = array(
	'value' => $BlankForm['morph_method']
);

$a2sSelector_morph_method = array2select($MorphTypesId, 'morph_type_id', 'morph_type_title', 'morph_method',
	'class="form-control mysqleditor" data-action="edit"
data-table="' . CAOP_MORPHOLOGY . '"
data-assoc="0"
data-fieldid="' . $BLANK_TABLE_FIELD_ID . '"
data-id="' . $BlankForm[$BLANK_TABLE_FIELD_ID] . '"
data-field="morph_method"', $a2sDefault_morph_sex, $a2sSelected_morph_method);
//echo $a2sSelector['result'];

NewFormItem(
	'<b>15. Способ получения биопсийного (операционного) материала:</b>',
	'morph_method',
	'required-field',
	$BLANK_TABLE_FIELD_ID,
	'morph_method',
	$BLANK_TABLE_LIST,
	$BlankForm[$BLANK_TABLE_FIELD_ID],
	$BLANK_TABLE_FIELD_UPDATE,
	'Выберите способ',
	$BlankForm['morph_method'],
	'',
	'select',
	null,
	$a2sSelector_morph_method['result']
);

NewFormItem(
	'<b>16.1. Дата забора материала:</b>',
	'morph_sampling_date',
	'required-field russianBirth',
	$BLANK_TABLE_FIELD_ID,
	'morph_sampling_date',
	$BLANK_TABLE_LIST,
	$BlankForm[$BLANK_TABLE_FIELD_ID],
	$BLANK_TABLE_FIELD_UPDATE,
	'Дата заполнения',
	$BlankForm['morph_sampling_date'],
	'',
	'input'
);

NewFormItem(
	'<b>16.2. Время забора материала:</b>',
	'morph_sampling_time',
	'required-field russianTime',
	$BLANK_TABLE_FIELD_ID,
	'morph_sampling_time',
	$BLANK_TABLE_LIST,
	$BlankForm[$BLANK_TABLE_FIELD_ID],
	$BLANK_TABLE_FIELD_UPDATE,
	'Дата заполнения',
	$BlankForm['morph_sampling_time'],
	'',
	'input'
);

$a2sSelected_morph_phormaline = array(
	'value' => $BlankForm['morph_phormaline']
);

$a2sSelector_morph_phormaline = array2select($MorphConfirmId, 'confirm_id', 'confirm_title', 'morph_phormaline',
	'class="form-control mysqleditor" data-action="edit"
data-table="' . CAOP_MORPHOLOGY . '"
data-assoc="0"
data-fieldid="' . $BLANK_TABLE_FIELD_ID . '"
data-id="' . $BlankForm[$BLANK_TABLE_FIELD_ID] . '"
data-field="morph_phormaline"', $a2sDefault_morph_sex, $a2sSelected_morph_phormaline);

//debug($a2sSelector_morph_phormaline);

NewFormItem(
	'<b>17. Материал помещен в 10% раствор формалина:</b>',
	'morph_phormaline',
	'required-field',
	$BLANK_TABLE_FIELD_ID,
	'morph_phormaline',
	$BLANK_TABLE_LIST,
	$BlankForm[$BLANK_TABLE_FIELD_ID],
	$BLANK_TABLE_FIELD_UPDATE,
	'Выберите ответ',
	$BlankForm['morph_phormaline'],
	'',
	'select',
	null,
	$a2sSelector_morph_phormaline['result']
);

/**
 * ДРУГИЕ ПОЛЯ
 */

NewFormItem(
	'11. Задача прижизненного патолого-анатомического исследования биопсийного (операционного) материала (ППАИБОМ):',
	'morph_other_task',
	'',
	$BLANK_TABLE_FIELD_ID,
	'morph_other_task',
	$BLANK_TABLE_LIST,
	$BlankForm[$BLANK_TABLE_FIELD_ID],
	$BLANK_TABLE_FIELD_UPDATE,
	'Напишите что-нибудь, если надо',
	$BlankForm['morph_other_task']
);

NewFormItem(
	'12. Дополнительные клинические сведения:',
	'morph_other_addon',
	'',
	$BLANK_TABLE_FIELD_ID,
	'morph_other_addon',
	$BLANK_TABLE_LIST,
	$BlankForm[$BLANK_TABLE_FIELD_ID],
	$BLANK_TABLE_FIELD_UPDATE,
	'Напишите что-нибудь, если надо',
	$BlankForm['morph_other_addon']
);

NewFormItem(
	'13. Результаты предыдущих ППАИБОМ:',
	'morph_other_prev',
	'',
	$BLANK_TABLE_FIELD_ID,
	'morph_other_prev',
	$BLANK_TABLE_LIST,
	$BlankForm[$BLANK_TABLE_FIELD_ID],
	$BLANK_TABLE_FIELD_UPDATE,
	'Напишите что-нибудь, если надо',
	$BlankForm['morph_other_prev']
);

NewFormItem(
	'14. Проведенное предоперационное лечение:',
	'morph_other_cure',
	'',
	$BLANK_TABLE_FIELD_ID,
	'morph_other_cure',
	$BLANK_TABLE_LIST,
	$BlankForm[$BLANK_TABLE_FIELD_ID],
	$BLANK_TABLE_FIELD_UPDATE,
	'Напишите что-нибудь, если надо',
	$BlankForm['morph_other_cure']
);

?>
<div class="dropdown-divider"></div>

<div class="row">
    <div class="col full-center">
		<?php
		bt_notice('<b>18. МАРКИРОВКА БИОПСИЙНОГО (ОПЕРАЦИОННОГО) МАТЕРИАЛА</b>', BT_THEME_SUCCESS);
		?>
    </div>
</div>
<button class="btn btn-sm btn-primary btn-addMarker"
        data-morphology="<?= $BlankForm[$BLANK_TABLE_FIELD_ID]; ?>">Добавить маркировку материала
</button>

<table class="table table-sm">
    <thead>
    <tr>
        <th scope="col"
            class="text-center"
            data-title="npp"
            width="1%">Номер флакона
        </th>
        <th scope="col"
            class="text-center"
            data-title="local">Локализация патологического процесса
        </th>
        <th scope="col"
            class="text-center"
            data-title="character">Характер патологического процесса
        </th>
        <th scope="col"
            class="text-center"
            data-title="amount"
            width="1%">Количество объектов
        </th>
        <th scope="col"
            class="text-center"
            data-title="delete"
            width="1%">Удалить
        </th>
    </tr>
    </thead>
    <tbody>
	<?php
	$MarkerData = getarr(CAOP_MORPHOLOGY_MARKER, "marker_morph_id='{$BlankForm[$BLANK_TABLE_FIELD_ID]}'", "ORDER BY marker_id ASC");
	if (count($MarkerData) > 0)
	{
		$npp = 1;
		foreach ($MarkerData as $markerDatum)
		{
			?>
            <tr>
                <td data-cell="npp"
                    class="text-center"><?= $npp; ?></td>
                <td data-cell="local">
                    <input type="text"
                           class="form-control form-control-sm mysqleditor"
                           data-action="edit"
                           data-table="<?= CAOP_MORPHOLOGY_MARKER; ?>"
                           data-assoc="0"
                           data-fieldid="marker_id"
                           data-id="<?= $markerDatum['marker_id']; ?>"
                           data-field="marker_local"
                           value="<?= $markerDatum['marker_local']; ?>">
                </td>
                <td data-cell="character">
                    <input type="text"
                           class="form-control form-control-sm mysqleditor"
                           data-action="edit"
                           data-table="<?= CAOP_MORPHOLOGY_MARKER; ?>"
                           data-assoc="0"
                           data-fieldid="marker_id"
                           data-id="<?= $markerDatum['marker_id']; ?>"
                           data-field="marker_description"
                           value="<?= $markerDatum['marker_description']; ?>">
                </td>
                <td data-cell="amount">
                    <input type="text"
                           class="form-control form-control-sm mysqleditor"
                           data-action="edit"
                           data-table="<?= CAOP_MORPHOLOGY_MARKER; ?>"
                           data-assoc="0"
                           data-fieldid="marker_id"
                           data-id="<?= $markerDatum['marker_id']; ?>"
                           data-field="marker_amount"
                           value="<?= $markerDatum['marker_amount']; ?>">
                </td>
                <td data-cell="delete"
                    class="text-center">
                    <button class="btn btn-sm btn-success"
                            onclick="if (confirm('Вы действительно хотите удалить данную маркировку?')){MySQLEditorAction(this, true); window.location.reload()}"
                            data-action="remove"
                            data-table="<?= CAOP_MORPHOLOGY_MARKER; ?>"
                            data-assoc="0"
                            data-fieldid="marker_id"
                            data-id="<?= $markerDatum['marker_id']; ?>">
						<?= BT_ICON_CLOSE_LG; ?>
                    </button>
                </td>
            </tr>
			<?php
			$npp++;
		}
	} else
	{
		?>
        <tr>
            <td colspan="5">
				<?php
				bt_notice('Нет маркировок. Добавьте...', BT_THEME_WARNING);
				?>
            </td>
        </tr>
		<?php
	}
	?>
    </tbody>
</table>

<?php


?>
<div class="dropdown-divider"></div>
<?php

NewFormItem(
	'Дата заполнения:',
	'morph_date',
	'required-field russianBirth',
	$BLANK_TABLE_FIELD_ID,
	'morph_date',
	$BLANK_TABLE_LIST,
	$BlankForm[$BLANK_TABLE_FIELD_ID],
	$BLANK_TABLE_FIELD_UPDATE,
	'Дата заполнения',
	$BlankForm['morph_date'],
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


<script defer
        type="text/javascript"
        src="/engine/js/document_morphology_form.js?<?= rand(0, 99999); ?>"></script>