<?php
$AllPatients = getarr(CAOP_PATIENTS, "1", "ORDER BY patid_name ASC", false, 'patid_id, patid_ident, patid_name, patid_birth');
foreach ($AllPatients as $Patient) {
	$pat_name_data = explode(' ', $Patient['patid_name']);
	if (count($pat_name_data) < 3)
	{
		debug($Patient);
		debug($pat_name_data);
		$name_data = [];
		foreach ($pat_name_data as $name_part)
		{
			if ( strlen($name_part) > 0 )
			{
				$name_part = nospaces($name_part);
				$name_data[] = $name_part;
			}
			
		}
		$name_string = implode(' ', $name_data);
		$formatted_name_data = explode(' ', $name_string);
		debug('|'.$name_string.'|');
		debug($formatted_name_data);
		$param_update_name = array(
			'patid_name' => $name_string
		);
		$Patient = updateData(CAOP_PATIENTS, $param_update_name, $Patient, "patid_id='{$Patient['patid_id']}'");
		if ($Patient['stat'] == RES_SUCCESS)
		{
		
		} else
		{
			bt_notice('ПРОБЛЕМА С ОБНОВЛЕНИЕМ ИМЕНИ', BT_THEME_WARNING);
		}
		echo editPersonalDataLink($Patient['patid_name'], $Patient['patid_id']);
		echo '<div class="dropdown-divider"></div>';
	}
}