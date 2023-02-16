<? spoiler_begin('Назначенное лечение', 'pateint_procedure', '');?>
<?php

if ( $isPatient )
{
	$VisitRegimens = getarr(CAOP_DS_VISITS_REGIMENS, "visreg_dspatid='{$PatientData['patient_id']}' AND visreg_dirlist_id='{$dirlist['dirlist_id']}'");
	if ( count($VisitRegimens) > 0 )
	{
		
	 
		foreach ($VisitRegimens as $visitRegimen)
		{
			$visitRegimen['visreg_dose'] = ($visitRegimen['visreg_dose'] == '' || $visitRegimen['visreg_dose'] == 0) ? '' : $visitRegimen['visreg_dose'];
			$visitRegimen['visreg_freq_amount'] = ($visitRegimen['visreg_freq_amount'] == '' || $visitRegimen['visreg_freq_amount'] == 0) ? '' : $visitRegimen['visreg_freq_amount'];
			$visitRegimen['visreg_freq_period_amount'] = ($visitRegimen['visreg_freq_period_amount'] == '' || $visitRegimen['visreg_freq_period_amount'] == 0) ? '' : $visitRegimen['visreg_freq_period_amount'];
			?>
			<? spoiler_begin('Схема лечения [#'.$visitRegimen['visreg_id'].']: "'.$visitRegimen['visreg_title'].'" ('.$visitRegimen['visreg_drug'].')', 'visreg_'.$visitRegimen['visreg_id']);?>
			<form id="form_visreg_<?=$visitRegimen['visreg_id']?>">
				<input type="hidden" name="visreg_id" hiud="visreg_id" value="<?=$visitRegimen['visreg_id']?>">
				<div class="row">
					<div class="col">
						<label class="sr-only" for="visreg_temp_load">Загрузить схему из Шаблона</label>
						<div class="input-group mb-2">
							<div class="input-group-prepend">
								<div class="input-group-text" <?=super_bootstrap_tooltip('Загрузить схему из Шаблона');?>>
									<?=BT_ICON_REGIMENS_LIST;?>
								</div>
							</div>
							<select id="visreg_template_<?=$visitRegimen['visreg_id'];?>" data-visregid="<?=$visitRegimen['visreg_id'];?>" class="form-control">
								<?=$RegimenSelectOptions;?>
							</select>
						</div>
					</div>
					<div class="col-auto">
						<button class="btn col btn-secondary btn-sm importTemplate" data-visreg="<?=$visitRegimen['visreg_id'];?>">Импорт</button>
					</div>
					<div class="col-auto">
						<button class="btn col btn-warning btn-sm clearTemplate" data-visreg="<?=$visitRegimen['visreg_id'];?>">Очистить</button>
					</div>
				</div>
				<div class="row">
					<div class="col">
						<label class="sr-only" for="visreg_title">Название схемы лечения</label>
						<div class="input-group mb-2">
							<div class="input-group-prepend">
								<div class="input-group-text" <?=super_bootstrap_tooltip('Название схемы лечения');?>>
									<?=BT_ICON_REGIMEN_NAME;?>
								</div>
							</div>
							<input type="text" class="form-control required-field" huid="visreg_title" name="visreg_title" placeholder="Название схемы лечения" value="<?=$visitRegimen['visreg_title'];?>">
						</div>
					</div>
					<div class="col">
						<label class="sr-only" for="visreg_drug">Название препарата</label>
						<div class="input-group mb-2">
							<div class="input-group-prepend">
								<div class="input-group-text" <?=super_bootstrap_tooltip('Название препарата');?>>
									<?=BT_ICON_DRUG_NAME;?>
								</div>
							</div>
							<input type="text" class="form-control required-field" huid="visreg_drug" name="visreg_drug" placeholder="Название препарата" value="<?=$visitRegimen['visreg_drug'];?>">
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col">
						<label class="sr-only" for="visreg_dose">Дозировка препарата</label>
						<div class="input-group mb-2">
							<div class="input-group-prepend">
								<div class="input-group-text" <?=super_bootstrap_tooltip('Дозировка препарата');?>>
									<?=BT_ICON_DRUG_DOSE;?>
								</div>
							</div>
							<input type="text" class="form-control required-field" huid="visreg_dose" name="visreg_dose" placeholder="Дозировка препарата" value="<?=$visitRegimen['visreg_dose'];?>">
						</div>
					</div>
					<div class="col-3">
						<label class="sr-only" for="visreg_dose_measure_type">Измерение дозировки</label>
						<div class="input-group mb-2">
							<div class="input-group-prepend">
								<div class="input-group-text" <?=super_bootstrap_tooltip('Измерение дозировки');?>>
									<?=BT_ICON_DRUG_MEASURE;?>
								</div>
							</div>
							<?php
							$DOSE_MEASURE_TYPES_DS = array(
								'key'   =>   'visreg_dose_measure_type',
								'value'   =>  $visitRegimen['visreg_dose_measure_type']
							);
							$DOSE_MEASURE_TYPES_SELECT = array2select($DOSE_MEASURE_TYPES, 'type_id', 'type_title', 'visreg_dose_measure_type', ' huid="visreg_dose_measure_type" class="form-control required-field"', $DOSE_MEASURE_TYPES_DEFAULT, $DOSE_MEASURE_TYPES_DS);
							?>
							<?=$DOSE_MEASURE_TYPES_SELECT['result'];?>
						</div>
					</div>
					<div class="col-auto">
						В
					</div>
					<div class="col-3">
						<label class="sr-only" for="visreg_dose_period_type">Время дозировки</label>
						<div class="input-group mb-2">
							<div class="input-group-prepend">
								<div class="input-group-text" <?=super_bootstrap_tooltip('Время дозировки');?>>
									<?=BT_ICON_DRUG_PERIOD;?>
								</div>
							</div>
							<?php
							$DOSE_PERIOD_TYPES_DS = array(
								'key'   =>   'visreg_dose_measure_type',
								'value'   =>  $visitRegimen['visreg_dose_period_type']
							);
							$DOSE_PERIOD_TYPES_SELECT = array2select($DOSE_PERIOD_TYPES, 'type_id', 'type_title', 'visreg_dose_period_type', ' huid="visreg_dose_period_type" class="form-control required-field"', $DOSE_PERIOD_TYPES_DEFAULT, $DOSE_PERIOD_TYPES_DS);
							?>
							<?=$DOSE_PERIOD_TYPES_SELECT['result'];?>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col">
						<label class="sr-only" for="visreg_freq_amount">Количество введений</label>
						<div class="input-group mb-2">
							<div class="input-group-prepend">
								<div class="input-group-text" <?=super_bootstrap_tooltip('Количество введений');?>>
									<?=BT_ICON_DRUG_FREQ_AMOUNT;?>
								</div>
							</div>
							<input type="text" class="form-control required-field" huid="visreg_freq_amount" name="visreg_freq_amount" placeholder="Количество введений" value="<?=$visitRegimen['visreg_freq_amount'];?>">
						</div>
					</div>
					<div class="col-auto">
						РАЗ В
					</div>
					<div class="col-3">
						<label class="sr-only" for="visreg_freq_period_amount">Периодичность</label>
						<div class="input-group mb-2">
							<div class="input-group-prepend">
								<div class="input-group-text" <?=super_bootstrap_tooltip('Периодичность введений');?>>
									<?=BT_ICON_DRUG_PERIOD_AMOUNT;?>
								</div>
							</div>
							<input type="text" class="form-control required-field" huid="visreg_freq_period_amount" name="visreg_freq_period_amount" placeholder="Периодичность" value="<?=$visitRegimen['visreg_freq_period_amount'];?>">
						</div>
					</div>
					<div class="col-3">
						<label class="sr-only" for="visreg_freq_period_type">Временной промежуток</label>
						<div class="input-group mb-2">
							<div class="input-group-prepend">
								<div class="input-group-text" <?=super_bootstrap_tooltip('Временной промежуток');?>>
									<?=BT_ICON_DRUG_FREQ_PERIOD;?>
								</div>
							</div>
							<?php
							$DOSE_FREQ_PERIOD_TYPES_DS = array(
								'key'   =>   'visreg_dose_measure_type',
								'value'   =>  $visitRegimen['visreg_freq_period_type']
							);
							$DOSE_FREQ_PERIOD_TYPES_SELECT = array2select($DOSE_FREQ_PERIOD_TYPES, 'type_id', 'type_title', 'visreg_freq_period_type', ' huid="visreg_freq_period_type" class="form-control required-field"', $DOSE_FREQ_PERIOD_TYPES_DEFAULT, $DOSE_FREQ_PERIOD_TYPES_DS);
							?>
							<?=$DOSE_FREQ_PERIOD_TYPES_SELECT['result'];?>
						</div>
					</div>
				</div>
				
				<div class="row">
					<div class="col">
						<label class="sr-only" for="visreg_dasigna">Примечание к процедуре</label>
						<div class="input-group mb-2">
							<div class="input-group-prepend align-top">
								<div class="input-group-text align-top" <?=super_bootstrap_tooltip('Примечание к процедуре');?>>
									<?=BT_ICON_DIAG_TEXT;?>
								</div>
							</div>
							<textarea class="form-control autosizer" name="visreg_dasigna" huid="visreg_dasigna" rows="5" placeholder="Пример: развести в 400 мл изотонического раствора Натрия Хлорида, вводить внутривенно, капельно"><?=$visitRegimen['visreg_dasigna'];?></textarea>
						</div>
					</div>
				</div>
<!--
                <div class="row">
                    <div class="col">
                        <label class="sr-only" for="visreg_visit_current">Текущее количество процедур</label>
                        <div class="input-group mb-2">
                            <div class="input-group-prepend">
                                <div class="input-group-text" <?/*=super_bootstrap_tooltip('Текущее количество процедур');*/?>>
									<?/*=BT_ICON_PROCEDURE_AMOUNT_CURRENT;*/?>
                                </div>
                            </div>
                            <input type="text" class="form-control" huid="visreg_visit_current" name="visreg_visit_current" placeholder="Текущее количество процедур" value="<?/*=$visitRegimen['visreg_visit_current'];*/?>">
                        </div>
                    </div>
                    <div class="col">
                        <label class="sr-only" for="visreg_visit_total">Максимальное количество процедур</label>
                        <div class="input-group mb-2">
                            <div class="input-group-prepend">
                                <div class="input-group-text" <?/*=super_bootstrap_tooltip('Максимальное количество процедур');*/?>>
									<?/*=BT_ICON_PROCEDURE_AMOUNT;*/?>
                                </div>
                            </div>
                            <input type="text" class="form-control" huid="visreg_visit_total" name="visreg_visit_total" placeholder="Максимальное количество процедур" value="<?/*=$visitRegimen['visreg_visit_total'];*/?>">
                        </div>
                    </div>
                </div>
				-->
				<div class="row">
					<div class="col">
						<label class="sr-only" for="visreg_freq_period_amount">Сохранить как Шаблон</label>
						<div class="input-group mb-2">
							<div class="input-group-prepend">
								<div class="input-group-text" <?=super_bootstrap_tooltip('Сохранить как Шаблон');?>>
									<?=BT_ICON_REGIMEN_TEMPLATE;?>
								</div>
							</div>
							<input class="form-check-input move-labeler" type="checkbox" name="visreg_isTemplate" id="visreg_isTemplate_<?=$visitRegimen['visreg_id'];?>" value="1">
							<label class="form-check-label box-label" for="visreg_isTemplate_<?=$visitRegimen['visreg_id'];?>"><span></span><b>Сохранить как Шаблон</b></label>
						</div>
					
					</div>
				</div>
				
				<?php
				require_once ("engine/html/dayStac/include/inc_spoiler_visit.php");
				?>

                <div class="row">
					<div class="col">
						<button class="btn col btn-primary btn-lg saveVisreg" data-visreg="<?=$visitRegimen['visreg_id'];?>">СОХРАНИТЬ</button>
					</div>
					<div class="col-auto">
						<button class="btn col btn-danger btn-sm deleteVisreg" data-visreg="<?=$visitRegimen['visreg_id'];?>" data-patient="<?=$visitRegimen['visreg_dspatid'];?>">УДАЛИТЬ</button>
					</div>
				</div>
			</form>
			<? spoiler_end();?>
			<?php
		}
	} else
	{
		bt_notice('Назначений нет. Создайте через "Назначение" в разделе "Документы направления"', BT_THEME_WARNING);
	}
	?>
	
	<!--        <button class="btn btn-sm btn-primary" id="addProcedure" data-patient="--><?//=$dirlist['dirlist_dspatid'];?><!--">Добавить назначение</button>-->
	<?php
} else
{
	bt_notice('Сначала выберите пациента', BT_THEME_WARNING);
}
?>
<? spoiler_end();?>