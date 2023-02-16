<div class="css-table-row" id="form-new-pattern">
	<div class="<?= $activeColor; ?> align-center">NEW</div>
	<div class="<?= $activeColor; ?> align-center">NEW</div>
	<div class="<?= $activeColor; ?>">
		<input class="form-control form-control-sm"
		       type="text"
		       id="pattern_title"
		       name="pattern_title"
		       placeholder="Название паттерна"
		>
	</div>
	<div class="<?= $activeColor; ?>">
		<input class="form-control form-control-sm"
		       type="text"
		       id="pattern_description"
		       name="pattern_description"
		       placeholder="Описание паттерна"
		>
	</div>
	<div class="<?= $activeColor; ?>">
		<input class="form-control form-control-sm"
		       type="text"
		       id="pattern_codes"
		       name="pattern_codes"
		       placeholder="МКБ-диагнозы через ;"
		>
	</div>
	<div class="<?= $activeColor; ?>"></div>
	<div class="<?= $activeColor; ?>"></div>
	
	<div class="align-center <?= $activeColor; ?>">
		<button class="btn btn-warning btn-sm button-pattern-new"
		        type="submit"
			<?= super_bootstrap_tooltip('Добавить'); ?>
		>
			<?= BT_ICON_OK; ?>
		</button>
	</div>
</div>
<div class="css-table-row"
     style="height: 20px">
</div>