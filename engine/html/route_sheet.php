<?php
//debug($RouteSheetForm);
$Diagnosis = getarr(CAOP_CANCER, "cancer_patid='{$PatientData['patid_id']}'");

$TNM = stnmg_parser();
$TNM_S = $TNM['s'];
$TNM_T = $TNM['t'];
$TNM_N = $TNM['n'];
$TNM_M = $TNM['m'];
$TNM_G = $TNM['g'];

if (count($Diagnosis) > 0)
{
	foreach ($Diagnosis as $dg)
	{
		?>
        <input type="hidden"
               id="dsmkb_<?= $dg['cancer_id'] ?>"
               value="<?= $dg['cancer_ds'] ?>">
        <input type="hidden"
               id="dstext_<?= $dg['cancer_id'] ?>"
               value="<?= $dg['cancer_ds_text'] ?>">
        <input type="hidden"
               id="dsmorph_<?= $dg['cancer_id'] ?>"
               value="<?= $dg['cancer_morph_text'] ?>">
		<?php
	}
	?>

    <b>Импортировать из ранее установленных:</b>
    <div class="row">
        <div class="col-10">
            <select class="form-control form-control-lg"
                    name="ds"
                    id="ds">
                <option value="0">ВЫБЕРИТЕ ИЗ РАНЕЕ УСТАНОВЛЕННЫХ ДИАГНОЗОВ</option>
				<?php
				foreach ($Diagnosis as $ds)
				{
					echo '<option value="' . $ds['cancer_id'] . '">[' . $ds['cancer_ds'] . '] ' . $ds['cancer_ds_text'] . '</option>';
				}
				?>
            </select>
        </div>
        <div class="col">
            <button type="button"
                    onclick="javascript:importDiagnosis()"
                    class="btn btn-warning">Импорт
            </button>
        </div>
    </div>
    <br>

    <b>Поле для импорта данных из ОнкоИнформации ЦАОП:</b>
    <div class="row">
        <div class="col-10">
            <textarea class="form-control form-control-sm"
                      name="oncoinfo"
                      id="oncoinfo"></textarea>
        </div>
        <div class="col">
            <button type="button"
                    onclick="javascript:parseOncoInfo()"
                    class="btn btn-warning">Импорт
            </button>
        </div>
    </div>
    <br>
	<?php
}
?>
<div class="form-group row">
    <div class="col full-center">
		<?php
		bt_notice('<b>ТИТУЛЬНАЯ ИНФОРМАЦИЯ</b>', BT_THEME_SUCCESS);
		?>
    </div>
</div>
<div class="form-group row">
    <div class="col-4">МКБ-10:</div>
    <div class="col">
        <input type="text"
               name="rs_ds_mkb"
               id="rs_ds_mkb"
               class="form-control form-control-sm mysqleditor required-field mkbDiagnosis"
               data-action="edit"
               data-table="<?= $CAOP_ROUTE_SHEET ?>"
               data-assoc="0"
               data-fieldid="rs_id"
               data-id="<?= $RouteSheetForm['rs_id'] ?>"
               data-field="rs_ds_mkb"
               data-unixfield="rs_update_unix"
               data-adequate="MKB"
               data-return="#rs_ds_mkb"
               data-returntype="input"
               data-returnfunc="value"
               placeholder="МКБ-10"
               value="<?= $RouteSheetForm['rs_ds_mkb']; ?>">
    </div>
</div>
<div class="form-group row">
    <div class="col-4">Диагноз:</div>
    <div class="col">
        <textarea name="rs_ds_text"
                  id="rs_ds_text"
                  class="form-control form-control-sm mysqleditor required-field"
                  data-action="edit"
                  data-table="<?= $CAOP_ROUTE_SHEET ?>"
                  data-assoc="0"
                  data-fieldid="rs_id"
                  data-id="<?= $RouteSheetForm['rs_id'] ?>"
                  data-field="rs_ds_text"
                  data-unixfield="rs_update_unix"
                  placeholder="Текст диагноза"><?= $RouteSheetForm['rs_ds_text']; ?></textarea>
    </div>
