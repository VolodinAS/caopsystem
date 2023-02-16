<?php

header("Cache-Control: no-store, no-cache, must-revalidate");
header("Expires: " . date("r"));

require_once ( "engine/php/configs/defines.php" );
require_once ( "engine/php/config.php" );

require_once ( "engine/php/db_settings.php" );

if ( $_SERVER['HTTP_HOST'] == 'apk7caop.ru' )
{
	$current_file = __FILE__;
	$dir_caop = dirname($current_file);
	$dir_logs = dirname($dir_caop);
	$ERROR_LOG = $dir_logs . '/error_log';
	
} elseif ( $_SERVER['HTTP_HOST'] == 'caopsystem.local' )
{
	$current_file = __FILE__;
	$dir_caop = dirname($current_file);
	$dir_www = dirname($dir_caop);
	$dir_data = dirname($dir_www);
	$dir_logs = $dir_data . '/logs';
	$ERROR_LOG = $dir_logs . '/caopsystems.ru.error.log';
}

require_once ( "engine/php/mysqli/mysqli-functions.php" );
require_once ( "engine/php/mysqli/mysqli-freefunc.php" );
require_once ( "engine/php/mysqli/mysqli-tables.php" );
require_once ( "engine/php/html_functions.php" );
require_once ( "engine/php/functions.php" );
require_once ( "engine/php/mysqli/mysqli-connect.php" );

require_once ( "engine/php/selector_variables.php" );

$params['title'] = 'ОШИБКИ';
require_once "engine/html/html_content/html.php";


?>
<div class="container">
<?php

//print_r($dir_logs);
//exit();

/*print_r($current_file);
echo "\n";
print_r($dir_caop);
echo "\n";
$scan_dir_caop = scandir($dir_caop);
print_r($scan_dir_caop);
echo "\n";
print_r($dir_www);
echo "\n";
$scan_dir_www = scandir($dir_www);
print_r($scan_dir_www);
echo "\n";
print_r($dir_data);
echo "\n";
$scan_dir_data = scandir($dir_data);
print_r($scan_dir_data);
echo "\n";
print_r($dir_logs);
echo "\n";
$scan_dir_logs = scandir($dir_logs);
print_r($scan_dir_logs);*/


//$ACCESS_LOG = $dir_logs . '/caopsystems.ru.error.log';

$ERROR_DATA = file($ERROR_LOG);
//print_r($ERROR_LOG);
//print_r($ERROR_DATA);
$ERROR_DATA = array_reverse($ERROR_DATA);



$Exclude = array(
    'upstream response' => 'Исключить предупреждения о буферизации №1',
    'request body' => 'Исключить предупреждения о буферизации №2'
);

$row_limit = 30;
$npp = 1;
foreach ($ERROR_DATA as $error_index=>$error_text)
{
	$error_data = explode(' [', $error_text, 2);
	$is_excluded = false;
	foreach ($Exclude as $exclude_find=>$exclude_desc)
	{
		if ( ifound($error_data[1], $exclude_find) )
		{
		    $is_excluded = true;
		    break;
		}
	}
	if ( !$is_excluded )
	{
		spoiler_begin('Error #'.$npp . ' ['.$error_data[0].']', 'error_' . $error_index, '');
		{
			echo $error_data[1];
		}
		spoiler_end();
		$row_limit--;
		$npp++;
	}
	if ( $row_limit <= 0 ) break;
}

?>
</div>
<?php
require_once "engine/html/html_content/footer.php";


