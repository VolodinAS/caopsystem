<?php
$response['stage'] = $action;

$CitologyArray = array(
    'citology_journal_id'   => '',
    'citology_patient_name' => '',
    'citology_patient_birth'    =>  '',
    'citology_patient_sex'  =>  '',
    'citology_patient_address' => '',
    'citology_patient_phone'    =>  '',
    'citology_patient_ident'    =>  '',
    'citology_doctor_id'    =>  '',
    'citology_ds_mkb'   =>  '',
    'citology_ds_text'  =>  '',
    'citology_analise_type' =>  '',
    'citology_dir_date_unix'    =>  '',
    'citology_dir_doctor_id'    =>  '',
    'citology_result_date' => '',
    'citology_result_text'  =>  ''
);

$patient_id = $_POST['patient_id'];

$PatientData = getarr(CAOP_JOURNAL, "journal_id='{$patient_id}'");
if ( count($PatientData) == 1 )
{
    $PatientData = $PatientData[0];

    $PatientPersonalData = getarr(CAOP_PATIENTS, "patid_id='{$PatientData['journal_patid']}'");

    if ( count($PatientPersonalData) == 1 ) {

        $PatientPersonalData = $PatientPersonalData[0];

        $CitologyDirect = array(
            'citology_journal_id'   =>  $PatientData['journal_id'],
//        'citology_patient_name' =>  $PatientData['journal_patient_name'],
//        'citology_patient_birth'    =>  $PatientData['journal_patient_birth'],
//        'citology_patient_address'  =>  $PatientData['journal_patient_address'],
//        'citology_patient_phone'    =>  $PatientData['journal_patient_phone'],
//        'citology_patient_ident'    =>  $PatientData['journal_patient_ident'],
            'citology_doctor_id'    =>  $PatientData['journal_doctor'],
            'citology_ds_mkb'   =>  $PatientData['journal_ds'],
            'citology_ds_text'  =>  $PatientData['journal_ds_text'],
            'citology_dir_date_unix'    =>  time(),
            'citology_action_date'    =>  date("d.m.Y", time()),
            'citology_dir_doctor_id'    =>  $PatientData['journal_doctor'],
            'citology_patid'    =>  $PatientPersonalData['patid_id']
        );

        $NewCitology = appendData(CAOP_CITOLOGY, $CitologyDirect);
        if ( $NewCitology[ID] > 0 )
        {
            $response['result'] = true;
        } else
        {
            $response['msg'] = $NewCitology;
        }

    } else
    {
        $response['msg'] = 'Пациента в базе нет';
    }

    /*$CitologyDirect = array(
        'citology_journal_id'   =>  $PatientData['journal_id'],
        'citology_patient_name' =>  $PatientData['journal_patient_name'],
        'citology_patient_birth'    =>  $PatientData['journal_patient_birth'],
        'citology_patient_address'  =>  $PatientData['journal_patient_address'],
        'citology_patient_phone'    =>  $PatientData['journal_patient_phone'],
        'citology_patient_ident'    =>  $PatientData['journal_patient_ident'],
        'citology_doctor_id'    =>  $PatientData['journal_doctor'],
        'citology_ds_mkb'   =>  $PatientData['journal_ds'],
        'citology_ds_text'  =>  $PatientData['journal_ds_text'],
        'citology_dir_date_unix'    =>  time(),
        'citology_dir_doctor_id'    =>  $PatientData['journal_doctor']
    );*/



} else
{
    $response['msg'] = 'Такого пациента не существует';
}