<?php
$ModalSettings = array(
	'modal_name' => 'graphicClear',
	'modal_header' => 'Выберите период, где нужно убрать смены',
	'modal_width' => CODE_MD, /*РАЗМЕР*/
	'modal_cancel_button' => true, /*ПОКАЗАТЬ КНОПКУ ЗАКРЫТИЯ?*/
	'modal_loader_default' => false /*ПОКАЗАТЬ ИКОНКУ ЗАГРУЗКИ? (ДЛЯ ДИНАМИЧЕСКИХ ОКОН)*/
);

$modal_cancel_button = '';
if ($ModalSettings['modal_cancel_button']) $modal_cancel_button = '<button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>';

$modal_loader_default = '';
if ($ModalSettings['modal_loader_default']) $modal_loader_default = '<div class="input-group input-group-sm"><div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div></div>'

?>
<div class="modal fade modalled" id="<?= $ModalSettings['modal_name']; ?>" tabindex="-1" role="dialog"
     aria-labelledby="<?= $ModalSettings['modal_name']; ?>" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-<?= $ModalSettings['modal_width']; ?>" role="document">
		<div class="modal-content">
			<div class="modal-header" id="<?= $ModalSettings['modal_name']; ?>_header">
				<h3 class="modal-title"
				    id="<?= $ModalSettings['modal_name']; ?>_header_string"><?= $ModalSettings['modal_header']; ?></h3>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body" id="<?= $ModalSettings['modal_name']; ?>_body">
				<form id="<?= $ModalSettings['modal_name']; ?>_form">
					<input type="hidden" name="uzi_doctor_id" value="<?=$UziDoctor['uzi_doctor_id'];?>">
					<div class="form-group">
						<label for="dater_from">Дата начала очистки:</label>
						<input type="date" class="form-control" id="dater_from" name="dater_from" required aria-required="true">
					</div>
					<div class="form-group">
						<label for="dater_to">Дата конца очистки:</label>
						<input type="date" class="form-control" id="dater_to" name="dater_to" required aria-required="true">
					</div>
				</form>
			
			</div>
			<div class="modal-footer" id="<?= $ModalSettings['modal_name']; ?>_footer">
				<button class="btn btn-primary btn-removeGraphic">Очистить</button>
				<?php
				// buttons
				?>
				<?= $modal_cancel_button; ?>
			</div>
		</div>
	</div>
</div>