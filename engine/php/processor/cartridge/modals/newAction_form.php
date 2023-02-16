<?php
$response['stage'] = $action;
$response['msg'] = 'begin';

$HTTP = $_POST;
extract($HTTP, EXTR_PREFIX_SAME, '_caop');

$response['result'] = true;

$a2sDefault = array(
    'key' => 0,
    'value' => 'Выберите...'
);

$CartridgeTypes = getarr(CAOP_CARTRIDGE_ACTION_TYPES, 1, "ORDER BY type_id ASC");
$CartridgeTypesId = getDoctorsById($CartridgeTypes, 'type_id');

$a2sSelector = array2select($CartridgeTypesId, 'type_id', 'type_title', 'action_type_id',
'class="form-control form-control-lg" id="action_type_id"', $a2sDefault);

$response['htmlData'] .= '
	<form id="newAction_form">
		<input type="hidden" name="action_cartridge_id" id="action_cartridge_id" value="'.$cartridge_id.'">
	    <div class="form-group form-inline">
	        <label for="action_type_id" class="p-3"><b>Выберите тип действия:</b></label>
	        '.$a2sSelector['result'].'
	    </div>
	    
	    <div class="form-group form-inline">
	        <label for="action_date_responsed" class="p-3"><b>Дата получения от курьера:</b></label>
	        <input type="text" class="form-control form-control-lg russianBirth" id="action_date_responsed" name="action_date_responsed">
	    </div>
	    
	    <div class="form-group form-inline">
	        <label for="action_date_caop_get" class="p-3"><b>Дата получения ЦАОПом:</b></label>
	        <input type="text" class="form-control form-control-lg russianBirth" id="action_date_caop_get" name="action_date_caop_get">
	    </div>
	    
	    <div class="form-group form-inline">
	        <label for="action_date_printer" class="p-3"><b>Дата установки в принтер:</b></label>
	        <input type="text" class="form-control form-control-lg russianBirth" id="action_date_printer" name="action_date_printer">
	    </div>
	    
	    <div class="form-group form-inline">
	        <label for="action_cabinet" class="p-3"><b>Кабинет:</b></label>
	        <input type="text" class="form-control form-control-lg" id="action_cabinet" name="action_cabinet" aria-describedby="cabinet_hint">
	        <small id="cabinet_hint" class="form-text text-muted p-3">Например, "127 врач" или "127 справа"</small>
	    </div>
	    
	    <div class="form-group form-inline">
	        <label for="action_date_bad_begin" class="p-3"><b>Дата начала плохой печати:</b></label>
	        <input type="text" class="form-control form-control-lg russianBirth" id="action_date_bad_begin" name="action_date_bad_begin">
	    </div>
	    
	    <div class="form-group form-inline">
	        <label for="action_date_courier" class="p-3"><b>Дата передачи хозяйке:</b></label>
	        <input type="text" class="form-control form-control-lg russianBirth" id="action_date_courier" name="action_date_courier">
	    </div>
	    
	    <div class="form-group form-inline">
	        <label for="action_response_pack_barcode" class="p-3"><b>Штрихкод упаковки:</b></label>
	        <input type="text" class="form-control form-control-lg cartridge-ident" id="action_response_pack_barcode" name="action_response_pack_barcode" aria-describedby="barcode_hint">
	        <small id="barcode_hint" class="form-text text-muted p-3">Последние 5 цифр штрихкода</small>
	    </div>
	    
	    <div class="form-group">
	        <label for="action_response_pack_description"><b>Примечание к упаковке:</b></label>
	        <input type="text" class="form-control form-control-lg" id="action_response_pack_description" name="action_response_pack_description">
	    </div>
	    
	    <div class="form-group">
	        <label for="action_description"><b>Примечание:</b></label>
	        <input type="text" class="form-control form-control-lg" id="action_description" name="action_description">
	    </div>
	    
	    <div class="form-group text-center">
	        <button class="btn btn-primary btn-addNewAction col-10">Добавить действие</button>
	    </div>
	    
	</form>
';