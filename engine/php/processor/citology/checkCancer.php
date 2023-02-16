<?php
$response['stage'] = $action;
$response['msg'] = 'begin';

$HTTP = $_POST;
extract($HTTP, EXTR_PREFIX_SAME, '_caop');

switch ($type)
{
    case "citology":
	    $CitologyRM = RecordManipulation($citology_id, CAOP_CITOLOGY, 'citology_id');
	    if ( $CitologyRM['result'] )
	    {
		    $CitologyData = $CitologyRM['data'];
		
//		    $response['debug']['$CitologyData'] = $CitologyData;
		
		    if ($CitologyData['citology_cancer'] > 0)
		    {
			    $response['result'] = true;
		    } else $response['msg'] = 'НЕОБХОДИМО ВЫБРАТЬ, ВЫЯВЛЕН ЛИ РАК В РЕЗУЛЬТАТЕ ИССЛЕДОВАНИЯ (красное поле)!';
		
	    } else $response['result'] = true;
    break;
    case "research":
        $ResearchRM = RecordManipulation($research_id, CAOP_RESEARCH, 'research_id');
        if ( $ResearchRM['result'] )
        {
            $ResearchData = $ResearchRM['data'];
                
//            $response['debug']['$ResearchData'] = $ResearchData;
	
	        if ($ResearchData['research_cancer'] > 0)
	        {
		        $response['result'] = true;
	        } else $response['msg'] = 'НЕОБХОДИМО ВЫБРАТЬ, ВЫЯВЛЕН ЛИ РАК В РЕЗУЛЬТАТЕ ИССЛЕДОВАНИЯ (красное поле)!';
        
        } else $response['msg'] = $ResearchRM['msg'];
    break;
    default:
        $response['msg'] = 'Такого типа обследования не найдено!';
    break;
}