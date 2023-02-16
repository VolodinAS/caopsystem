<?php
$AllPatients = getarr(CAOP_PATIENTS, "1", "ORDER BY patid_id ASC");
foreach ($AllPatients as $Patient) {
	if ( strlen($Patient['patid_birth']) > 0 )
	{
		$unix = birthToUnix($Patient['patid_birth']);
		if ($unix != $Patient['patid_birth_unix'])
		{
			$newBirth = array(
				'patid_birth_unix'  =>  $unix
			);
			$UpdatePatient = updateData(CAOP_PATIENTS, $newBirth, $Patient, "patid_id='{$Patient['patid_id']}'");
			debug($UpdatePatient);
		}
	}
	
}