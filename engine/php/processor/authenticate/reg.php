<?php
$response['stage'] = $_POST['action'];
$doctor_f = mb_strtolower($_POST['doctor_f'], UTF8);
$doctor_i = mb_strtolower($_POST['doctor_i'], UTF8);
$doctor_o = mb_strtolower($_POST['doctor_o'], UTF8);

if ( strlen($doctor_f)>0 && strlen($doctor_i) && strlen($doctor_o)>0 )
{
	$CheckDoctor = getarr( 'caop_doctor', "doctor_f='{$doctor_f}' AND doctor_i='{$doctor_i}' AND doctor_o='{$doctor_o}'" );
	if ( count($CheckDoctor) > 0 )
	{
		$response['msg'] = 'Вы уже зарегистрированы в системе. Если Вы забыли данные для входа - обратитесь к Володину Александру Сергеевичу!';
	} else
	{
		$newDoctor = array(
		 'doctor_f'  =>   $doctor_f,
		 'doctor_i'  =>  $doctor_i,
		 'doctor_o'  =>  $doctor_o,
		 'doctor_miac_login' =>  $_POST['doctor_miac_login'],
		 'doctor_miac_pass'  =>  getHashPassword($_POST['doctor_miac_pass'])
		);
		$Append = appendData('caop_doctor', $newDoctor);
		if ( $Append[ID] > 0 )
		{
			$response['result'] = true;
			$response['msg'] = 'Регистрация успешно завершена. Теперь обратитесь к Володину Александру Сергеевичу для получения полного доступа!';
		}
	}
} else
{
	$response['msg'] = 'Фамилия, Имя и Отчество не могут быть пустыми!';
}

