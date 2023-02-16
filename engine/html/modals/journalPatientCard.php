<div class="modal fade" id="journalPatientCard" tabindex="-1" role="dialog" aria-labelledby="journalPatientCardModalLabel" data-focus-on="input:first">
	<div class="modal-dialog modal-dialog-centered modal-xl" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="journalPatientCardModalLabel">Редактировать карточку пациента</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close" aria-hidden="true">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body size-16pt" id="journalPatientData">

				<div class="input-group input-group-sm" id="journal_field_card_loader">
					<div class="spinner-border text-primary" role="status">
						<span class="sr-only">Loading...</span>
					</div>
				</div>

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-info " id="btn-journalCard-refresh">Обновить</button>
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
			</div>
		</div>
	</div>
</div>