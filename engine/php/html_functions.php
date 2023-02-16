<?php



function html_title_begin($params = array())
{
    require_once ( "engine/html/title_begin.php" );
}

function html_title_end($params = array())
{
	require_once ( "engine/html/title_end.php" );
}

function html_img_gen($src, $attr, $isReturn=false)
{
    $img = '<img src="'.$src.'" '.$attr.'>';
    if ( $isReturn )
    {
    	return $img;
    } else
    {
    	echo $img;
    }
}

require_once "table_constructor.php";

function checkbox_switcher($options = [])
{
	if ( count($options['mye']) > 0 )
	{
		$mye_unixfield = ( strlen($options['mye']['unixfield']) ) ? 'data-unixfield="'.$options['mye']['unixfield'].'"' : '';
		return '
			<label class="checkbox-green">
		        <input type="checkbox"
		        	id="'.$options['mye']['field'].'"
					class="mysqleditor '.$options['class'].'"
					data-action="edit"
					data-table="'.$options['mye']['table'].'"
					data-assoc="0"
					data-fieldid="'.$options['mye']['field_id'].'"
					data-id="'.$options['mye']['id'].'"
					data-field="'.$options['mye']['field'].'"
					'.$mye_unixfield.'
					placeholder="'.$options['placeholder'].'"
					'.$options['addon'].'
		        >
		        <span class="checkbox-green-switch"
		              data-label-on="Да"
		              data-label-off="Нет"
		        ></span>
		    </label>
		';
	} else
	{
		if ( strlen($options['values']) > 0 )
		{
		    $values_data = explode(';', $options['values']);
		}
		return '
			<input type="hidden" name="'.$options['name'].'" value="'.$values_data[0].'">
			<label class="checkbox-green">
		        <input type="checkbox"
		        	name="'.$options['name'].'"
		        	id="'.$options['id'].'"
					class="'.$options['class'].'"
					name="'.$options['value'].'"
					placeholder="'.$options['placeholder'].'"
					value="'.$values_data[1].'"
					'.$options['addon'].'
		        >
		        <span class="checkbox-green-switch" '.super_bootstrap_tooltip($options['placeholder']).'
		              data-label-on="Да"
		              data-label-off="Нет"
		        ></span>
		    </label>
		';
	}
	
}

function patient_dead($Patient)
{
	$dead_date = ( strlen($Patient['patid_exitus_date']) > 0 ) ? ': ' . $Patient['patid_exitus_date'] : '';
	return ( $Patient['patid_isDead'] ) ? '<span '.super_bootstrap_tooltip('Пациент умер' . $dead_date).'>'.BT_ICON_PATIENT_DEAD.'</span>' : '';
}

function patient_mark($Patient)
{
	return ( strlen($Patient['patid_mark']) > 0 ) ? '<span '.super_bootstrap_tooltip($Patient['patid_mark']).'>'.BT_ICON_CASE_WARNING.'</span>' : '';
}

function tab_menu_begin($tab_id)
{
	return '<ul class="nav nav-tabs" id="'.$tab_id.'-tab-menu" role="tablist">';
}
function tab_menu_end()
{
    return '</ul>';
}
function tab_menu_item($item_id, $title, $addon_a='', $addon_li='', $selected_bool=false, $active_bool=false, $class_li='', $addon_a_class='')
{
	$selected = ( $selected_bool ) ? 'true' : 'false';
	$active = ( $active_bool ) ? 'active' : '';
    return '
    <li '.$addon_li.' class="nav-item '.$class_li.'" role="presentation">
	    <a '.$addon_a.' class="nav-link '.$active.' '.$addon_a_class.'" id="'.$item_id.'-tab" data-toggle="tab" href="#'.$item_id.'" role="tab" aria-controls="'.$item_id.'" aria-selected="'.$selected.'">'.$title.'</a>
	</li>
    ';
}
function tab_content_begin($content_id)
{
    return '<div class="tab-content" id="'.$content_id.'-tab-content">';
}
function tab_end()
{
    return '</div>';
}
function tab_pane_begin($pane_id, $fade_bool = false, $show_bool = false, $active_bool = false)
{
	$fade = ( $fade_bool ) ? 'fade' : '';
	$show = ( $show_bool ) ? 'show' : '';
	$active = ( $active_bool ) ? 'active' : '';
    return '<div class="tab-pane '.$fade.' '.$show.' '.$active.'" id="'.$pane_id.'" role="tabpanel" aria-labelledby="'.$pane_id.'-tab">';
}

function make_selector($arr, $selected, $myehtml, $cb, $choose, $valueField, $is_mse=true, $name=null, $full_class='' )
{
	if ( $is_mse )
	{
		if ( strlen($myehtml) == 0 )
		{
		    return false;
		}
	}
	$DoctorSelectorDefault = array(
		'key' => 0,
		'value' => $choose
	);
	$DoctorSelectorSelected = array(
		'value' => $selected
	);
	$DoctorSelectorSelector = array2select(
		$arr,
		$valueField,
		$cb,
		null,
		'class="'.$full_class.'" ' . $myehtml,
		$DoctorSelectorDefault,
		$DoctorSelectorSelected);
	return $DoctorSelectorSelector['result'];
}


