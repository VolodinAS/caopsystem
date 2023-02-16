<?php

//$AllSurveys = [];
//$CitologyTypes = getarr(CAOP_CITOLOGY_TYPE, 1, "ORDER BY {$PK[CAOP_CITOLOGY_TYPE]}");
//$ResearchTypes = getarr(CAOP_RESEARCH_TYPES, 1, "ORDER BY {$PK[CAOP_RESEARCH_TYPES]}");
//$AllSurveys = array_merge($CitologyTypes, $ResearchTypes);


$AllSurveysTypes = getDoctorsById($AllSurveys, 'survey_id');
//debug($AllSurveysTypes);

//unset($_SESSION['mef_filters']);
//debug($_SESSION);
$ZNO_query = "
SELECT * FROM ".CAOP_ZNO_DU."
LEFT JOIN ".CAOP_PATIENTS." ON ".CAOP_PATIENTS.".patid_id=".CAOP_ZNO_DU.".zno_patient_id
{mysqleditor_filter}
ORDER BY zno_id DESC
";
$ZNO_query = mysqleditor_filters_applyer($ZNO_query);
$mysql_filters_queryset = $ZNO_query;
$ZNO_result = mqc($ZNO_query);
$ZNOData = mr2a($ZNO_result);
//debug($ZNO_query);
?>
	<button class="btn btn-primary btn-addPatientZNODU">Добавить</button>
<!--	<hr>-->
<?php

require_once("engine/html/include/table_du/filters.php");

$TNM = stnmg_parser();
$TNM_S = $TNM['s'];
$TNM_T = $TNM['t'];
$TNM_N = $TNM['n'];
$TNM_M = $TNM['m'];

//debug($TNM_S);

$sorter_format = "sorter-shortDate dateFormat-ddmmyyyy";

?>
<table class="table table-sm" id="zno_du_table">
    <thead>
    <tr>
        <th scope="col" class="text-center sorter-false" data-title="npp" width="1%"<?=STYLE_VERTICAL_ALIGN_TOP;?>>
			<?=BT_ICON_PEN_FILL;?>
        </th>
        <th scope="col" class="text-center" data-title="npp" width="1%"<?=STYLE_VERTICAL_ALIGN_TOP;?>>
            №1
        </th>
        <th scope="col" class="text-center valign-top" data-title="patid_name"<?=STYLE_VERTICAL_ALIGN_TOP;?>>
            Ф.И.О.<br>
	        <?=$mefb_patid_name;?>
        </th>
        <th scope="col" class="text-center <?=$sorter_format;?>" data-title="patid_birth"<?=STYLE_VERTICAL_ALIGN_TOP;?>>
            <?=nbsper('Дата рожд.');?><br>
	        <?=$mefb_patid_birth_unix;?>
        </th>
        <!--                <th scope="col" class="text-center sorter-false" data-title="patid_address">Адрес</th>-->
        <!--                <th scope="col" class="text-center sorter-false" data-title="patid_phone">Телефон</th>-->
        <th scope="col" class="text-center" data-title="zno_apk"<?=STYLE_VERTICAL_ALIGN_TOP;?>>
            АПК<br>
	        <?=$mefb_zno_apk;?>
        </th>
        <th scope="col" class="text-center <?=$sorter_format;?>" data-title="zno_date_first_visit_caop"<?=STYLE_VERTICAL_ALIGN_TOP;?>>
            <?=nbsper('Перв. обр. в ЦАОП');?><br>
	        <?=$mef_zno_date_first_visit_caop;?>
        </th>
        <th scope="col" class="text-center <?=$sorter_format;?>" data-title="zno_date_set_zno"<?=STYLE_VERTICAL_ALIGN_TOP;?>>
            <?=nbsper('Уст. диагноза');?><br>
            <?=$mef_zno_date_set_zno;?>
        </th>
        <th scope="col" class="text-center" data-title="zno_diagnosis_mkb"<?=STYLE_VERTICAL_ALIGN_TOP;?>>
            <?=nbsper('Код по МКБ');?><br>
	        <?=$mef_zno_diagnosis_mkb;?>
        </th>
        <th scope="col" class="text-center" data-title="zno_tnm_t"<?=STYLE_VERTICAL_ALIGN_TOP;?>>
            <?=_nbsp();?>T<?=_nbsp();?><br>
	        <?=$mef_zno_tnm_t;?>
        </th>
        <th scope="col" class="text-center" data-title="zno_tnm_n"<?=STYLE_VERTICAL_ALIGN_TOP;?>>
            <?=_nbsp();?>N<?=_nbsp();?><br>
	        <?=$mef_zno_tnm_n;?>
        </th>
        <th scope="col" class="text-center" data-title="zno_tnm_m"<?=STYLE_VERTICAL_ALIGN_TOP;?>>
            <?=_nbsp();?>M<?=_nbsp();?><br>
	        <?=$mef_zno_tnm_m;?>
        </th>
        <th scope="col" class="text-center" data-title="zno_tnm_s"<?=STYLE_VERTICAL_ALIGN_TOP;?>>
            Стадия <br>
	        <?=$mef_zno_tnm_s;?>
        </th>
        <th scope="col" class="text-center" data-title="zno_method_type"<?=STYLE_VERTICAL_ALIGN_TOP;?>>
            Метод <br>
	        <?=$mef_zno_method_type;?>
        </th>
        <th scope="col" class="text-center" data-title="zno_survey_type"<?=STYLE_VERTICAL_ALIGN_TOP;?>>
            Обследование <br>
	        <?=$mef_zno_survey_type;?>
        </th>
        <th scope="col" class="text-center <?=$sorter_format;?>" data-title="zno_method_date"<?=STYLE_VERTICAL_ALIGN_TOP;?>>
            <?=nbsper('Дата метода');?> <br>
	        <?=$mef_zno_method_date;?>
        </th>
        <th scope="col" class="text-center <?=$sorter_format;?>" data-title="zno_date_dir_in_gop"<?=STYLE_VERTICAL_ALIGN_TOP;?>>
            <?=nbsper('Напр. в ГОП');?><br>
	        <?=$mef_zno_date_dir_in_gop;?>
        </th>
        <th scope="col" class="text-center <?=$sorter_format;?>" data-title="zno_date_issue_notice"<?=STYLE_VERTICAL_ALIGN_TOP;?>>
            <?=nbsper('Оформ. извещ.');?><br>
	        <?=$mef_zno_date_issue_notice;?>
        </th>
        <th scope="col" class="text-center" data-title="zno_comment"<?=STYLE_VERTICAL_ALIGN_TOP;?>>Коммент.</th>
        <th scope="col" class="text-center" data-title="zno_diagnosis_text"<?=STYLE_VERTICAL_ALIGN_TOP;?>>Текст диагноза</th>
        <th scope="col" class="text-center" data-title="zno_doctor_id"<?=STYLE_VERTICAL_ALIGN_TOP;?>>
            Врач<br>
	        <?=$mef_zno_doctor_id;?>
        </th>
        <!--                <th scope="col" class="text-center" data-title="zno_create_at" width="1%">Добав.</th>-->
        <!--                <th scope="col" class="text-center" data-title="zno_update_at" width="1%">Обнов.</th>-->
    </tr>
    </thead>
    <tbody>
