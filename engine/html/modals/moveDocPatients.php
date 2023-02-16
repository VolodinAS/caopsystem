<div class="modal fade" id="moveDocPatients" tabindex="-1" role="dialog" aria-labelledby="moveDocPatCalendarWindow" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="moveDocPatCalendarWindow">Передать пациентов другому врачу</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body" id="moveDocCalendar">
				Дата для переноса: <br>
				<input type="date" class="form-control" id="dateMoveDocPatients" value="<?=date("Y-m-d")?>">
				<br>
				Выберите врача: <br>
				<select class="form-control form-control-lg" name="docid" id="docid">
					<option value="0">Выберите врача...</option>
					<?php
					foreach ($DoctorsListId as $docid=>$docdata) {
						$docid = str_replace("id", "", $docid);
						echo '
						<option value="'.$docid.'">'.docNameShort($docdata).'</option>
						';
					}
					?>
				</select>

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>
				<button type="button" class="btn btn-primary" id="moveDocIt">OK</button>
			</div>
		</div>
	</div>
</div>