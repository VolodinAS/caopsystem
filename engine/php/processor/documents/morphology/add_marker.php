<?php
$response['stage'] = $action;
$response['msg'] = 'begin';

$HTTP = $_POST;
extract($HTTP, EXTR_PREFIX_SAME, '_caop');

$MorphRM = RecordManipulation($morph_id, CAOP_MORPHOLOGY, 'morph_id');
if ( $MorphRM['result'] )
{
    $MorphData = $MorphRM['data'];
	
    $param_marker = array(
        'marker_morph_id' => $MorphData['morph_id']
    );
    
    $AddMarker = appendData(CAOP_MORPHOLOGY_MARKER, $param_marker);
    if ( $AddMarker[ID] > 0 )
    {
    	$response['result'] = true;
    } else
    {
    	$response['msg'] = 'Проблема с добавлением биоматериала';
    }

} else $response['msg'] = $MorphRM['msg'];