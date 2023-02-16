<?php

$JournalInfirst = getarr(CAOP_INFIRST, 1, "ORDER BY infirst_id ASC");
$JournalInfirstId = getDoctorsById($JournalInfirst, 'infirst_id');

$InfirstData = $JournalInfirstId['id' . $PatientData['journal_infirst']];

$response['htmlData'] .= '
<div class="row">
    <div class="col-12">
        '.bt_notice('<div class="align-center"><b>Ф.И.О. ПАЦИЕНТА: </b><br>' . mb_ucwords($PatientPersonalData['patid_name']) . ', '.$PatientPersonalData['patid_birth'].' г.р.</div>',  BT_THEME_WARNING, 1).'
    </div>
</div>
<br>
<div class="row">
    <div class="col-12">
        <b>Дата и время приёма:</b> '.$day_date.' '.$PatientData['journal_time'].'
    </div>
</div>
<br>
<div class="row">
    <div class="col-12">
        <b>Основной диагноз:</b> ['.$PatientData['journal_ds'].'] '.$PatientData['journal_ds_text'].'
    </div>
</div>
<br>
<div class="row">
    <div class="col-12">
        <b>Сопутствующий диагноз:</b> ['.$PatientData['journal_ds_follow'].'] '.$PatientData['journal_ds_follow_text'].'
    </div>
</div>
<br>
<div class="row">
    <div class="col-12">
        <b>Исход:</b> '.$PatientData['journal_recom'].'
    </div>
</div>
<br>
<div class="row">
    <div class="col-12">
        <b>Рекомендации:</b> '.$PatientData['journal_ds_recom'].'
    </div>
</div>
<br>
<div class="row">
    <div class="col-12">
        <b>Случай:</b> '.$InfirstData['infirst_title'].'
    </div>
</div>
';
?>