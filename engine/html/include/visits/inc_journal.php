<?php



$visit_time = ( strlen($visit['journal_time']) > 1 ) ? $visit_time = ' ' . $visit['journal_time'] : '';
$Doctor = $DoctorsListId['id' . $visit['journal_doctor']];
$doc_name = $Doctor['doctor_f'] . ' ' . $Doctor['doctor_i'] . ' ' . $Doctor['doctor_o'];
$doc_name = mb_ucwords($doc_name);

//$response['htmlData'] .= debug_ret($RegButtons);

$InfirstData = $JournalInfirstId['id' . $visit['journal_infirst']]['infirst_title'];

$CardPlaceInput = '<input type="text" class="mysqleditor font-weight-bolder form-control form-control-lg" value="'.$visit['journal_cardplace'].'"
		        id="journal_cardplace_'.$visit['journal_id'].'"
		        placeholder="место карты"
		        data-action="edit"
		        data-table="caop_journal"
		        data-assoc="0"
		        data-fieldid="journal_id"
		        data-id="'.$visit['journal_id'].'"
		        data-field="journal_cardplace"
		        data-return="0">';

$record_data = '';
if ( strlen($visit['journal_record_lpu']) > 0 )
{
	$record_data .= '<b>Запись учреждением:</b> '.$visit['journal_record_lpu'].' ('.$visit['journal_record_division'].')<br/>';
	$record_data .= '<b>Чья запись:</b> '.$visit['journal_record_worker'].' ('.$visit['journal_record_date'].' '.$visit['journal_record_time'].')<br/>';
}
$collapser = '';
if ( $closed ) $collapser = 'collapse';

$spo_ender = '';
if ( $visit['journal_spo_end_reason_type'] > 0 )
{
	$spo_ender = ' [СПО-исход визита: '.wrapper($SPOReasonTypesId['id' . $visit['journal_spo_end_reason_type']]['reason_type_title']).']';
}

$response['htmlData'] .= spoiler_begin_return('<b>[#'.$npp.']</b> Посещение от ' . $VisitDay['day_date'].$visit_time . $spo_ender, 'visit'.$visit['journal_id'], $collapser);
if ( $visit['journal_visit_type'] == 2 )
{
	$response['htmlData'] .= bt_notice(wrapper('ТЕЛЕМЕДИЦИНА'), BT_THEME_WARNING, 1);
	$response['htmlData'] .= '<b>Врач:</b> ' . $doc_name . '<br/>';
	$response['htmlData'] .= '<b>Диагноз:</b> [' . $visit['journal_ds'] . '] '.$visit['journal_ds_text'].'<br/>';
	$response['htmlData'] .= '<b>Место карты:</b> '.$visit['journal_cardplace'].'<br/>';
} else
{
	$response['htmlData'] .= wrapper(wrapper('СПО: ') . $visit['journal_spo_id'] . '<br>', 'small');
	$response['htmlData'] .= '<b>Ф.И.О:</b> ' . mb_ucwords($PatientPersonalData['patid_name']) . '<br/>';
	$response['htmlData'] .= '<b>Последний случай:</b> ' . $CaseStatusesListId['id' . $PatientPersonalData['patid_casestatus']]['casestatus_title'] . '<br/>';
	$response['htmlData'] .= '<b>Врач:</b> ' . $doc_name . '<br/>';
	$response['htmlData'] .= '<b>Диагноз:</b> [' . $visit['journal_ds'] . '] '.$visit['journal_ds_text'].'<br/>';
	$response['htmlData'] .= '<b>Рекомендации:</b> '.$visit['journal_ds_recom'].'<br/>';
	$response['htmlData'] .= '<b>Исход:</b> '.$visit['journal_recom'].'<br/>';
	$response['htmlData'] .= '<b>Случай:</b> '.$InfirstData.'<br/>';
	$response['htmlData'] .= '<b>Место карты:</b> '.$visit['journal_cardplace'].'<br/>';
	$response['htmlData'] .= $record_data;
}


//                $response['htmlData'] .= debug_ret($visit);

if ( isset($_POST['hideCase']) )
{
	if ( $_POST['hideCase'] == "1" )
	{
	
	} else
	{
		$response['htmlData'] .= bt_divider(1) . '<b>Расположение карты:</b> '.$CardPlaceInput;
		if ( $USER_PROFILE['doctor_isreg'] == 1 || $USER_PROFILE['doctor_id'] == 1 )
		{
			$RegButtons_dom = '';
			foreach ($RegButtons as $regButton)
			{
				$RegButtons_dom .= '<a class="dropdown-item" id="reg_button_'.$regButton['reg_button_id'].'" href="javascript:setCardPlace(\''.$regButton['reg_button_title'].'\', '.$visit['journal_id'].')">'. BT_ICON_OK .' '.$regButton['reg_button_title'].'</a>';
			}
			$response['htmlData'] .= '
			<div class="dropdown align-right">
				<a class="btn btn-info dropdown-toggle" href="#" role="button" id="reg_buttons" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				Быстрые места для карты
				</a>
				
				<div class="dropdown-menu" aria-labelledby="reg_buttons">
					'.$RegButtons_dom.'
				</div>
			</div>
			';
			$response['htmlData'] .= bt_divider(1);
		}
		
		
		foreach ($CaseStatusesList as $caseStatus) {
			$response['htmlData'] .= '
		            <button class="btn btn-secondary btn-sm setPatientCase" data-case="casestatus'.$PatientPersonalData['patid_casestatus'].'" data-patid="'.$PatientPersonalData['patid_id'].'" data-caseid="'.$caseStatus['casestatus_id'].'">'.$caseStatus['casestatus_title'].'</button>
		            ';
		}
		$response['htmlData'] .= '<br/><br/>';
	}
}

$response['htmlData'] .= '<a target="_blank" href="/journalAlldays/'.$VisitDay['day_id'].'/light'.$visit['journal_id'].'"><b>Перейти к приёму...</b></a>';
$response['htmlData'] .= spoiler_end_return();
