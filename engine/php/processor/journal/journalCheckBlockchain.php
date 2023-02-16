<?php
$response['stage'] = $action;
$Today_Array = getarr('caop_days', "day_unix='{$CURRENT_DAY['unix']}' AND day_doctor='{$USER_PROFILE['doctor_id']}'");

if ( count($Today_Array) == 1 )
{
    $Today_Array = $Today_Array[0];
//    $response['debug']['$Today_Array'] = $Today_Array;

    $PatientsToday = getarr('caop_journal', "journal_day='{$Today_Array['day_id']}' AND journal_doctor='{$USER_PROFILE['doctor_id']}'", "ORDER BY journal_id DESC");

    $JSON = json_encode($PatientsToday);
    $JSON_HASH = md5($JSON);

    $response['result'] = true;
    $response['patientsServerHash'] = $JSON_HASH;
}