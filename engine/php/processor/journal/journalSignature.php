<?php
$response['stage'] = $action;

$day_id = $_POST['day_id'];

$Day = getarr(CAOP_DAYS, "day_id='{$day_id}' AND day_doctor='{$USER_PROFILE['doctor_id']}'");

if ( count($Day) == 1 )
{

    $DayData = $Day[0];

    if ( $DayData['day_signature_state'] == 0 )
    {

        $SignatureArray = array(
            'day_signature_state'   =>  '1',
            'day_signature_doctor_id'   =>  $USER_PROFILE['doctor_id'],
            'day_signature_unix'    =>  time()
        );

        $SignatureRequest = updateData(CAOP_DAYS, $SignatureArray, $DayData, "day_id='{$DayData['day_id']}'");
        if ( $SignatureRequest['stat'] == RES_SUCCESS )
        {
            $response['result'] = true;
        }

    } else
    {
        $response['msg'] = 'Данный день уже подписан';
    }

} else
{
    $response['msg'] = 'Такого дня в Вашем приёме не существует';
}