<?php

/**
 *
 * Генератор таблицы из массива
 *
 * $options = array(
 *      'width' => 'ширина таблицы'
 *      'class' => 'класс таблицы'
 *      'border' => 'границы'
 *      'addon' => 'доп. атрибуты таблицы'
 *      'num_str' => 'включить нумерацию строк?',
 *      'header_index' => 'индекс заголовков'
 *      'if_header_index' => 'использовать header, только если указан header_index'
 *      'row_npp' => 'номера строк по порядку'
 *      'ARG' => 'DESC'
 * );
 *
 * @param $table_arr
 * @param array $options
 * @return false|string
 */
function table_generator($table_arr, $options=[])
{
	$width = ( isset($options['width']) ) ? 'width="'.$options['width'].'"' : '';
	$border = ( isset($options['border']) ) ? 'border="'.$options['width'].'"' : 'border="1"';
	$class = ( isset($options['class']) ) ? $options['class'] : '';
	
	$table = '';
	$rows = count($table_arr);
	if ( $rows > 0 )
	{
		$table = '<table '.$width.' class="tbc '.$class.'" '.$border.' '.$options['addon'].'>';
		$is_first = true;
		$npp = 1;
		foreach ($table_arr as $row_index=>$row_data)
		{
			$header = '';
			if ( $options['header'] )
			{
				if ( $options['if_header_index'] )
				{
				
				} else
				{
					if ( $is_first )
					{
						$is_first = false;
						$header = 'align="center" class="font-weight-bolder"';
					}
				}
				
			}
			
			if ( (int)$options['header_index'] == $row_index )
			{
				$header = 'align="center" class="font-weight-bolder"';
			}
			
			$num_str = ( $options['num_str'] === true ) ? '<td width="1%" class="bg-info">'.nbsper('[Строка ' . wrapper($npp) . ']').'</td>' : '';
			
			$table .= '
			<tr id="table_gen_row_'.$row_index.'" data-hi="'.$options['header_index'].'" data-hr="'.$options['header'].'">
				'.$num_str.'
			';
			if ($row_index == 0)
			{
				$table .= '<td width="1%" '.$header.'>№</td>';
			} else
			{
				$table .= '<td align="center">'.($npp-1).'</td>';
			}
			foreach ($row_data as $col_index=>$col_data)
			{
				$table .= '
				<td '.$header.' id="table_gen_col_'.$col_index.'">
				';
				$table .= $col_data;
				$table .= '
				</td>
				';
			}
			$table .= '
			</tr>
			';
			$npp++;
		}
		$table .= '</table>';
	    return $table;
	} else return false;
}

/**
 *
 * Генерация массива из таблицы
 *
 * @param integer $table_id ID-таблицы
 * @param array $fields поля (если пусто - то все)
 * @param bool $headers заголовки
 * @param int $offset изначальный отступ
 * @param int $rows количество строк (0 - все строки)
 * @return bool
 */
function table_to_array($table_id, $headers = true, $fields = [], $offset = 0, $rows = 10)
{
	global $PK;
	$table = false;
	$TableData = getarr(CAOP_TABLES, $PK[CAOP_TABLES] . "='{$table_id}'");
	if ( count($TableData) > 0 )
	{
	    $TableData = $TableData[0];
	    
	    $TableFields = getarr(CAOP_TABLE_FIELDS, "field_table_id='{$TableData['table_id']}'");
	    
	    if ( count($TableFields) > 0 )
	    {
		    $table['data'] = array();
		    
		    if ( $headers )
		    {
			    $table['headers'] = array();
			
			    foreach ($TableFields as $tableField)
			    {
				    $table['headers'][] = $tableField['field_title_full'];
			    }
		    }
	    	
		    
		    $TableCells = getarr(CAOP_TABLE_CELLS, "cell_table_id='{$TableData['table_id']}'", "ORDER BY cell_id ASC");
		
		    if ( count($TableCells) > 0 )
		    {
			    $TableCellsContent = table_record_divider($TableCells, 'cell_record_id');
				
			    foreach ($TableCellsContent as $record_id => $record_data)
			    {
			    	
//			    	$table['data'][$record_id] = $record_data;
				    foreach ($record_data as $record_field_item)
				    {
					    $table['data'][$record_id][] = $record_field_item['cell_content'];
				    }
			    	
			    }
			    
//			    $table['data'] = $TableCellsContent;
		    }
	    } else return false;
	} else return false;
	return $table;
}

