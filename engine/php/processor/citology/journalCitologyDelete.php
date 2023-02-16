<?php
$response['stage'] = $action;

$patient_id = $_POST['patient_id'];

$DeletePatient = deleteitem('caop_citology', "citology_id='{$patient_id}'");
if ( $DeletePatient['result'] === true )
{
    $response['debug'] = $DeletePatient;
    $response['result'] = true;
} else
{
    $response['msg'] = $DeletePatient;
}