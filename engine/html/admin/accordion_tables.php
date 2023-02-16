<?php
spoiler_begin('Структура таблиц', 'tables_structure', 'collapse', '', 'admin-spoiler');
?>

<?php
foreach ($AllTables as $Table)
{
	spoiler_begin($Table, 'tables_structure_' . $Table, 'collapse', '');
	
//	$TableStructure = tablestructure2(array($Table), 1);
//	$TableStructure = array_values($TableStructure);
	
	$TableStructure = tablestructure2(array($Table), 1);
	$TableStructure = array_values($TableStructure);
	debug($TableStructure[0]);
    
//    debug( getcolumns2($Table) );
    
//    debug(getPrimaryKey($Table));
	
    spoiler_end();
    break;
}
spoiler_end();
