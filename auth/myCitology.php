<?php

$ShowArchiveCitology = doctor_param('get', $USER_PROFILE['doctor_id'], 'citologyShowArchive');
$research_query_archive_addon = '';
if ( $ShowArchiveCitology['stat'] == RES_SUCCESS )
{
	$ShowArchiveCitology = $ShowArchiveCitology['data']['settings_param_value'];
	if ( $ShowArchiveCitology == 0 )
	{
		bt_notice('Внимание! У Вас установлена фильтрация данных. Архивные (законченные) результаты скрыты! <a href="/profileSettings">'.wrapper('Изменить...').'</a>', BT_THEME_WARNING);
		$research_query_archive_addon = " AND citology_result_text=''";
	}
} else
{
	$ShowArchiveCitology = 1;
}

$CitologyPeriod = doctor_param('get', $USER_PROFILE['doctor_id'], 'citologyShowMePeriod');
if ( $CitologyPeriod['stat'] == RES_SUCCESS )
{
	$ShowMePeriodCitology = $CitologyPeriod['data']['settings_param_value'];
	if ( $ShowMePeriodCitology != "all" )
	{
		//citology_dir_date_unix
		$PeriodData = PeriodToSec($ShowMePeriodCitology);
		
		if ( $PeriodData !== false )
		{
			bt_notice('Внимание! У Вас установлена фильтрация данных. Отображены результаты за последние '.wrapper($PeriodData['title']) . '. <a href="/profileSettings">'.wrapper('Изменить...').'</a>', BT_THEME_WARNING);
			$lastPeriod = time() - $PeriodData['interval'];
			
			$queryCitology = "SELECT * FROM $CAOP_CITOLOGY cc LEFT JOIN $CAOP_PATIENTS cp ON cc.citology_patid=cp.patid_id WHERE cc.citology_dir_date_unix>='{$lastPeriod}' AND citology_doctor_id='{$USER_PROFILE['doctor_id']}' ".$research_query_archive_addon." ORDER BY cc.citology_result_text ASC, cc.citology_id DESC";
		}

//		debug($PeriodData);
	} else
	{
		$queryCitology = "SELECT * FROM $CAOP_CITOLOGY cc LEFT JOIN $CAOP_PATIENTS cp ON cc.citology_patid=cp.patid_id WHERE citology_doctor_id='{$USER_PROFILE['doctor_id']}' ".$research_query_archive_addon." ORDER BY cc.citology_result_text ASC, cc.citology_id DESC";
	}
} else
{
	$queryCitology = "SELECT * FROM $CAOP_CITOLOGY cc LEFT JOIN $CAOP_PATIENTS cp ON cc.citology_patid=cp.patid_id WHERE citology_doctor_id='{$USER_PROFILE['doctor_id']}' ".$research_query_archive_addon." ORDER BY cc.citology_result_text ASC, cc.citology_id DESC";
}


$resultCitology = mqc($queryCitology);
$amountCitology = mnr($resultCitology);

if ( $amountCitology > 0 )
{
	$CitologyArray = array();
	while ( $pat = mfa($resultCitology) )
	{
		$CitologyArray[] = $pat;
//		debug($pat);
	}
}

//exit();
//$CitologyArray = getarr(CAOP_CITOLOGY, 1, "ORDER BY citology_result_text ASC, citology_id DESC");

$RequestData = explode("/", $request_params);
$request_params = $RequestData[0];
//debug($RequestData);

if ( ifound($RequestData[0], "light") )
{
	$light_id = str_replace("light", "", $RequestData[0]);
}

//debug($light_id);

require_once ( "engine/html/citology_table.php" );

require_once ( "engine/html/modals/citologyMarker.php" );



?>



	<script defer language="JavaScript" type="text/javascript" src="/engine/js/citology.js?<?=rand(0, 9999);?>"></script>

<?php
if ( ifound($RequestData[0], "light") )
{
	echo '
	<script>
	$(document).ready(function(){
	    scrollToAnchor(\'citopat'.$light_id.'\');
	});
	</script>
	';
}
?>