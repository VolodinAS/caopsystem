<div class="css-table-row header-sticky-2" id="form-new-menu">
	<div class="<?= $activeColor; ?> align-center">NEW</div>
	<div class="<?= $activeColor; ?> align-center">NEW</div>
	<div class="<?= $activeColor; ?> align-center">NEW</div>
	<div class="<?= $activeColor; ?>">
		<input class="form-control form-control-sm"
		       type="text"
		       id="headmenu_title"
		       name="headmenu_title"
		       placeholder="Заголовок"
		>
	</div>
	<div class="<?= $activeColor; ?>">
		<input class="form-control form-control-sm"
		       type="text"
		       id="headmenu_subid"
		       name="headmenu_subid"
		       placeholder="ID родителя"
		>
	</div>
	<div class="<?= $activeColor; ?>">
		<input class="form-control form-control-sm"
		       type="text"
		       id="headmenu_hassubmenu"
		       name="headmenu_hassubmenu"
		       placeholder="Есть дети?"
		>
	</div>
	<div class="<?= $activeColor; ?>">
		<input class="form-control form-control-sm"
		       type="text"
		       id="headmenu_link"
		       name="headmenu_link"
		       placeholder="Ссылка"
		>
	</div>
	<div class="<?= $activeColor; ?>">
		<input class="form-control form-control-sm"
		       type="text"
		       id="headmenu_order"
		       name="headmenu_order"
		       placeholder="Порядок"
		>
	</div>
	<div class="<?= $activeColor; ?>">
		<input class="form-control form-control-sm"
		       type="text"
		       id="headmenu_access"
		       name="headmenu_access"
		       placeholder="Доступы"
		>
	</div>
	<div class="<?= $activeColor; ?>">
		<input class="form-control form-control-sm"
		       type="text"
		       id="headmenu_enabled"
		       name="headmenu_enabled"
		       placeholder="Включено?"
		>
	</div>
	<div class="<?= $activeColor; ?>">
		<input class="form-control form-control-sm"
		       type="text"
		       id="headmenu_header"
		       name="headmenu_header"
		       placeholder="Просто заголовок?"
		>
	</div>
	<div class="<?= $activeColor; ?>">
		<input class="form-control form-control-sm"
		       type="text"
		       id="headmenu_divider_before"
		       name="headmenu_divider_before"
		       placeholder="Палка до?"
		>
	</div>
	<div class="<?= $activeColor; ?>">
		<input class="form-control form-control-sm"
		       type="text"
		       id="headmenu_divider_after"
		       name="headmenu_divider_after"
		       placeholder="Палка после?"
		>
	</div>
	<div class="<?= $activeColor; ?>">
		<input class="form-control form-control-sm"
		       type="text"
		       id="header_blank"
		       name="header_blank"
		       placeholder="Новое окно?"
		>
	</div>
	<div class="align-center <?= $activeColor; ?>">
		<button class="btn btn-warning btn-sm button-menu-new"
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