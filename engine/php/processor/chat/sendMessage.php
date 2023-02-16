<?php
$response['stage'] = $action;
$response['msg'] = 'begin';

/**
 * НОВАЯ ARRAY-ФУНКЦИЯ
 * mysqlGroupArray - возвращает массив с объединенными данными
 * Входящие параметры
 *  array - входящий массив
 *  key_field - ключевое поле - поле, по которому будут сверяться массивы
 *  diff_field - поле различий - поле, которое будет объединяться из двух в одно
 */

$HTTP = $_POST;
extract($HTTP, EXTR_PREFIX_SAME, '_caop');

if ( mb_strlen($message, UTF8) > 0 )
{

	// TODO проверяем прикрепления
	$Attach_array = [];
	$Attaches = getarr(CAOP_CHAT_ATTACH, "attach_doctor_id='{$USER_PROFILE['doctor_id']}'", "ORDER BY attach_id ASC");
	if ( count($Attaches) > 0 )
	{
		foreach ($Attaches as $attach)
		{
			$Attach_array[] = $attach['attach_file_id'];
	    }
	}
	$Attach_json = json_encode($Attach_array);
	
	$message_values = array(
	    'message_doctor_id' =>  $USER_PROFILE['doctor_id'],
		'message_text'  =>  trim(mres($message)),
		'message_attachments_json'  =>  $Attach_json,
		'message_send_unix' =>  time(),
	);
	
	$AddMessage = appendData(CAOP_CHAT_MESSAGES, $message_values);
	if ( $AddMessage[ID] > 0 )
	{
		// сообщение добавилось, удаляем аттачи
		$DeleteAttaches = deleteitem(CAOP_CHAT_ATTACH, "attach_doctor_id='{$USER_PROFILE['doctor_id']}'");
		if ( $DeleteAttaches ['result'] === true )
		{
			// аттачи удалены
			$response['result'] = true;
		}
		
	}

} else $response['msg'] = 'Сообщение не может быть пустым';