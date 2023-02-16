<?php
if ( $Patient['journal_visit_type'] == 1 )
{
	// 1 - ВРЕМЯ
	if ( strlen($Patient['journal_time'])>0 )
	{
	
	} else {
		$icon_case_journal_ok = false;
		$icon_case_journal_data[] = '`Время приёма`';
	}

// 2 - МКБ
	if ( strlen($Patient['journal_ds'])>0 )
	{
	
	} else {
		$icon_case_journal_ok = false;
		$icon_case_journal_data[] = '`МКБ`';
	}

// 3 - Диагноз
	if ( strlen($Patient['journal_ds_text'])>0 )
	{
	
	} else {
		$icon_case_journal_ok = false;
		$icon_case_journal_data[] = '`Текст диагноза`';
	}

// 4 - ИСХОД
	if ( strlen($Patient['journal_recom'])>0 )
	{
	
	} else {
		$icon_case_journal_ok = false;
		$icon_case_journal_data[] = '`Исход`';
	}

// 5 - РЕКОМЕНДАЦИИ
	if ( strlen($Patient['journal_ds_recom'])>0 )
	{
	
	} else {
		$icon_case_journal_ok = false;
		$icon_case_journal_data[] = '`Рекомендации`';
	}

// 6 - СЛУЧАЙ
	if ( $Patient['journal_infirst']>0 )
	{
	
	} else {
		$icon_case_journal_ok = false;
		$icon_case_journal_data[] = '`Первичность случая`';
	}

// 7 - МЕСТО КАРТЫ
	if ( strlen($Patient['journal_cardplace'])>0 )
	{
	
	} else {
		$icon_case_journal_ok = false;
		$icon_case_journal_data[] = '`Место карты`';
	}

// 8 - КУДА НАПРАВЛЕН ПАЦИЕНТ
	if ( (int)$Patient['journal_dirstac']>-1 )
	{
		if ((int)$Patient['journal_dirstac']==6)
		{
			if ( strlen($Patient['journal_dirstac_desc']) > 0 )
			{
			
			}  else
			{
				$icon_case_journal_ok = false;
				$icon_case_journal_data[] = '`Название учреждения`';
			}
		}
	} else {
		$icon_case_journal_ok = false;
		$icon_case_journal_data[] = '`Направлен ли куда-то пациент`';
	}
	
	if ( $icon_case_journal_ok === false )
	{
		$icons_case .= $icon_case_journal;
	}
}

