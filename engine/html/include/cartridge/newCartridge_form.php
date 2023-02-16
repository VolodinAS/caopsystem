<form id="newCartridge_form">
	<div class="form-group">
		<label for="cartridge_ident">Идентификатор картриджа:</label>
		<input type="text"
		       class="form-control cartridge-ident required-field"
		       id="cartridge_ident"
		       name="cartridge_ident"
		       aria-describedby="cartridge_ident"
		       placeholder="00000"
		       required>
		<small id="cartridge_ident"
		       class="form-text text-muted">Указывается <b>последние</b> 5 цифр из наклейки "ГБУЗ СО ТГКП №3".
		                                    Например, 01160</small>
	</div>
	
	<div class="form-group">
		<label for="cartridge_barcode">Штрихкод картриджа:</label>
		<input type="text"
		       class="form-control cartridge-ident"
		       id="cartridge_barcode"
		       name="cartridge_barcode"
		       aria-describedby="cartridge_barcode">
		<small id="cartridge_barcode"
		       class="form-text text-muted">Указываются <b>последние</b> 5 цифр из наклейки со штрихкодом.
		                                    Например, 98726</small>
	</div>
	
	<div class="form-group">
		<label for="cartridge_desc">Примечание к картриджу:</label>
		<input type="text"
		       class="form-control"
		       id="cartridge_desc"
		       name="cartridge_desc"
		       placeholder="примечание">
	</div>
	
	<div class="form-group">
		<button class="btn btn-info col-12 btn-addCartridge">Добавить в список</button>
	</div>

</form>