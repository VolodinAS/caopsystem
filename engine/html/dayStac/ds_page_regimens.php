<?php
$RegimensTemplate = getarr(CAOP_DS_REGIMENS, "1");

$DOSE_MEASURE_TYPES = getarr(CAOP_DS_DOSE_MEASURE_TYPE, "type_enabled='1'", "ORDER BY type_order ASC");
$DOSE_MEASURE_TYPES_ID = getDoctorsById($DOSE_MEASURE_TYPES, 'type_id');

$DOSE_PERIOD_TYPES = getarr(CAOP_DS_DOSE_PERIOD_TYPE, "type_enabled='1'", "ORDER BY type_order ASC");
$DOSE_PERIOD_TYPES_ID = getDoctorsById($DOSE_PERIOD_TYPES, 'type_id');

$DOSE_FREQ_PERIOD_TYPES = getarr(CAOP_DS_FREQ_PERIOD_TYPE, "type_enabled='1'", "ORDER BY type_order ASC");
$DOSE_FREQ_PERIOD_TYPES_ID = getDoctorsById($DOSE_FREQ_PERIOD_TYPES, 'type_id');

if ( count($RegimensTemplate) > 0 )
{
	
	foreach ($RegimensTemplate as $Regimen)
	{

		$Regimen['regimen_dose'] = ($Regimen['regimen_dose'] == '' || $Regimen['regimen_dose'] == 0) ? '' : $Regimen['regimen_dose'];
		$Regimen['regimen_freq_amount'] = ($Regimen['regimen_freq_amount'] == '' || $Regimen['regimen_freq_amount'] == 0) ? '' : $Regimen['regimen_freq_amount'];
		$Regimen['regimen_freq_period_amount'] = ($Regimen['regimen_freq_period_amount'] == '' || $Regimen['regimen_freq_period_amount'] == 0) ? '' : $Regimen['regimen_freq_period_amount'];
		?>
		<? spoiler_begin('Схема лечения [#'.$Regimen['regimen_id'].']: "'.$Regimen['regimen_title'].'" ('.$Regimen['regimen_drug'].')', 'regimen_'.$Regimen['regimen_id']);?>
		<form id="form_regimen_<?=$Regimen['regimen_id']?>">
			<input type="hidden" name="regimen_id" hiud="regimen_id" value="<?=$Regimen['regimen_id']?>">
			<div class="row">
				<div class="col">
					<label class="sr-only" for="regimen_title">Название схемы лечения</label>
					<div class="input-group mb-2">
						<div class="input-group-prepend">
							<div class="input-group-text" <?=super_bootstrap_tooltip('Название схемы лечения');?>>
								<?=BT_ICON_REGIMEN_NAME;?>
							</div>
						</div>
						<input type="text" class="form-control" huid="regimen_title" name="regimen_title" placeholder="Название схемы лечения" value="<?=$Regimen['regimen_title'];?>">
					</div>
				</div>
				<div class="col">
					<label class="sr-only" for="regimen_drug">Название препарата</label>
					<div class="input-group mb-2">
						<div class="input-group-prepend">
							<div class="input-group-text" <?=super_bootstrap_tooltip('Название препарата');?>>
								<?=BT_ICON_DRUG_NAME;?>
							</div>
						</div>
						<input type="text" class="form-control" huid="regimen_drug" name="regimen_drug" placeholder="Название препарата" value="<?=$Regimen['regimen_drug'];?>">
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col">
					<label class="sr-only" for="regimen_dose">Дозировка препарата</label>
					<div class="input-group mb-2">
						<div class="input-group-prepend">
							<div class="input-group-text" <?=super_bootstrap_tooltip('Дозировка препарата');?>>
								<?=BT_ICON_DRUG_DOSE;?>
							</div>
						</div>
						<input type="text" class="form-control" huid="regimen_dose" name="regimen_dose" placeholder="Дозировка препарата" value="<?=$Regimen['regimen_dose'];?>">
					</div>
				</div>
				<div class="col-3">
					<label class="sr-only" for="regimen_dose_measure_type">Измерение дозировки</label>
					<div class="input-group mb-2">
						<div class="input-group-prepend">
							<div class="input-group-text" <?=super_bootstrap_tooltip('Измерение дозировки');?>>
								<?=BT_ICON_DRUG_MEASURE;?>
							</div>
						</div>
						<?php
						$DOSE_MEASURE_TYPES_DS = array(
							'key'   =>   'regimen_dose_measure_type',
							'value'   =>  $Regimen['regimen_dose_measure_type']
						);
						$DOSE_MEASURE_TYPES_SELECT = array2select($DOSE_MEASURE_TYPES, 'type_id', 'type_title', 'regimen_dose_measure_type', ' huid="regimen_dose_measure_type" class="form-control"', $DOSE_MEASURE_TYPES_DEFAULT, $DOSE_MEASURE_TYPES_DS);
						?>
						<?=$DOSE_MEASURE_TYPES_SELECT['result'];?>
					</div>
				</div>
				<div class="col-auto">
					В
				</div>
				<div class="col-3">
					<label class="sr-only" for="regimen_dose_period_type">Время дозировки</label>
					<div class="input-group mb-2">
						<div class="input-group-prepend">
							<div class="input-group-text" <?=super_bootstrap_tooltip('Время дозировки');?>>
								<?=BT_ICON_DRUG_PERIOD;?>
							</div>
						</div>
						<?php
						$DOSE_PERIOD_TYPES_DS = array(
							'key'   =>   'regimen_dose_measure_type',
							'value'   =>  $Regimen['regimen_dose_period_type']
						);
						$DOSE_PERIOD_TYPES_SELECT = array2select($DOSE_PERIOD_TYPES, 'type_id', 'type_title', 'regimen_dose_period_type', ' huid="regimen_dose_period_type" class="form-control"', $DOSE_PERIOD_TYPES_DEFAULT, $DOSE_PERIOD_TYPES_DS);
						?>
						<?=$DOSE_PERIOD_TYPES_SELECT['result'];?>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col">
					<label class="sr-only" for="regimen_freq_amount">Количество введений</label>
					<div class="input-group mb-2">
						<div class="input-group-prepend">
							<div class="input-group-text" <?=super_bootstrap_tooltip('Количество введений');?>>
								<?=BT_ICON_DRUG_FREQ_AMOUNT;?>
							</div>
						</div>
						<input type="text" class="form-control" huid="regimen_freq_amount" name="regimen_freq_amount" placeholder="Количество введений" value="<?=$Regimen['regimen_freq_amount'];?>">
					</div>
				</div>
				<div class="col-auto">
					РАЗ В
				</div>
				<div class="col-3">
					<label class="sr-only" for="regimen_freq_period_amount">Периодичность</label>
					<div class="input-group mb-2">
						<div class="input-group-prepend">
							<div class="input-group-text" <?=super_bootstrap_tooltip('Периодичность введений');?>>
								<?=BT_ICON_DRUG_PERIOD_AMOUNT;?>
							</div>
						</div>
						<input type="text" class="form-control" huid="regimen_freq_period_amount" name="regimen_freq_period_amount" placeholder="Периодичность" value="<?=$Regimen['regimen_freq_period_amount'];?>">
					</div>
				</div>
				<div class="col-3">
					<label class="sr-only" for="regimen_freq_period_type">Временной промежуток</label>
					<div class="input-group mb-2">
						<div class="input-group-prepend">
							<div class="input-group-text" <?=super_bootstrap_tooltip('Временной промежуток');?>>
								<?=BT_ICON_DRUG_FREQ_PERIOD;?>
							</div>
						</div>
						<?php
						$DOSE_FREQ_PERIOD_TYPES_DS = array(
							'key'   =>   'regimen_dose_measure_type',
							'value'   =>  $Regimen['regimen_freq_period_type']
						);
						$DOSE_FREQ_PERIOD_TYPES_SELECT = array2select($DOSE_FREQ_PERIOD_TYPES, 'type_id', 'type_title', 'regimen_freq_period_type', ' huid="regimen_freq_period_type" class="form-control"', $DOSE_FREQ_PERIOD_TYPES_DEFAULT, $DOSE_FREQ_PERIOD_TYPES_DS);
						?>
						<?=$DOSE_FREQ_PERIOD_TYPES_SELECT['result'];?>
					</div>
				</div>
			</div>
			
			<div class="row">
				<div class="col">
					<label class="sr-only" for="regimen_dasigna">Примечание к процедуре</label>
					<div class="input-group mb-2">
						<div class="input-group-prepend align-top">
							<div class="input-group-text align-top" <?=super_bootstrap_tooltip('Примечание к процедуре');?>>
								<?=BT_ICON_DIAG_TEXT;?>
							</div>
						</div>
						<textarea class="form-control autosizer" name="regimen_dasigna" huid="regimen_dasigna" rows="5" placeholder="Пример: развести в 400 мл изотонического раствора Натрия Хлорида, вводить внутривенно, капельно"><?=$Regimen['regimen_dasigna'];?></textarea>
					</div>
				</div>
			</div>
			
			<div class="row">
				<div class="col">
					<button class="btn col btn-primary btn-sm saveRegimen" data-regimen="<?=$Regimen['regimen_id'];?>">СОХРАНИТЬ</button>
				</div>
				<div class="col-auto">
					<button class="btn col btn-danger btn-sm deleteRegimen" data-regimen="<?=$Regimen['regimen_id'];?>">УДАЛИТЬ</button>
				</div>
			</div>
		</form>
		<? spoiler_end();?>
		<?php
	}
	
} else bt_notice('Нет шаблонов схем лечения', BT_THEME_WARNING);