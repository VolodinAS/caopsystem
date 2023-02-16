<div class="modal fade" id="miasImportWindow2" tabindex="-1" role="dialog" aria-labelledby="miasImportWindow2" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="miasImportWindow2">Импорт списка из МИАС (новый)</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body" id="miasImport2">
				
				<form name="miasImportForm2" id="miasImportForm2" action="php_processor.php?new" method="post">
					<div class="form-group">
						<label for="recipient-name" class="col-form-label">Копия текста из экспорт-панели журнала записей пациентов из МИАС:</label>
						<textarea class="form-control" name="miasText2" id="miasText2" placeholder="Копировать - Вставить, поля: ПОЛНАЯ ТАБЛИЦА" rows="15"></textarea>
					</div>

                    <div class="form-group">
                        <input class="form-check-input" type="checkbox"
                               name="editIgnore" id="editIgnore" value="1">
                        <label class="form-check-label box-label"
                               for="editIgnore"><span></span>Игнорировать принятых</label>
                    </div>
					
					<input type="submit" style="display: none" />
				
				</form>
			
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>
				<button type="button" class="btn btn-primary" id="newMias2">Импорт</button>
			</div>
		</div>
	</div>
</div>