<?php
if ( strlen($request_params) > 0 )
{
	$table_id = $request_params;
	$TableRM = RecordManipulation($table_id, CAOP_TABLES, 'table_id');
	if ( $TableRM['result'] )
	{
		$TableData = $TableRM['data'];
//		debug($TableData);
		?>
		
		<div class="p-3">
			<h1 class="display-4"><?=$TableData['table_title'];?></h1>
			<span class="lead"><?=$TableData['table_subtitle'];?></span>
			<div class="dropdown-divider"></div>
			<?=$TableData['table_description'];?>
			<div class="dropdown-divider"></div>
		</div>
		
		<button type="button" class="btn btn-primary btn-newRecord" data-table="<?=$TableData['table_id'];?>">Новая строка</button>
		<button type="button" class="btn btn-warning btn-removeSelected" data-table="<?=$TableData['table_id'];?>">Удалить выбранные</button>
		<?php
	    
	    
	    $TableConstructor = table_constructor($TableData['table_id'], CAOP_TABLES, CAOP_TABLE_FIELDS, CAOP_TABLE_CELLS);
	    
	    echo $TableConstructor['htmlData'];
	
	} else bt_notice($TableRM['msg'], BT_THEME_WARNING);
 
} else bt_notice('Не указана таблица для просмотра');
?>

<script defer type="text/javascript" src="/engine/js/table_viewer.js?<?=rand(0, 999999);?>"></script>
