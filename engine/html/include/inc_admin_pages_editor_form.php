<div class="css-table-row header-sticky-2" id="form-new-page">
	<div class="<?= $activeColor; ?> align-center">NEW</div>
	<div class="<?= $activeColor; ?> align-center">NEW</div>
	<div class="<?= $activeColor; ?>">
		<input class="form-control form-control-sm"
		       type="text"
		       id="pages_title"
		       name="pages_title"
		       placeholder="Title страницы"
		>
	</div>
    <div class="<?= $activeColor; ?>">
        <input class="form-control form-control-sm"
               type="text"
               id="pages_link"
               name="pages_link"
               placeholder="Ссылка"
        >
    </div>
	<div class="<?= $activeColor; ?>">
		<input class="form-control form-control-sm"
		       type="text"
		       id="pages_head"
		       name="pages_head"
		       placeholder="Заголовок"
		>
	</div>
	<div class="<?= $activeColor; ?>">
		<input class="form-control form-control-sm"
		       type="text"
		       id="pages_access"
		       name="pages_access"
		       placeholder="Доступы"
		>
	</div>
	<div class="<?= $activeColor; ?>">
		<input class="form-control form-control-sm"
		       type="text"
		       id="pages_container"
		       name="pages_container"
		       placeholder="Контейнер?"
		>
	</div>
	<div class="<?= $activeColor; ?>">
		<input class="form-control form-control-sm"
		       type="text"
		       id="pages_icon"
		       name="pages_icon"
		       placeholder="Иконка"
		>
	</div>
	<div class="<?= $activeColor; ?>">
		<input class="form-control form-control-sm"
		       type="text"
		       id="pages_icon_ext"
		       name="pages_icon_ext"
		       placeholder="Расш. ик."
		>
	</div>
	<div class="<?= $activeColor; ?>">
		<input class="form-control form-control-sm"
		       type="text"
		       id="pages_calendar"
		       name="pages_calendar"
		       placeholder="Календарь?"
		>
	</div>
	<div class="<?= $activeColor; ?>">
		<input class="form-control form-control-sm"
		       type="text"
		       id="page_process"
		       name="page_process"
		       placeholder="В разработке?"
		>
	</div>
	
	<div class="align-center <?= $activeColor; ?>">
		<button class="btn btn-warning btn-sm button-page-new"
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