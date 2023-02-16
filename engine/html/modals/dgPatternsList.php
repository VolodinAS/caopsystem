<?php
$ModalSettings = array(
	'modal_name' => 'patternsList',
	'modal_header' => 'Список паттернов диагнозов',
	'modal_width' => 'xl',
	'modal_cancel_button' => true,
	'modal_loader_default' => false
);

$modal_cancel_button = '';
if ($ModalSettings['modal_cancel_button']) $modal_cancel_button = '<button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>';

$modal_loader_default = '';
if ($ModalSettings['modal_loader_default']) $modal_loader_default = '<div class="input-group input-group-sm"><div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div></div>'

?>
<div class="modal fade" id="<?= $ModalSettings['modal_name']; ?>" tabindex="-1" role="dialog"
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
				<?= $modal_loader_default; ?>
				<div id="<?= $ModalSettings['modal_name']; ?>_result">
				</div>
			</div>
			<div class="modal-footer" id="<?= $ModalSettings['modal_name']; ?>_footer">
				<?php
				// buttons
				?>
				<?= $modal_cancel_button; ?>
			</div>
		</div>
	</div>
</div>