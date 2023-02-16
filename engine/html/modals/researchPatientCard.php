<div class="modal fade" id="researchPatientCard" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Информация о пациенте</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body size-16pt" id="researchPatientData">

				<div class="input-group input-group-sm" id="fieldloader<?=$Patient['journal_id'];?>">
					<div class="spinner-border text-primary" role="status">
						<span class="sr-only">Loading...</span>
					</div>
				</div>

			</div>
			<div class="modal-footer">
                <button type="button" class="btn btn-danger" id="btn-forceClose">Закрыть, так как результата пока еще нет</button>
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
			</div>
		</div>
	</div>
</div>