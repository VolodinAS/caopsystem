<?php
$measures_tables = array(
	array(
		'table' =>  'caop_ds_dose_measure_type',
		'title' =>  'Настройка единиц измерения дозировки препарата',
		'example'   =>  'г/мл, мл, г, мг и т.д.'
	),
	array(
		'table' =>  'caop_ds_dose_period_type',
		'title' =>  'Настройка единиц измерения скорости введения препарата',
		'example'   =>  'сутки, час, кг и т.д.'
	),
	array(
		'table' =>  'caop_ds_freq_period_type',
		'title' =>  'Настройка частоты введения препарата',
		'example'   =>  'час, день, неделя, месяц'
	),
	array(
		'table' =>  'caop_ds_research_types',
		'title' =>  'Настройка показателей анализов',
		'example'   =>  ''
	)
)
?>

<?php
foreach ($measures_tables as $measures_table)
{
?>
	<?=spoiler_begin($measures_table['title'], 'collapse_'.$measures_table['table'], '');?>
    <div class="text-muted"><b>Пример:</b> <?=$measures_table['example'];?></div><br>
	<button type="button" class="btn btn-primary btn-sm addField" data-table="<?=$measures_table['table'];?>">Добавить поле</button>
	<button type="button" class="btn btn-success btn-sm saveFields" data-table="<?=$measures_table['table'];?>">Сохранить поля</button>
	<div id="id_<?=$measures_table['table'];?>">
    <?php
    $MeasuresData = getarr($measures_table['table'], "1", "ORDER BY type_order ASC");
    if ( count($MeasuresData) > 0 )
    {
	    foreach ($MeasuresData as $measuresDatum)
	    {
        ?>
            <div class="row row_<?=$measures_table['table'];?>">
                <div class="col p-1"><input type="text" class="form-control form-control-sm type_title" value="<?=$measuresDatum['type_title'];?>" placeholder="Название поля"></div>
                <div class="col p-1"><input type="text" class="form-control form-control-sm type_enabled" value="<?=$measuresDatum['type_enabled'];?>" placeholder="Значение включено(1) или нет(0)"></div>
                <div class="col p-1"><input type="text" class="form-control form-control-sm type_order" value="<?=$measuresDatum['type_order'];?>" placeholder="Порядок сортировки"></div>
                <div class="col p-1"><input type="text" class="form-control form-control-sm type_addon" value="<?=$measuresDatum['type_addon'];?>" placeholder="Доп. параметр"></div>
                <div class="col p-1"><input type="text" class="form-control form-control-sm type_addon_2" value="<?=$measuresDatum['type_addon_2'];?>" placeholder="Доп. параметр 2"></div>
                <div class="col p-1"><input type="text" class="form-control form-control-sm type_addon_3" value="<?=$measuresDatum['type_addon_3'];?>" placeholder="Доп. параметр 3"></div>
                <div class="col p-1"><input type="text" class="form-control form-control-sm type_addon_4" value="<?=$measuresDatum['type_addon_4'];?>" placeholder="Доп. параметр 4"></div>
                <div class="col p-1"><button type="button" class="btn btn-sm btn-danger deleteField">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x" viewBox="0 0 16 16">
                            <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"></path>
                        </svg>
                    </button>
                </div>
            </div>
        <?php
        }
    } else
    {
        bt_notice('Нет полей для сохранения', BT_THEME_WARNING);
    }
    ?>
	</div>
	<?=spoiler_end();?>
<?php
}
?>
