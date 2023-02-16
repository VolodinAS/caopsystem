<?php

if ( $USER_PROFILE['access_level'] == 2 )
{

    $AllTables = mysql_tables(DB_NAME, TABLES_COLUMN);
    
//    debug($AllTables);
//    exit();
	
	$ExcludeTables = array(
		CAOP_JOURNAL,
		CAOP_PATIENTS,
		CAOP_CITOLOGY,
		CAOP_RESEARCH,
		CAOP_DS_DIRLIST,
		CAOP_DS_PATIENTS,
		CAOP_DS_VISITS,
		CAOP_DS_RESEARCH,
		CAOP_DS_VISITS_REGIMENS,
		CAOP_DS_REGIMENS,
        CAOP_NEEDADD,
        CAOP_CANCER,
        CAOP_DAYS,
        CAOP_ROUTE_SHEET,
        CAOP_DOCTOR_VITRINA,
        CAOP_MKB,
        CAOP_MKB_DISP,              // МКБ-диспансер
        CAOP_TABLES,
        CAOP_TABLE_FIELDS,
        CAOP_TABLE_CELLS,
        CAOP_TABLE_FILES,
        CAOP_NOTICE_F1A,
        CAOP_CHAT_MESSAGES,
        CAOP_CHAT_ATTACH,
        CAOP_CHAT_FILES,
        CAOP_SPO,
        CAOP_HEADMENU,
        CAOP_PAGES,
        CAOP_NEWS,
        CAOP_SCHEDULE_UZI_DOCTORS,
        CAOP_SCHEDULE_UZI_DATES,
        CAOP_SCHEDULE_UZI_TIMES,
        CAOP_SCHEDULE_UZI_SHIFTS,
        CAOP_SCHEDULE_UZI_DATES_SHIFTS_TEMP,
        CAOP_SCHEDULE_UZI_PATIENTS,
        CAOP_BLANK_DECLINE,
        CAOP_DAILY,
        CAOP_MSKT_MDC,
        CAOP_SCHEDULE_UZI_RESEARCH_AREA,
        CAOP_DOCTOR_SETTINGS,
        CAOP_CARTRIDGE_ACTION,
        CAOP_CARTRIDGE,
        CAOP_MORPHOLOGY,
        CAOP_MORPHOLOGY_MARKER,
        CAOP_DIR_057,
        CAOP_DISP_PATIENTS,
        CAOP_CAT_SYSTEM,
        CAOP_ZNO_DU,
        CAOP_LOG,
        CAOP_PARAMS,
        CAOP_DOCTOR,                // своё редактор
        CAOP_DS_COMB,               // не используется
        
	);
	$SmallTables = array();
	foreach ($AllTables as $table) {
		if ( !in_array($table, $ExcludeTables) ) $SmallTables[] = $table;
	}
	
	bt_notice(wrapper('Показано таблиц:') . ' '.count($SmallTables).' из '.count($AllTables), BT_THEME_WARNING);
	
    require_once ("engine/html/admin/accordion_table_editor.php");


} else
{
    bt_notice('У Вас нет доступа к данному разделу',BT_THEME_DANGER);
}

require_once ( "engine/html/modals/adminTableNewItem.php" );

?>

<script defer src="/engine/js/admin/admin.js?<?=rand(0,999999);?>" type="text/javascript"></script>
