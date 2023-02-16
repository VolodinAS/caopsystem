<?php
$journal_infirst = [4, 5];
$report_begin = birthToUnix('01.09.2022');

//$DayData = getarr(CAOP_DAYS, "day_unix>='{$report_begin}'", null, false, 'day_id');

//$DayIds = getLinearIds($DayData, 'day_id');

//debug($DayIds);
//debug($DayData);

//$JournalData = getarr(CAOP_JOURNAL, "journal_infirst IN ('4', '5') AND journal_day IN {$DayIds}", null, false, 'journal_id');
//print_r($JournalData);

$JournalData_query = "
SELECT
    patid_name,
    patid_id,
    patid_ident,
    patid_birth,
    patid_phone,
    day_date,
    journal_ds,
    journal_ds_text
FROM ".CAOP_JOURNAL."
LEFT JOIN ".CAOP_PATIENTS." ON ".CAOP_PATIENTS.".patid_id = ".CAOP_JOURNAL.".journal_patid
LEFT JOIN ".CAOP_CANCER." ON ".CAOP_CANCER.".cancer_patid = ".CAOP_JOURNAL.".journal_patid
LEFT JOIN ".CAOP_DAYS." ON ".CAOP_DAYS.".day_id = ".CAOP_JOURNAL.".journal_day
WHERE journal_infirst IN ('4', '5') AND day_unix>='{$report_begin}'
ORDER BY day_unix DESC
";
$JournalData_result = mqc($JournalData_query);
$Journal = mr2a($JournalData_result);
$Journal_count = count($Journal);
//debug($Journal[0]);
bt_notice('Отображены пациенты '.wrapper('('.$Journal_count.')').' с исходом '.wrapper('"Повторная явка по ЗНО в ЦАОП"').' и '.wrapper('"Выявлено ЗНО и состоит в КБ №5"').' с '.wrapper('01.09.2022'), BT_THEME_WARNING);
?>
<table class="table table-sm allpatients">
    <thead>
    <tr>
        <th scope="col" class="font-weight-bolder full-center" width="1%">#</th>
        <th scope="col" class="font-weight-bolder full-center" width="1%">Карта</th>
        <th scope="col" class="font-weight-bolder full-center" width="1%">CR</th>
        <th scope="col" class="font-weight-bolder full-center">Ф.И.О.</th>
        <th scope="col" class="font-weight-bolder full-center" width="1%" date-format="ddmmyyyy">Дата рождения</th>
        <th scope="col" class="font-weight-bolder full-center">Дата визита</th>
        <th scope="col" class="font-weight-bolder full-center">Диагноз</th>
        <th scope="col" class="font-weight-bolder full-center">Посещения</th>
    </tr>
    </thead>
    <tbody>
    
    <?php
    $npp = 1;
    foreach ($Journal as $Patient) {
    
        $patient_name = editPersonalDataLink( shorty($Patient['patid_name'], "famimot"), $Patient['patid_id'], super_bootstrap_tooltip( mb_ucwords($Patient['patid_name']) ) );
        
        $Cancer = extractValueByKey($Patient, "cancer_");
	    $cancer_image = ( count($Cancer) > 0 ) ? '<img src="/engine/images/icons/cancer.png" /><div style="display: none">1</div>' : '';
        $dead = patient_dead($Patient);
	    $mark = patient_mark($Patient);
	
	    $badge = mkbAnalizer($Patient['journal_ds']);
        
        ?>
        <tr class="highlighter" data-case="<?=$activeTab;?>" id="tr_patid_<?=$activeTab;?>_<?=$Patient['patid_id'];?>">
            <td class="font-weight-bolder align-center"><?=$npp;?>)</td>
            <td class="align-center"><?=$Patient['patid_ident'];?></td>
            <td class="align-center"><?=$cancer_image;?></td>
            <td><?=$dead;?><?=$mark;?><?=$patient_name?></td>
            <td class="align-center"><?=$Patient['patid_birth'];?></td>
            <td class="align-center"><?=$Patient['day_date'];?></td>
            <td class="align-center">
                <span class="size-12pt badge badge-<?= $badge; ?>" <?=super_bootstrap_tooltip($Patient['journal_ds_text']);?>><?= $Patient['journal_ds']; ?></span>
            </td>
            <td class="align-center font-weight-bolder">
                <button class="btn btn-secondary btn-sm" onclick="javascript:allVisits('<?=$Patient['patid_id'];?>')">визиты</button>
            </td>
        </tr>
        <?php
        $npp++;
    }
    ?>
    </tbody>
</table>
<?php
include ( "engine/html/modals/visitsPatientData.php" );
?>