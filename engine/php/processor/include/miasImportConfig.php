<?php

$AvailableFields = array(
	'Дата',
	'Время',
	'Врач',
	'Пациент',
	'Номер карты',
	'Дата рождения',
	'Адрес регистрации пациента',
	'Фактический адрес пациента',
	'Статус',
	'Контакты',
	'Полис ОМС',
	'Перс. документ',
	'Дата записи',
	'Время записи',
	'Записавшая организация',
	'Направившее отделение',
	'Кем записан'
);

$UsefulFields = array(
	'Дата',
	'Время',
	'Пациент',
	'Номер карты',
	'Дата рождения',
	'Адрес регистрации пациента',
	'Фактический адрес пациента',
	'Контакты',
	'Полис ОМС',
	'Перс. документ',
	'Дата записи',
	'Время записи',
	'Записавшая организация',
	'Направившее отделение',
	'Кем записан',
	'СНИЛС',
	'Тип услуги',
	'ЛПУ прикрепления',
);

$MaximalFieldPatientsList = array(
	'Время' => array(
		'title' => 'import_journal_time'
	),
	'Пациент' => array(
		'title' => 'import_patient_name',
		'field' =>  'patid_name'
	),
	'Номер карты' => array(
		'title' => 'import_patient_ident',
		'field' =>  'patid_ident'
	),
	'Дата рождения' => array(
		'title' => 'import_patient_birth',
		'field' =>  'patid_birth'
	),
	'Адрес регистрации пациента' => array(
		'title' => 'import_patient_address_reg',
		'field' =>  'patid_address'
	),
	'Фактический адрес пациента' => array(
		'title' => 'import_patient_address_live',
		'field' =>  'patid_address'
	),
	'Контакты' => array(
		'title' => 'import_patient_phone',
		'field' =>  'patid_phone'
	),
	'Полис ОМС' => array(
		'title' => 'import_patient_insurance_number',
		'field' =>  'patid_insurance_number'
	),
	'Перс. документ' => array(
		'title' => 'import_patient_passport',
		'field' =>  'patid_passport_serialnumber'
	),
	'Статус' => array(
		'title' => 'import_journal_status'
	),
	'Дата записи' => array(
	    'title' => 'import_journal_record_date'
	),
	'Время записи' => array(
	    'title' => 'import_journal_record_time'
	),
	'Записавшая организация' => array(
	    'title' => 'import_journal_record_lpu'
	),
	'Направившее отделение' => array(
	    'title' => 'import_journal_record_division'
	),
	'Кем записан' => array(
	    'title' => 'import_journal_record_worker'
	),
	'СНИЛС' => array(
		'title' => 'import_patient_snils',
		'field' =>  'patid_snils'
	),
	'Тип услуги' => array(
		'title' => 'import_journal_visit_type',
	),
	'ЛПУ прикрепления' => array(
		'title' => 'import_patient_lpu',
		'field' =>  'patid_pin_lpu_id'
	),
);

$StartFieldText = 'Журнал записей пациентов';
$IsListText = "\t";
$EndListText = 'Всего';