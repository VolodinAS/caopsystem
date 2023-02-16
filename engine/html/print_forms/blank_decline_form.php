<?php
//$BlankDeclineForm
?>

<div class="form-group row">
	<div class="col full-center">
		<?php
		bt_notice('<b>БЛАНК ОТКАЗА ОТ МЕДИЦИНСКОГО ВМЕШАТЕЛЬСТВА</b>', BT_THEME_SUCCESS);
		?>
	</div>
</div>

<div class="form-group row">
	<div class="col-4">Отказ от медицинских мероприятий:</div>
	<div class="col">
        <textarea name="f1a_lpu_from"
                  id="f1a_lpu_from"
                  class="form-control form-control-sm mysqleditor required-field"
                  data-action="edit"
                  data-table="<?= CAOP_BLANK_DECLINE ?>"
                  data-assoc="0"
                  data-fieldid="decline_id"
                  data-id="<?= $BlankDeclineForm['decline_id'] ?>"
                  data-field="decline_phrase"
                  data-unixfield="decline_update_unix"
                  placeholder="от чего отказывается пациент"><?= $BlankDeclineForm['decline_phrase']; ?></textarea>
	</div>
</div>

<div class="form-group row">
	<div class="col-4">Дата заполнения:</div>
	<div class="col">
		<input type="text"
		       id="f1a_date_create"
		       name="f1a_date_create"
		       class="form-control form-control-sm mysqleditor russianBirth required-field"
		       data-action="edit"
		       data-table="<?= CAOP_BLANK_DECLINE ?>"
		       data-assoc="0"
		       data-fieldid="decline_id"
		       data-id="<?= $BlankDeclineForm['decline_id'] ?>"
		       data-field="decline_date_create"
		       data-unixfield="decline_update_unix"
		       value="<?= $BlankDeclineForm['decline_date_create'] ?>">
	</div>
	<div class="col"
	     data-import="rs_fill_date">
	
	</div>
</div>
<div class="form-group row">
	<div class="col">
		<button type="button"
		        class="btn btn-primary col-12"
		        onclick="javascript:window.location.href='/blankDeclined/<?= $PatientData['patid_id'] ?>'">ГОТОВО
		</button>
	</div>
</div>