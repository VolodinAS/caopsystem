<?php
$PAGE_SIZE = 100;
$PAGE = 0;
$PAGE_GET = ( isset($_GET['page']) ) ? $_GET['page'] : '1';
if ( is_numeric($PAGE_GET) )
{
	$PAGE = $PAGE_GET;
} else $PAGE = 1;
$OFFSET = ($PAGE-1) * $PAGE_SIZE;
//debug($PAGE);
//debug($OFFSET);

$isRequest = false;
if ( count($_POST) > 0 )
{
	$isRequest = true;
//	debug($_POST);
	$HTTP = $_POST;
	extract($HTTP, EXTR_PREFIX_SAME, '_caop');
	
	$isZNOChecked = ($isZNO == 1) ? ' checked' : '';
	$isDead_has = ($isDead == 1) ? ' checked' : '';
//	$isVisitedChecked = ($isVisited == 1) ? ' checked' : '';
	$isBirthCurrentChecked = ($birth_current == 1) ? ' checked' : '';
	$isBirthToVisible = ($birth_current == 1) ? ' style="display: none"' : '';
	
	
	
	$Patients_query = "SELECT *[query_visit_field] FROM ".CAOP_PATIENTS."
	[query_zno]
	[query_visit]
	WHERE 1
	[query_db_id]
	[query_amb_card]
	[query_fio]
	[query_birth]
	[query_address]
	[query_phone]
	[query_insurance_number]
	[query_dead]
	[query_visit_group]
	ORDER BY patid_name ASC";
//	debug($Patients_query);
	
	// КРИТЕРИЙ ПО ИД
	$query_db_id = '';
	if ( strlen($db_id) > 0 )
	{
		$query_db_id = " AND patid_id='{$db_id}'";
	}
	if ( strlen($query_db_id) > 0 ) $Patients_query = str_replace('[query_db_id]', $query_db_id, $Patients_query);
	
	// КРИТЕРИЙ ПО НОМЕРУ
	$query_amb_card = '';
	if ( strlen($amb_card) > 0 )
	{
		$query_amb_card = " AND patid_ident LIKE '{$amb_card}'";
	}
	if ( strlen($query_amb_card) > 0 ) $Patients_query = str_replace('[query_amb_card]', $query_amb_card, $Patients_query);
	
	// КРИТЕРИЙ ПО ФИО
	$query_fio = '';
	if ( strlen($fio) > 0 )
	{
		$fio_query = name_for_query($fio);
		$query_fio = " AND patid_name LIKE '{$fio_query['querySearchPercent']}'";
	}
	if ( strlen($query_fio) > 0 ) $Patients_query = str_replace('[query_fio]', $query_fio, $Patients_query);
	
	// КРИТЕРИЙ ПО ДАТЕ РОЖДЕНИЯ
	$query_birth = '';
	if ( $birth_current == 1 )
	{
		if ( strlen($birth_form) > 0 )
		{
		    $birth_from_unix = birthToUnix($birth_form);
		    $query_birth = " AND patid_birth_unix='{$birth_from_unix}'";
		}
	} else
	{
		if ( strlen($birth_form) > 0 )
		{
			$birth_from_unix = birthToUnix($birth_form);
			$query_birth = " AND patid_birth_unix>='{$birth_from_unix}'";
		}
		if ( strlen($birth_to) > 0 )
		{
			$birth_to_unix = birthToUnix($birth_to);
			$query_birth .= " AND patid_birth_unix<='{$birth_to_unix}'";
		}
	}
	if ( strlen($query_birth) > 0 ) $Patients_query = str_replace('[query_birth]', $query_birth, $Patients_query);
	
	// КРИТЕРИЙ ПО АДРЕСУ
	$query_address = '';
	if ( strlen($address) > 0 )
	{
		$address = mb_strtolower($address, UTF8);
		$query_address = " AND patid_address LIKE '{$address}'";
	}
	if ( strlen($query_address) > 0 ) $Patients_query = str_replace('[query_address]', $query_address, $Patients_query);
	
	// КРИТЕРИЙ ПО ТЕЛЕФОНУ
	$query_phone = '';
	if ( strlen($phone) > 0 )
	{
		$query_phone = " AND patid_phone LIKE '{$phone}'";
	}
	if ( strlen($query_phone) > 0 ) $Patients_query = str_replace('[query_phone]', $query_phone, $Patients_query);
	
	// КРИТЕРИЙ ПО СТРАХОВОМУ НОМЕРУ
	$query_insurance_number = '';
	if ( strlen($insurance_number) > 0 )
	{
		$query_insurance_number = " AND patid_insurance_number LIKE '{$insurance_number}'";
	}
	if ( strlen($query_insurance_number) > 0 ) $Patients_query = str_replace('[query_insurance_number]', $query_insurance_number, $Patients_query);
	
	// КРИТЕРИЙ ПО НАЛИЧИЮ ЗНО
	$query_zno = '';
	if ( $isZNO == 1 )
	{
		$query_zno = " INNER JOIN ".CAOP_CANCER." ON ".CAOP_CANCER.".cancer_patid=".CAOP_PATIENTS.".patid_id";
	}
	if ( strlen($query_zno) > 0 ) $Patients_query = str_replace('[query_zno]', $query_zno, $Patients_query);
	
	// КРИТЕРИЙ ПО УМЕРШИМ
	$query_dead = '';
	if ( $isDead == 1 )
	{
		$query_dead = " AND patid_isDead='1'";
	}
	if ( strlen($query_dead) > 0 ) $Patients_query = str_replace('[query_dead]', $query_dead, $Patients_query);
	
	// КРИТЕРИЙ ПО НАЛИЧИЮ ВИЗИТОВ
	$query_visits = '';
	if ( $isVisited == 1 )
	{
	
	}
	
	
	
	
	$isVisited_any = ' checked';
	switch ($isVisited)
	{
		case 1:
			$isVisited_has = $isVisited_none = '';
			$isVisited_any = ' checked';

			$query_visits = " LEFT JOIN ".CAOP_JOURNAL." ON ".CAOP_JOURNAL.".journal_patid=".CAOP_PATIENTS.".patid_id";
			$query_visits_field = ", COUNT(journal_patid) AS visits";
			$query_visits_group = " GROUP BY journal_patid";
        break;
		case 2:
			$isVisited_any = $isVisited_none = '';
			$isVisited_has = ' checked';
			
			$query_visits = " INNER JOIN ".CAOP_JOURNAL." ON ".CAOP_JOURNAL.".journal_patid=".CAOP_PATIENTS.".patid_id";
			$query_visits_field = ", COUNT(journal_patid) AS visits";
			$query_visits_group = " GROUP BY journal_patid";
        break;
//		case 3:
//			$isVisited_any = $isVisited_has = '';
//			$isVisited_none = ' checked';
//
//			$query_visits = " INNER JOIN ".CAOP_JOURNAL." ON ".CAOP_JOURNAL.".journal_patid=".CAOP_PATIENTS.".patid_id";
//			$query_visits_field = ", COUNT(journal_patid) AS visits";
//			$query_visits_group = " GROUP BY journal_patid HAVING COUNT(journal_patid)=0";
//        break;
	}
	
	if ( strlen($query_visits) > 0 && strlen($query_visits_field) > 0 && strlen($query_visits_group) > 0 )
	{
		$Patients_query = str_replace('[query_visit]', $query_visits, $Patients_query);
		$Patients_query = str_replace('[query_visit_field]', $query_visits_field, $Patients_query);
		$Patients_query = str_replace('[query_visit_group]', $query_visits_group, $Patients_query);
	}
	
//	$query_visits = " INNER JOIN ".CAOP_JOURNAL." ON ".CAOP_JOURNAL.".journal_patid=".CAOP_PATIENTS.".patid_id";
//	$query_visits_field = ", COUNT(journal_patid) AS visits";
//	$query_visits_group = " GROUP BY journal_patid";
//	$Patients_query = str_replace('[query_visit]', $query_visits, $Patients_query);
//	$Patients_query = str_replace('[query_visit_field]', $query_visits_field, $Patients_query);
//	$Patients_query = str_replace('[query_visit_group]', $query_visits_group, $Patients_query);
	
	
	$Patients_query = str_replace('[query_db_id]', '', $Patients_query);
	$Patients_query = str_replace('[query_amb_card]', '', $Patients_query);
	$Patients_query = str_replace('[query_fio]', '', $Patients_query);
	$Patients_query = str_replace('[query_birth]', '', $Patients_query);
	$Patients_query = str_replace('[query_address]', '', $Patients_query);
	$Patients_query = str_replace('[query_phone]', '', $Patients_query);
	$Patients_query = str_replace('[query_insurance_number]', '', $Patients_query);
	$Patients_query = str_replace('[query_zno]', '', $Patients_query);
	$Patients_query = str_replace('[query_dead]', '', $Patients_query);
	$Patients_query = str_replace('[query_visit]', '', $Patients_query);
	$Patients_query = str_replace('[query_visit_field]', '', $Patients_query);
	$Patients_query = str_replace('[query_visit_group]', '', $Patients_query);
	$Patients_query = nodoublespaces($Patients_query);
	
//	debug($Patients_query);
	
	$Patient_data = [];
	$Patients_result = mqc_soft($Patients_query);
	if ($Patients_result ['result'] === true)
	{
		$Patients_result = $Patients_result['data'];
		$Patient_rows = mnr($Patients_result);
		$TOTAL_PAGES = ceil($Patient_rows / $PAGE_SIZE);
	} else
	{
		debug($Patients_result);
	}
	
	$Patients_query_count = $Patients_query . " LIMIT {$OFFSET}, {$PAGE_SIZE}";
	$Patients_result_count = mqc_soft($Patients_query_count);
	if ($Patients_result_count ['result'] === true)
    {
	    $Patients_result_count = $Patients_result_count['data'];
	    $Patients_rows_count = mnr($Patients_result_count);
    } else
	{
		debug($Patients_result_count);
	}
	
}

