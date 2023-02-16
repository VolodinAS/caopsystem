<?php
$response['stage'] = $action;
$response['msg'] = 'begin';

$HTTP = $_POST;
extract($HTTP, EXTR_PREFIX_SAME, '_caop');

$response['result'] = true;

$response['htmlData'] = '';

$AreasUZI = getarr(CAOP_SCHEDULE_UZI_RESEARCH_AREA, "1", "ORDER BY area_title ASC");
$AreasUZIId = getDoctorsById($AreasUZI, 'area_id');

include "inc_uzicaop_record.php";