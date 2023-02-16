<?php

//require_once 'engine/php/mysqli/plugins/idiorm.php';
//ORM::configure('mysql:host='.$DB_host.';dbname=caop');
//ORM::configure('username', 'caop_volodinas');
//ORM::configure('password', 'kW0dB8dP2hwS6g');
//ORM::configure('id_column_overrides', array(
//	CAOP_DOCTOR => 'doctor_id',
//));
//ORM::configure('driver_options', array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));

$mysqli = mysqli_init();
if (!$mysqli) {
	die('mysqli_init failed');
}

if (!$mysqli->options(MYSQLI_INIT_COMMAND, 'SET AUTOCOMMIT = 0')) {
	die('Setting MYSQLI_INIT_COMMAND failed');
}

if (!$mysqli->real_connect($DB_host, DB_USER, DB_PASSWORD, DB_NAME)) {
	die('Connect Error (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
}
$mysqli->autocommit(true);