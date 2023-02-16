<form id="patientSearchFilter" method="post" action="/patientSearch">
	<div class="form-group row">
		<label for="db_id" class="col-2 col-form-label"><b>ID в базе данных:</b></label>
		<div class="col">
			<input type="text" class="<?=$class_form_control;?>" id="db_id" name="db_id" placeholder="patid_id" value="<?=$db_id;?>">
		</div>
	</div>
	
	<div class="form-group row">
		<label for="amb_card" class="col-2 col-form-label"><b>Номер карты:</b></label>
		<div class="col">
			<input type="text" class="<?=$class_form_control;?>" id="amb_card" name="amb_card" placeholder="Номер карты" value="<?=$amb_card;?>">
		</div>
	</div>
	
	<div class="form-group row">
		<label for="fio" class="col-2 col-form-label"><b>Ф. И. О.:</b></label>
		<div class="col">
			<input type="text" class="<?=$class_form_control;?>" id="fio" name="fio" placeholder="Ф. И. О. пациента" value="<?=$fio;?>">
		</div>
	</div>
	
	<?php
	bt_notice('Если Вам нужен поиск по периоду - заполните ОБЕ даты. Если нужен период от определенной даты - только первую дату. Если период до определенной даты, то вторую. Если нужна ТОЧНАЯ дата, то заполните дату ОТ и поставьте галку "Точная дата"')
	?>
	
	<div class="form-group row">
		<label for="birth_form" class="col-2 col-form-label"><b>Дата рождения:</b></label>
		<div class="col">
			<input type="text" class="<?=$class_form_control;?> russianBirth" id="birth_form" name="birth_form" placeholder="Дата рождения ОТ / Точная дата" value="<?=$birth_form;?>">
		</div>
		<!--			<div class="col-1"></div>-->
		<div class="col">
			<input type="text" class="<?=$class_form_control;?> russianBirth" <?=$isBirthToVisible;?> id="birth_to" name="birth_to" placeholder="Дата рождения ДО" value="<?=$birth_to;?>">
		</div>
		<div class="col">
			<input class="form-check-input " type="checkbox" name="birth_current" id="birth_current" value="1" <?=$isBirthCurrentChecked;?>>
			<label class="form-check-label box-label" for="birth_current"><span></span><b>Точная дата</b></label>
		</div>
	</div>
	
	<div class="form-group row">
		<label for="address" class="col-2 col-form-label"><b>Адрес:</b></label>
		<div class="col">
			<input type="text" class="<?=$class_form_control;?>" id="address" name="address" placeholder="Адрес (приблизительно)" value="<?=$address;?>">
		</div>
	</div>
	
	<div class="form-group row">
		<label for="phone" class="col-2 col-form-label"><b>Телефон</b></label>
		<div class="col">
			<input type="text" class="<?=$class_form_control;?>" id="phone" name="phone" placeholder="Телефон" value="<?=$phone;?>">
		</div>
	</div>
	
	<div class="form-group row">
		<label for="insurance_number" class="col-2 col-form-label"><b>Страховой полис:</b></label>
		<div class="col">
			<input type="text" class="<?=$class_form_control;?>" id="insurance_number" name="insurance_number" placeholder="Номер страхового полиса" value="<?=$insurance_number;?>">
		</div>
	</div>
	
	<div class="form-group row">
		<label for="isZNO" class="col-2 col-form-label"><b>Имеет ЗНО:</b></label>
		<div class="col">
			<input class="form-check-input " type="checkbox" name="isZNO" id="isZNO" value="1" <?=$isZNOChecked;?>>
			<label class="form-check-label box-label" for="isZNO"><span></span><b>Да</b></label>
		</div>
	</div>
	
	<div class="form-group row">
		<label for="isVisited_general" class="col-2 col-form-label"><b>Посещения:</b></label>
		<div class="col">
			<input class="form-check-input " type="checkbox" name="isVisited" id="isVisited_has" value="2" <?=$isVisited_has;?>>
			<label class="form-check-label box-label" for="isVisited_has"><span></span><b>имеет визиты</b></label>
		</div>
	</div>
	
	<div class="form-group row">
		<label for="isDead_general" class="col-2 col-form-label"><b>Умерший:</b></label>
		<div class="col">
			<input class="form-check-input " type="checkbox" name="isDead" id="isDead_has" value="1" <?=$isDead_has;?>>
			<label class="form-check-label box-label" for="isDead_has"><span></span><b>да</b></label>
		</div>
	</div>
	
	<div class="form-group row">
		<div class="col">
			<button type="submit" class="btn btn-primary btn-lg col">Поиск</button>
		</div>
	</div>


</form>