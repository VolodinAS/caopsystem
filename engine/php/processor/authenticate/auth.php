<?php
$response['stage'] = $_POST['action'];
$login = $_POST['doctor_miac_login'];
$password = $_POST['doctor_miac_pass'];

$pwd = getHashPassword($password);

$Auth = getarr('caop_doctor', "doctor_miac_login='{$login}' AND doctor_miac_pass='{$pwd}'");
if ( count($Auth) == 1 )
{
	$User = $Auth[0];
	if ( $User['doctor_enabled'] == 1 )
	{
		setcookie('login', $login, time() + 86400*365, "/");
		setcookie('password', $pwd, time() + 86400*365, "/");
		$redirect_path = str_replace("'", "", getDoctorRedirectPath($User));
		$response['result'] = true;
		$response['user'] = $User;
		$response['redirect'] = $redirect_path;
		$response['msg'] = 'Авторизация успешно завершена!';
	} else
	{
		$response['msg'] = 'Ваш аккаунт еще не активирован! Обратитесь за доступом к Володину Александру Сергеевичу!';
	}

} else
{
	$response['msg'] = 'Неверный логин или пароль';
}