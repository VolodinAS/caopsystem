<?php
$response['stage'] = $action;

$patid_ident = $_POST['patid_ident'];

$PatientPersonalData = getarr(CAOP_PATIENTS, "patid_ident='{$patid_ident}'");
if ( count($PatientPersonalData) == 0 )
{
    // такой карты в базе нет
    $response['result'] = true;
    $response['foundtype'] = 'notfound';
} else
{
    if ( count($PatientPersonalData) == 1 )
    {
        // пациент найден
        $response['result'] = true;
        $response['foundtype'] = 'found';
        $response['patientData'] = $PatientPersonalData[0];
    } else
    {
        // слишком много пациентов с таким номером
        $response['result'] = true;
        $response['foundtype'] = 'multy';
    }
}