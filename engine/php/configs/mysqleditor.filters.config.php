<?php
/**
 * Обработка текстовых данных:
 * - релативный поиск %
 * - перечисление поисков через ;
 */
const MEF_TEXT = 'text';
/**
 * Обработка числовых данных:
 * - фильтр ОТ
 * - фильтр ДО
 * - фильтр ОТ и ДО
 */
const MEF_NUMBER = 'number';
/**
 * Обработка даты:
 * - работа с unix-метками
 * - фильтр ОТ
 * - фильтр ДО
 * - фильтр ОТ и ДО
 */
const MEF_DATE = 'date';
/**
 * Единичный выбор из множества доступных
 */
const MEF_RADIO = 'radio';
/**
 * Множественный выбор из множества доступных
 */
const MEF_CHECKBOX = 'checkbox';

$Filters = [
	MEF_TEXT,
	MEF_NUMBER,
	MEF_DATE,
	MEF_RADIO,
	MEF_CHECKBOX
];

function mysqleditor_filters_button($options)
{
	$button = '';
	if ( count($options) > 0 )
	{
		$DefaultFilter = $_SESSION['mef_filters'][$options['filter']];
		$icon = $options['default_icon'];
		if ( $DefaultFilter['enabled'] == 1 )
		{
			if ( count($DefaultFilter['data']) > 0 )
			{
				$icon = $options['default_icon_ok'];
			} else
			{
				$icon = $options['default_icon_empty'];
			}
		} else
		{
			$icon = $options['default_icon_close'];
		}
		$button .= '
        <button '.super_bootstrap_tooltip(implode(', ', $DefaultFilter['data'])).'
			class="'.$options['class'].' mysqleditor-filters"
		    data-filter="'.$options['filter'].'"
			data-table="'.$options['table'].'"
			data-field="'.$options['field'].'"
			data-preprocessor="'.$options['preprocessor'].'"
			data-postprocessor="'.$options['postprocessor'].'"
		    data-relatedfield="'.$options['relatedfield'].'"
			data-type="'.$options['type'].'"
			data-useequal="'.$options['use_equal'].'"
			data-queryset="'.$options['queryset'].'"
			data-header="'.$options['header'].'"
		>
			<span id="'.$options['filter'].'_icon">
		        '.$icon.'
		    </span>
		    <span id="'.$options['filter'].'_title">
		        '.$options['title'].'
		    </span>
		</button>
        ';
	} else return false;
	return $button;
}

function mysqleditor_filters_applyer($query)
{
	$Filters = $_SESSION['mef_filters'];
	$filter_query = 'WHERE 1';
	if ( count($Filters) > 0 )
	{
		foreach ($Filters as $filter)
		{
			if ( $filter['enabled'] )
			{
				if ( count($filter['data']) > 0 )
				{
					$is_first = true;
					$is_accuracy = false;
					$union = ( $filter['use_equal'] ) ? ' LIKE ' : '=';
					$uq = ( $filter['use_equal'] ) ? "'" : "";
					switch ($filter['type'])
					{
						case MEF_CHECKBOX:
						case MEF_TEXT:
							$filter_query .= ' AND (';
							foreach ($filter['data'] as $filter_data)
							{
								if ( strlen($filter['postprocessor']) > 0 )
								{
									if ( function_exists($filter['postprocessor']) )
									{
										$filter_data = call_user_func($filter['postprocessor'], $filter_data);
									} else
									{
										$filter_data = "1";
									}
								}
								if ( $is_first )
								{
									$is_first = false;
									$filter_query .= "{$filter['field']}{$union}{$uq}{$filter_data}{$uq}";
								} else
								{
									$filter_query .= " OR {$filter['field']}{$union}{$uq}{$filter_data}{$uq}";
								}
							}
							$filter_query .= ') ';
							
							break;
						case MEF_DATE:
							$filter_data = $filter['data'];
							$filter_query .= ' AND (';
							$is_accuracy = $filter_data['accuracy'] == "accuracy";
							$date_from = birthToUnix($filter_data['from']);
							$date_to = birthToUnix($filter_data['to']);
							if ( $is_accuracy )
							{
								$filter_query .= "{$filter['field']}={$date_from}";
							} else
							{
								if ( strlen($date_from) > 0 )
								{
									$filter_query .= "{$filter['field']}>={$date_from}";
								}
								if ( strlen($date_to) > 0 )
								{
									if ( strlen($date_from) > 0 )
									{
										$filter_query .= " AND ";
									}
									$filter_query .= "{$filter['field']}<={$date_to}";
								}
								
							}
							$filter_query .= ') ';
						break;
						default:
							$filter_query .= "1";
						break;
					}
					
				}
			}
	    }
	}
	return str_replace('{mysqleditor_filter}', $filter_query, $query);
}

require_once ("engine/html/modals/mysqleditor.filters/processors.php");