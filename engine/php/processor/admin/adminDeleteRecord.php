<?php
$response['stage'] = $action;

$DeleteItem = deleteitem( $_POST['table'], "{$_POST['field_id']}='{$_POST['item_id']}'" );

if ( $DeleteItem['result'] === true )
{
	$response['result'] = true;
} else
{
	$response['msg'] = $DeleteItem;
}