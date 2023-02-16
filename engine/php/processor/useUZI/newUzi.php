<?php
$response['stage'] = $action;

$uziNote = $_POST['uziNote'];

$newNote = array(
	'note_doctor_id'    =>  $USER_PROFILE['doctor_id'],
	'note_unix' =>  time(),
	'note_text' =>  $uziNote
);

$NewNote = appendData(CAOP_UZI_NOTED, $newNote);
if ( $NewNote[ID] > 0 )
{
//	debug($NewNote);
	$response['result'] = true;
} else
{
	$response['msg'] = $NewNote;
}