<?php
$query_rs = "
SELECT
       patid_name,
       patid_birth,
       rs_ds_mkb,
       rs_ds_text,
       rs_stadia,
       rs_tnm_t,
       rs_tnm_n,
       rs_tnm_m,
       patid_address,
       patid_phone,
       DATE_FORMAT(str_to_date(rs_fill_date, '%d.%m.%Y'), '%d.%m.%Y') as fill_date
FROM ".CAOP_ROUTE_SHEET."
LEFT JOIN ".CAOP_PATIENTS." ON ".CAOP_PATIENTS.".patid_id = ".CAOP_ROUTE_SHEET.".rs_patid
ORDER BY fill_date DESC
";
$result_rs = mqc($query_rs);
$rs = mr2a($result_rs);

$header = [
	'Имя',
	'Дата рождения',
	'МКБ',
	'Диагноз',
	'Стадия',
	'T',
	'N',
	'M',
	'Адрес',
	'Телефон',
	'Дата заполнения'
];
array_unshift($rs, $header);
//debug($rs);
$options = [
	'width' => '100%',
	'class' => 'table table-sm small',
	'header_index' => 0,
	'row_npp' => true
];
echo table_generator($rs, $options);
