<?php
$response['stage'] = $action;
$response['msg'] = 'begin';

$HTTP = $_POST;
extract($HTTP, EXTR_PREFIX_SAME, '_caop');

$PatternRM = RecordManipulation($pattern_id, CAOP_DIAGNOSIS_PATTERNS, 'pattern_id');
if ( $PatternRM['result'] )
{
    $PatternData = $PatternRM['data'];
    
    $DeletePattern = deleteitem(CAOP_DIAGNOSIS_PATTERNS, "pattern_id='{$PatternData['pattern_id']}'");
    if ( $DeletePattern ['result'] === true )
    {
        $response['result'] = true;
        $response['msg'] = 'Паттерн успешно удалён';
    } else
    {
    	$response['msg'] = 'Проблема с удалением паттерна';
    	$response['debug']['$DeletePattern'] = $DeletePattern;
    }

} else $response['msg'] = $PatternRM['msg'];