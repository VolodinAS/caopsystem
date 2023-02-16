<?php
$response['stage'] = $action;
$response['msg'] = 'begin';


$response['htmlData'] = 'Нет сообщений';
//$query_message = "SELECT * FROM {$CAOP_CHAT_MESSAGES}
//					LEFT JOIN {$CAOP_DOCTOR} ON {$CAOP_CHAT_MESSAGES}.message_doctor_id = {$CAOP_DOCTOR}.doctor_id
//					ORDER BY {$CAOP_CHAT_MESSAGES}.message_send_unix DESC";
//$result_message = mqc($query_message);
//$amount_message = mnr($result_message);

$Messages = getarr(CAOP_CHAT_MESSAGES, 1, "ORDER BY message_send_unix DESC");
if ( count($Messages) > 0 )
{
	$response['htmlData'] = '';
	foreach ($Messages as $message)
	{
		$general_align = "left";
		$general_bg = "info";
		$DoctorData = $DoctorsListId['id'.$message['message_doctor_id']];
		if ( $DoctorData['doctor_id'] == $USER_PROFILE['doctor_id'] )
		{
			$general_align = "right";
			$general_bg = BT_THEME_SECONDARY;
		}
		$DoctorName = docNameShort($DoctorData, "famimot");
		$MessageTime = date("d.m.Y H:i:s", $message['message_send_unix']);
		
		$MESSAGE = $message['message_text'];
		$MESSAGE = getLinksFromMessage($MESSAGE);
		
		$response['htmlData'] .= '
		<div class="row general-row">
			<div class="col general-col align-'.$general_align.'">
				<div class="row message-row p-1">
					<div class="col message-col">
					['.$MessageTime.'] <span class="text-white bg-'.$general_bg.' rounded">'.$DoctorName.'</span>:<br>
					'.$MESSAGE.'
					</div>
				</div>
			</div>
		</div>
		<div class="dropdown-divider"></div>
		';
    }
}

$response['result'] = true;