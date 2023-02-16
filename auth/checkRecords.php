<?php
//bt_notice('РАЗДЕЛ В РАЗРАБОТКЕ', BT_THEME_WARNING);

?>
<form action="/checkRecords"
      method="post">
	<textarea class="form-control form-control-sm"
              name="checkRegField"
              id="checkRegField"
              cols="30"
              rows="3"
              placeholder="Вставьте сюда данные таблицы приёма">
        <?= $_POST['checkRegField']; ?>
    </textarea>
    <button type="submit"
            class="btn btn-sm btn-primary"
            id="checkRegButton">Проверка карт
    </button>
</form>

<?php
//debug($_POST);

if (count($_POST) > 0)
{
	echo '<hr>';
	
	$reuired_fields = array(
		'Дата',
		'Время',
		'Врач',
		'Пациент',
		'Номер карты',
		'Дата рождения'
	);
	
//	debug($_POST);
	
	$mias_data = trim($_POST['checkRegField']);
	$mias_data = explode("\n", $mias_data);
	
	$PATIENTS = [];
	
	$right_strings_arr = [];
	
	$header_string = '';
	for ($header_index = 0; $header_index < count($mias_data); $header_index++)
	{
		$mias_item_string = $mias_data[$header_index];
		if (ifound($mias_item_string, 'Номер карты'))
		{
			$header_string = $mias_item_string;
			break;
		}
	}
	
	$header_string_arr = explode("\t", $header_string);
	
// 	debug($header_string_arr);
	
	$required_fields_new = [];
	$HeaderIndex = 0;
//	debug(wrapper('< foreach ($reuired_fields as $required_field) >'));
	
	for ($header_index=0; $header_index<count($header_string_arr); $header_index++)
	{
	    $header_item = trim($header_string_arr[$header_index]);
		foreach ($reuired_fields as $required_field)
        {
	        if (mb_strtolower($required_field, UTF8) == mb_strtolower($header_item, UTF8))
	        {
		        $required_fields_new[$required_field] = $HeaderIndex;
		        break;
	        }
        }
		$HeaderIndex++;
	}
	
// 	debug($required_fields_new);
	

//	Всего:
	$isHeader = false;
	for ($i = 0; $i < count($mias_data); $i++)
	{
		$mias_string = $mias_data[$i];
		$mias_string = trim($mias_string);

//	    debug($mias_string);
		
		if (ifound($mias_string, 'Номер карты'))
		{
			$isHeader = true;
			continue;
		}
		
		if ($isHeader)
		{
			// DATA
			if (ifound($mias_string, "Всего:"))
			{
				break;
			} else
			{
				$PATIENTS[] = $mias_string;
			}
		}
	}

// 	debug($required_fields_new);
// 	debug($PATIENTS);

//	for ($i=0; $i<count($mias_data); $i++)
//	{
//	    $mias_string = $mias_data[$i];
//		$mias_string = trim($mias_string);
//
////		debug('$mias_string: ' . $mias_string);
//
//	    if ( ifound($mias_string, "записей пациентов") )
//	    {
//	        // следующая строка - столбцы
//            $isHeader = true;
//            continue;
//	    }
//
////		debug('$isHeader: ' . $isHeader);
//
//	    if ( $isHeader )
//        {
//            if ( ifound($mias_string, "Врач-онколог") )
//            {
//                $isHeader = false;
//            } else
//            {
//	            foreach ($reuired_fields as $required_field)
//	            {
//		            if ( mb_strtolower($required_field, UTF8) ==  mb_strtolower($mias_string, UTF8) )
//		            {
//			            $required_fields_new[$required_field] = $HeaderIndex;
//			            break;
//		            }
//	            }
//	            $HeaderIndex++;
//            }
//
//        }
//
//		if ( ifound($mias_string, "Врач-онколог") )
//		{
//			$PATIENTS[] = $mias_string;
//		}
//	}


//	debug($required_fields_new);
//	debug($PATIENTS);
	
	$TOTAL_PATIENTS_DOCTOR = [];
	
	$PatientData_NoVisits = [];
	$PatientData_Visits = [];
	$PatientData_Repeats = [];
	foreach ($PATIENTS as $patient_string)
	{
		$patient_string = trim($patient_string);
		$patient_data = explode("\t", $patient_string);
		$PATIENT_NAME = $patient_data[$required_fields_new['Пациент']];
		$PATIENT_BIRTH = $patient_data[$required_fields_new['Дата рождения']];
		$PATIENT_CARD = $patient_data[$required_fields_new['Номер карты']];
		$PATIENT_DOCTOR = $patient_data[$required_fields_new['Врач']];
		$PATIENT_NAME_DATA = name_for_query($PATIENT_NAME);
//		debug('['.$PATIENT_CARD.'] ' . $PATIENT_NAME . ', ' . $PATIENT_BIRTH);
//		debug($PATIENT_NAME_DATA);
		
		$PatientRepeat = [];
		$PatientData = array(
			'name' => $PATIENT_NAME,
			'birth' => $PATIENT_BIRTH,
			'card' => $PATIENT_CARD
		);
		
		$PatientByName = getarr(CAOP_PATIENTS, "patid_name LIKE '{$PATIENT_NAME_DATA['querySearchPercent']}' AND patid_birth LIKE '{$PATIENT_BIRTH}'");
		if (count($PatientByName) > 0)
		{
			if (count($PatientByName) == 1)
			{
				$PatientByName = $PatientByName[0];
				$PatientData_Visits[] = $PatientByName;
				$TOTAL_PATIENTS_DOCTOR[$PATIENT_DOCTOR]['visits'][] = $PatientByName;
			} else
			{
				$PatientRepeat['patient'] = $PatientData;
				$PatientRepeat['repeats'] = $PatientByName;
				$PatientData_Repeats[] = $PatientRepeat;
				$TOTAL_PATIENTS_DOCTOR[$PATIENT_DOCTOR]['repeats'][] = $PatientRepeat;
			}
		} else
		{
			$PatientData_NoVisits[] = $PatientData;
			$TOTAL_PATIENTS_DOCTOR[$PATIENT_DOCTOR]['novisit'][] = $PatientData;
		}
	}
	
	unset($PatientData_NoVisits);
	unset($PatientData_Visits);
	unset($PatientData_Repeats);

// 	debug($PatientData_NoVisits);
// 	debug($PatientData_Visits);
// 	debug($PatientData_Repeats);
	/*
	if ( count($PatientData_Repeats) > 0 )
	{
	    bt_notice(wrapper('ОБРАТИТЕ ВНИМАНИЕ! У ДАННЫХ ПАЦИЕНТОВ В СИСТЕМЕ ЕСТЬ ПОВТОРЫ!'), "danger");
		$PatientData_Repeats = array_orderby($PatientData_Repeats, 'name', SORT_ASC);
		foreach ($PatientData_Repeats as $repeat)
		{
		    $Patient = $repeat['patient'];
		    $Repeats = $repeat['repeats'];
            spoiler_begin("Пациент: {$Patient['name']}, {$Patient['birth']} г.р. Карта: {$Patient['card']}", 'repeat_' . rand(0, 999999) );
            {
	            foreach ($Repeats as $patient_repeat)
	            {
                    debug($patient_repeat);
                }
            }
            spoiler_end();
	    }
	}
	
	if ( count($PatientData_NoVisits) > 0 )
	{
	    echo "<br>";
		echo "<hr/>";
		bt_notice(wrapper('ПАЦИЕНТЫ, КОТОРЫЕ БУДУТ ПОСЕЩАТЬ ЦАОП ВПЕРВЫЕ (но это не точно)'), "warning");
		$PatientData_NoVisits = array_orderby($PatientData_NoVisits, 'name', SORT_ASC);
		foreach ($PatientData_NoVisits as $noVisit)
		{
            bt_notice(wrapper($noVisit['name']) . ', ' . $noVisit['birth'] . ' [КАРТА: '.$noVisit['card'].']');
		}
	} else
    {
	    bt_notice(wrapper('В записи нет первичных пациентов'), BT_THEME_PRIMARY);
    }
	
	if ( count($PatientData_Visits) > 0 )
	{
		echo "<br>";
		echo "<hr/>";
		$AllPatients = $PatientData_Visits;
		unset($PatientData_Visits);
		$AllPatients = array_orderby($AllPatients, 'patid_name', SORT_ASC);
		bt_notice(wrapper('ПАЦИЕНТЫ, У КОТОРЫХ УЖЕ ЕСТЬ ВИЗИТЫ В ЦАОП'), BT_THEME_SUCCESS);
		include ("engine/php/patient_search.php");
	}
	*/
//	debug($TOTAL_PATIENTS_DOCTOR);
//	debug(count($TOTAL_PATIENTS_DOCTOR));
	
	$isFirst = true;
	?>
    <ul class="nav nav-tabs">
		<?php
		foreach ($TOTAL_PATIENTS_DOCTOR as $DOCTOR_NAME => $VISITS_DATA)
		{
			$active = "";
			if ($isFirst)
			{
				$isFirst = false;
				$active = " active";
			}
			?>
            <li class="nav-item">
                <a class="nav-link<?= $active; ?>"
                   data-toggle="tab"
                   href="#patients_<?= md5($DOCTOR_NAME); ?>"><?= $DOCTOR_NAME; ?></a>
            </li>
			<?php
		}
		?>
    </ul>
    <div class="tab-content">
		<?php
		$isFirst = true;
		foreach ($TOTAL_PATIENTS_DOCTOR as $DOCTOR_NAME => $VISITS_DATA)
		{
			$active = "";
			if ($isFirst)
			{
				$isFirst = false;
				$active = " show active";
			}
			?>
            <div class="tab-pane fade<?= $active; ?>"
                 id="patients_<?= md5($DOCTOR_NAME); ?>">
				
				<?php
				$PatientData_Repeats = $VISITS_DATA['repeats'];
				$PatientData_NoVisits = $VISITS_DATA['novisit'];
				$PatientData_Visits = $VISITS_DATA['visits'];
				
				if (count($PatientData_Repeats) > 0)
				{
					bt_notice(wrapper('ОБРАТИТЕ ВНИМАНИЕ! У ДАННЫХ ПАЦИЕНТОВ В СИСТЕМЕ ЕСТЬ ПОВТОРЫ!'), "danger");
					$PatientData_Repeats = array_orderby($PatientData_Repeats, 'name', SORT_ASC);
					foreach ($PatientData_Repeats as $repeat)
					{
						$Patient = $repeat['patient'];
						$Repeats = $repeat['repeats'];
						spoiler_begin("Пациент: {$Patient['name']}, {$Patient['birth']} г.р. Карта: {$Patient['card']}", 'repeat_' . rand(0, 999999));
						{
							foreach ($Repeats as $patient_repeat)
							{
								debug($patient_repeat);
							}
						}
						spoiler_end();
					}
				}
				
				if (count($PatientData_NoVisits) > 0)
				{
					echo "<br>";
					echo "<hr/>";
					bt_notice(wrapper('ПАЦИЕНТЫ, КОТОРЫЕ БУДУТ ПОСЕЩАТЬ ЦАОП ВПЕРВЫЕ (но это не точно)'), "warning");
					$PatientData_NoVisits = array_orderby($PatientData_NoVisits, 'name', SORT_ASC);
					foreach ($PatientData_NoVisits as $noVisit)
					{
						bt_notice(wrapper($noVisit['name']) . ', ' . $noVisit['birth'] . ' [КАРТА: ' . $noVisit['card'] . ']');
					}
				} else
				{
					bt_notice(wrapper('В записи нет первичных пациентов'), BT_THEME_PRIMARY);
				}
				
				if (count($PatientData_Visits) > 0)
				{
					echo "<br>";
					echo "<hr/>";
					$AllPatients = $PatientData_Visits;
					unset($PatientData_Visits);
					$AllPatients = array_orderby($AllPatients, 'patid_name', SORT_ASC);
					bt_notice(wrapper('ПАЦИЕНТЫ, У КОТОРЫХ УЖЕ ЕСТЬ ВИЗИТЫ В ЦАОП'), "success");
					include("engine/php/patient_search.php");
				}
				?>

            </div>
			<?php
		}
		?>
    </div>
	
	<?php
//	debug($mias_data);


// 0 - дата приёма
// 1 - время приёма
// 2 - ФИО врача
// 3 - ФИО пациента
// 4 - номер карты
// 5 - дата рождения

//debug($arr3);
	/*$AllPatients = array();
	$FirstPatients = array();
	foreach ($arr3 as $patData) {
//	debug($patData);
		$pat_name = name_for_query( trim($patData[3]) );
		$pat_name2 = $patData[3];
		$pat_name2 = str_replace(' ', '% %', $pat_name2);

		$PatientSearch = getarr(CAOP_PATIENTS, "patid_name LIKE '%{$pat_name2}%' AND patid_birth='{$patData[5]}'");
//	debug($PatientSearch);

		if ( count($PatientSearch) > 0 )
		{
//		debug($PatientSearch);
			$Patient = $PatientSearch[0];
//		debug($Patient);
//		$response['debug']['patients'][] = $PatientSearch;
//		include ( "engine/php/patient_search_single.php" )
			$AllPatients[] = $Patient;

		} else
		{
			$FirstPatients[] = $patData;
		}
	}

	if ( count($FirstPatients) > 0 )
	{
		foreach ($FirstPatients as $firstPatient) {
			bt_notice( '<b>Пациент '.trim( $firstPatient[3] ).', '.$firstPatient[5].' г.р. будет посещать онколога впервые</b>' , BT_THEME_WARNING );
		}
	}



	if ( count($AllPatients) > 0 )
	{
		bt_notice('Данные пациенты уже были в ЦАОП и имеют карты:' ,BT_THEME_PRIMARY);
		include ("engine/php/patient_search.php");
	}*/
}


//$response['debug']['$arr0'] = $arr0;
//$response['debug']['$arr1'] = $arr1;
//$response['debug']['$arr2'] = $arr2;
//$response['debug']['$arr3'] = $arr3;
?>



<?php
require_once("engine/html/modals/visitsPatientData.php");
?>
<script defer
        src="/engine/js/checkRecords.js?<?= rand(0, 999999); ?>"
        type="text/javascript"></script>
<script defer
        src="/engine/js/allpatients.js?<?= rand(0, 999999); ?>"
        type="text/javascript"></script>