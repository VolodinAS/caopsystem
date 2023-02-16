<div class="modal fade" id="newNoteRecord" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Новая запись в журнал УЗИ-аппарата</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body" id="patientNameModal">

				<form name="newUzi" id="newUzi" action="php_journal.php?new" method="post">

					<div class="form-group">
						<label for="recipient-name" class="col-form-label">Врач:</label>
						<div class="ui-widget">
							<?=$DOCTOR_NAME;?>
						</div>
					</div>
					<div class="form-group">
						<label for="uziNote" class="col-form-label">Примечание:</label>
						<input type="text" class="form-control form-control-lg" id="uziNote" name="uziNote" placeholder="Примечание" value="б/о">
					</div>

					<input type="submit" style="display: none" />

				</form>

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>
				<button type="button" class="btn btn-primary" id="addNewUzi">Добавить</button>
			</div>
		</div>
	</div>
</div>