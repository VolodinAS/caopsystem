<?php
// GROUP BY visreg_dspatid
//,MAX(visreg_id)
$query_Note = "SELECT * , MAX(visreg_id) as visreg_id FROM {$CAOP_DS_VISITS} WHERE visreg_note!='' GROUP BY visreg_dspatid ORDER BY visreg_dspatid DESC";
$result_Note = mqc($query_Note);
$DSVNoteData = mr2a($result_Note);
//                $DSVNoteData = getarr(CAOP_DS_VISITS, "visreg_note!=''", "GROUP BY visreg_dspatid ORDER BY visreg_dspatid DESC");
//				debug('$DSVNoteData = ' . $query_Note);
//                debug( 'count($DSVNoteData): ' . count($DSVNoteData) );
foreach ($DSVNoteData as $DSVNoteDatum)
{
	if ( strlen($DSVNoteDatum['visreg_note']) > 0 )
	{
		debug($DSVNoteDatum);
		$paramValues_patientNote = array(
			'patient_note' => $DSVNoteDatum['visreg_note']
		);
		$UpdateDSPatient = updateData(CAOP_DS_PATIENTS, $paramValues_patientNote, $DSVNoteDatum, "patient_id='{$DSVNoteDatum['visreg_dspatid']}'");
		if ( $UpdateDSPatient['stat'] == RES_SUCCESS )
		{
			bt_notice($UpdateDSPatient, BT_THEME_SUCCESS);
		} else
		{
			bt_notice(wrapper('ERROR: ') . $UpdateDSPatient,BT_THEME_DANGER);
		}
	}
}