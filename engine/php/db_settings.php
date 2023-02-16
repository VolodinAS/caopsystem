<?php
$SETTINGS = [];
const APPLICATION_SETTINGS_FILE = "application_settings.json";
$application_settings = file_get_contents(APPLICATION_SETTINGS_FILE);
$go_next = false;
if ( isJson($application_settings) )
{
	$SETTINGS = json_decode($application_settings, 1);
	$go_next = true;
} else die('APPLICATION_SETTINGS_FILE is not valid!');

if ( $go_next )
{
	if ( count($SETTINGS) > 0 )
	{
		
		$SERVER_SETTINGS = $SETTINGS[$_SERVER['HTTP_HOST'] . '_settings'];
		
		if ( count($SERVER_SETTINGS) > 0 )
		{
			$DB_host = $SERVER_SETTINGS['db_host'];
			define("DB_USER", $SERVER_SETTINGS['db_user']);
			define("DB_NAME", $SERVER_SETTINGS['db_name']);
			define("DB_PASSWORD", $SERVER_SETTINGS['db_password']);
			define("DOMAIN", $SERVER_SETTINGS['domain']);
			define("TABLES_COLUMN", $SERVER_SETTINGS['columns']);
			
		} else die('$SERVER_SETTINGS is not valid!');
	} else die('$SETTINGS is not valid!');
}

function isJson($string) {
	json_decode($string);
	return json_last_error() === JSON_ERROR_NONE;
}