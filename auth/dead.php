<?php

$REMOVE = false;
if ( strlen($request_params) > 0 )
{
    if($request_params == "remove") $REMOVE = true;
}



$table_dead = 14;
$table_data = table_to_array($table_dead, false);

$table_data = $table_data['data'];

//debug($table_data);

//$table_data = array();
foreach ($table_data as $record_id => $patient_record)
{
	$card = $patient_record[0];
	$insurance = $patient_record[1];
	$fam = $patient_record[2];
	$ima = $patient_record[3];
	$otc = $patient_record[4];
	$birth = $patient_record[5];
	
	$name = mb_strtolower($fam . ' ' . $ima . ' ' . $otc);
	
	
	spoiler_begin('[' . $card . '] ' . mb_ucwords($name) . ', ' . $birth . ' г.р., ЕНП: ' . $insurance, 'pat_' . $insurance, '');
	{
		?>
        <ul>
			<?php
			$go_next = false;
			$many_seq = '';
			$status = 'поиск...';
			$addon_seq = '';
			$Patient = array();
			$Patient_IDENT = getarr(CAOP_PATIENTS, "patid_ident='{$card}'");
			if (count($Patient_IDENT) == 1)
			{
				$Patient = $Patient_IDENT[0];
				$status = 'есть совпадение по карте [' . $Patient[$PK[CAOP_PATIENTS]] . ']';
				if (mb_strtolower(trim($Patient['patid_name'])) == mb_strtolower(trim($name)))
					$addon_seq .= '<li>имена совпадают полностью</li>';
				else
					$addon_seq .= '<li><b><i>ИМЕНА НЕ СОВПАДАЮТ</i></b></li>';
				
				if (trim($birth) == trim($Patient['patid_birth']))
					$addon_seq .= '<li>ДР совпадают полностью</li>';
				else
					$addon_seq .= '<li><b><i>ДР НЕ СОВПАДАЮТ</i></b></li>';
				
				if (trim($insurance) == trim($Patient['patid_insurance_number']))
					$addon_seq .= '<li>ЕНП совпадают полностью</li>';
				else
					$addon_seq .= '<li><b><i>ЕНП НЕ СОВПАДАЮТ</i></b></li>';
			} else
			{
				if (count($Patient_IDENT) == 0)
				{
					$status = 'совпадений по карте нет!';
					$go_next = true;
				} else
				{
					$status = 'найдено несколько совпадений по карте (' . count($Patient_IDENT) . ')! Сверяем по имени...';
					$many_seq = debug_ret($Patient_IDENT);
					$go_next = true;
				}
			}
			?>
            <li><b>Выполняется поиск по номеру карты:</b> {<?= $card; ?>} -> <?= $status; ?><?= $many_seq; ?></li>
			<?= $addon_seq; ?>
			<?php
			if ($go_next)
			{
				$go_next = false;
				$many_seq = '';
				$status = 'поиск...';
				$addon_seq = '';
				$name_query = name_for_query($name);
				$Patient = array();
//				debug($name_query);
				$Patient_NAME = getarr(CAOP_PATIENTS, "patid_name LIKE '{$name_query['querySearchPercentOneSpacesDefault']}'");
				if (count($Patient_NAME) == 1)
				{
					$Patient = $Patient_NAME[0];
					$status = 'есть совпадение по имени [' . $Patient[$PK[CAOP_PATIENTS]] . ']';
				} else
				{
					if (count($Patient_NAME) == 0)
					{
						$status = 'совпадений по имени нет!';
						$go_next = true;
					} else
					{
						$status = 'найдено несколько совпадений по имени (' . count($Patient_NAME) . ')! Сверяем по ЕНП...';
						$many_seq = debug_ret($Patient_NAME);
						$go_next = true;
					}
				}
				?>
                <li><b>Выполняется поиск по Ф.И.О.:</b> {<?= $name; ?>} -> <?= $status; ?><?= $many_seq; ?></li>
				<?php
			}
			?>
			<?php
			if ($go_next)
			{
				$go_next = false;
				$many_seq = '';
				$status = 'поиск...';
				$addon_seq = '';
				$Patient = array();
				$Patient_INSURANCE = getarr(CAOP_PATIENTS, "patid_insurance_number='{$insurance}'");
				if (count($Patient_INSURANCE) == 1)
				{
					$Patient = $Patient_INSURANCE[0];
					$status = 'есть совпадение по ЕНП [' . $Patient[$PK[CAOP_PATIENTS]] . ']';
				} else
				{
					if (count($Patient_INSURANCE) == 0)
					{
						$status = 'совпадений по ЕНП нет!';
					} else
					{
						$status = 'найдено несколько совпадений по ЕНП (' . count($Patient_INSURANCE) . ')!';
						$many_seq = debug_ret($Patient_INSURANCE);
					}
				}
				?>
                <li><b>Выполняется поиск по ЕНП:</b> {<?= $insurance; ?>} -> <?= $status; ?><?= $many_seq; ?></li>
				<?php
			}
			
			if (count($Patient) > 0)
			{
				?>
                <li><b>ПАЦИЕНТ
                       НАЙДЕН:</b> <?= editPersonalDataLink(mb_ucwords($Patient['patid_name']) . ', ' . $birth . ' г.р.', $Patient[$PK[CAOP_PATIENTS]]); ?>
                </li>
				<?php
				
				$param_exitus = array(
					'patid_isDead' => true
				);
				
                if ( $REMOVE )
                {
	                $DeadPatient = updateData(CAOP_PATIENTS, $param_exitus, $Patient, $PK[CAOP_PATIENTS] . "='{$Patient[$PK[CAOP_PATIENTS]]}'");
	                if ( $DeadPatient['stat'] == RES_SUCCESS )
	                {
		                ?>
                        <li>ПАЦИЕНТ УМЕР...</li>
		                <?php
		
		                $row_delete = table_delete_row($table_dead, $record_id);
		                if ( $row_delete )
		                {
			                ?>
                            <li>Пациент удалён из таблицы</li>
			                <?php
		                }
		
	                } else
	                {
		                ?>
                        <li><b>ПАЦИЕНТ НЕ УМЕРЩВЛЕН!</b></li>
		                <?php
	                }
                }
				
			} else
			{
				?>
                <li>Пациент в базе данных отсутствует</li>
				<?php
			}
			
			?>

        </ul>
		<?php
	}
	spoiler_end();
}

//debug($table_data);