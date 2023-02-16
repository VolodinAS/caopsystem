<div class="form-group row">
    <div class="col full-center">
		<?php
		bt_notice('<b>ИНФОРМАЦИЯ О ПОДОЗРЕНИИ НА ЗНО</b>', BT_THEME_SUCCESS);
		?>
    </div>
</div>

<div class="form-group row">
        <div class="col-4">Адрес и название учреждения, в котором было заполнено извещение:</div>
    <div class="col">
        <textarea name="f1a_lpu_from"
                  id="f1a_lpu_from"
                  class="form-control form-control-sm mysqleditor required-field"
                  data-action="edit"
                  data-table="<?= CAOP_NOTICE_F1A ?>"
                  data-assoc="0"
                  data-fieldid="f1a_id"
                  data-id="<?= $NoticeF1aForm['f1a_id'] ?>"
                  data-field="f1a_lpu_from"
                  data-unixfield="f1a_date_update_unix"
                  placeholder="Откуда отправлено извещение"><?= $NoticeF1aForm['f1a_lpu_from']; ?></textarea>
    </div>
</div>

<div class="form-group row">
        <div class="col-4">Извещение отправлено в:</div>
    <div class="col">
        <textarea name="f1a_lpu_to"
                  id="f1a_lpu_to"
                  class="form-control form-control-sm mysqleditor required-field"
                  data-action="edit"
                  data-table="<?= CAOP_NOTICE_F1A ?>"
                  data-assoc="0"
                  data-fieldid="f1a_id"
                  data-id="<?= $NoticeF1aForm['f1a_id'] ?>"
                  data-field="f1a_lpu_to"
                  data-unixfield="f1a_date_update_unix"
                  placeholder="Извенение отправлено в"><?= $NoticeF1aForm['f1a_lpu_to']; ?></textarea>
    </div>
</div>

<div class="form-group row">
    <div class="col-4">МКБ-10:</div>
    <div class="col">
        <input type="text"
               name="f1a_dg_mkb"
               id="f1a_dg_mkb"
               class="form-control form-control-sm mysqleditor required-field mkbDiagnosis"
               data-action="edit"
               data-table="<?= CAOP_NOTICE_F1A ?>"
               data-assoc="0"
               data-fieldid="f1a_id"
               data-id="<?= $NoticeF1aForm['f1a_id'] ?>"
               data-field="f1a_dg_mkb"
               data-unixfield="f1a_date_update_unix"
               data-adequate="MKB"
               data-return="#f1a_dg_mkb"
               data-returntype="input"
               data-returnfunc="value"
               placeholder="МКБ-10"
               value="<?= $NoticeF1aForm['f1a_dg_mkb']; ?>">
    </div>
</div>

<div class="form-group row">
    <div class="col-4">Диагноз:</div>
    <div class="col">
        <textarea name="f1a_dg_text"
                  id="f1a_dg_text"
                  class="form-control form-control-sm mysqleditor required-field"
                  data-action="edit"
                  data-table="<?= CAOP_NOTICE_F1A ?>"
                  data-assoc="0"
                  data-fieldid="f1a_id"
                  data-id="<?= $NoticeF1aForm['f1a_id'] ?>"
                  data-field="f1a_dg_text"
                  data-unixfield="f1a_date_update_unix"
                  placeholder="Текст диагноза"><?= $NoticeF1aForm['f1a_dg_text']; ?></textarea>
    </div>
</div>

<div class="form-group row">
    <div class="col-4">Причина, не позволившая окончательно установить диагноз:</div>
    <div class="col">
        <textarea name="f1a_reason"
                  id="f1a_reason"
                  class="form-control form-control-sm mysqleditor required-field"
                  data-action="edit"
                  data-table="<?= CAOP_NOTICE_F1A ?>"
                  data-assoc="0"
                  data-fieldid="f1a_id"
                  data-id="<?= $NoticeF1aForm['f1a_id'] ?>"
                  data-field="f1a_reason"
                  data-unixfield="f1a_date_update_unix"
                  placeholder="Причина, не позволившая окончательно установить диагноз"><?= $NoticeF1aForm['f1a_reason']; ?></textarea>
    </div>
</div>

<div class="form-group row">
    <div class="col-4">Рекомендации:</div>
    <div class="col">
        <textarea name="f1a_recom"
                  id="f1a_recom"
                  class="form-control form-control-sm mysqleditor required-field"
                  data-action="edit"
                  data-table="<?= CAOP_NOTICE_F1A ?>"
                  data-assoc="0"
                  data-fieldid="f1a_id"
                  data-id="<?= $NoticeF1aForm['f1a_id'] ?>"
                  data-field="f1a_recom"
                  data-unixfield="f1a_date_update_unix"
                  placeholder="Рекомендации"><?= $NoticeF1aForm['f1a_recom']; ?></textarea>
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
               data-table="<?= CAOP_NOTICE_F1A ?>"
               data-assoc="0"
               data-fieldid="f1a_id"
               data-id="<?= $NoticeF1aForm['f1a_id'] ?>"
               data-field="f1a_date_create"
               data-unixfield="f1a_date_update_unix"
               value="<?= $NoticeF1aForm['f1a_date_create'] ?>">
    </div>
    <div class="col"
         data-import="rs_fill_date">

    </div>
</div>
<div class="form-group row">
    <div class="col">
        <button type="button"
                class="btn btn-primary col-12"
                onclick="javascript:window.location.href='/noticeF1A/<?= $PatientData['patid_id'] ?>'">ГОТОВО
        </button>
    </div>
</div>