function table_constructor_row_content($table_id, $npp, $record_id, $record_data, $FIELDS)
{
	$result = '<tr data-recordid="'.$record_id.'" id="viewer_row_'.$record_id.'">';
	
	$result .= '<td class="text-center" data-cell="#">
											                <input class="form-check-input recordsItemCB" type="checkbox" name="recordsItem'.$record_id.'" id="recordsItem'.$record_id.'" data-id="'.$record_id.'" value="'.$record_id.'" >
											                <label class="form-check-label box-label" for="recordsItem'.$record_id.'"><span></span></label>
											            </td>';
	$result .= '<td class="text-center">'.$npp.'</td>';
	
	foreach ($FIELDS as $FIELD)
	{
		$find_cell = searchArray($record_data, 'cell_field_id', $FIELD['field_id']);
		if ( $find_cell['status'] == RES_SUCCESS )
		{
			$cell_data = $find_cell['data'];
			
			$dataattr_action = "edit";
			$dataattr_id = $cell_data['cell_id'];
		} else
		{
			$cell_data = [];
			
			$dataattr_action = "add";
			$dataattr_id = -1;
		}
		
		$preset_params = array(
			'cell_table_id' =>  $table_id,
			'cell_record_id'    =>  $record_id,
			'cell_field_id' =>  $FIELD['field_id']
		);
		
		$result .= '<td class="'.$FIELD['field_class'].' mysqleditor-realtime"
							    data-rttable="'. CAOP_TABLE_CELLS .'"
							    data-rtaction="'.$dataattr_action.'"
							    data-rtfieldid="cell_id"
							    data-rtid="'.$dataattr_id.'"
							    data-rtfield="cell_content"
							    data-rtpreset="'.base64_encode(http_build_query($preset_params)).'"
							    data-rtreturn="#t'.$table_id.'_r'.$record_id.'_f'.$FIELD['field_id'].'"
							    data-rtreturntype="html"
							    data-defaultvalue="'.$cell_data['cell_content'].'"
							    data-rtaddonclass="'.$FIELD['field_mysqleditor'].'"
							    data-rtcallbackfunc="ChangeMySQLEditorActionToEdit"
							    data-rtcallbackcond="success"
							    data-rtcallbackparams="#t'.$table_id.'_r'.$record_id.'_f'.$FIELD['field_id'].'"
							    data-rtupdatedefaultvalue="1"
							    data-rtupdatedefaultvaluedom="#t'.$table_id.'_r'.$record_id.'_f'.$FIELD['field_id'].'"
							    data-inputtype="'.$FIELD['field_type'].'"
							    id="t'.$table_id.'_r'.$record_id.'_f'.$FIELD['field_id'].'">
							        '.$cell_data['cell_content'].'
						        </td>';
	}
	
	$result .= '<td class="text-center">Действия</td>';
	
	$result .= '</tr>';
	
	return $result;
	
}

function table_constructor_cell_field($field_data)
{
	$field_attr = stripcslashes($field_data['field_attr']);
	$sorterFalse = ( (int)$field_data['field_sorted'] == 0) ? ' sorter-false' : '';
	return '<th scope="col" '.$field_attr.' class="text-center'.$sorterFalse.'" '.super_bootstrap_tooltip($field_data['field_title_full']).'>'.$field_data['field_title'].'</th>';
}

function table_constructor($table_id, $table_of_tables, $table_of_fields, $table_of_cells)
{
	$response = [];
	$response['result'] = false;
	if ($table_id > 0)
	{
		if ( strlen($table_of_tables) > 0 && strlen($table_of_fields) > 0 && strlen($table_of_cells) > 0)
		{
		 
			$TABLE = getarr($table_of_tables, "table_id='{$table_id}'");
			if ( count($TABLE) > 0 )
			{
			    $TABLE = $TABLE[0];
			    $FIELDS = getarr($table_of_fields, "field_table_id='{$TABLE['table_id']}'", "ORDER BY field_order ASC");
			    if ( count($FIELDS) > 0 )
			    {
			    	$count_fields = count($FIELDS);
//				    $response['htmlData'] = '';
				    
				    
				
				    $response['htmlData'] .= '
				    <table class="table table-sm table-bordered" id="table_id_'.$table_id.'">
				    	<thead>
			                <tr>
			                	<th class="text-center" scope="col" data-title="#" width="1%">
						            <input class="form-check-input checkbox-checkall" type="checkbox" id="selectAllCB" data-target=".recordsItemCB" value="1" >
						            <label class="form-check-label box-label" for="selectAllCB"><span></span></label>
						        </th>
			                	<th scope="col" class="text-center" width="1%">№</th>';
				    foreach ($FIELDS as $FIELD)
				    {
					    $response['htmlData'] .= table_constructor_cell_field($FIELD);
				    }
				    $response['htmlData'] .= '
								<th scope="col" class="text-center sorter-false">Действия</th>
			                </tr>
		                </thead>
		                <tbody>';
				
				    $CELLS = getarr($table_of_cells, "cell_table_id='{$table_id}'", "ORDER BY cell_id ASC");

				    if ( count($CELLS) > 0 )
				    {
					    $CELLS_CONTENT = table_record_divider($CELLS, 'cell_record_id');
					    $npp = 1;
					    foreach ($CELLS_CONTENT as $record_id => $record_data)
					    {
						    $response['htmlData'] .= table_constructor_row_content($table_id, $npp, $record_id, $record_data, $FIELDS);
						
						    $npp++;
					    }

//				        $response['htmlData'] .= debug_ret($CELLS_CONTENT);
				    } else
				    {
					    $response['htmlData'] .= '
				    	<tr>
							<td colspan="'.($count_fields+3).'">
							'.bt_notice(wrapper('Таблица еще пуста'), BT_THEME_WARNING, 1).'
							</td>
						</tr>
				    	';
				    }
				    
				    $response['htmlData'] .= '
						</tbody>
				    </table>
				    ';
				
//				    $response['data']['fieldsHtml'] .= $response['htmlData'];
//				    unset($response['htmlData']);
				    
			    } else $response['msg'] = 'У указанной таблицы отсутствуют поля';
			    
			}
			
		} else $response['msg'] = 'Не указаны таблицы';
	} else $response['msg'] = 'Не указан ID таблицы';
	
	return $response;
}

function table_record_divider($arr, $divide_field)
{
	$response = [];
	foreach ($arr as $item)
	{
		$response[$item[$divide_field]][] = $item;
    }
	return $response;
}

function table_delete_row($table_id, $row_id)
{
    $CheckRow = getarr(CAOP_TABLE_CELLS, "cell_table_id='{$table_id}' AND cell_record_id='{$row_id}'");
    if ( count($CheckRow) > 0 )
    {
        $DeleteRow = deleteitem(CAOP_TABLE_CELLS, "cell_table_id='{$table_id}' AND cell_record_id='{$row_id}'");
	    return $DeleteRow['result'];
    } else return false;
}