<?php
bt_notice( wrapper("Поиск по указанным данным"), "warning" );
?>
	<div class="row">
		<div class="col">
			<b>Название таблицы:</b>
			<input type="text" name="name_table" id="name_table" class="form-control form-control-sm" value="caop_journal">
		</div>
		<div class="col">
			<b>Название столбца:</b>
			<input type="text" name="name_field" id="name_field" class="form-control form-control-sm" value="journal_recom">
		</div>
		<div class="col">
			<b>Искомое значение:</b>
			<input type="text" name="name_value" id="name_value" class="form-control form-control-sm" value="%%">
		</div>
		<div class="col">
			<b>ID пациента в поле:</b>
			<input type="text" name="name_patient_id_field" id="name_patient_id_field" class="form-control form-control-sm" value="journal_patid">
		</div>
		<div class="col">
			<button type="button" class="btn btn-sm btn-primary btn-searchByData col">Поиск</button>
		</div>
	</div>
	<div id="adminTableSearchResults"></div>




<?php