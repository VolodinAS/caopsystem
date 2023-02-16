<?php
// 1 - Проверка ИМЕНИ
$PatientNameData = name_for_query($PatientPersonalData['patid_name']);
if ( strlen($PatientNameData['PatientNameDataFormatted'][1])>1 && strlen($PatientNameData['PatientNameDataFormatted'][2])>1 )
{

} else {
	$icon_case_personal_ok = false;
	$icon_case_personal_data[] = '`ФИО`';
}

// 2 - Проверка НОМЕРА КАРТЫ
if ( strlen($PatientPersonalData['patid_ident'])>0 )
{

} else {
	$icon_case_personal_ok = false;
	$icon_case_personal_data[] = '`Номер карты`';
}

// 3 - Проверка АДРЕСА
if ( strlen($PatientPersonalData['patid_address'])>0 )
{

} else {
	$icon_case_personal_ok = false;
	$icon_case_personal_data[] = '`Адрес`';
}

// 4 - Проверка ДАТЫ РОЖДЕНИЯ
if ( strlen($PatientPersonalData['patid_birth'])>0 && $PatientPersonalData['patid_birth_unix']!=0 )
{

} else {
	$icon_case_personal_ok = false;
	$icon_case_personal_data[] = '`Дата рождения`';
}

// 5 - Проверка ТЕЛЕФОНА
if ( strlen($PatientPersonalData['patid_phone'])>0 )
{

} else {
	$icon_case_personal_ok = false;
	$icon_case_personal_data[] = '`Телефон`';
}

// 6 - Проверка НОМЕРА СТРАХОВОГО ПОЛИСА
if ( strlen($PatientPersonalData['patid_insurance_number'])>0 )
{

} else {
	$icon_case_personal_ok = false;
	$icon_case_personal_data[] = '`Страховой номер`';
}

// 7 - Проверка СТРАХОВОЙ КОМПАНИИ
if ( $PatientPersonalData['patid_insurance_company']>0 )
{

} else {
	$icon_case_personal_ok = false;
	$icon_case_personal_data[] = '`Страховая компания`';
}

/*
// 8 - Проверка СНИЛС
if ( strlen($PatientPersonalData['patid_snils'])>10 )
{

} else {
	$icon_case_personal_ok = false;
	$icon_case_personal_data[] = '`СНИЛС`';
}*/

// 9 - Проверка СЛУЧАЯ
if ( $PatientPersonalData['patid_casestatus']>0 )
{

} else {
	$icon_case_personal_ok = false;
	$icon_case_personal_data[] = '`Случай`';
}

if ( $icon_case_personal_ok === false )
{
	$icons_case .= $icon_case_personal;
}