</div>
<div class="form-group row">
    <div class="col-4">Стадия заболевания, префикс TNM:</div>
    <div class="col">
        <input type="text"
               name="rs_tnm_prefix"
               id="rs_tnm_prefix"
               class="form-control form-control-sm mysqleditor required-field"
               data-action="edit"
               data-table="<?= $CAOP_ROUTE_SHEET ?>"
               data-assoc="0"
               data-fieldid="rs_id"
               data-id="<?= $RouteSheetForm['rs_id'] ?>"
               data-field="rs_tnm_prefix"
               data-unixfield="rs_update_unix"
               placeholder="типа, 'a', 'c', 'p'"
               value="<?= $RouteSheetForm['rs_tnm_prefix'] ?>">
    </div>
</div>
<div class="form-group row">
    <div class="col-4">Стадия заболевания, статус Т:</div>
    <div class="col">
        <?php
        $tDefault = array(
            'key' => -1,
            'value' => 'Выберите T...'
        );
        $tSelected = array(
            'value' => $RouteSheetForm['rs_tnm_t']
        );
        $tSelector = array2select($TNM_T, 'stnmg_code', 'stnmg_code', "rs_tnm_t",
        'class="form-control form-control-sm mysqleditor required-field" data-action="edit"
        id="rs_tnm_t"
        data-table="'.$CAOP_ROUTE_SHEET.'"
        data-assoc="0"
        data-fieldid="rs_id"
        data-id="'.$RouteSheetForm['rs_id'].'"
        data-field="rs_tnm_t"
        data-unixfield="rs_update_unix"', $tDefault, $tSelected);
        echo $tSelector['result'];
        ?>
<!--        <input type="text"-->
<!--               name="rs_tnm_t"-->
<!--               id="rs_tnm_t"-->
<!--               class="form-control form-control-sm mysqleditor required-field"-->
<!--               data-action="edit"-->
<!--               data-table="--><?//= $CAOP_ROUTE_SHEET ?><!--"-->
<!--               data-assoc="0"-->
<!--               data-fieldid="rs_id"-->
<!--               data-id="--><?//= $RouteSheetForm['rs_id'] ?><!--"-->
<!--               data-field="rs_tnm_t"-->
<!--               data-unixfield="rs_update_unix"-->
<!--               placeholder="0, 1, 2 и т.д."-->
<!--               value="--><?//= $RouteSheetForm['rs_tnm_t'] ?><!--">-->
    </div>
    <div class="col"
         data-import="rs_tnm_t">

    </div>
</div>
<div class="form-group row">
    <div class="col-4">Стадия заболевания, статус N:</div>
    <div class="col">
	    <?php
	    $nDefault = array(
		    'key' => -1,
		    'value' => 'Выберите N...'
	    );
	    $nSelected = array(
		    'value' => $RouteSheetForm['rs_tnm_n']
	    );
	    $nSelector = array2select($TNM_N, 'stnmg_code', 'stnmg_code', "rs_tnm_n",
		    'class="form-control form-control-sm mysqleditor required-field" data-action="edit"
        id="rs_tnm_n"
        data-table="'.$CAOP_ROUTE_SHEET.'"
        data-assoc="0"
        data-fieldid="rs_id"
        data-id="'.$RouteSheetForm['rs_id'].'"
        data-field="rs_tnm_n"
        data-unixfield="rs_update_unix"', $nDefault, $nSelected);
	    echo $nSelector['result'];
	    ?>
<!--        <input type="text"-->
<!--               name="rs_tnm_n"-->
<!--               id="rs_tnm_n"-->
<!--               class="form-control form-control-sm mysqleditor required-field"-->
<!--               data-action="edit"-->
<!--               data-table="--><?//= $CAOP_ROUTE_SHEET ?><!--"-->
<!--               data-assoc="0"-->
<!--               data-fieldid="rs_id"-->
<!--               data-id="--><?//= $RouteSheetForm['rs_id'] ?><!--"-->
<!--               data-field="rs_tnm_n"-->
<!--               data-unixfield="rs_update_unix"-->
<!--               placeholder="0, 1, 2 и т.д."-->
<!--               value="--><?//= $RouteSheetForm['rs_tnm_n'] ?><!--">-->
    </div>
    <div class="col"
         data-import="rs_tnm_n">

    </div>
</div>
<div class="form-group row">
    <div class="col-4">Стадия заболевания, статус M:</div>
    <div class="col">
	    <?php
	    $mDefault = array(
		    'key' => -1,
		    'value' => 'Выберите M...'
	    );
	    $mSelected = array(
		    'value' => $RouteSheetForm['rs_tnm_m']
	    );
	    $mSelector = array2select($TNM_M, 'stnmg_code', 'stnmg_code', "rs_tnm_m",
		    'class="form-control form-control-sm mysqleditor required-field" data-action="edit"
        id="rs_tnm_m"
        data-table="'.$CAOP_ROUTE_SHEET.'"
        data-assoc="0"
        data-fieldid="rs_id"
        data-id="'.$RouteSheetForm['rs_id'].'"
        data-field="rs_tnm_m"
        data-unixfield="rs_update_unix"', $mDefault, $mSelected);
	    echo $mSelector['result'];
	    ?>
<!--        <input type="text"-->
<!--               name="rs_tnm_m"-->
<!--               id="rs_tnm_m"-->
<!--               class="form-control form-control-sm mysqleditor required-field"-->
<!--               data-action="edit"-->
<!--               data-table="--><?//= $CAOP_ROUTE_SHEET ?><!--"-->
<!--               data-assoc="0"-->
<!--               data-fieldid="rs_id"-->
<!--               data-id="--><?//= $RouteSheetForm['rs_id'] ?><!--"-->
<!--               data-field="rs_tnm_m"-->
<!--               data-unixfield="rs_update_unix"-->
<!--               placeholder="0, 1, X"-->
<!--               value="--><?//= $RouteSheetForm['rs_tnm_m'] ?><!--">-->
    </div>
    <div class="col"
         data-import="rs_tnm_m">

    </div>
</div>
<div class="form-group row">
    <div class="col-4">Стадия заболевания, дифференцировка G:</div>
    <div class="col">
	    <?php
	    $gDefault = array(
		    'key' => -1,
		    'value' => 'Выберите G...'
	    );
	    $gSelected = array(
		    'value' => $RouteSheetForm['rs_tnm_g']
	    );
	    $gSelector = array2select($TNM_G, 'stnmg_code', 'stnmg_code', "rs_tnm_g",
		    'class="form-control form-control-sm mysqleditor required-field" data-action="edit"
        id="rs_tnm_g"
        data-table="'.$CAOP_ROUTE_SHEET.'"
        data-assoc="0"
        data-fieldid="rs_id"
        data-id="'.$RouteSheetForm['rs_id'].'"
        data-field="rs_tnm_g"
        data-unixfield="rs_update_unix"', $gDefault, $gSelected);
	    echo $gSelector['result'];
//	    echo '>'.$RouteSheetForm['rs_tnm_g'].'<';
	    ?>
<!--        <input type="text"-->
<!--               name="rs_tnm_g"-->
<!--               id="rs_tnm_g"-->
<!--               class="form-control form-control-sm mysqleditor required-field"-->
<!--               data-action="edit"-->
<!--               data-table="--><?//= $CAOP_ROUTE_SHEET ?><!--"-->
<!--               data-assoc="0"-->
<!--               data-fieldid="rs_id"-->
<!--               data-id="--><?//= $RouteSheetForm['rs_id'] ?><!--"-->
<!--               data-field="rs_tnm_g"-->
<!--               data-unixfield="rs_update_unix"-->
<!--               placeholder="1, 2, 3, 4, X"-->
<!--               value="--><?//= $RouteSheetForm['rs_tnm_g'] ?><!--">-->
    </div>
    <div class="col"
         data-import="rs_tnm_g">

    </div>
</div>
<div class="form-group row">
    <div class="col-4">Стадия заболевания:</div>
    <div class="col">
	    <?php
	    $sDefault = array(
		    'key' => -1,
		    'value' => 'Выберите стадию...'
	    );
	    $sSelected = array(
		    'value' => $RouteSheetForm['rs_stadia']
	    );
	    $sSelector = array2select($TNM_S, 'stnmg_code', 'stnmg_code', "rs_stadia",
		    'class="form-control form-control-sm mysqleditor required-field" data-action="edit"
        id="rs_stadia"
        data-table="'.$CAOP_ROUTE_SHEET.'"
        data-assoc="0"
        data-fieldid="rs_id"
        data-id="'.$RouteSheetForm['rs_id'].'"
        data-field="rs_stadia"
        data-unixfield="rs_update_unix"', $sDefault, $sSelected);
	    echo $sSelector['result'];
	    //	    echo '>'.$RouteSheetForm['rs_tnm_g'].'<';
	    ?>
<!--        <input type="text"-->
<!--               name="rs_stadia"-->
<!--               id="rs_stadia"-->
<!--               class="form-control form-control-sm mysqleditor required-field"-->
<!--               data-action="edit"-->
<!--               data-table="--><?//= $CAOP_ROUTE_SHEET ?><!--"-->
<!--               data-assoc="0"-->
<!--               data-fieldid="rs_id"-->
<!--               data-id="--><?//= $RouteSheetForm['rs_id'] ?><!--"-->
<!--               data-field="rs_stadia"-->
<!--               data-unixfield="rs_update_unix"-->
<!--               placeholder="1, 2, 3, 4"-->
<!--               value="--><?//= $RouteSheetForm['rs_stadia'] ?><!--">-->
    </div>
    <div class="col"
         data-import="rs_stadia">

    </div>
</div>
<div class="form-group row">
    <div class="col-4">Морфология опухоли, кратко:</div>
    <div class="col">
        <textarea name="rs_morphology"
                  id="rs_morphology"
                  class="form-control form-control-sm mysqleditor required-field"
                  data-action="edit"
                  data-table="<?= $CAOP_ROUTE_SHEET ?>"
                  data-assoc="0"
                  data-fieldid="rs_id"
                  data-id="<?= $RouteSheetForm['rs_id'] ?>"
                  data-field="rs_morphology"
                  data-unixfield="rs_update_unix"
                  placeholder="аденокарцинома, плоскоклеточный рак и т.д."><?= $RouteSheetForm['rs_morphology']; ?></textarea>
    </div>
    <div class="col"
         data-import="rs_morphology">

    </div>
</div>
<div class="form-group row">
    <div class="col-4">Дата установки диагноза:</div>
    <div class="col">
        <input type="text"
               id="rs_ds_set_date"
               name="rs_ds_set_date"
               class="form-control form-control-sm mysqleditor russianBirth required-field"
               data-action="edit"
               data-table="<?= $CAOP_ROUTE_SHEET ?>"
               data-assoc="0"
               data-fieldid="rs_id"
               data-id="<?= $RouteSheetForm['rs_id'] ?>"
               data-field="rs_ds_set_date"
               data-unixfield="rs_update_unix"
               value="<?= $RouteSheetForm['rs_ds_set_date'] ?>">
    </div>
    <div class="col"
         data-import="rs_ds_set_date">

    </div>
</div>
<div class="form-group row">
    <div class="col-4">Обстоятельства постановки на учет:</div>
    <div class="col">
        <input type="text"
               id="rs_ds_cond"
               name="rs_ds_cond"
               class="form-control form-control-sm mysqleditor required-field"
               data-action="edit"
               data-table="<?= $CAOP_ROUTE_SHEET ?>"
               data-assoc="0"
               data-fieldid="rs_id"
               data-id="<?= $RouteSheetForm['rs_id'] ?>"
               data-field="rs_ds_cond"
               data-unixfield="rs_update_unix"
               value="<?= $RouteSheetForm['rs_ds_cond'] ?>">
    </div>
    <div class="col"
         data-import="rs_ds_cond">

    </div>
</div>
<div class="form-group row">
    <div class="col-4">Дата постановки на учет:</div>
    <div class="col">
        <input type="text"
               id="rs_ds_du_date"
               name="rs_ds_du_date"
               class="form-control form-control-sm mysqleditor russianBirth required-field"
               data-action="edit"
               data-table="<?= $CAOP_ROUTE_SHEET ?>"
               data-assoc="0"
               data-fieldid="rs_id"
               data-id="<?= $RouteSheetForm['rs_id'] ?>"
               data-field="rs_ds_du_date"
               data-unixfield="rs_update_unix"
               value="<?= $RouteSheetForm['rs_ds_du_date'] ?>">
    </div>
    <div class="col"
         data-import="rs_ds_du_date">

    </div>
</div>
<div class="form-group row">
    <div class="col-4">Дата смерти:</div>
    <div class="col">
        <input type="text"
               id="rs_ds_dead_date"
               name="rs_ds_dead_date"
               class="form-control form-control-sm mysqleditor russianBirth"
               data-action="edit"
               data-table="<?= $CAOP_ROUTE_SHEET ?>"
               data-assoc="0"
               data-fieldid="rs_id"
               data-id="<?= $RouteSheetForm['rs_id'] ?>"
               data-field="rs_ds_dead_date"
               data-unixfield="rs_update_unix"
               value="<?= $RouteSheetForm['rs_ds_dead_date'] ?>">
    </div>
</div>
<div class="form-group row">
    <div class="col full-center">
		<?php
		bt_notice('<b>СРОКИ ОБРАЩЕНИЯ НА ЭТАПЫ МАРШРУТИЗАЦИИ</b>', BT_THEME_SUCCESS);
		?>
    </div>
</div>
<div class="form-group row">
    <div class="col-4">Появление первых признаков ЗНО:</div>
    <div class="col-2">
        <input type="text"
               id="rs_stage_po_pe_pr_zno_date"
               name="rs_stage_po_pe_pr_zno_date"
               class="form-control form-control-sm mysqleditor russianBirth required-field"
               data-action="edit"
               data-table="<?= $CAOP_ROUTE_SHEET ?>"
               data-assoc="0"
               data-fieldid="rs_id"
               data-id="<?= $RouteSheetForm['rs_id'] ?>"
               data-field="rs_stage_po_pe_pr_zno_date"
               data-unixfield="rs_update_unix"
               value="<?= $RouteSheetForm['rs_stage_po_pe_pr_zno_date'] ?>"
               placeholder="Дата">
    </div>
    <div class="col">
        <textarea name="rs_stage_po_pe_pr_zno_comm"
                  id="rs_stage_po_pe_pr_zno_comm"
                  class="form-control form-control-sm mysqleditor"
                  data-action="edit"
                  data-table="<?= $CAOP_ROUTE_SHEET ?>"
                  data-assoc="0"
                  data-fieldid="rs_id"
                  data-id="<?= $RouteSheetForm['rs_id'] ?>"
                  data-field="rs_stage_po_pe_pr_zno_comm"
                  data-unixfield="rs_update_unix"
                  placeholder="Комментарий"><?= $RouteSheetForm['rs_stage_po_pe_pr_zno_comm']; ?></textarea>
    </div>
    <div class="col"
         data-import="rs_stage_po_pe_pr_zno_date">

    </div>
</div>
<div class="form-group row">
    <div class="col full-center"><b>Первичное обращение за медицинской помощью</b></div>
</div>
<div class="form-group row">
    <div class="col-4">На фельдшерско-акушерский пункт (офис врача общей практики):</div>
    <div class="col-2">
        <input type="text"
               id="rs_stage_fap_date"
               name="rs_stage_fap_date"
               class="form-control form-control-sm mysqleditor russianBirth"
               data-action="edit"
               data-table="<?= $CAOP_ROUTE_SHEET ?>"
               data-assoc="0"
               data-fieldid="rs_id"
               data-id="<?= $RouteSheetForm['rs_id'] ?>"
               data-field="rs_stage_fap_date"
               data-unixfield="rs_update_unix"
               value="<?= $RouteSheetForm['rs_stage_fap_date'] ?>"
               placeholder="Дата">
    </div>
    <div class="col">
        <textarea name="rs_stage_fap_comm"
                  id="rs_stage_fap_comm"
                  class="form-control form-control-sm mysqleditor"
                  data-action="edit"
                  data-table="<?= $CAOP_ROUTE_SHEET ?>"
                  data-assoc="0"
                  data-fieldid="rs_id"
                  data-id="<?= $RouteSheetForm['rs_id'] ?>"
                  data-field="rs_stage_fap_comm"
                  data-unixfield="rs_update_unix"
                  placeholder="Комментарий"><?= $RouteSheetForm['rs_stage_fap_comm']; ?></textarea>
    </div>
</div>
<div class="form-group row">
    <div class="col-4">В смотровой кабинет:</div>
    <div class="col-2">
        <input type="text"
               id="rs_stage_sm_kab_date"
               name="rs_stage_sm_kab_date"
               class="form-control form-control-sm mysqleditor russianBirth"
               data-action="edit"
               data-table="<?= $CAOP_ROUTE_SHEET ?>"
               data-assoc="0"
               data-fieldid="rs_id"
               data-id="<?= $RouteSheetForm['rs_id'] ?>"
               data-field="rs_stage_sm_kab_date"
               data-unixfield="rs_update_unix"
               value="<?= $RouteSheetForm['rs_stage_sm_kab_date'] ?>"
               placeholder="Дата">
    </div>
    <div class="col">
		<textarea
                name="rs_stage_sm_kab_comm"
                id="rs_stage_sm_kab_comm"
                class="form-control form-control-sm mysqleditor"
                data-action="edit"
                data-table="<?= $CAOP_ROUTE_SHEET ?>"
                data-assoc="0"
                data-fieldid="rs_id"
                data-id="<?= $RouteSheetForm['rs_id'] ?>"
                data-field="rs_stage_sm_kab_comm"
                data-unixfield="rs_update_unix"
                placeholder="Комментарий"><?= $RouteSheetForm['rs_stage_sm_kab_comm']; ?></textarea>
    </div>
</div>
<div class="form-group row">
    <div class="col-4">К врачу участковой поликлиники (женской консультации, стоматологической поликлиники,
                       кожно-венерологического диспансера):
    </div>
    <div class="col-2">
        <input type="text"
               id="rs_stage_vr_u4_pol_date"
               name="rs_stage_vr_u4_pol_date"
               class="form-control form-control-sm mysqleditor russianBirth required-field"
               data-action="edit"
               data-table="<?= $CAOP_ROUTE_SHEET ?>"
               data-assoc="0"
               data-fieldid="rs_id"
               data-id="<?= $RouteSheetForm['rs_id'] ?>"
               data-field="rs_stage_vr_u4_pol_date"
               data-unixfield="rs_update_unix"
               value="<?= $RouteSheetForm['rs_stage_vr_u4_pol_date'] ?>"
               placeholder="Дата">
    </div>
    <div class="col">
		<textarea
                name="rs_stage_vr_u4_pol_comm"
                id="rs_stage_vr_u4_pol_comm"
                class="form-control form-control-sm mysqleditor"
                data-action="edit"
                data-table="<?= $CAOP_ROUTE_SHEET ?>"
                data-assoc="0"
                data-fieldid="rs_id"
                data-id="<?= $RouteSheetForm['rs_id'] ?>"
                data-field="rs_stage_vr_u4_pol_comm"
                data-unixfield="rs_update_unix"
                placeholder="Комментарий"><?= $RouteSheetForm['rs_stage_vr_u4_pol_comm']; ?></textarea>
    </div>
    <div class="col"
         data-import="rs_stage_vr_u4_pol_date">

    </div>
</div>
<div class="form-group row">
    <div class="col-4">В стационар общей лечебной сети:</div>
    <div class="col-2">
        <input type="text"
               id="rs_stage_stac_date"
               name="rs_stage_stac_date"
               class="form-control form-control-sm mysqleditor russianBirth"
               data-action="edit"
               data-table="<?= $CAOP_ROUTE_SHEET ?>"
               data-assoc="0"
               data-fieldid="rs_id"
               data-id="<?= $RouteSheetForm['rs_id'] ?>"
               data-field="rs_stage_stac_date"
               data-unixfield="rs_update_unix"
               value="<?= $RouteSheetForm['rs_stage_stac_date'] ?>"
               placeholder="Дата">
    </div>
    <div class="col">
		<textarea
                name="rs_stage_stac_comm"
                id="rs_stage_stac_comm"
                class="form-control form-control-sm mysqleditor"
                data-action="edit"
                data-table="<?= $CAOP_ROUTE_SHEET ?>"
                data-assoc="0"
                data-fieldid="rs_id"
                data-id="<?= $RouteSheetForm['rs_id'] ?>"
                data-field="rs_stage_stac_comm"
                data-unixfield="rs_update_unix"
                placeholder="Комментарий"><?= $RouteSheetForm['rs_stage_stac_comm']; ?></textarea>
    </div>
</div>
<div class="form-group row">
    <div class="col-4">Обращение в первичный онкологический кабинет:</div>
    <div class="col-2">
        <input type="text"
               id="rs_stage_pe_onk_kab_date"
               name="rs_stage_pe_onk_kab_date"
               class="form-control form-control-sm mysqleditor russianBirth"
               data-action="edit"
               data-table="<?= $CAOP_ROUTE_SHEET ?>"
               data-assoc="0"
               data-fieldid="rs_id"
               data-id="<?= $RouteSheetForm['rs_id'] ?>"
               data-field="rs_stage_pe_onk_kab_date"
               data-unixfield="rs_update_unix"
               value="<?= $RouteSheetForm['rs_stage_pe_onk_kab_date'] ?>"
               placeholder="Дата">
    </div>
    <div class="col">
		<textarea
                name="rs_stage_pe_onk_kab_comm"
                id="rs_stage_pe_onk_kab_comm"
                class="form-control form-control-sm mysqleditor"
                data-action="edit"
                data-table="<?= $CAOP_ROUTE_SHEET ?>"
                data-assoc="0"
                data-fieldid="rs_id"
                data-id="<?= $RouteSheetForm['rs_id'] ?>"
                data-field="rs_stage_pe_onk_kab_comm"
                data-unixfield="rs_update_unix"
                placeholder="Комментарий"><?= $RouteSheetForm['rs_stage_pe_onk_kab_comm']; ?></textarea>
    </div>
</div>
<div class="form-group row">
    <div class="col-4">Обращение в ЦАОП:</div>
    <div class="col-2">
        <input type="text"
               id="rs_stage_caop_date"
               name="rs_stage_caop_date"
               class="form-control form-control-sm mysqleditor russianBirth required-field"
               data-action="edit"
               data-table="<?= $CAOP_ROUTE_SHEET ?>"
               data-assoc="0"
               data-fieldid="rs_id"
               data-id="<?= $RouteSheetForm['rs_id'] ?>"
               data-field="rs_stage_caop_date"
               data-unixfield="rs_update_unix"
               value="<?= $RouteSheetForm['rs_stage_caop_date'] ?>"
               placeholder="Дата">
    </div>
    <div class="col">
		<textarea
                name="rs_stage_caop_comm"
                id="rs_stage_caop_comm"
                class="form-control form-control-sm mysqleditor"
                data-action="edit"
                data-table="<?= $CAOP_ROUTE_SHEET ?>"
                data-assoc="0"
                data-fieldid="rs_id"
                data-id="<?= $RouteSheetForm['rs_id'] ?>"
                data-field="rs_stage_caop_comm"
                data-unixfield="rs_update_unix"
                placeholder="Комментарий"><?= $RouteSheetForm['rs_stage_caop_comm']; ?></textarea>
    </div>
</div>
<div class="form-group row">
    <div class="col-4">Обращение в консультативно-диагностическую поликлинику ГБУЗ «СОКОД» (ГБУЗ «ТГКБ №5»):</div>
    <div class="col-2">
        <input type="text"
               id="rs_stage_kdo_date"
               name="rs_stage_kdo_date"
               class="form-control form-control-sm mysqleditor russianBirth"
               data-action="edit"
               data-table="<?= $CAOP_ROUTE_SHEET ?>"
               data-assoc="0"
               data-fieldid="rs_id"
               data-id="<?= $RouteSheetForm['rs_id'] ?>"
               data-field="rs_stage_kdo_date"
               data-unixfield="rs_update_unix"
               value="<?= $RouteSheetForm['rs_stage_kdo_date'] ?>"
               placeholder="Дата">
    </div>
    <div class="col">
		<textarea
                name="rs_stage_kdo_comm"
                id="rs_stage_kdo_comm"
                class="form-control form-control-sm mysqleditor"
                data-action="edit"
                data-table="<?= $CAOP_ROUTE_SHEET ?>"
                data-assoc="0"
                data-fieldid="rs_id"
                data-id="<?= $RouteSheetForm['rs_id'] ?>"
                data-field="rs_stage_kdo_comm"
                data-unixfield="rs_update_unix"
                placeholder="Комментарий"><?= $RouteSheetForm['rs_stage_kdo_comm']; ?></textarea>
    </div>
</div>
<div class="form-group row">
    <div class="col-4">Начало специализированного лечения ЗНО:</div>
    <div class="col-2">
        <input type="text"
               id="rs_stage_cure_date"
               name="rs_stage_cure_date"
               class="form-control form-control-sm mysqleditor russianBirth"
               data-action="edit"
               data-table="<?= $CAOP_ROUTE_SHEET ?>"
               data-assoc="0"
               data-fieldid="rs_id"
               data-id="<?= $RouteSheetForm['rs_id'] ?>"
               data-field="rs_stage_cure_date"
               data-unixfield="rs_update_unix"
               value="<?= $RouteSheetForm['rs_stage_cure_date'] ?>"
               placeholder="Дата">
    </div>
    <div class="col">
		<textarea
                name="rs_stage_cure_comm"
                id="rs_stage_cure_comm"
                class="form-control form-control-sm mysqleditor"
                data-action="edit"
                data-table="<?= $CAOP_ROUTE_SHEET ?>"
                data-assoc="0"
                data-fieldid="rs_id"
                data-id="<?= $RouteSheetForm['rs_id'] ?>"
                data-field="rs_stage_cure_comm"
                data-unixfield="rs_update_unix"
                placeholder="Комментарий"><?= $RouteSheetForm['rs_stage_cure_comm']; ?></textarea>
    </div>
</div>
<div class="form-group row">
    <div class="col-4">Дата заполнения:</div>
    <div class="col">
        <input type="text"
               id="rs_fill_date"
               name="rs_fill_date"
               class="form-control form-control-sm mysqleditor russianBirth required-field"
               data-action="edit"
               data-table="<?= $CAOP_ROUTE_SHEET ?>"
               data-assoc="0"
               data-fieldid="rs_id"
               data-id="<?= $RouteSheetForm['rs_id'] ?>"
               data-field="rs_fill_date"
               data-unixfield="rs_update_unix"
               value="<?= $RouteSheetForm['rs_fill_date'] ?>">
    </div>
    <div class="col"
         data-import="rs_fill_date">

    </div>
</div>
<div class="form-group row">
    <div class="col">
        <button type="button"
                class="btn btn-primary col-12"
                onclick="javascript:window.location.href='/routeSheet/<?= $PatientData['patid_id'] ?>'">ГОТОВО
        </button>
    </div>
</div>