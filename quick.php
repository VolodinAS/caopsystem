<?php

header("Cache-Control: no-store, no-cache, must-revalidate");
header("Expires: " . date("r"));

require_once ( "engine/php/configs/defines.php" );
require_once ( "engine/php/config.php" );

require_once ( "engine/php/db_settings.php" );

require_once ( "engine/php/mysqli/mysqli-functions.php" );
require_once ( "engine/php/mysqli/mysqli-freefunc.php" );
require_once ( "engine/php/mysqli/mysqli-tables.php" );
require_once ( "engine/php/html_functions.php" );
require_once ( "engine/php/functions.php" );
require_once ( "engine/php/mysqli/mysqli-connect.php" );

require_once ( "engine/php/selector_variables.php" );

$hash = array_keys($_GET)[0];

$DoctorHash = getarr(CAOP_DOCTOR, "doctor_quick='{$hash}'");
$go_error = false;
if ( count($DoctorHash) == 1 )
{
	$DoctorHash = $DoctorHash[0];
	
	$CheckHash = quickHashByDocarr($DoctorHash, $hash);
	
	if ( $CheckHash )
	{
		$docio = mb_ucwords($DoctorHash['doctor_i'] . ' ' . $DoctorHash['doctor_o']);
		setcookie('login', $DoctorHash['doctor_miac_login'], time() + 86400*365, "/");
		setcookie('password', $DoctorHash['doctor_miac_pass'], time() + 86400*365, "/");
		$docfio = docNameShort($DoctorHash);
		$params['title'] = $docfio;
		include "engine/html/html_content/html.php";
		
		$redirect_path = getDoctorRedirectPath($DoctorHash);
		
//		print_r($redirect_path);
		
		?>
		<div class="container p-2 text-center">
		<button class="btn btn-primary" onclick="window.location.href=<?=$redirect_path;?>">Добро пожаловать, <?=$docio;?>. Нажмите для ВХОДА >>></button>
		<?php
	} else
	{
		$go_error = true;
	}
} else
{
	$go_error = true;
}

if ($go_error)
{
	include "engine/html/html_content/html.php";
	?>
	<div class="container p-2 text-center">
	<?php
	bt_notice('Неверный код авторизации! Войдите '.wrapper('<a href="/login">обычным способом</a>'));
}
//
?>
	</div>
<?php

require_once "engine/html/html_content/footer.php";


