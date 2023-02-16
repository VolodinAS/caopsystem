<? spoiler_begin('Документы направления', 'pateint_dirdoc', ''); ?>
<?php
if ($isPatient)
{
	?>
    <button class="btn btn-sm btn-primary"
            id="addDirection"
            data-patient="<?= $PatientData['patient_id']; ?>">Добавить направление
    </button>
	<?php
	$Dirlists = getarr(CAOP_DS_DIRLIST, "dirlist_dspatid='{$PatientData['patient_id']}'", "ORDER BY dirlist_doc_unix DESC");
	if (count($Dirlists) > 0)
	{
		$DOSE_MEASURE_TYPES = getarr(CAOP_DS_DOSE_MEASURE_TYPE, "type_enabled='1'", "ORDER BY type_order ASC");
		$DOSE_MEASURE_TYPES_ID = getDoctorsById($DOSE_MEASURE_TYPES, 'type_id');
		
		$DOSE_PERIOD_TYPES = getarr(CAOP_DS_DOSE_PERIOD_TYPE, "type_enabled='1'", "ORDER BY type_order ASC");
		$DOSE_PERIOD_TYPES_ID = getDoctorsById($DOSE_PERIOD_TYPES, 'type_id');
		
		$DOSE_FREQ_PERIOD_TYPES = getarr(CAOP_DS_FREQ_PERIOD_TYPE, "type_enabled='1'", "ORDER BY type_order ASC");
		$DOSE_FREQ_PERIOD_TYPES_ID = getDoctorsById($DOSE_FREQ_PERIOD_TYPES, 'type_id');
		
		$DS_RESEARCH_TYPES = getarr(CAOP_DS_RESEARCH_TYPES, "type_enabled='1'", "ORDER BY type_order ASC");
		$DS_RESEARCH_TYPES_ID = getDoctorsById($DS_RESEARCH_TYPES, 'type_id');
	 
		foreach ($Dirlists as $dirlist)
		{
			$mainDirlist = ($dirlist['dirlist_isMain'] == 1) ? BT_ICON_PLAY_FILL : '';
			?>
			<? spoiler_begin($mainDirlist . '[#' . $dirlist['dirlist_id'] . '] Направление от ' . $dirlist['dirlist_doc_date'] . ', визит от ' . $dirlist['dirlist_visit_date'], 'dirlist_' . $dirlist['dirlist_id']); ?>
            <form id="form_dirlist_<?= $dirlist['dirlist_id']; ?>">
                <input type="hidden"
                       name="patient_id"
                       huid="patient_id"
                       value="<?= $dirlist['dirlist_dspatid']; ?>">
                <input type="hidden"
                       name="dirlist_id"
                       huid="dirlist_id"
                       value="<?= $dirlist['dirlist_id']; ?>">
                <div class="row">
                    <div class="col">
                        <label class="sr-only"
                               for="dirlist_doc_date">Дата направления</label>
                        <div class="input-group mb-2">
                            <div class="input-group-prepend">
                                <div class="input-group-text" <?= super_bootstrap_tooltip('Дата направления'); ?>>
									<?= BT_ICON_ARROW_UP_SQUARE_FILL; ?>
                                </div>
                            </div>
                            <input type="text"
                                   class="form-control russianBirth required-field"
                                   huid="dirlist_doc_date"
                                   name="dirlist_doc_date"
                                   placeholder="Дата направления. Пример: <?= date('d.m.Y'); ?>"
                                   value="<?= $dirlist['dirlist_doc_date']; ?>">
                        </div>
                    </div>

                    <div class="col">
                        <label class="sr-only"
                               for="dirlist_visit_date">Дата визита</label>
                        <div class="input-group mb-2">
                            <div class="input-group-prepend">
                                <div class="input-group-text" <?= super_bootstrap_tooltip('Дата визита'); ?>>
									<?= BT_ICON_ARROW_DOWN_SQUARE_FILL; ?>
                                </div>
                            </div>
                            <input type="text"
                                   class="form-control russianBirth required-field"
                                   huid="dirlist_visit_date"
                                   name="dirlist_visit_date"
                                   placeholder="Дата визита. Пример: <?= date('d.m.Y'); ?>"
                                   value="<?= $dirlist['dirlist_visit_date']; ?>">
                        </div>
                    </div>

                    <div class="col">
                        <label class="sr-only"
                               for="dirlist_done_date">Дата завершения лечения</label>
                        <div class="input-group mb-2">
                            <div class="input-group-prepend">
                                <div class="input-group-text" <?= super_bootstrap_tooltip('Дата завершения лечения'); ?>>
									<?= BT_ICON_REGIMEN_DONE; ?>
                                </div>
                            </div>
                            <input type="text"
                                   class="form-control russianBirth"
                                   huid="dirlist_done_date"
                                   name="dirlist_done_date"
                                   placeholder="Дата завершения лечения. Пример: <?= date('d.m.Y'); ?>"
                                   value="<?= $dirlist['dirlist_done_date']; ?>">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <label class="sr-only"
                               for="dirlist_diag_mkb">Диагноз по МКБ</label>
                        <div class="input-group mb-2">
                            <div class="input-group-prepend">
                                <div class="input-group-text" <?= super_bootstrap_tooltip('Диагноз по МКБ'); ?>>
									<?= BT_ICON_DIAG_MKB; ?>
                                </div>
                            </div>
                            <input type="text"
                                   class="form-control mkbDiagnosis required-field"
                                   huid="dirlist_diag_mkb"
                                   name="dirlist_diag_mkb"
                                   placeholder="Диагноз по МКБ. Пример: C61"
                                   value="<?= $dirlist['dirlist_diag_mkb']; ?>">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <label class="sr-only"
                               for="dirlist_diag_text">Текст диагноза</label>
                        <div class="input-group mb-2">
                            <div class="input-group-prepend align-top">
                                <div class="input-group-text align-top" <?= super_bootstrap_tooltip('Текст диагноза'); ?>>
									<?= BT_ICON_DIAG_TEXT; ?>
                                </div>
                            </div>
                            <textarea class="form-control autosizer required-field"
                                      name="dirlist_diag_text"
                                      huid="dirlist_diag_text"
                                      rows="5"
                                      placeholder="Текст диагноза"><?= $dirlist['dirlist_diag_text']; ?></textarea>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <label class="sr-only"
                               for="dirlist_dirdoc_name">Ф.И.О. направившего врача</label>
                        <div class="input-group mb-2">
                            <div class="input-group-prepend">
                                <div class="input-group-text" <?= super_bootstrap_tooltip('Ф.И.О. направившего врача'); ?>>
									<?= BT_ICON_PERSONAL; ?>
                                </div>
                            </div>
                            <input type="text"
                                   class="form-control"
                                   huid="dirlist_dirdoc_name"
                                   name="dirlist_dirdoc_name"
                                   placeholder="Ф.И.О. направившего врача"
                                   value="<?= $dirlist['dirlist_dirdoc_name']; ?>">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <label class="sr-only"
                               for="dirlist_found_title">Название направившего учреждения</label>
                        <div class="input-group mb-2">
                            <div class="input-group-prepend">
                                <div class="input-group-text" <?= super_bootstrap_tooltip('Название направившего учреждения'); ?>>
									<?= BT_ICON_BUILD; ?>
                                </div>
                            </div>
                            <input type="text"
                                   class="form-control"
                                   huid="dirlist_found_title"
                                   name="dirlist_found_title"
                                   placeholder="Название направившего учреждения"
                                   value="<?= $dirlist['dirlist_found_title']; ?>">
                        </div>
                    </div>
                </div>

                <div class="row">
					<?php
					$dirlist_isMain_checked = ($dirlist['dirlist_isMain'] == 1) ? ' checked' : '';
					?>
                    <div class="col">
                        <label class="sr-only"
                               for="dirlist_isMain">Это основное направление!</label>
                        <div class="input-group mb-2">
                            <div class="input-group-prepend">
                                <div class="input-group-text" <?= super_bootstrap_tooltip('Это основное направление!'); ?>>
									<?= BT_ICON_PLAY_FILL; ?>
                                </div>
                            </div>
                            <input class="form-check-input move-labeler"
                                   type="checkbox"
                                   name="dirlist_isMain"
                                   id="dirlist_isMain_<?= $dirlist['dirlist_id']; ?>"
                                   value="1"<?= $dirlist_isMain_checked; ?>>
                            <label class="form-check-label box-label"
                                   for="dirlist_isMain_<?= $dirlist['dirlist_id']; ?>"><span></span><b>Это основное
                                                                                                       направление!</b></label>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <button class="btn col btn-primary btn-lg saveDirlist"
                                data-dirlist="<?= $dirlist['dirlist_id']; ?>">СОХРАНИТЬ
                        </button>
                    </div>
                    <div class="col">
                        <button class="btn col btn-success btn-lg createRegimen"
                                data-dirlist="<?= $dirlist['dirlist_id']; ?>">НАЗНАЧЕНИЕ
                        </button>
                    </div>
                    <div class="col-auto">
                        <button class="btn col btn-danger btn-sm deleteDirlist"
                                data-dirlist="<?= $dirlist['dirlist_id']; ?>"
                                data-patient="<?= $dirlist['dirlist_dspatid']; ?>">УДАЛИТЬ
                        </button>
                    </div>
                </div>
            </form>
            <br>
			
			<?php
			include("engine/html/dayStac/include/inc_spoiler_visreg.php");
			?>
			
			<? spoiler_end(); ?>
			<?php
		}
	}
	?>
    <!--        ДИАГНОЗЫ ДОЛЖНЫ БЫТЬ В НАПРАВЛЕНИИ-->
    <!---->
	<?php
} else
{
	bt_notice('Сначала выберите пациента', BT_THEME_WARNING);
}
?>
<? spoiler_end(); ?>