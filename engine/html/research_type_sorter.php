<?php
spoiler_begin('<b>Какие типы обследований отобразить?</b>', 'filter_research', '');
$ResearchTypesHead = getarr(CAOP_RESEARCH_TYPES, "type_enabled='1' AND type_forresearch='1'", "ORDER BY type_order ASC");
?>
<?php
foreach ($ResearchTypesHead as $researchType) {
	echo '
	<div class="div-inline">
		<input class="form-check-input move-labeler research_type" type="checkbox" name="labeler'.$researchType['type_id'].'" id="labeler'.$researchType['type_id'].'" data-typeid="'.$researchType['type_id'].'" value="1" checked>
		<label class="form-check-label box-label" for="labeler'.$researchType['type_id'].'"><span></span>'.$researchType['type_title'].'</label>
	</div>
	&nbsp;&nbsp;&nbsp;
	';
}
?>
<br><br><div class="text-muted">Чтобы выбрать одно обследование, нажмите его с удерживанием клавиши CTRL</div>
<?php
spoiler_end();
?>
<br>
