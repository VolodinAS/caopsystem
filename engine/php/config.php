<?php

$DB_host = 'localhost';
define("CAT", $_COOKIE['GEY_MACHINE'] ); // остаётся также, иначе, собъётся на всех компах

require_once ( "engine/php/configs/reports.mkb.php" );
require_once ( "engine/php/configs/icon.constants.php" );

$MonthsRusFull = array(
	'Январь',
	'Февраль',
	'Март',
	'Апрель',
	'Май',
	'Июнь',
	'Июль',
	'Август',
	'Сентябрь',
	'Октябрь',
	'Ноябрь',
	'Декабрь'
);

$MonthsRusShort = array(
	'Янв',
	'Фев',
	'Мар',
	'Апр',
	'Май',
	'Июн',
	'Июл',
	'Авг',
	'Сен',
	'Окт',
	'Ноя',
	'Дек'
);

$AllSurveys = array(
	array(
	    'survey_id' => 1,
		'survey_title' => 'Соскоб',
		'survey_morph' => true
	),
	array(
	    'survey_id' => 2,
		'survey_title' => 'Пункция',
		'survey_morph' => true
	),
	array(
	    'survey_id' => 3,
		'survey_title' => 'Пункция под УЗИ',
		'survey_morph' => true
	),
	array(
	    'survey_id' => 4,
		'survey_title' => 'Мазок-отпечаток',
		'survey_morph' => true
	),
	array(
	    'survey_id' => 5,
		'survey_title' => 'Мазок',
		'survey_morph' => true
	),
	array(
	    'survey_id' => 6,
		'survey_title' => 'Анализ мокроты',
		'survey_morph' => true
	),
	array(
	    'survey_id' => 7,
		'survey_title' => 'ФГДС-гистология',
		'survey_morph' => true
	),
	array(
	    'survey_id' => 8,
		'survey_title' => 'ФКС-гистология',
		'survey_morph' => true
	),
	array(
	    'survey_id' => 9,
		'survey_title' => 'Биопсия-гистология',
		'survey_morph' => true
	),
	array(
	    'survey_id' => 10,
		'survey_title' => 'РДВ',
		'survey_morph' => true
	),
	array(
	    'survey_id' => 11,
		'survey_title' => 'операция',
		'survey_morph' => true
	),
	array(
	    'survey_id' => 12,
		'survey_title' => 'УЗИ'
	),
	array(
	    'survey_id' => 13,
		'survey_title' => 'R-графия'
	),
	array(
	    'survey_id' => 14,
		'survey_title' => 'R-скопия'
	),
	array(
	    'survey_id' => 15,
		'survey_title' => 'Ирригоскопия'
	),
	array(
	    'survey_id' => 16,
		'survey_title' => 'ММГ'
	),
	array(
	    'survey_id' => 17,
		'survey_title' => 'МСКТ'
	),
	array(
	    'survey_id' => 18,
		'survey_title' => 'МРТ'
	),
	array(
	    'survey_id' => 19,
		'survey_title' => 'ПЭТ/КТ'
	),
	array(
	    'survey_id' => 20,
		'survey_title' => 'РИКС'
	),
);


define("TIME_SEC", 1);
define("TIME_MIN", 60 * TIME_SEC);
define("TIME_HOUR", 60 * TIME_MIN);
define("TIME_DAY", 24 * TIME_HOUR);
define("TIME_WEEK", 7 * TIME_DAY);

define('V_MYP', 'vitrina_myPatients');

