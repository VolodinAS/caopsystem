<?php
$response['stage'] = $action;
$response['msg'] = 'begin';

$HTTP = $_POST;
extract($HTTP, EXTR_PREFIX_SAME, '_caop');

$DoubleRM = RecordManipulation($double_id, CAOP_DOUBLE, 'double_id');
if ( $DoubleRM['result'] )
{
    $DoubleData = $DoubleRM['data'];
    
    $DeleteDouble = deleteitem(CAOP_DOUBLE, "double_id='$double_id'");
    if ($DeleteDouble ['result'] === true)
    {
    	$response['result'] = true;
    	$response['msg'] = 'Конфликт дубля разрешен';
    } else
    {
    	$response['msg'] = 'Проблема при удалении дубля';
    }

} else $response['msg'] = $DoubleRM['msg'];