<?php

if ( count($ZNOData) > 0 )
{
    $npp = 1;
    $sorting_error = false;
    $sorting_errors_data = [];
    foreach ($ZNOData as $ZNODatum)
    {
//                debug($ZNODatum);
        $name_format = $birth_format = $address_format = $phone_format = 'Укажите пациента';
        $id_format = 0;
        if ($ZNODatum['patid_id'])
        {
            $name_format = mb_ucwords($ZNODatum['patid_name']);
            $id_format = $ZNODatum['patid_id'];
            $birth_format = $ZNODatum['patid_birth'];
            $address_format = $ZNODatum['patid_address'];
            $phone_format = $ZNODatum['patid_phone'];
        }
        
        $zno_apk_format = $ZNODatum['zno_apk'] ? $ZNODatum['zno_apk'] : 'не указано';
        $zno_diagnosis_mkb_format = ($ZNODatum['zno_diagnosis_mkb']) ? $ZNODatum['zno_diagnosis_mkb'] : 'не указан';
        $zno_date_first_visit_caop_format = ($ZNODatum['zno_date_first_visit_caop']) ? date(DMY, $ZNODatum['zno_date_first_visit_caop']) : 'не указана';
        $zno_date_set_zno_format = ($ZNODatum['zno_date_set_zno']) ? date(DMY, $ZNODatum['zno_date_set_zno']) : 'не указана';
        $zno_method_type_format = ( $ZNODatum['zno_method_type'] ) ? $ZNODUMorphMethodId[ 'id' . $ZNODatum['zno_method_type'] ]['morph_title'] : 'не выбран';
        $zno_survey_type_format = ( $ZNODatum['zno_survey_type'] ) ? $AllSurveysTypes[ 'id' . $ZNODatum['zno_survey_type'] ]['survey_title'] : 'не выбрано';
        $zno_method_date_format = ($ZNODatum['zno_method_date']) ? date(DMY, $ZNODatum['zno_method_date']) : 'не указана';
        $zno_date_dir_in_gop_format = ($ZNODatum['zno_date_dir_in_gop']) ? date(DMY, $ZNODatum['zno_date_dir_in_gop']) : 'не указана';
        $zno_date_issue_notice_format = ($ZNODatum['zno_date_issue_notice']) ? date(DMY, $ZNODatum['zno_date_issue_notice']) : 'не указана';
        $zno_create_at_format = ($ZNODatum['zno_create_at']) ? date(DMYHIS, $ZNODatum['zno_create_at']) : 'не указана';
        $zno_update_at_format = ($ZNODatum['zno_update_at']) ? date(DMYHIS, $ZNODatum['zno_update_at']) : 'не указана';
        $zno_doctor_id = ( $ZNODatum['zno_doctor_id'] ) ? nbsper(docNameShort($DoctorsListId[ 'id' . $ZNODatum['zno_doctor_id'] ])) : 'не указан';
	
//	    $zno_survey_type_format = 'не выбрано';

        $zno_tnm_t = strlen($ZNODatum['zno_tnm_t']) ? $ZNODatum['zno_tnm_t'] : 'не указано';
        $zno_tnm_n = strlen($ZNODatum['zno_tnm_n']) ? $ZNODatum['zno_tnm_n'] : 'не указано';
        $zno_tnm_m = strlen($ZNODatum['zno_tnm_m']) ? $ZNODatum['zno_tnm_m'] : 'не указано';
        $zno_tnm_s = strlen($ZNODatum['zno_tnm_s']) ? $ZNODatum['zno_tnm_s'] : 'не указано';
        
        include ("engine/php/processor/include/zno_du/stnm_ordering.php");
        ?>
        <tr>
            <td data-cell="npp" class="text-center">
                <button <?=super_bootstrap_tooltip('Создано: '.$zno_create_at_format.'; Обновлено: '.$zno_update_at_format);?> class="btn btn-sm btn-primary btn-editZNODU" data-znodu="<?=$ZNODatum['zno_id'];?>" <?=super_bootstrap_tooltip('Редактировать');?>>
                    <?=BT_ICON_PEN_FILL;?>
                </button>
            </td>
            <td data-cell="npp" class="text-center"><?=$npp;?></td>
            <td data-cell="patid_name"><?=editPersonalDataLink($name_format, $id_format);?></td>
            <td data-cell="patid_birth" class="text-center"><?=$birth_format;?></td>
<!--                    <td data-cell="patid_address">--><?//=$address_format;?><!--</td>-->
<!--                    <td data-cell="patid_phone">--><?//=$phone_format;?><!--</td>-->
            <td data-cell="zno_apk" class="text-center"><?=$zno_apk_format;?></td>
            <td data-cell="zno_date_first_visit_caop" class="text-center"><?=$zno_date_first_visit_caop_format;?></td>
            <td data-cell="zno_date_set_zno"><?=$zno_date_set_zno_format;?></td>
            <td data-cell="zno_diagnosis_mkb" class="text-center"><?=$zno_diagnosis_mkb_format;?></td>
            <td data-cell="zno_tnm_t" class="text-center" <?=super_bootstrap_tooltip('Порядок сортировки: '.$t_order);?>>
                <span class="nondisplay"><?=$t_order;?></span>
                <?=$zno_tnm_t;?>
            </td>
            <td data-cell="zno_tnm_n" class="text-center" <?=super_bootstrap_tooltip('Порядок сортировки: '.$n_order);?>>
                <span class="nondisplay"><?=$n_order;?></span>
                <?=$zno_tnm_n;?>
            </td>
            <td data-cell="zno_tnm_m" class="text-center" <?=super_bootstrap_tooltip('Порядок сортировки: '.$m_order);?>>
                <span class="nondisplay"><?=$m_order;?></span>
                <?=$zno_tnm_m;?>
            </td>
            <td data-cell="zno_tnm_s" class="text-center" <?=super_bootstrap_tooltip('Порядок сортировки: '.$s_order);?>>
                <span class="nondisplay"><?=$s_order;?></span>
                <?=$zno_tnm_s;?>
            </td>
            <td data-cell="zno_method_type" class="text-center"><?=$zno_method_type_format;?></td>
            <td data-cell="zno_method_type" class="text-center"><?=$zno_survey_type_format;?></td>
            <td data-cell="zno_method_date" class="text-center"><?=$zno_method_date_format;?></td>
            <td data-cell="zno_date_dir_in_gop" class="text-center">
                <?=$zno_date_dir_in_gop_format;?>
            </td>
            <td data-cell="zno_date_issue_notice" class="text-center"><?=$zno_date_issue_notice_format;?></td>
            <td data-cell="zno_comment"><?=$ZNODatum['zno_comment'];?></td>
            <td data-cell="zno_diagnosis_text"><?=$ZNODatum['zno_diagnosis_text'];?></td>
            <td data-cell="zno_doctor_id" class="text-center"><?=$zno_doctor_id;?></td>
            
        </tr>
        <?php
        $npp++;
    }
} else
{
	?>
        <tr>
            <td colspan="100%">
                <?php
                bt_notice('Пока нет пациентов с ЗНО');
                ?>
            </td>
        </tr>
	<?php
}
?>
    </tbody>
</table>
<?php
require_once ("engine/html/modals/zno_du/add_zno_du.php");
?>
<script defer type="text/javascript" src="/engine/js/zno_du.js?<?=rand(0, 9999);?>"></script>
