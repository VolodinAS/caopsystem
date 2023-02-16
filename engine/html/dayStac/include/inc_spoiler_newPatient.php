<? spoiler_begin('Персональные данные пациента', 'patient_personal', '');?>
	<form id="form-newPatient">
		<?php
		if ( $isPatient )
		{
			?>
			<input type="hidden" name="patient_id" id="patient_id" value="<?=$PatientData['patient_id'];?>">
			<?php
		}
		?>
		<div class="row">
			<div class="col">
				<label class="sr-only" for="patient_ident">Номер истории болезни/карты</label>
				<div class="input-group mb-2">
					<div class="input-group-prepend">
						<div class="input-group-text" <?=super_bootstrap_tooltip('Номер истории болезни/карты');?>>
							<?=BT_ICON_CARD;?>
						</div>
					</div>
					<input type="text" class="form-control required-field" id="patient_ident" name="patient_ident" placeholder="Номер истории болезни/карты" value="<?=$PatientData['patient_ident'];?>">
				</div>
			</div>
		</div>
		
		<div class="row">
			<div class="col">
				<label class="sr-only" for="patient_fio">Ф.И.О. пациента</label>
				<div class="input-group mb-2">
					<div class="input-group-prepend">
						<div class="input-group-text" <?=super_bootstrap_tooltip('Ф.И.О. пациента');?>>
							<?=BT_ICON_PERSONAL;?>
						</div>
					</div>
					<input type="text" class="form-control required-field" id="patient_fio" name="patient_fio" placeholder="Ф.И.О. пациента. Пример: иванов иван иванович" value="<?=$PatientData['patient_fio'];?>">
				</div>
			</div>
			
			<div class="col">
				<label class="sr-only" for="patient_birth">Дата рождения</label>
				<div class="input-group mb-2">
					<div class="input-group-prepend">
						<div class="input-group-text" <?=super_bootstrap_tooltip('Дата рождения');?>>
							<?=BT_ICON_BIRTH;?>
						</div>
					</div>
					<input type="text" class="form-control russianBirth required-field" id="patient_birth" name="patient_birth" placeholder="Дата рождения. Пример: 01.01.1990" value="<?=$PatientData['patient_birth'];?>">
				</div>
			</div>
		</div>
		
		<div class="row">
			<div class="col">
				<label class="sr-only" for="patient_address">Адрес</label>
				<div class="input-group mb-2">
					<div class="input-group-prepend">
						<div class="input-group-text" <?=super_bootstrap_tooltip('Адрес');?>>
							<?=BT_ICON_ADDRESS;?>
						</div>
					</div>
					<input type="text" class="form-control required-field" id="patient_address" name="patient_address" placeholder="Адрес" value="<?=$PatientData['patient_address'];?>">
				</div>
			</div>
		</div>
		
		<div class="row">
			<div class="col">
				<label class="sr-only" for="patient_height">Рост, см</label>
				<div class="input-group mb-2">
					<div class="input-group-prepend">
						<div class="input-group-text" <?=super_bootstrap_tooltip('Рост');?>>
							<?=BT_ICON_HEIGHT;?>
						</div>
					</div>
					<input type="text" class="form-control" id="patient_height" name="patient_height" placeholder="Рост, см. Пример: 180" value="<?=$PatientData['patient_height'];?>">
				</div>
			</div>
			
			<div class="col">
				<label class="sr-only" for="patient_weight">Вес, кг</label>
				<div class="input-group mb-2">
					<div class="input-group-prepend">
						<div class="input-group-text" <?=super_bootstrap_tooltip('Вес');?>>
							<?=BT_ICON_WEIGHT;?>
						</div>
					</div>
					<input type="text" class="form-control" id="patient_weight" name="patient_weight" placeholder="Вес. Пример: 95,7" value="<?=$PatientData['patient_weight'];?>">
				</div>
			</div>
		</div>
		
		<div class="row">
			<div class="col">
				<label class="sr-only" for="patient_insurance_number">Номер страхового полиса</label>
				<div class="input-group mb-2">
					<div class="input-group-prepend">
						<div class="input-group-text" <?=super_bootstrap_tooltip('Номер страхового полиса');?>>
							<?=BT_ICON_INSURANCE_NUMBER;?>
						</div>
					</div>
					<input type="text" class="form-control" id="patient_insurance_number" name="patient_insurance_number" placeholder="Номер страхового полиса" value="<?=$PatientData['patient_insurance_number'];?>">
				</div>
			</div>
			
			<div class="col">
				<label class="sr-only" for="patient_insurance_company_id">Страховая компания</label>
				<div class="input-group mb-2">
					<div class="input-group-prepend">
						<div class="input-group-text" <?=super_bootstrap_tooltip('Страховая компания');?>>
							<?=BT_ICON_INSURANCE_COMPANY;?>
						</div>
					</div>
					<?=$CompanyListSelectorHTML;?>
				</div>
			</div>
		</div>
        
        <div class="row">
            <div class="col">
                <button class="btn col btn-primary btn-lg" id="savePatient">СОХРАНИТЬ</button>
            </div>
            <div class="col-auto">
                <button class="btn col btn-danger btn-sm" id="deletePatient" data-patient="<?=$PatientData['patient_id'];?>">УДАЛИТЬ</button>
            </div>
        </div>
	</form>
<? spoiler_end();?>