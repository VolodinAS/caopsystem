<?php
$REPORT = array(
	array(
	    'list' => 0,
		'list_title' => 'Деятельность ЦАОП',
		'data' => array(
			array(
				'title' => 'Всего посещений в Центре',
				'column_number' => 3,
				'column_coords' => 'D5',
				'possibility_of_realization' => '10 / 10',
				'accuracy' => 'Довольно точно'
			),
			array(
				'title' => 'Число физ. лиц, принятых в Центре, всего',
				'column_number' => 4,
				'column_coords' => 'E5',
				'possibility_of_realization' => '10 / 10',
				'accuracy' => 'Довольно точно'
			),
			array(
				'title' => 'Число физ. лиц принятых в Центре с подозрением на ЗНО  (только пациенты направленные с целью дообследования и установления диагноза)',
				'column_number' => 5,
				'column_coords' => 'F5',
				'possibility_of_realization' => '6 / 10',
				'accuracy' => 'Низкая точность: человеческий фактор - могут не отмечать с подозрением или нет'
			),
			array(
				'title' => 'Число физ. лиц, принятых в Центре, находящихся в процессе лечения (посещения врача-онколога перед или между курсами лечения)',
				'column_number' => 6,
				'column_coords' => 'G5',
				'possibility_of_realization' => '8 / 10',
				'accuracy' => 'Довольно точно, так как дневным стационаром занимается один человек',
				'note' => 'Прием с целью осуществления динамического наблюдения за пациентами с онкологическими заболеваниями, получающими лекарственную противоопухолевую терапию, контроль лабораторных показателей, при развитии токсических реакций'
			),
			array(
				'title' => 'Число физ. лиц, принятых в Центре, с целью проведения противоопухолевой лекарственной терапии',
				'column_number' => 7,
				'column_coords' => 'H5',
				'possibility_of_realization' => '8 / 10',
				'accuracy' => 'Довольно точно, так как дневным стационаром занимается один человек',
				'note' => ''
			),
			array(
				'title' => 'Число физ. лиц, принятых в Центре с целью диспансерного наблюдения',
				'column_number' => 8,
				'column_coords' => 'I5',
				'possibility_of_realization' => '5 / 10',
				'accuracy' => 'Низкая - не все отмечают верно диспансерных пациентов',
				'note' => ''
			),
			array(
				'title' => 'Количество пациентов c подозрением на ЗНО, которым была проведена биопсия опухоли',
				'column_number' => 9,
				'column_coords' => 'J5',
				'possibility_of_realization' => '1 / 10',
				'accuracy' => 'Очень низкая. Не все ставят отметку о подозрении, а также не ведется учет биопсий',
				'note' => 'Количество физических лиц c подозрением на ЗНО, которым была проведена биопсия в Центре, а так же если  они были направлены в другую медицинскую организацию для проведения биопсии'
			),
			array(
				'title' => 'Выявлено  ЗНО в Центре в отчетном периоде (без выявленных посмертно)',
				'column_number' => 10,
				'column_coords' => 'K5',
				'possibility_of_realization' => '8 / 10',
				'accuracy' => 'Довольно точно',
				'note' => 'Данные подтягиваются из вкладки "Выявленные случаи"'
			),
			array(
				'title' => 'Число пациентов с впервые в жизни установленным диагнозом ЗНО, взятых под диспансерное наблюдение в отчетном периоде',
				'column_number' => 11,
				'column_coords' => 'L5',
				'possibility_of_realization' => '0 / 10',
				'accuracy' => 'Разве не все встают на Д-учет, у которых было выявлено ЗНО впервые?',
				'note' => ''
			),
			array(
				'title' => 'Число впервые выявленных новообразований in situ в Центре (D00 - D09)',
				'column_number' => 12,
				'column_coords' => 'M5',
				'possibility_of_realization' => '8 / 10',
				'accuracy' => 'Выше среднего - не все могут правильно отметить in situ',
				'note' => ''
			),
			array(
				'title' => 'Кол-во пациентов, состоящих под диспансерным наблюдением в Центре',
				'column_number' => 13,
				'column_coords' => 'N5',
				'possibility_of_realization' => '9 / 10',
				'accuracy' => 'Довольно точно',
				'note' => ''
			),
		)
	),
	array(
	    'list' => 1,
		'list_title' => 'Деятельность ДС',
		'data' => array(
			array(
				'title' => 'Количество пациенто-мест',
				'column_number' => 2,
				'column_coords' => 'C4',
				'possibility_of_realization' => '---',
				'accuracy' => 'Постоянная величина',
				'value' => 4,
				'note' => 'Кол-во пациенто мест = кол-во коек *  кол-во смен коек'
			),
			array(
				'title' => 'Всего число госпитализаций в  дневной стационар ЦАОПа',
				'column_number' => 3,
				'column_coords' => 'D4',
				'possibility_of_realization' => '10/10',
				'accuracy' => 'Довольно точно',
				'value' => null,
				'note' => ''
			),
			array(
				'title' => 'Из них, число госпитализаций с целью проведения симптоматического и иных видов лечения',
				'column_number' => 6,
				'column_coords' => 'G4',
				'possibility_of_realization' => '10/10',
				'accuracy' => 'Дублирует D4',
				'value' => null,
				'copy_of' => 'D4',
				'note' => ''
			),
			array(
				'title' => 'Число пользованных пациентов, всего',
				'column_number' => 7,
				'column_coords' => 'H4',
				'possibility_of_realization' => '10/10',
				'accuracy' => 'Вычисляется по формуле',
				'value' => null,
				'copy_of' => null,
				'formula' => 'used_patients',
				'note' => 'За отчетный период, (число госпитализированных пациентов + число выписанных + умерших)/2'
			),
			array(
				'title' => 'Проведено пациентами дней лечения',
				'column_number' => 8,
				'column_coords' => 'I4',
				'possibility_of_realization' => '10/10',
				'accuracy' => 'Вычисляется по формуле',
				'value' => null,
				'copy_of' => null,
				'formula' => 'days_summary',
				'note' => 'Общее число дней лечения, проведенных пациентами в дневном стационаре Центра за отчетный период'
			),
		)
	),
);