spoiler_begin('Фильтры поиска', 'filter');
{
	$class_form_control = 'form-control form-control-sm';
//	bt_notice('Для относительного поиска используйте знак <b>процента</b>. Напишите в поле ' . wrapper('%етров%') . '. Под шаблон подойдут как "Петров", так и "Ветров"', BT_THEME_INFO);
    require_once ("engine/html/include/patientSearch/filterForm.php");
}
spoiler_end();

if ( $Patients_rows_count > 0 )
{
    $FIRST_PAGE = 1;
    $LAST_PAGE = $TOTAL_PAGES;
	$PREV_PAGE = $PAGE - 1;
	$NEXT_PAGE = $PAGE + 1;
    $prevPageDisabled = '';
    $nextPageDisabled = '';
    $SHOW_MIN = 0;
    $SHOW_MAX = 0;
    if ( $PREV_PAGE < 1 ) $prevPageDisabled = ' disabled';
    if ( $NEXT_PAGE > $TOTAL_PAGES ) $nextPageDisabled = ' disabled';
    
    $SHOW_MIN = ($PAGE * $PAGE_SIZE) - $PAGE_SIZE + 1;
    $SHOW_MAX = $SHOW_MIN + ($Patients_rows_count) - 1;
    
    bt_notice('Найдено совпадений: ' . wrapper($Patient_rows) . '<br><b>Показано:</b> ' . $SHOW_MIN . ' - ' . $SHOW_MAX);
	
	
	
	if ( $PAGE > $TOTAL_PAGES )
    {
//        debug('here1');
        ?>
        <script defer type="text/javascript">
            paginator('#patientSearchFilter', <?=$TOTAL_PAGES;?>)
        </script>
        <?php
    } else{
//		debug('here2');
		include "engine/html/include/patientSearch/pagination.php";
		
		$AllPatients = $Patients_result_count;
		include ( "engine/php/patient_search.php" );
		
		include "engine/html/include/patientSearch/pagination.php";
	 
	}
	
	
 
} else
{
	if ( $isRequest )
	{
		bt_notice('По выбранным критериям результатов не найдено. Попробуйте расширить поиск...', BT_THEME_WARNING);
	}
}

require_once("engine/html/modals/visitsPatientData.php");

?>



<script defer type="text/javascript" src="/engine/js/patientSearch.js?<?=rand(0, 9999); ?>"></script>
