<?php
$response['stage'] = $action;

$appendParams = $_POST;
$TABLE = $_POST['table'];
unset($appendParams['table']);

$mysql_params = array();
foreach ($appendParams as $appendParam=>$appendValue) {
	$mysql_params[$appendParam] = mres($appendValue);
}

//$response['debug']['$appendParams'] = $appendParams;
//$response['debug']['$mysql_params'] = $mysql_params;

$Append = appendData( $TABLE, $mysql_params );

if ( $Append[ID] > 0 )
{
	$response['result'] = true;
} else
{
	$response['msg'] = $Append;
}