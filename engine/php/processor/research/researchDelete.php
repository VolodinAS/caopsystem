<?php
$response['stage'] = $action;

$patient_id = $_POST['patid'];

$DeleteResearch = deleteitem('caop_research', "research_id='{$patient_id}'");
if ( $DeleteResearch['result'] === true )
{
	$response['result'] = true;
} else
{
	$response['msg'] = $DeleteResearch;
}