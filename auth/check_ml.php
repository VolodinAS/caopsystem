<?php
$table_ml = 16;
$table_field_id = 140; // дата обращения в ЦАОП
$table_data = table_to_array($table_ml, false);

$table_data = $table_data['data'];

//debug($table_data);
$npp = 1;

foreach ($table_data as $record_id => $patient_record)
{
	$go_next = false;
	$RS = null;
	
//	debug($patient_record);
	
	$insurance_number = $patient_record[0];
	$patid_f = $patient_record[1];
	$patid_i = $patient_record[2];
	$patid_o = $patient_record[3];
	$patid_birth = $patient_record[4];
	$patid_ds = $patient_record[5];
	
	if ( strlen($insurance_number) > 5 )
	{
		$patid_name_full = $patid_f . ' ' . $patid_i . ' ' . $patid_o;
		$patid_name_full = mb_strtolower($patid_name_full, UTF8);
		$patid_name_full = mb_ucwords($patid_name_full);
		
		$name_data = name_for_query($patid_name_full);
		
		$birth_unix = birthToUnix($patid_birth);
		
		
		$search_by = 'ПО ИМЕНИ';
		$PatientSearch = getarr(CAOP_PATIENTS, "patid_name LIKE '{$name_data['querySearchPercentSpaces']}' AND ( patid_birth='{$patid_birth}'  )");
		if ( count($PatientSearch) == 1 )
		{
			$go_next = true;
		} else
		{
			$PatientSearch = getarr(CAOP_PATIENTS, "patid_insurance_number='{$insurance_number}'");
			if ( count($PatientSearch) == 1 )
			{
				$go_next = true;
				$search_by = 'ПО ПОЛИСУ';
			}
		}
		
		if ( $go_next )
		{
			$go_next = false;
			$PatientSearch = $PatientSearch[0];
			$RS = getarr(CAOP_ROUTE_SHEET, "rs_patid='{$PatientSearch['patid_id']}'");
			
			if ( count($RS) > 0 )
			{
			    $RS = $RS[0];
			    $go_next = true;
			}
		}
		
		if ( $go_next )
		{
			$go_next = false;
			
			spoiler_begin(wrapper($npp . '.') . ' ['.$insurance_number.'] '.$patid_name_full.', '.$patid_birth, 'pat_'. $insurance_number, '');
			echo wrapper('record_id: ') . $record_id;
			bt_divider();
			echo wrapper('Диагноз: ') . $patid_ds;
			bt_notice('НАЙДЕН ' . $search_by, BT_THEME_SUCCESS);
			
			bt_notice('НАЙДЕНЫ МЛ', BT_THEME_PRIMARY);
//			debug($RS);
			$patid_ds_data = explode(' ', $patid_ds);
			$patid_ds_data = explode('.', $patid_ds_data[0]);
			$rs_ds_mkb = $RS['rs_ds_mkb'];
			$rs_ds_mkb_data = explode('.', $rs_ds_mkb);
			$set_data = false;
			if ($patid_ds_data[0] == $rs_ds_mkb_data[0])
			{
				bt_notice('Табличный диагноз ' . wrapper($patid_ds_data[0]) . ' совпадает с диагнозом МЛ ' . wrapper($rs_ds_mkb_data[0]), BT_THEME_SUCCESS);
				$set_data = true;
			} else
			{
				bt_notice('Разные диагнозы таблицы ' . wrapper($patid_ds_data[0]) . ' и МЛ ' . wrapper($rs_ds_mkb_data[0]), BT_THEME_DANGER);
				if ($rs_ds_mkb_data[0] == "C80")
				{
					bt_notice('Метастазы были уточнены: '.wrapper($rs_ds_mkb_data[0]).' > ' . wrapper($patid_ds_data[0]));
					$set_data = true;
				}
			}
			
			if ($set_data)
			{
				bt_notice('Дата обращения будет установлена!');
				if ( strlen($RS['rs_stage_caop_date']) > 0 )
				{
					bt_notice('Устанавливаю дату...');
					$table_cell_update = array(
						'cell_content' => '|'.$RS['rs_stage_caop_date'].'|'
					);
					debug($table_cell_update);
					$UpdateCells = updateData(CAOP_TABLE_CELLS, $table_cell_update, [], "cell_table_id='$table_ml' AND cell_record_id='$record_id' AND cell_field_id='$table_field_id'");
					if ( $UpdateCells['stat'] == RES_SUCCESS )
					{
						bt_notice('ДАТА УСПЕШНО ОБНОВЛЕНА', BT_THEME_SUCCESS);
					} else
					{
						bt_notice('ПРОБЛЕМА ОБНОВЛЕНИЯ ДАТЫ', BT_THEME_DANGER);
					}
					
				} else
				{
					bt_notice('Нет даты для установки!');
				}
			}
			
			spoiler_end();
			$npp++;
		}
		
	}
	
//	if ($npp == 11) break;
}



