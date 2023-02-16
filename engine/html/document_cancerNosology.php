<style>
    @media print{
        @page {size: landscape}
    }
</style>

<?php
//debug($_SESSION);

$go_next = false;
if ( isset($_SESSION['nosologyPrint']) )
{
    $MKB_String = $_SESSION['nosologyPrint'];
    //$MKB_Data = explode(";", $MKB_String);
    $MKB_query = AccessString2QueryORs($MKB_String, "{$CAOP_CANCER}.cancer_ds");
    $queryCancer = "SELECT * FROM {$CAOP_CANCER}
                    LEFT JOIN {$CAOP_PATIENTS}
                        ON {$CAOP_CANCER}.cancer_patid={$CAOP_PATIENTS}.patid_id
                    LEFT JOIN {$CAOP_ROUTE_SHEET}
                        ON {$CAOP_ROUTE_SHEET}.rs_patid={$CAOP_PATIENTS}.patid_id
                    LEFT JOIN   (
                                SELECT * FROM {$CAOP_JOURNAL} WHERE (journal_ds LIKE 'C%' OR journal_ds LIKE 'С%') OR (journal_ds LIKE 'D0%')
                                GROUP BY journal_patid
                                HAVING journal_unix = MIN(journal_unix)
                                ) last_c_visit
                        ON {$CAOP_CANCER}.cancer_patid=last_c_visit.journal_patid
                    WHERE {$MKB_query}
                        ORDER BY {$CAOP_CANCER}.cancer_ds ASC";
    $go_next = true;
} else
{
    bt_notice("Не выбраны диагнозы для распечатки");
}

if ( $go_next )
{
    $resultCancer = mqc($queryCancer);
    $amountCancer = mnr($resultCancer);
    $CancerData = mr2a($resultCancer);

    //debug($queryCancer);
    //debug($amountCancer);
    //debug($CancerData);

    $CancerNosologyData = [];

    foreach ($CancerData as $cancerDatum)
    {
        //debug($cancerDatum);
        $mkbData = explode(".", $cancerDatum['cancer_ds']);
        $MAIN_MKB = '';
        if ( count($mkbData) == 2 )
        {
            $MAIN_MKB = $mkbData[0];
        } else $MAIN_MKB = $cancerDatum['cancer_ds'];

        $CancerNosologyData[$MAIN_MKB]['data'][] = $cancerDatum;
    }
    $MAIN_ARRAY = $CancerData;
    unset($CancerData);
    ?>
    <table border="1" class="tbc">
        <thead>
            <tr>
                <th scope="col" class="text-center" width="1%" data-title="mkb">МКБ</th>
                <th scope="col" class="text-center" width="1%" data-title="npp">№</th>
                <th scope="col" class="text-center">Фамилия И.О.</th>
                <th scope="col" class="text-center" width="1%" data-title="patid_birth">Дата рождения</th>
                <th scope="col" class="text-center" width="1%" data-title="cancer_route_date">Марш. лист</th>

<!--                <th scope="col" class="text-center" width="1%" data-title="cancer_ds">МКБ</th>-->
                <th scope="col" class="text-center" data-title="cancer_ds_text">Диагноз</th>
                <th scope="col" class="text-center" width="1%" data-title="rs_stage_po_pe_pr_zno_date">Первичные признаки
                </th>
                <th scope="col" class="text-center" width="1%" data-title="rs_stage_fap_date_OR_rs_stage_vr_u4_pol_date">
                    Первичное обращение
                </th>
                <th scope="col" class="text-center" width="1%"
                    data-title="rs_stage_caop_date"><?= nbsper('Обращение в ЦАОП'); ?></th>
                <th scope="col" class="text-center" width="1%" data-title="rs_ds_set_date" width="1%">Установлен</th>
<!--                <th scope="col" class="text-center" width="1%" data-title="rs_stage_cure_date">--><?//= nbsper('Начало спец. лечения'); ?><!--</th>-->
                <th scope="col" class="text-center" width="1%" data-title="cancer_doctor_id" width="1%">Врач</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $preMKB = '';
        foreach ($CancerNosologyData as $mkbGroup => $mkbData)
        {
            $npp = 1;
            $useHighlight = false;
            $rowspan_count = count($mkbData['data']);
            foreach ($mkbData['data'] as $mkbDatum)
            {
                extract($mkbDatum);

                $rowspan = '';
            ?>
            <tr>
                <?php

                if ( $preMKB != $mkbGroup )
                {
                    $preMKB = $mkbGroup;
                    ?>
                    <th scope="col" valign="top" rowspan="<?=$rowspan_count?>">
                        <?=$mkbGroup?>
                    </th>
                    <?php
                }

                include ( "engine/html/include/inc_cancerList_row_print.php" );
                ?>

            </tr>
            <?php
                $npp++;
            }
        }
        ?>
        </tbody>
    </table>
    <?php
}

