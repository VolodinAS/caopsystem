<?php
session_start();
$debug_arr = array();

$QUERY_ARRAY = [];

$startScriptTime = microtime(TRUE);
$StartMemory = memory_get_usage();

header("Cache-Control: no-store, no-cache, must-revalidate");
header("Expires: " . date("r"));
//SHOW KEYS FROM {$table} WHERE Key_name = 'PRIMARY'
require_once ( "engine/php/configs/defines.php" );
require_once ( "engine/php/config.php" );
require_once ( "engine/php/db_settings.php" );


// print_r($_SERVER);
// exit();

require_once ( "engine/php/mysqli/mysqli-functions.php" );
require_once ( "engine/php/mysqli/mysqli-freefunc.php" );
require_once ( "engine/php/mysqli/mysqli-tables.php" );
require_once ( "engine/php/html_functions.php" );
require_once ( "engine/php/functions.php" );
require_once ( "engine/php/functions-callback.php" );
require_once ( "engine/php/mysqli/mysqli-connect.php" );

require_once ( "engine/php/selector_variables.php" );

require_once ( "engine/php/functions-menu.php" );

require_once ("engine/php/configs/mysqleditor.filters.config.php");

//debug($AdminParams);

$CURRENT_DAY = getCurrentDay( time() );

$MAIN_CAT_ACCESS = FALSE;
$CAT_DATA = getarr(CAOP_CAT_SYSTEM, "cat_content='".CAT."' AND cat_approved='1'");
if ( count($CAT_DATA) == 1 )
{
    // 1 - нашли ключ.
	$CAT_DATA = $CAT_DATA[0];
	
	// 2 - проверяем срок действия
	$future_unix = strtotime("+1 year", $CAT_DATA['cat_approved_date_unix']);
	$remain_unix = $future_unix - time();
	$remain = floor($remain_unix / TIME_DAY);
	
	$last_use_update = array(
		'cat_last_use_unix' => time(),
		'cat_last_use_ip' => $_SERVER['REMOTE_ADDR']
	);
	
	$go_next = false;
	if ( $remain > 0 )
    {
        // 3 - ключ пока еще действует
        $go_next = true;
    } else
    {
        // 4 - срок ключа действия кончился. Проверяем, подключено ли автообновление
        if ($CAT_DATA['cat_auto_renewal'] == 1)
        {
            // 5 - автообновление подключено, обновляем!
            $current_time = time();
            $new_hash = GetRandomCat(512);
            $last_use_update['cat_approved_date'] = date(DMYHIS, $current_time);
            $last_use_update['cat_approved_date_unix'] = $current_time;
            $last_use_update['cat_content'] = $new_hash;
	        setcookie('GEY_MACHINE', $new_hash, time() + 86400 * 365, "/");
	        $go_next = true;
        } else
        {
	        // 6 - автообновление не подключено
        }
    }
    
	if ( $go_next )
	{
		
		$UpdateCats = updateData(CAOP_CAT_SYSTEM, $last_use_update, $CAT_DATA, "{$PK[CAOP_CAT_SYSTEM]}='{$CAT_DATA[$PK[CAOP_CAT_SYSTEM]]}'");
		$MAIN_CAT_ACCESS = TRUE;
	}
 
}

$CatActive = $AdminParams['data']['cat_active']['param_value'];

if ( $CatActive == 1 )
{
	if ( $MAIN_CAT_ACCESS === FALSE )
	{
		if ( isset($_GET['autogenkeymachineforvps']) )
		{
			
//			$GeyArray = array(
//				'gey_content' => GetRandomGeyMachine(512),
//				'gey_date' => $CURRENT_DAY['format']['dd.mm.yyyy hh:mm:ss'],
//				'gey_date_unix' => $CURRENT_DAY['full_unix'],
//                'gey_desc'      => 'FOR FREE VPS',
//				'gey_approved'  =>  '1',
//				'gey_approved_date' =>  $CURRENT_DAY['format']['dd.mm.yyyy hh:mm:ss'],
//				'gey_approved_date_unix'    =>  $CURRENT_DAY['full_unix']
//			);
//			$NewGey = appendData(CAOP_GEYMACHINE, $GeyArray);
//			setcookie('GEY_MACHINE', $GeyArray['gey_content'], time() + 86400*365, "/");
//			echo '<meta http-equiv="refresh" content="1; url=https://caopsystems.ru/news">';
			die('Free VPS is totally SHIT');
        } else
        {
	        die('!У ВАС НЕТ ДОСТУПА К ДАННОМУ РЕСУРСУ!<br/>ОБРАТИТЕСЬ К АДМИНИСТРАТОРУ ДЛЯ ПОЛУЧЕНИЯ ДОСТУПА!');
        }
	    
		
	}
}



if ( $_SERVER['REQUEST_URI'] == '/' ) $page = 'news';
else
{
	$data = substr( $_SERVER['REQUEST_URI'], 1 );
//	if ( !preg_match('/^[A-z0-9\/]{3,15}$/', $data) ) exit('error url');
	$RequestUriParse = explode("/", $data, 2);
	$page = $RequestUriParse[0];
	$request_params = $RequestUriParse[1];
}

if ( ifound($page, "?") )
{
    $pageData = explode("?", $page);
    $page = $pageData[0];
}

//debug($page);