/**
 * ДАННЫЕ ПАРСЯТСЯ ИЗ ТАБЛИЦЫ ГАРКАВОГО
 */

$REPORT_STADIES = array(
	'I стадия' => array(
		'row_id' => 5
	),
	'II стадия' => array(
		'row_id' => 6
	),
	'III стадия' => array(
		'row_id' => 7
	),
	'Без стадии' => array(
		'row_id' => 8
	),
	'Всего' => array(
		'row_id' => 9
	)
);

$ZNO_REPORT = array(
	'list' => 4,
	'list_title' => 'Выявленные случаи',
	'data' => array(
		array(
			'title' => 'Выявлено ЗНО - всего, из них',
			'mkb' => 'C00 - C96',
			'column_number' => 3,
			'column' => 'D'
		),
		array(
			'title' => 'губы',
			'mkb' => 'C00',
			'column_number' => 4,
			'column' => 'E',
		),
		array(
			'title' => 'полости рта',
			'mkb' => 'C01 - C09',
			'column_number' => 5,
			'column' => 'F',
		),
		array(
			'title' => 'глотки',
			'mkb' => 'C10 - C13',
			'column_number' => 6,
			'column' => 'G',
		),
		array(
			'title' => 'пищевода',
			'mkb' => 'C15',
			'column_number' => 7,
			'column' => 'H',
		),
		array(
			'title' => 'желудка',
			'mkb' => 'C16',
			'column_number' => 8,
			'column' => 'I',
		),
		array(
			'title' => 'ободочной кишки',
			'mkb' => 'C18',
			'column_number' => 9,
			'column' => 'J',
		),
		array(
			'title' => 'прямой кишки, ректосигмоидного соединения, ануса',
			'mkb' => 'C19 - C21',
			'column_number' => 10,
			'column' => 'K',
		),
		array(
			'title' => 'печени и внутрипеченочных желчных протоков',
			'mkb' => 'C22',
			'column_number' => 11,
			'column' => 'L',
		),
		array(
			'title' => 'поджелудочной железы',
			'mkb' => 'C25',
			'column_number' => 12,
			'column' => 'M',
		),
		array(
			'title' => 'гортани',
			'mkb' => 'C32',
			'column_number' => 13,
			'column' => 'N',
		),
		array(
			'title' => 'трахеи, бронхов, легкого',
			'mkb' => 'C33, C34',
			'column_number' => 14,
			'column' => 'O',
		),
		array(
			'title' => 'костей и суставных хрящей',
			'mkb' => 'C40, C41',
			'column_number' => 15,
			'column' => 'P',
		),
		array(
			'title' => 'меланома кожи',
			'mkb' => 'C43',
			'column_number' => 16,
			'column' => 'Q',
		),
		array(
			'title' => 'других новообразований кожи',
			'mkb' => 'C44',
			'column_number' => 17,
			'column' => 'R',
		),
		array(
			'title' => 'соединительной и других мягких тканей',
			'mkb' => 'C47, C49',
			'column_number' => 18,
			'column' => 'S',
		),
		array(
			'title' => 'молочной железы',
			'mkb' => 'C50',
			'column_number' => 19,
			'column' => 'T',
		),
		array(
			'title' => 'шейки матки',
			'mkb' => 'C53',
			'column_number' => 20,
			'column' => 'U',
		),
		array(
			'title' => 'тела матки',
			'mkb' => 'C54',
			'column_number' => 21,
			'column' => 'V',
		),
		array(
			'title' => 'яичника',
			'mkb' => 'C56',
			'column_number' => 22,
			'column' => 'W',
		),
		array(
			'title' => 'предстательной железы',
			'mkb' => 'C61',
			'column_number' => 23,
			'column' => 'X',
		),
		array(
			'title' => 'почки',
			'mkb' => 'C64',
			'column_number' => 24,
			'column' => 'Y',
		),
		array(
			'title' => 'мочевого пузыря',
			'mkb' => 'C67',
			'column_number' => 25,
			'column' => 'Z',
		),
		array(
			'title' => 'щитовидной железы',
			'mkb' => 'C73',
			'column_number' => 26,
			'column' => 'AA',
		),
		array(
			'title' => 'злокачественные лимфомы',
			'mkb' => 'C81 - C86, C88, C90, C96',
			'column_number' => 27,
			'column' => 'AB',
		),
		array(
			'title' => 'лейкозы',
			'mkb' => 'C91 - C95',
			'column_number' => 28,
			'column' => 'AC',
		),
		array(
			'title' => 'Прочие',
			'mkb' => '__non_included__',
			'column_number' => 29,
			'column' => 'AD',
		),
		// INSITU остались
	)
);
// создать парсер промежутков МКБ с последующим преобразованием в ID или строки с названиями, включая паттерны
debug($REPORT);
debug($REPORT_STADIES);
debug($ZNO_REPORT);

//$doctor = ORM::for_table(CAOP_DOCTOR)->where('doctor_id', 1)->find_one();
//print_r($doctor);
//print_r($doctor->doctor_f);