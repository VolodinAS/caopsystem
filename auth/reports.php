<?php

$q_journal = "SELECT * FROM {$CAOP_JOURNAL} cj LEFT JOIN {$CAOP_PATIENTS} cp ON cj.journal_patid=cp.patid_id WHERE journal_ds LIKE '%C%' GROUP BY journal_patid ORDER BY journal_patid ASC";
$r_journal = mqc($q_journal);
$Journal = mr2a($r_journal);
?>

<table class="table table-sm allpatients">
	<thead>
	<tr>
		<th scope="col" class="font-weight-bolder full-center" width="1%">#</th>
		<th scope="col" class="font-weight-bolder full-center" width="1%">Карта</th>
		<th scope="col" class="font-weight-bolder full-center" width="1%">CR</th>
		<th scope="col" class="font-weight-bolder full-center">Ф.И.О.</th>
		<th scope="col" class="font-weight-bolder full-center" width="1%" data-date-format="ddmmyyyy">Дата рождения</th>
		<th scope="col" class="font-weight-bolder full-center sorter-false">Контакты</th>
		<th scope="col" class="font-weight-bolder full-center sorter-false">Адрес</th>
		<th scope="col" class="font-weight-bolder full-center">Посещения</th>
	</tr>
	</thead>
	<tbody>

	<?php
	$npp = 1;
	foreach ($Journal as $Patient) {
//    debug($Patient);
		include ( "engine/php/patient_search_single.php" );
	}



	?>
	</tbody>
</table>
