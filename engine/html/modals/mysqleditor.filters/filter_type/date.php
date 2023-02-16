<?php
$default_data = $_SESSION['mef_filters'][$filter]['data'];
$date_from = $default_data['from'];
$date_to = $default_data['to'];
$checked = ( $default_data['accuracy'] == "accuracy" ) ? 'checked' : '';

$response['htmlData'] .= '
<div class="row">
	<div class="col">
		<input class="form-check-input" type="checkbox" name="filter_data[accuracy]" id="accuracy" value="accuracy" '.$checked.'>
		<label class="form-check-label box-label" for="accuracy"><span></span>Точная дата</label>
	</div>
	<br><br>
</div>
<div class="row">
	<div class="col">
		<label for="date_from">
			<b>Дата ОТ:</b>
		</label>
		<input
			type="text"
			name="filter_data[from]"
			id="date_from"
			class="form-control form-control-lg russianBirth"
			placeholder="дата ОТ"
			value="'.$date_from.'"
		>
	</div>
	<div class="col">
		<label for="date_to">
			<b>Дата ДО:</b>
		</label>
		<input
			type="text"
			name="filter_data[to]"
			id="date_to"
			class="form-control form-control-lg russianBirth"
			placeholder="дата ДО (если нужно)"
			value="'.$date_to.'"
		>
	</div>
</div>
';