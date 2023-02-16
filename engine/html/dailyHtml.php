<?php

//debug($PatientData);
//debug($BlankPrint);

$HTTP = $BlankPrint;
extract($HTTP, EXTR_PREFIX_SAME, '_caop');
$HTTP = $PatientData;
extract($HTTP, EXTR_PREFIX_SAME, '_caop');

$table_cell_header_width = 35;

$SHOW_DATA = array(
	array(
		'field' => 'daily_complains',
		'title' => 'Жалобы'
	),
	array(
		'field' => 'daily_anam_disease',
		'title' => 'Анамнез заболевания'
	),
	array(
		'field' => 'daily_anam_allergy',
		'title' => 'Аллергический анамнез'
	),
	array(
		'field' => 'daily_anam_life',
		'title' => 'Анамнез жизни'
	),
	array(
		'field' => 'daily_anam_family',
		'title' => 'Семейный анамнез'
	),
	array(
		'field' => 'daily_researches',
		'title' => 'Обследование'
	),
	array(
		'field' => 'daily_presens',
		'title' => 'Объективный статус'
	),
	array(
		'field' => 'daily_local',
		'title' => 'Локальный статус'
	)
);
?>
<div class="size-12pt">
    <table width="100%"
           class="tbc"
           border="0">
        <tr>
            <!--		<td width="--><? //=$table_cell_header_width;?><!--%">-->
            <!--			&nbsp;-->
            <!--		</td>-->
            <td>
                <b><?= $daily_type; ?> приём пациента:</b><br><?= mb_ucwords($patid_name); ?>, <?= $patid_birth; ?>
            </td>
            <td width="<?= $table_cell_header_width; ?>%"
                align="right">
                <b>Дата/время приёма:</b><br><?= $daily_date_create; ?>
            </td>
        </tr>
    </table>
	
	<?php
	foreach ($SHOW_DATA as $showMe)
	{
		if (strlen($BlankPrint[$showMe['field']]) > 0)
		{
			echo '<br><b>' . $showMe['title'] . ':</b> ' . str_replace("\n", "<br/>", $BlankPrint[$showMe['field']]);
		}
	}
	?>
    <br><br>
    <b>Диагноз:</b> [<?= $daily_main_dg_mkb; ?>] <?= $daily_main_dg_text; ?>
    <br><br>
    <b>Рекомендации:</b> <?= $daily_recom; ?>
    <br><br>
    <div style="text-align: right">
        Подпись врача <u><?= _nbsp(30); ?></u> <?= docNameShort($DoctorData); ?>
    </div>
</div>

<?php
//debug($BlankPrint);
//debug($PatientData);
//debug($DoctorData);
?>

