<?php
require_once ( "engine/php/configs/defines.php" );
require_once ( "engine/php/config.php" );

require_once ( "engine/php/db_settings.php" );

require_once ( "engine/php/mysqli/mysqli-functions.php" );
require_once ( "engine/php/mysqli/mysqli-freefunc.php" );
require_once ( "engine/php/mysqli/mysqli-tables.php" );
require_once ( "engine/php/html_functions.php" );
require_once ( "engine/php/functions.php" );
require_once ( "engine/php/mysqli/mysqli-connect.php" );

require_once ( "engine/php/selector_variables.php" );

header("Expires: Mon, 1 Apr 1974 05:00:00 GMT");
header("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Content-type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=file.xls");

$document = new \PHPExcel();
$objWriter = new PHPExcel_Writer_Excel5($document);
$objWriter->save('php://output');
exit();