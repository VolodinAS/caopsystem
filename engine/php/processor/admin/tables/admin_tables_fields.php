<?php
$response['stage'] = $action;
$response['msg'] = 'begin';

$HTTP = $_POST;
extract($HTTP, EXTR_PREFIX_SAME, '_caop');

$TableRM = RecordManipulation($table, CAOP_TABLES, 'table_id');
if ( $TableRM['result'] )
{
	$TableData = $TableRM['data'];
	
	switch ($act)
	{
	    case "edit":
		
		    $Fields = getarr(CAOP_TABLE_FIELDS, "field_table_id='{$TableData['table_id']}'", "ORDER BY field_order ASC");
		    
		    $FieldEnd = end($Fields);
		    
		    $response['debug']['$FieldEnd'] = $FieldEnd;
		    $ORDER = 0;
		    if ( isset($FieldEnd['field_order']) )
		    {
			    $ORDER = (int)$FieldEnd['field_order'] + 1;
		    }
	    	
		    $response['htmlData'] .= '<h5>Редактирование полей таблицы "'.$TableData['table_title'].'"</h5>';
		
		    $response['htmlData'] .= '<button class="btn btn-sm btn-primary" type="button" onclick="javascript:updateEditField()">Обновить</button>';
		
		    $response['htmlData'] .= spoiler_begin_return('Добавить новое поле', 'table-new-field');
		    $response['htmlData'] .= '
			<form id="form-new-field">
				<div class="form-group row">
					<label for="field_title" class="col-sm-3 col-form-label">Отображаемое название столбца</label>
		            <div class="col-sm">
		                <input class="form-control form-control-sm" type="text" name="field_title" id="field_title" placeholder="Отображаемое название столбца">
		            </div>
		        </div>
				<div class="form-group row">
					<label for="field_title_full" class="col-sm-3 col-form-label">Полное название столбца</label>
		            <div class="col-sm">
		                <input class="form-control form-control-sm" type="text" name="field_title_full" id="field_title_full" placeholder="Полное название столбца">
		            </div>
		        </div>
				<div class="form-group row">
					<label for="field_type" class="col-sm-3 col-form-label">Тип столбца</label>
		            <div class="col-sm">
		                <input class="form-control form-control-sm" type="text" name="field_type" id="field_type" placeholder="Тип столбца (text, date)" value="text">
		            </div>
		        </div>
				<div class="form-group row">
					<label for="field_sorted" class="col-sm-3 col-form-label">Сортировать?</label>
		            <div class="col-sm">
		                <input class="form-control form-control-sm" type="text" name="field_sorted" id="field_sorted" placeholder="Сортировать? (1/0)" value="1">
		            </div>
		        </div>
				<div class="form-group row">
					<label for="field_attr" class="col-sm-3 col-form-label">Атрибуты</label>
		            <div class="col-sm">
		                <input class="form-control form-control-sm" type="text" name="field_attr" id="field_attr" placeholder="Атрибуты (param1=val1 param2=val2)">
		            </div>
		        </div>
				<div class="form-group row">
					<label for="field_class" class="col-sm-3 col-form-label">Классовые параметры</label>
		            <div class="col-sm">
		                <input class="form-control form-control-sm" type="text" name="field_class" id="field_class" placeholder="Классовые параметры (mysqleditor russianBirth mkbDiagnosis)" value="mysqleditor">
		            </div>
		        </div>
				<div class="form-group row">
					<label for="field_class" class="col-sm-3 col-form-label">Порядок</label>
		            <div class="col-sm">
		                <input class="form-control form-control-sm" type="text" name="field_order" id="field_order" placeholder="Порядок расположения столбцов (1, 2, 3 и т.д.)" value="'.$ORDER.'">
		            </div>
		        </div>
				<div class="form-group row">
					<button type="button" class="btn btn-sm btn-success col" onclick="javascript:addNewField(event)">Сохранить</button>
		        </div>
			</form>
			
			';
		    $response['htmlData'] .= spoiler_end_return();
		
		    $response['htmlData'] .= '<div class="dropdown-divider"></div>';
		
		    if ( count($Fields) > 0 )
		    {
			    foreach ($Fields as $field)
			    {
				    $response['htmlData'] .= spoiler_begin_return('[ID:'.$field['field_id'].'] ' . wrapper($field['field_title']) . ' (тип: '.$field['field_type'].', порядок: '.$field['field_order'].')', 'field_id' . $field['field_id']);
				
				    $response['htmlData'] .= '<button type="button" class="btn btn-warning btn-sm" onclick="javascript:deleteField(event, '.$field['field_id'].')" data-fieldid="'.$field['field_id'].'">Удалить</button><div class="dropdown-divider"></div>';
				    
			    	$response['htmlData'] .= '
			    	Отображаемое название столбца: <input type="text" class="form-control form-control-sm mysqleditor" name="edit_field_title" id="edit_field_title" value="'.$field['field_title'].'"
			    	data-action="edit"
			    	data-table="'.CAOP_TABLE_FIELDS.'"
			    	data-assoc="0" 
			    	data-fieldid="field_id" 
			    	data-id="'.$field['field_id'].'" 
			    	data-field="field_title">';
				    
			    	$response['htmlData'] .= '
			    	Полное название столбца: <input type="text" class="form-control form-control-sm mysqleditor" name="edit_field_title_full" id="edit_field_title_full" value="'.$field['field_title_full'].'"
			    	data-action="edit"
			    	data-table="'.CAOP_TABLE_FIELDS.'"
			    	data-assoc="0"
			    	data-fieldid="field_id"
			    	data-id="'.$field['field_id'].'"
			    	data-field="field_title_full">';
			    	
			    	$response['htmlData'] .= '
			    	Тип столбца: <input type="text" class="form-control form-control-sm mysqleditor" name="edit_field_title" id="edit_field_title" value="'.$field['field_type'].'"
			    	data-action="edit"
			    	data-table="'.CAOP_TABLE_FIELDS.'"
			    	data-assoc="0"
			    	data-fieldid="field_id"
			    	data-id="'.$field['field_id'].'"
			    	data-field="field_type">';
			    	
			    	$response['htmlData'] .= '
			    	Сортировка столбца: <input type="text" class="form-control form-control-sm mysqleditor" name="edit_field_sorted" id="edit_field_sorted" value="'.$field['field_sorted'].'"
			    	data-action="edit"
			    	data-table="'.CAOP_TABLE_FIELDS.'"
			    	data-assoc="0"
			    	data-fieldid="field_id"
			    	data-id="'.$field['field_id'].'"
			    	data-field="field_sorted">';
			    	
			    	$response['htmlData'] .= '
			    	Атрибуты столбца: <input type="text" class="form-control form-control-sm mysqleditor" name="edit_field_attr" id="edit_field_attr" value="'.$field['field_attr'].'"
			    	data-action="edit"
			    	data-table="'.CAOP_TABLE_FIELDS.'"
			    	data-assoc="0"
			    	data-fieldid="field_id"
			    	data-id="'.$field['field_id'].'"
			    	data-field="field_attr">';
			    	
			    	$response['htmlData'] .= '
			    	Класс столбца: <input type="text" class="form-control form-control-sm mysqleditor" name="edit_field_class" id="edit_field_class" value="'.$field['field_class'].'"
			    	data-action="edit"
			    	data-table="'.CAOP_TABLE_FIELDS.'"
			    	data-assoc="0"
			    	data-fieldid="field_id"
			    	data-id="'.$field['field_id'].'"
			    	data-field="field_class">';
			    	
			    	$response['htmlData'] .= '
			    	Класс редактирования: <input type="text" class="form-control form-control-sm mysqleditor" name="edit_field_mysqleditor" id="edit_field_mysqleditor" value="'.$field['field_mysqleditor'].'"
			    	data-action="edit"
			    	data-table="'.CAOP_TABLE_FIELDS.'"
			    	data-assoc="0"
			    	data-fieldid="field_id"
			    	data-id="'.$field['field_id'].'"
			    	data-field="field_mysqleditor">';
			    	
			    	$response['htmlData'] .= '
			    	Порядок столбца: <input type="text" class="form-control form-control-sm mysqleditor" name="edit_field_title" id="edit_field_title" value="'.$field['field_order'].'"
			    	data-action="edit"
			    	data-table="'.CAOP_TABLE_FIELDS.'"
			    	data-assoc="0"
			    	data-fieldid="field_id"
			    	data-id="'.$field['field_id'].'"
			    	data-field="field_order">';
			    	
			    	$response['htmlData'] .= spoiler_end_return();
			    }
		    } else
		    {
			    $response['htmlData'] .= bt_notice('Нет полей. Воспользуйтесь формой добавления полей', BT_THEME_WARNING, 1);
		    }
		    $response['result'] = true;
	    break;
	    case "new":
			$param_values = $fields;
			$param_values['field_table_id'] = $table;
			
			$NewField = appendData(CAOP_TABLE_FIELDS, $param_values);
			if ( $NewField[ID] > 0 )
			{
				$response['result'] = true;
			} else
			{
				$response['msg'] = 'Ошибка создания поля';
				$response['debug']['$NewField'] = $NewField;
			}
	    break;
		case "delete":
	        $DeleteField = deleteitem(CAOP_TABLE_FIELDS, "field_table_id='{$table}' AND field_id='{$field_id}'");
	        if ($DeleteField ['result'] === true)
	        {
		        $response['result'] = true;
	        } else
	        {
	        	$response['msg'] = 'Ошибка удаления поля';
	        	$response['debug']['$DeleteField'] = $DeleteField;
	        }
		break;
	}
	
	
	
	
} else $response['msg'] = $TableRM['msg'];