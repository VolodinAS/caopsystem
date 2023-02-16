<?php
$DoctorBirths = getarr(CAOP_DOCTOR, 1, 'ORDER BY doctor_f ASC');
foreach ($DoctorBirths as $doctor)
{
	
	if ( strlen($doctor['doctor_birth']) > 0 )
	{
		debug($doctor);
		if ($doctor['doctor_birth_unix'] > 0 || $doctor['doctor_birth_unix'] < 0)
		{
			if ( $doctor['doctor_isBirth'] )
			{
				debug('CORRECT');
			} else
			{
				debug('WRONG');
			}
		} else
		{
			$birth_unix = birthToUnix($doctor['doctor_birth']);
			$birth_date = date("d.m.Y", $birth_unix);
			
			debug($birth_unix);
			debug($birth_date);
			if ($birth_date == $doctor['doctor_birth'])
			{
				$update_values = array(
					'doctor_isBirth' => 1,
					'doctor_birth_unix' => $birth_unix
				);
				$UpdateDoctorBirth = updateData(CAOP_DOCTOR, $update_values, $doctor, "doctor_id='{$doctor['doctor_id']}'");
				if ( $UpdateDoctorBirth['stat'] == RES_SUCCESS )
				{
					debug('UPDATED');
				} else
				{
					debug('ERROR');
				}
			} else
			{
				debug('DIFFERENCE');
			}
		}
	}
}