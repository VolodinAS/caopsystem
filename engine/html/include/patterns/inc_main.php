<div class="css-table-row">
	<div class="<?= $activeColor; ?> align-center">
		<?= $patternCounter; ?>
	</div>
	<div class="<?= $activeColor; ?> align-center"><?= $patternItem['pattern_id']; ?></div>
	<div class="<?= $activeColor; ?>" <?=super_bootstrap_tooltip($patternItem['pattern_title']);?>>
		<input class="form-control form-control-sm mysqleditor"
		       type="text"
		       value="<?= $patternItem['pattern_title']; ?>"
		       data-action="edit"
		       data-table="<?= CAOP_DIAGNOSIS_PATTERNS; ?>"
		       data-assoc="0"
		       data-fieldid="pattern_id"
		       data-id="<?= $patternItem['pattern_id']; ?>"
		       data-field="pattern_title"
		       data-unixfield="pattern_updated_unix"
		       data-loader="main-loader"
		>
	</div>
	<div class="<?= $activeColor; ?>" <?=super_bootstrap_tooltip($patternItem['pattern_description']);?>>
		<input class="form-control form-control-sm mysqleditor"
		       type="text"
		       value="<?= $patternItem['pattern_description']; ?>"
		       data-action="edit"
		       data-table="<?= CAOP_DIAGNOSIS_PATTERNS; ?>"
		       data-assoc="0"
		       data-fieldid="pattern_id"
		       data-id="<?= $patternItem['pattern_id']; ?>"
		       data-field="pattern_description"
		       data-unixfield="pattern_updated_unix"
		       data-loader="main-loader"
		>
	</div>
	<div class="<?= $activeColor; ?>">
		<input class="form-control form-control-sm mysqleditor"
		       type="text"
		       value="<?= $patternItem['pattern_codes']; ?>"
		       data-action="edit"
		       data-table="<?= CAOP_DIAGNOSIS_PATTERNS; ?>"
		       data-assoc="0"
		       data-fieldid="pattern_id"
		       data-id="<?= $patternItem['pattern_id']; ?>"
		       data-field="pattern_codes"
		       data-unixfield="pattern_updated_unix"
		       data-loader="main-loader"
		>
        <textarea class="class-for-copy" id="codes-copy-<?=$patternItem['pattern_id'];?>"><?=$patternItem['pattern_codes'];?></textarea>
	</div>

    <div class="<?= $activeColor; ?>">
	    <?= date("d.m.Y", $patternItem['pattern_created_unix']); ?>
    </div>

    <div class="<?= $activeColor; ?>">
	    <?= date("d.m.Y", $patternItem['pattern_updated_unix']); ?>
    </div>
	
	
	<div class="align-center <?= $activeColor; ?>" style="display: flex">
		<button class="btn btn-primary btn-sm clickForCopy"
		        type="button"
                data-target="codes-copy-<?=$patternItem['pattern_id'];?>"
			<?= super_bootstrap_tooltip('Скопировать коды'); ?>
		>
			<?= BT_ICON_REGIMEN_NAME; ?>
		</button>
		<button class="btn btn-danger btn-sm button-pattern-delete"
		        type="button"
		        data-patternid="<?= $patternItem['pattern_id']; ?>"
			<?= super_bootstrap_tooltip('Удалить'); ?>
		>
			<?= BT_ICON_CLOSE; ?>
		</button>
	</div>

</div>