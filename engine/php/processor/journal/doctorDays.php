<?php
$response['stage'] = $action;
$response['msg'] = 'begin';

$HTTP = $_POST;
extract($HTTP, EXTR_PREFIX_SAME, '_caop');

if ( $doctor_id > 0 )
{
	$response['result'] = true;
	$DoctorData = $DoctorsListId['id' . $doctor_id];
	
	if ( count($DoctorData) > 0 )
	{
		
//		$response['htmlData'] .= debug_ret($DoctorData);
		
		$journal_query = "SELECT day_id, day_doctor, day_date, day_unix, COUNT(cj.journal_id) AS journal_visit FROM ".CAOP_DAYS." cd
							LEFT JOIN ".CAOP_JOURNAL." cj ON cj.journal_day=cd.day_id
							WHERE day_doctor='{$DoctorData[$PK[CAOP_DOCTOR]]}'
							GROUP BY cd.day_id
							ORDER BY cd.day_unix DESC";
		$journal_result = mqc($journal_query);
		$JournalDays = mr2a($journal_result);
		
		if ( count($JournalDays) > 0 )
		{
			$DoctorJournal = getrows(CAOP_JOURNAL, "journal_doctor='{$DoctorData[$PK[CAOP_DOCTOR]]}'", $PK[CAOP_JOURNAL]);
			$response['htmlData'] .= bt_notice( 'Приёмные дни: <b>'.count($JournalDays).'</b><br/>Врачом проведено: <b>'.$DoctorJournal['count'].'</b> ' . pluralForm( $DoctorJournal['count'], 'приём', 'приёма', 'приёмов' ) , 'info', 1);
			$response['htmlData'] .= '<hr>';
			
			foreach ($JournalDays as $day) {
				$signed = ($day['day_signature_state'] == 1) ? 'signature.png' : 'notsignature.png';
				$response['htmlData'] .= '
				<div class="row">
					<div class="col-1 align-right"><img src="/engine/images/icons/'.$signed.'" alt=""></div>
					<div class="col-11"><a target="_blank" href="/journalAlldaysDoctors/'.$day['day_id'].'">'.date("d.m.Y", $day['day_unix']).'</a> ['.$day['journal_visit'].']</div>
				</div>
				<hr>
				';
			}
		
		} else $response['msg'] = bt_notice('У врача нет приёмов', BT_THEME_WARNING, 1);
		
//		$response['htmlData'] .= debug_ret($JournalDays);
		
//		$DoctorDays = getarr(CAOP_DAYS, "day_doctor='{$DoctorData[$PK[CAOP_DOCTOR]]}'", "ORDER BY day_unix DESC");
//		if ( count($DoctorDays) > 0 )
//		{
//
//
//
//
//		} else $response['msg'] = bt_notice('У врача нет приёмов', BT_THEME_WARNING, 1);
		
	} else $response['msg'] = bt_notice('Врач в списке не значится', BT_THEME_WARNING, 1);

} else $response['msg'] = bt_notice('Выберите врача из списка', BT_THEME_WARNING, 1);