<?php
$FILTER = $_SESSION['nosology_report']['filter'];
$MainData = $_SESSION['nosology_report'][$FILTER];

//debug($MainData);

if (count($MainData) > 0)
{
	if ($FILTER == "caop")
	{
		extract($MainData, EXTR_PREFIX_SAME, '_caop');
		
		$queryFrom = '';
		if (strlen($date_from) > 0)
		{
			$caop_date_from = strtotime($date_from);
			$queryFrom = " AND ({$CAOP_JOURNAL}.journal_unix>='{$caop_date_from}') ";
		}
		
		$queryTo = '';
		if (strlen($date_to) > 0)
		{
			$caop_date_to = strtotime($date_to);
			$caop_date_to = $caop_date_to + (TIME_DAY - 1);
			$queryTo = " AND ({$CAOP_JOURNAL}.journal_unix<='{$caop_date_to}') ";
		}
		
		$queryDoctor = '';
		if ((int)$doctor_id > 0)
		{
			$queryDoctor = " AND ({$CAOP_JOURNAL}.journal_doctor='{$doctor_id}') ";
		}
		
		$queryDiagnosis = '';
		if (strlen($diagnosis) > 0)
		{
//			$queryDiagnosis = AccessString2QueryORs($diagnosis, CAOP_JOURNAL . '.journal_ds', ';', '=');
//			$queryDiagnosis = " AND {$queryDiagnosis} ";
			
			$queryDiagnosis = getQueryByPattern($diagnosis, CAOP_JOURNAL . '.journal_ds');
			$queryDiagnosis = " AND ({$queryDiagnosis}) ";
			
		}
		
		$querySearch = "SELECT {$CAOP_JOURNAL}.journal_ds, COUNT(`journal_ds`) AS `count` FROM {$CAOP_JOURNAL} WHERE 1 {$queryFrom}{$queryTo}{$queryDoctor}{$queryDiagnosis} GROUP BY `journal_ds`";
	} elseif ($FILTER == "ds")
	{
		$HTTP = $_SESSION['nosology_report'][$FILTER];
		extract($HTTP, EXTR_PREFIX_SAME, '_caop');
		
		$queryFrom = '';
		if (strlen($date_from) > 0)
		{
			$date_from_unix = strtotime($date_from);
			$date_from_unix_begin = $date_from_unix;
			$queryFrom = " AND ( {$CAOP_DS_VISITS}.visreg_visit_unix>='{$date_from_unix_begin}' ) ";
		}
		
		$queryTo = '';
		if (strlen($date_to) > 0)
		{
			$date_to_unix = strtotime($date_to);
			$date_to_unix_end = $date_to_unix + (TIME_DAY - 1);
			$queryTo = " AND ( {$CAOP_DS_VISITS}.visreg_visit_unix<='{$date_to_unix_end}' ) ";
		}
		
		debug($diagnosis);
		
		$queryDiagnosis = '';
		if (strlen($diagnosis) > 0)
		{
//			$queryDiagnosis = AccessString2QueryORs($diagnosis, CAOP_DS_DIRLIST . '.dirlist_diag_mkb', ';', '=');
//			$queryDiagnosis = " AND {$queryDiagnosis} ";
            $queryDiagnosis = getQueryByPattern($diagnosis, CAOP_DS_DIRLIST . '.dirlist_diag_mkb');
			$queryDiagnosis = " AND ({$queryDiagnosis}) ";
		}
		
		
		$querySearch = "SELECT {$CAOP_DS_DIRLIST}.dirlist_diag_mkb AS `journal_ds`, COUNT(`dirlist_diag_mkb`) AS 'count' FROM {$CAOP_DS_VISITS}
					LEFT JOIN {$CAOP_DS_PATIENTS} ON {$CAOP_DS_VISITS}.visreg_dspatid={$CAOP_DS_PATIENTS}.patient_id
					LEFT JOIN {$CAOP_DS_DIRLIST} ON {$CAOP_DS_VISITS}.visreg_dirlist_id={$CAOP_DS_DIRLIST}.dirlist_id
					WHERE 1 {$queryFrom}{$queryTo}{$queryDiagnosis} GROUP BY `journal_ds`";
	}
}

//debug($queryFrom);
//debug($queryTo);
//debug($queryDoctor);
//debug($queryDiagnosis);
//debug($querySearch);
//exit();

$report_result = mqc($querySearch);
$report_rows = mnr($report_result);
//exit();
$period = 'за весь период';
$isPeriod = false;
if (strlen($date_from) > 0)
{
	$isPeriod = true;
	$period = 'c ' . date("d.m.Y", strtotime($date_from));
}
if (strlen($date_to) > 0)
{
	if ($isPeriod)
	{
		$period .= ' по ' . date("d.m.Y", strtotime($date_to));
	} else
	{
		$period .= 'до ' . date("d.m.Y", strtotime($date_to));
	}
}

if ($report_rows > 0)
{
	$report_data = mr2a($report_result);
	
	$report_data = array_orderby($report_data, 'count', SORT_DESC, 'journal_ds', SORT_ASC);
	$visits = 0;
	$correct = 0;
	$correct_data = [];
	$incorrect_data = [];
	foreach ($report_data as $ds)
	{
		$MKB_DATA = CheckMKBCode($ds['journal_ds']);
		$isCheck = $MKB_DATA['value'];
		if ($isCheck !== false)
		{
			$correct++;
			$correct_data[] = $ds;
			$visits += (int)$ds['count'];
		} else
		{
			$incorrect_data[] = $ds;
		}
	}

//	debug( count($correct_data) );
	
	?>
    <h3><u>Всего нозологий:</u> <?= $report_rows; ?></h3>
    <h3><u>Из них корректных нозологий:</u> <?= $correct; ?></h3>
    <h3><u>Период:</u> <?= $period; ?></h3>
    <h3><u>Всего визитов:</u> <?= $visits; ?></h3>

    <table class="print_nosology_report"
           border="1"
           cellpadding="5"
    >
		
		<?php
		$COLUMNS = $_SESSION['nosology_report']['columns'];
		$chucked_data = array_chunk($correct_data, ceil(count($correct_data) / $COLUMNS));
		$max_height = count($chucked_data[0]);
		$npp = 1;
		
		//		array_unshift($chucked_data, null);
		//		$chucked_data = call_user_func_array("array_map", $chucked_data);
		
		for ($row = 0; $row < $max_height; $row++)
		{
			echo '<tr style="padding: 5px">';
			for ($column = 0; $column < count($chucked_data); $column++)
			{
				$data = $chucked_data[$column][$row];
				if (count($data) > 0)
				{
					echo '<td>';
					echo '<b>' . $data['journal_ds'] . '</b> - ' . $data['count'];
//					echo  ' [column-'.$column.'][row-'.$row.']';
					echo '</td>';
				}
				
			}
			echo '</tr>';
		}
		
		?>
    </table>
	<?php
} else
{
    echo 'Таких пациентов не найдено!';
}

//debug($chucked_data);
//debug($correct_data);
if (count($incorrect_data) > 0)
{
	debug($incorrect_data);
}
