<?php
$response['stage'] = $action;
$response['msg'] = 'begin';

$HTTP = $_POST;
extract($HTTP, EXTR_PREFIX_SAME, '_caop');

$DeleteResearch = deleteitem(CAOP_DS_RESEARCH, "research_id='{$research_id}'");
if ( $DeleteResearch['result'] )
{
	$response['result'] = true;
} else
{
	$response['msg'] = 'Ошибка удаления результата';
	$response['debug']['$DeleteResearch'] = $DeleteResearch;
}