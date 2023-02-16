<?php
$response['stage'] = $action;

$CID = $_POST['citology_id'];
$CitologyData = getarr(CAOP_CITOLOGY, "citology_id='{$CID}'");
if ( count($CitologyData) == 1 )
{
	$CitologyData = $CitologyData[0];
	$PatientPersonalData = getarr(CAOP_PATIENTS, "patid_id='{$CitologyData['citology_patid']}'");
	if ( count($PatientPersonalData) == 1 )
	{
		$response['result'] = true;
		$PatientPersonalData = $PatientPersonalData[0];

		$response['debug']['$CitologyData'] = $CitologyData;
		$response['debug']['$PatientPersonalData'] = $PatientPersonalData;

		$JSON_markers = stripcslashes($CitologyData['citology_analise_markers']);
		$MarkersData = json_decode($JSON_markers);

//		$response['htmlData'] .= debug($JSON_markers, null, null, 1);
//		$response['htmlData'] .= debug($MarkersData, null, null, 1);

		for ($i=1; $i<=10; $i++)
		{
			$index = $i-1;
			$valueMarker = $MarkersData[$index];
			$response['htmlData'] .= '
			<label class="sr-only" for="mark_'.$i.'">#'.$i.'</label>
			<div class="input-group">
				<div class="input-group-prepend">
				<div class="input-group-text">№'.$i.'</div>
			</div>
			<input type="text" 
					class="mysqleditor form-control" 
					id="mark_'.$i.'" 
					data-action="edit" 
					data-table="'.CAOP_CITOLOGY.'"
					data-assoc="0" 
					data-fieldid="citology_id" 
					data-id="'.$CID.'" 
					data-jsonarray="1"
					data-return="0" 
					data-field="citology_analise_markers" 
					placeholder="маркировка материала №'.$i.' (оставьте пустым, если не требуется)" value="'.htmlspecialchars($valueMarker).'">
			</div>
            ';
		}

	} else
	{
		$response['msg'] = 'Такого пациента нет';
	}


} else
{
	$response['msg'] = 'Такого направления не существует';
}



//$response['htmlData'] = debug($CitologyData, null, null, 1);
//$response['result'] = true;