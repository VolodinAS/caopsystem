<?php
$response['stage'] = $action;

if ( in_array( $USER_PROFILE['doctor_access'], $UpdaterAccessModerator ) )
{

	$uzi_id = $_POST['uzi_id'];

	$DeleteUzi = deleteitem(CAOP_UZI_NOTED, "note_id='{$uzi_id}'");
	if ( $DeleteUzi['result'] === true )
	{
//		$response['debug'] = $DeleteUzi;
		$response['result'] = true;
	} else
	{
		$response['msg'] = $DeleteUzi;
	}

} else
{
	$response['msg'] = 'У Вас нет доступа к данному действию';
}