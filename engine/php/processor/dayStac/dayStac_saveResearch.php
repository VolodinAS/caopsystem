<?php
$response['stage'] = $action;
$response['debug']['$_POST'] = $_POST;
$response['msg'] = 'begin';

$HTTP = $_POST;
extract($HTTP, EXTR_PREFIX_SAME, '_caop');

$DS_RESEARCH_TYPES = getarr(CAOP_DS_RESEARCH_TYPES, "type_enabled='1'", "ORDER BY type_order ASC");
$DS_RESEARCH_TYPES_ID = getDoctorsById($DS_RESEARCH_TYPES, 'type_id');

foreach ($DS_RESEARCH_TYPES_ID as $researchId=>$researchType)
{
	$researchId = str_replace('id', '', $researchId);
	
	$resvar_value = 'field_value_' . $researchId;
	$resvar_date = 'field_date_' . $researchId;
	$resvar_note = 'field_note_' . $researchId;
	
	if ( strlen($HTTP[$resvar_value])>0 && strlen($HTTP[$resvar_date])>0 )
	{
		$paramValues = array(
			'research_patid'    =>  $HTTP['patient_id'],
			'research_resid'    =>  $researchId,
			'research_value'    =>  $HTTP[$resvar_value],
			'research_note'    =>  $HTTP[$resvar_note],
			'research_date'    =>  $HTTP[$resvar_date],
			'research_unix'    =>  birthToUnix($HTTP[$resvar_date])
		);
		
		$response['debug']['$paramValues_'.$researchId][] = $paramValues;
		
		$InsertResearch = appendData(CAOP_DS_RESEARCH, $paramValues);
		
		$response['result'] = true;
	} else
	{
		if ( strlen($HTTP[$resvar_note]) > 0 )
		{
			// обновить примечание
//			$response['debug']['$researchId' . $researchId] = $HTTP[$resvar_note];
			$ResearchNote = getarr(CAOP_DS_RESEARCH, "research_patid='{$HTTP['patient_id']}' AND research_resid='{$researchId}' ORDER BY research_unix DESC LIMIT 1");
			$response['debug']['$ResearchNote_' . $researchId] = $ResearchNote;
			if ( count($ResearchNote) == 1 )
			{
				$ResearchNote = $ResearchNote[0];
//				$response['debug']['$ResearchNote'] = $ResearchNote;
				$updateValues = array(
					'research_note' =>  $HTTP[$resvar_note]
				);
				$UpdateNote = updateData(CAOP_DS_RESEARCH, $updateValues, $ResearchNote, "research_id='{$ResearchNote['research_id']}'");
				if ( $UpdateNote['stat'] == RES_SUCCESS )
				{
					$response['result'] = true;
				}
			}
		}
	}

}