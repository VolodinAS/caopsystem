<?php
$response['stage'] = $action;
$response['msg'] = 'begin';
$response['htmlData'] = '';

$HTTP = $_POST;
extract($HTTP, EXTR_PREFIX_SAME, '_caop');

$response['result'] = true;

$JournalRM = RecordManipulation($journal_id, CAOP_JOURNAL, 'journal_id');
if ($JournalRM['result'])
{
	$JournalData = $JournalRM['data'];
	//$response['htmlData'] .= debug_ret($JournalData);
	$need_move_date = (strlen($JournalData['journal_need_move']) < 10) ? '' : $JournalData['journal_need_move'];
	
	$response['htmlData'] .= '
	<b>Вы можете просто ввести дату, а когда будет необходимость - отменить перенос или перенести</b>
	<div class="dropdown-divider"></div>
	<div class="row">
		<div class="col">
			<input type="date" class="form-control mysqleditor" id="fastMove" value="' . $need_move_date . '" data-action="edit"
			data-table="' . CAOP_JOURNAL . '"
			data-assoc="0"
			data-fieldid="journal_id"
			data-id="' . $JournalData['journal_id'] . '"
			data-field="journal_need_move"
			data-unixfield="journal_update_unix"
			data-return="#fastMoveButtonIcon' . $JournalData['journal_id'] . '"
			data-returntype="html"
			data-returnfunc="fast_move_icon"
			>
		</div>
		<div class="col-auto">
			<button class="btn btn-primary" id="moveItFast" data-journalid="' . $JournalData['journal_id'] . '">Перенести</button>
		</div>
		<div class="col-auto">
			<button class="btn btn-warning" id="cancelFastMove">Отменить перенос</button>
		</div>
	</div>
	';
}