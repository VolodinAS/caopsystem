<div class="css-table-row">
	<div class="<?= $activeColor; ?> align-center">
		<?= $mainMenuCounter; ?>
	</div>
	<div class="<?= $activeColor; ?> align-center"></div>
	<div class="<?= $activeColor; ?> align-center"><?= $menuItem['headmenu_id']; ?></div>
	<div class="<?= $activeColor; ?>" <?=super_bootstrap_tooltip('Название меню');?>>
		<input class="form-control form-control-sm mysqleditor"
		       type="text"
		       value="<?= $menuItem['headmenu_title']; ?>"
		       data-action="edit"
		       data-table="<?= CAOP_HEADMENU; ?>"
		       data-assoc="0"
		       data-fieldid="headmenu_id"
		       data-id="<?= $menuItem['headmenu_id']; ?>"
		       data-field="headmenu_title"
		       data-loader="main-loader"
		>
	</div>
	<div class="<?= $activeColor; ?>" <?=super_bootstrap_tooltip('Чьим подменю является');?>>
		<input class="form-control form-control-sm mysqleditor"
		       type="text"
		       value="<?= $menuItem['headmenu_subid']; ?>"
		       data-action="edit"
		       data-table="<?= CAOP_HEADMENU; ?>"
		       data-assoc="0"
		       data-fieldid="headmenu_id"
		       data-id="<?= $menuItem['headmenu_id']; ?>"
		       data-field="headmenu_subid"
		       data-loader="main-loader"
		>
	</div>
	<div class="<?= $activeColor; ?>" <?=super_bootstrap_tooltip('Есть подменю? (1/0)');?>>
		<input class="form-control form-control-sm mysqleditor"
		       type="text"
		       value="<?= $menuItem['headmenu_hassubmenu']; ?>"
		       data-action="edit"
		       data-table="<?= CAOP_HEADMENU; ?>"
		       data-assoc="0"
		       data-fieldid="headmenu_id"
		       data-id="<?= $menuItem['headmenu_id']; ?>"
		       data-field="headmenu_hassubmenu"
		       data-loader="main-loader"
		>
	</div>
	<div class="<?= $activeColor; ?>" <?=super_bootstrap_tooltip('Ссылка');?>>
		<input class="form-control form-control-sm mysqleditor"
		       type="text"
		       value="<?= $menuItem['headmenu_link']; ?>"
		       data-action="edit"
		       data-table="<?= CAOP_HEADMENU; ?>"
		       data-assoc="0"
		       data-fieldid="headmenu_id"
		       data-id="<?= $menuItem['headmenu_id']; ?>"
		       data-field="headmenu_link"
		       data-loader="main-loader"
		>
	</div>
	<div class="<?= $activeColor; ?>"  <?=super_bootstrap_tooltip('Порядок');?>>
		<input class="form-control form-control-sm mysqleditor"
		       type="text"
		       value="<?= $menuItem['headmenu_order']; ?>"
		       data-action="edit"
		       data-table="<?= CAOP_HEADMENU; ?>"
		       data-assoc="0"
		       data-fieldid="headmenu_id"
		       data-id="<?= $menuItem['headmenu_id']; ?>"
		       data-field="headmenu_order"
		       data-loader="main-loader"
		>
	</div>
	<div class="<?= $activeColor; ?>"  <?=super_bootstrap_tooltip('Доступы (отображение)');?>>
		<input class="form-control form-control-sm mysqleditor"
		       type="text"
		       value="<?= $menuItem['headmenu_access']; ?>"
		       data-action="edit"
		       data-table="<?= CAOP_HEADMENU; ?>"
		       data-assoc="0"
		       data-fieldid="headmenu_id"
		       data-id="<?= $menuItem['headmenu_id']; ?>"
		       data-field="headmenu_access"
		       data-loader="main-loader"
		>
	</div>
	<div class="<?= $activeColor; ?>"  <?=super_bootstrap_tooltip('Включено (отображение общее)');?>>
		<input class="form-control form-control-sm mysqleditor"
		       type="text"
		       value="<?= $menuItem['headmenu_enabled']; ?>"
		       data-action="edit"
		       data-table="<?= CAOP_HEADMENU; ?>"
		       data-assoc="0"
		       data-fieldid="headmenu_id"
		       data-id="<?= $menuItem['headmenu_id']; ?>"
		       data-field="headmenu_enabled"
		       data-loader="main-loader"
		>
	</div>
	<div class="<?= $activeColor; ?>"  <?=super_bootstrap_tooltip('Жирный заголовок? (1/0)');?>>
		<input class="form-control form-control-sm mysqleditor"
		       type="text"
		       value="<?= $menuItem['headmenu_header']; ?>"
		       data-action="edit"
		       data-table="<?= CAOP_HEADMENU; ?>"
		       data-assoc="0"
		       data-fieldid="headmenu_id"
		       data-id="<?= $menuItem['headmenu_id']; ?>"
		       data-field="headmenu_header"
		       data-loader="main-loader"
		>
	</div>
	<div class="<?= $activeColor; ?>"  <?=super_bootstrap_tooltip('Разделитель ДО');?>>
		<input class="form-control form-control-sm mysqleditor"
		       type="text"
		       value="<?= $menuItem['headmenu_divider_before']; ?>"
		       data-action="edit"
		       data-table="<?= CAOP_HEADMENU; ?>"
		       data-assoc="0"
		       data-fieldid="headmenu_id"
		       data-id="<?= $menuItem['headmenu_id']; ?>"
		       data-field="headmenu_divider_before"
		       data-loader="main-loader"
		>
	</div>
	<div class="<?= $activeColor; ?>" <?=super_bootstrap_tooltip('Разделитель ПОСЛЕ');?>>
		<input class="form-control form-control-sm mysqleditor"
		       type="text"
		       value="<?= $menuItem['headmenu_divider_after']; ?>"
		       data-action="edit"
		       data-table="<?= CAOP_HEADMENU; ?>"
		       data-assoc="0"
		       data-fieldid="headmenu_id"
		       data-id="<?= $menuItem['headmenu_id']; ?>"
		       data-field="headmenu_divider_after"
		       data-loader="main-loader"
		>
	</div>
	<div class="<?= $activeColor; ?>" <?=super_bootstrap_tooltip('В новой вкладке');?>>
		<input class="form-control form-control-sm mysqleditor"
		       type="text"
		       value="<?= $menuItem['header_blank']; ?>"
		       data-action="edit"
		       data-table="<?= CAOP_HEADMENU; ?>"
		       data-assoc="0"
		       data-fieldid="headmenu_id"
		       data-id="<?= $menuItem['headmenu_id']; ?>"
		       data-field="header_blank"
		       data-loader="main-loader"
		>
	</div>
	<div class="align-center <?= $activeColor; ?>">
		<button class="btn btn-danger btn-sm button-menu-delete"
		        type="button"
                data-menuid="<?= $menuItem['headmenu_id']; ?>"
			<?= super_bootstrap_tooltip('Удалить'); ?>
		>
			<?= BT_ICON_CLOSE; ?>
		</button>
	</div>

</div>