$USER_PROFILE = array();
$USER_PROFILE = getUserProfile();
$DOCTOR_NAME = mb_ucwords($USER_PROFILE['doctor_f'] . ' ' . $USER_PROFILE['doctor_i'] . ' ' . $USER_PROFILE['doctor_o']);

$LPU_DOCTOR = $LpuId['id'.$USER_PROFILE['doctor_lpu_id']];

//debug($LPU_DOCTOR);

//$Doctor_Vitrina = getarr(CAOP_DOCTOR_VITRINA, "vitrina_doctor_id='{$USER_PROFILE['doctor_id']}'");

// ГЛАВНЫЙ СКРИПТ БЫТИЯ
if ( $page == "processor" )
{
//    debug('here');
	require_once ( "engine/php/processor.php" );
	exit();
}

// печатные документы
require_once ("engine/php/prints.php");

$Title = 'ОШИБКА';
$PageData = getPageData($page);
if ( $PageData['result'] === true )
{
//	debug($PageData);
	$PageData = $PageData['data'];
	$title_sql = $PageData['pages_title'];
	$head_sql = $PageData['pages_head'];
	$container_sql = $PageData['pages_container'];
	$icon_sql = $PageData['pages_icon'];
	$icon_ext = $PageData['pages_icon_ext'];
	$link_sql = $page;
	$needCalendar = $PageData['pages_calendar'];
	$IN_PROCESS = $PageData['page_process'];
	$PagesAccessData = explode(";", $PageData['pages_access']);
}

$ALL_page = 'all/'.$page.'.php';
$AUTH_page = 'auth/'.$page.'.php';
$GUEST_page = 'guest/'.$page.'.php';
$REDIRECT_page = 'redirect/404.php';

//$USER_HEADMENU = getUserMenu2($USER_PROFILE);

$Title = array(
	'title' =>  $title_sql,
	'head'  =>  $head_sql,
//	'headmenu'  =>  $USER_HEADMENU,
	'page'  =>  $page,
	'username'  =>  shorty($USER_PROFILE['doctor_f'] . ' ' . $USER_PROFILE['doctor_i'] . ' ' . $USER_PROFILE['doctor_o'], "famimot"),
	'container' =>  $container_sql,
	'icon' =>  $icon_sql,
	'icon_ext' =>  $icon_ext,
	'link' =>  $link_sql,
	'profile' =>  $USER_PROFILE,
	'calendar' =>  $needCalendar,
	'request_params' => $request_params
);

html_title_begin($Title);

$Doctor_Access_Array = explode(";", $USER_PROFILE['doctor_access']);

//if ( in_array($USER_PROFILE['doctor_access'], $PagesAccessData) || in_array('0', $PagesAccessData) )
if ( array_intersect_key($Doctor_Access_Array, $PagesAccessData) || in_array('0', $PagesAccessData) )
{
	if ( file_exists( $ALL_page ) ) require_once $ALL_page;

//    elseif ( file_exists( $TEST_page ) ) require_once $TEST_page;

	elseif ( $USER_PROFILE['ulogin'] == 1 && file_exists( $AUTH_page ) )
    {
        if ( $IN_PROCESS == 1 )
        {
            if ( in_array( '2', $Doctor_Access_Array ) )
            {
	            require_once $AUTH_page;
            } else bt_notice(wrapper('Данный раздел находится в разработке. Приходите позже...'), BT_THEME_WARNING);
        } else require_once $AUTH_page;
	    
    }

	elseif ( $USER_PROFILE['ulogin'] != 1 && file_exists( $GUEST_page ) ) require_once $GUEST_page;

	else require_once $REDIRECT_page;
} else
{
	bt_notice('<b>У ВАС НЕТ ДОСТУПА К ЭТОЙ СТРАНИЦЕ</b>',BT_THEME_DANGER);
}

require_once ( "engine/html/modals/mysqleditor.modalform/modal_form.php" );
require_once ( "engine/html/modals/mysqleditor.filters/filters.php" );
include ("engine/html/modals/spo/spo_viewer.php");
require_once ( "engine/html/modals/imageZoom.php" );
require_once ( "engine/html/modals/editPersonalData.php" );
require_once ( "engine/html/modals/editCitology.php" );


?>
    <script>
        var CURRENT_PAGE = '<?=$page;?>';
    </script>
<?php

html_title_end();

$endScriptTime = microtime(TRUE);

$version = $AdminParams['data']['version']['param_value'];

$EndMemory = memory_get_usage();
$UsedMemory = $EndMemory - $StartMemory;
$MemoryLimit = MemoryLimit();
$percent = round($UsedMemory / $MemoryLimit * 100000) / 1000;

$show_total_queries = $AdminParams['data']['show_total_queries']['param_value'];
if ($show_total_queries == 1)
{
	spoiler_begin('Запросы страницы', 'queries');
	debug($QUERY_ARRAY);
	spoiler_end();
}

//debug($AdminParams);
?>

<div class="text-muted small">
	Время загрузки страницы: <?= ($endScriptTime - $startScriptTime) ?><br>
    Затрачено памяти: <?=memorySizeConvert($UsedMemory);?> / <?=memorySizeConvert($MemoryLimit);?> (<?=$percent;?>%)<br>
    <b>Версия:</b> release-<?=$version;?>
</div>
