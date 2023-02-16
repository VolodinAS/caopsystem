<?php

if ( count($AllPatients) > 0 )
{
	?>
	<table class="table table-sm allpatients">
		<thead>
		<tr>
			<?php
			foreach ($fields as $field)
			{
				$w = ' width="1%"';
				if ( !$field['onePer'] ) $w = '';
				
				echo '<th scope="col" class="font-weight-bolder full-center" data-title="'.$field['data_title'].'" '.$w.' '.$field['addon'].'>'.$field['title'].'</th>';
			}
			?>
			<!--
			<th scope="col" class="font-weight-bolder full-center" width="1%">#</th>
			<th scope="col" class="font-weight-bolder full-center" width="1%">Карта</th>
			<th scope="col" class="font-weight-bolder full-center" width="1%">CR</th>
			<th scope="col" class="font-weight-bolder full-center">Ф.И.О.</th>
			<th scope="col" class="font-weight-bolder full-center" width="1%" date-format="ddmmyyyy">Дата рождения</th>
			<th scope="col" class="font-weight-bolder full-center sorter-false">Контакты</th>
			<th scope="col" class="font-weight-bolder full-center sorter-false">Адрес</th>
			<th scope="col" class="font-weight-bolder full-center">Посещения</th>
			-->
		</tr>
		</thead>
		<tbody>
		
		<?php
		$npp = 1;
		foreach ($AllPatients as $Patient) {
//    debug($Patient);
			include ( "engine/php/patient_search_single_gen.php" );
		}
		
		
		
		?>
		</tbody>
	</table>
	<?php
} else
{
	bt_notice(wrapper('Список пациентов по данному разделу ПУСТ'), BT_THEME_WARNING);
}

?>