/**
 *
 * Новый элемент формы в стиле MySQLEditor
 *
 * @param string $title название
 * @param string $input_ident id=""
 * @param string $addon_class добавочный класс
 * @param string $fieldid поле индекса
 * @param string $field редактируемое поле
 * @param string $table таблица
 * @param string $id id записи
 * @param string $unixfield обновляемое поле unixfield
 * @param string $placeholder placeholder=""
 * @param string $content дефолтное содержимое
 * @param string $attributes добавочные атрибуты поля
 * @param string $type тип поля
 * @param array $selector массив внутри ['data'] с данными для селектора
 * @param null $select_content уже готовый селект извне
 * @param bool $force_output вывод в echo. Если false - будет return
 * @return string
 */
function NewFormItem($title,
                     $input_ident,
                     $addon_class,
                     $fieldid,
                     $field,
                     $table,
                     $id,
                     $unixfield,
                     $placeholder,
                     $content,
                     $attributes = '',
                     $type = 'textarea',
                     $selector = [],
                     $select_content = null,
                     $force_output = true,
                     $col_value='3')
{
	$OutputHTML = '';
	if ($type == "textarea")
	{
		$OutputHTML .= '<div class="form-group row">
		    <div class="col-'.$col_value.' font-weight-bolder">' . $title . '</div>
		    <div class="col">
		        <textarea
		            rows="4"
					name="' . $input_ident . '"
					id="' . $input_ident . '"
					class="form-control form-control-sm mysqleditor ' . $addon_class . '"
					' . $attributes . '
					data-action="edit"
					data-table="' . $table . '"
					data-assoc="0"
					data-fieldid="' . $fieldid . '"
					data-id="' . $id . '"
					data-field="' . $field . '"
					data-unixfield="' . $unixfield . '"
					placeholder="' . $placeholder . '">' . $content . '</textarea>
		    </div>
		</div>';
		
	} elseif ($type == "input")
	{
		$OutputHTML .= '<div class="form-group row">
		    <div class="col-'.$col_value.' font-weight-bolder">' . $title . '</div>
		    <div class="col">
		        <input
		        	type="text"
					name="' . $input_ident . '"
					id="' . $input_ident . '"
					class="form-control form-control-sm mysqleditor ' . $addon_class . '"
					' . $attributes . '
					data-action="edit"
					data-table="' . $table . '"
					data-assoc="0"
					data-fieldid="' . $fieldid . '"
					data-id="' . $id . '"
					data-field="' . $field . '"
					data-unixfield="' . $unixfield . '"
					placeholder="' . $placeholder . '"
					value="' . $content . '"
				>
		    </div>
		</div>';
	} elseif ($type == "date")
	{
		$OutputHTML .= '<div class="form-group row">
		    <div class="col-'.$col_value.' font-weight-bolder">' . $title . '</div>
		    <div class="col">
		        <input
		        	type="date"
					name="' . $input_ident . '"
					id="' . $input_ident . '"
					class="form-control form-control-sm mysqleditor ' . $addon_class . '"
					' . $attributes . '
					data-action="edit"
					data-table="' . $table . '"
					data-assoc="0"
					data-fieldid="' . $fieldid . '"
					data-id="' . $id . '"
					data-field="' . $field . '"
					data-unixfield="' . $unixfield . '"
					placeholder="' . $placeholder . '"
					value="' . $content . '"
				>
		    </div>
		</div>';
	} elseif ($type == "select")
	{
		if ( !$select_content )
		{
			$defaultArr = array(
				'value' => $content
			);
			$defaultSelect = array(
				'key' => 'value',
				'value' => $content
			);
			$SELECT = array2select($selector['data'], 'value', 'title', $field, 'class="form-control form-control-sm mysqleditor ' . $addon_class . '" data-action="edit"
		data-table="'.CAOP_DAILY.'"
		data-assoc="0"
		data-fieldid="'.$fieldid.'"
		data-id="'.$id.'"
		data-field="'.$field.'"
		data-unixfield="'.$unixfield.'"', $defaultArr, $defaultSelect);
			$OutputHTML .=  '<div class="form-group row">
		    <div class="col-'.$col_value.' font-weight-bolder">' . $title . '</div>
		    <div class="col">
		        ' . $SELECT['result'] . '
		    </div>
		</div>';
		} else
		{
			$OutputHTML .=  '<div class="form-group row">
		    <div class="col-'.$col_value.' font-weight-bolder">' . $title . '</div>
		    <div class="col">
		        ' . $select_content . '
		    </div>
		</div>';
		}
		
	} else
	{
		$OutputHTML .= bt_notice('Неверный тип поля ввода', BT_THEME_DANGER, 1);
	}
	if ($force_output)
	{
		echo $OutputHTML;
	} else
	{
		return $OutputHTML;
	}
}

function text_muted($txt, $is_small=true)
{
	$small = ($is_small) ? ' small' : '';
    return '<span class="text-muted'.$small.'">'.$txt.'</span>';
}