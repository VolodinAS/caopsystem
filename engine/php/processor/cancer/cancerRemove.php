<?php
$response['stage'] = $action;

$Delete = deleteitem(CAOP_CANCER, "cancer_id={$_POST['diag_id']}");

$response['debug'] = $Delete;

if ( $Delete['result'] === true )
{
	$response['result'] = true;
}