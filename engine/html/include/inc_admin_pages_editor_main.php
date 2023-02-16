<div class="css-table-row">
	<div class="<?= $activeColor; ?> align-center">
		<?= $pagesCounter; ?>
	</div>
	<div class="<?= $activeColor; ?> align-center"><?= $pageItem['pages_id']; ?></div>
	<div class="<?= $activeColor; ?>" <?=super_bootstrap_tooltip('Title страницы');?>>
		<input class="form-control form-control-sm mysqleditor"
		       type="text"
		       value="<?= $pageItem['pages_title']; ?>"
		       data-action="edit"
		       data-table="<?= CAOP_PAGES; ?>"
		       data-assoc="0"
		       data-fieldid="pages_id"
		       data-id="<?= $pageItem['pages_id']; ?>"
		       data-field="pages_title"
		       data-loader="main-loader"
		>
	</div>
	<div class="<?= $activeColor; ?>" <?=super_bootstrap_tooltip('Ссылка');?>>
		<input class="form-control form-control-sm mysqleditor"
		       type="text"
		       value="<?= $pageItem['pages_link']; ?>"
		       data-action="edit"
		       data-table="<?= CAOP_PAGES; ?>"
		       data-assoc="0"
		       data-fieldid="pages_id"
		       data-id="<?= $pageItem['pages_id']; ?>"
		       data-field="pages_link"
		       data-loader="main-loader"
		>
	</div>
	<div class="<?= $activeColor; ?>" <?=super_bootstrap_tooltip('Заголовок');?>>
		<input class="form-control form-control-sm mysqleditor"
		       type="text"
		       value="<?= $pageItem['pages_head']; ?>"
		       data-action="edit"
		       data-table="<?= CAOP_PAGES; ?>"
		       data-assoc="0"
		       data-fieldid="pages_id"
		       data-id="<?= $pageItem['pages_id']; ?>"
		       data-field="pages_head"
		       data-loader="main-loader"
		>
	</div>
	<div class="<?= $activeColor; ?>" <?=super_bootstrap_tooltip('Пользовательские доступы');?>>
		<input class="form-control form-control-sm mysqleditor"
		       type="text"
		       value="<?= $pageItem['pages_access']; ?>"
		       data-action="edit"
		       data-table="<?= CAOP_PAGES; ?>"
		       data-assoc="0"
		       data-fieldid="pages_id"
		       data-id="<?= $pageItem['pages_id']; ?>"
		       data-field="pages_access"
		       data-loader="main-loader"
		>
	</div>
	<div class="<?= $activeColor; ?>" <?=super_bootstrap_tooltip('div.class=container?');?>>
		<input class="form-control form-control-sm mysqleditor"
		       type="text"
		       value="<?= $pageItem['pages_container']; ?>"
		       data-action="edit"
		       data-table="<?= CAOP_PAGES; ?>"
		       data-assoc="0"
		       data-fieldid="pages_id"
		       data-id="<?= $pageItem['pages_id']; ?>"
		       data-field="pages_container"
		       data-loader="main-loader"
		>
	</div>
	<div class="<?= $activeColor; ?>" <?=super_bootstrap_tooltip('Иконка');?>>
		<input class="form-control form-control-sm mysqleditor"
		       type="text"
		       value="<?= $pageItem['pages_icon']; ?>"
		       data-action="edit"
		       data-table="<?= CAOP_PAGES; ?>"
		       data-assoc="0"
		       data-fieldid="pages_id"
		       data-id="<?= $pageItem['pages_id']; ?>"
		       data-field="pages_icon"
		       data-loader="main-loader"
		>
	</div>
	<div class="<?= $activeColor; ?>" <?=super_bootstrap_tooltip('Расширение иконки');?>>
		<input class="form-control form-control-sm mysqleditor"
		       type="text"
		       value="<?= $pageItem['pages_icon_ext']; ?>"
		       data-action="edit"
		       data-table="<?= CAOP_PAGES; ?>"
		       data-assoc="0"
		       data-fieldid="pages_id"
		       data-id="<?= $pageItem['pages_id']; ?>"
		       data-field="pages_icon_ext"
		       data-loader="main-loader"
               placeholder="gif|png"
		>
	</div>
	<div class="<?= $activeColor; ?>" <?=super_bootstrap_tooltip('Вставить календарь?');?>>
		<input class="form-control form-control-sm mysqleditor"
		       type="text"
		       value="<?= $pageItem['pages_calendar']; ?>"
		       data-action="edit"
		       data-table="<?= CAOP_PAGES; ?>"
		       data-assoc="0"
		       data-fieldid="pages_id"
		       data-id="<?= $pageItem['pages_id']; ?>"
		       data-field="pages_calendar"
		       data-loader="main-loader"
		>
	</div>
	<div class="<?= $activeColor; ?>" <?=super_bootstrap_tooltip('Страница в разработке');?>>
		<input class="form-control form-control-sm mysqleditor"
		       type="text"
		       value="<?= $pageItem['page_process']; ?>"
		       data-action="edit"
		       data-table="<?= CAOP_PAGES; ?>"
		       data-assoc="0"
		       data-fieldid="pages_id"
		       data-id="<?= $pageItem['pages_id']; ?>"
		       data-field="page_process"
		       data-loader="main-loader"
		>
	</div>
	
	<div class="align-center <?= $activeColor; ?>">
		<button class="btn btn-danger btn-sm button-page-delete"
		        type="button"
                data-pageid="<?= $pageItem['pages_id']; ?>"
			<?= super_bootstrap_tooltip('Удалить'); ?>
		>
			<?= BT_ICON_CLOSE; ?>
		</button>
	</div>

</div>