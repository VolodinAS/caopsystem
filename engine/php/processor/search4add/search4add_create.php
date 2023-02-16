<?php
$response['stage'] = $action;
$response['debug']['$_POST'] = $_POST;
$response['msg'] = 'begin';
$response['result'] = true;
$response['htmlData'] = '';
$response['htmlData'] .= '
	
	<script defer type="text/javascript">
	$( document ).ready(function(err)
	{
	    $(\'#search4add_createButton\').click(function(e)
	    {
	        e.preventDefault();
	        createPatient();
	    });
	});
	</script>
	
	<form action="" id="search4add_add">
		<div class="row">
			<div class="col">
				<label class="sr-only" for="patient_ident">
					Номер карты
				</label>
			    <div class="input-group mb-2">
			        <div class="input-group-prepend">
			          	<div class="input-group-text" '.super_bootstrap_tooltip('Номер карты').'>
							'.BT_ICON_CARD.'
						</div>
			        </div>
			        <input type="text" class="form-control form-control-lg required-field" name="patient_ident" id="patient_ident" placeholder="Номер карты">
			      </div>
			</div>
			<div class="col">
				<label class="sr-only" for="journal_time">
					Время приёма
				</label>
			    <div class="input-group mb-2">
			        <div class="input-group-prepend">
			          	<div class="input-group-text" '.super_bootstrap_tooltip('Время приёма').'>
							'.BT_ICON_DRUG_PERIOD.'
						</div>
			        </div>
			        <input type="text" class="form-control form-control-lg russianTime required-field" name="journal_time" id="journal_time" placeholder="Время приёма">
			      </div>
			</div>
		</div>
		<div class="row">
			<div class="col">
				<label class="sr-only" for="patient_name">
					Ф.И.О. пациента
				</label>
			    <div class="input-group mb-2">
			        <div class="input-group-prepend">
			          	<div class="input-group-text" '.super_bootstrap_tooltip('Ф.И.О. пациента').'>
							'.BT_ICON_PERSONAL.'
						</div>
			        </div>
			        <input type="text" class="form-control form-control-lg required-field" name="patient_name" id="patient_name" placeholder="Ф.И.О. пациента">
			      </div>
			</div>
			<div class="col">
				<label class="sr-only" for="patient_birth">
					Дата рождения
				</label>
			    <div class="input-group mb-2">
			        <div class="input-group-prepend">
			          	<div class="input-group-text" '.super_bootstrap_tooltip('Дата рождения').'>
							'.BT_ICON_BIRTH.'
						</div>
			        </div>
			        <input type="text" class="form-control form-control-lg russianBirth required-field" name="patient_birth" id="patient_birth" placeholder="Дата рождения">
			      </div>
			</div>
		</div>
		<div class="row">
			<div class="col">
				<button type="submit" id="search4add_createButton" class="btn btn-lg btn-success col">Добавить в приём</button>
			</div>
		</div>
	</form>

';