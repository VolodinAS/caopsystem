<?php

/**
 *
 * Функция возвращает описание доступных функций фреймворка
 *
 * @return array
 */
function mysqli_about()
{
	$aboutArr = array();
	$aboutArr['mq']				                = array('mq($query)'											                    => 'Обычный mysql_query()-запрос');
	$aboutArr['mqc']				            = array('mqc($query)'											                    => 'mysql_query()-запрос с проверкой на правильность результата');
	$aboutArr['mqc_soft']			            = array('mqc_soft($query)'										                    => 'mysql_query()-запрос с проверкой на правильность результата, но возвращает ошибку вместо die()');
	$aboutArr['mnr']				            = array('mnr($result)'											                    => 'Количество строк в запросе');
	$aboutArr['mfa']				            = array('mfa($result)'											                    => 'Преобразовать результат в ассоциативный массив');
	$aboutArr['mr2a']				            = array('mr2a($result)'											                    => 'Преобразовать все результаты в один ассоциативный массив');
	$aboutArr['mres']				            = array('mres($str)'											                    => 'mysql_result() escape slashes');
	$aboutArr['mysqlierror']		            = array('mysqlierror()'											                    => 'Возвращает последнюю ошибку');
	$aboutArr['getInsertedId']		            = array('getInsertedId()'										                    => 'Возвращает id последнего запроса');
	$aboutArr['arraytomysqlstring']             = array('arraytomysqlstring($arr, $type="update")'				                    => 'Преобразует массив в mysqli-запрос (insert или update)');
	$aboutArr['updateData']		                = array('updateData($table, $paramValues, $origArr, $where)'	                    => 'Обновляет строку в БД (поиск через $where)');
	$aboutArr['appendData']		                = array('appendData($table, $paramValues, $ifNotExists)'		                    => 'Добавляет новую строку в БД (ищет повтор через $ifNotExists:array("index"=>"поле с индексом", "query"=>"query для поиска"))');
	$aboutArr['getarr']			                = array('getarr($table, $search=null, $addon=null, $debugger=false, $fields="*")'   => 'Получает строки из БД (поиск через $search)');
	$aboutArr['deleteitem']		                = array('deleteitem($table, $search)'							                    => 'Удаляет строки из БД (поиск через $search)');
	$aboutArr['getrows']			            = array('getrows($table, $search = null, $field, $addon = null)'                    => 'То же, что и getarr, но просто получает количество строк');
	$aboutArr['getcolumns']			            = array('getcolumns($table)'			        				                    => 'Получает колонки таблицы');
	$aboutArr['getcolumns2']		            = array('getcolumns2($table)'			        				                    => 'Получает колонки таблицы');
	$aboutArr['tablestructure2']	            = array('tablestructure2($tables)'			        			                    => 'Получает структуру таблиц из указанного массива');
	$aboutArr['mysql_tables']	                = array('mysql_tables($db)'			        			                            => 'Получает таблицы из БД');
	$aboutArr['table_joiner']	                = array('table_joiner($data)'			        			                        => 'Формирует query-строку для создания join-запросов');
	$aboutArr['copyitem']	                    = array('copyitem($table, $field, $id)'			        	                        => 'Копирует строку $id по полю $field в таблице $table');
	$aboutArr['equalatorQuery']	                = array('equalatorQuery($item, $tp="=")'			        	                    => 'Преобразование элемента поиска через = или LIKE');
	$aboutArr['getCopyQueryByFieldsAndData']    = array('getCopyQueryByFieldsAndData($column)'			        	                => 'Преобразует переменные в строке запроса на колонки');

	return $aboutArr;
}

/**
 *
 * MySQL-query запрос!
 *
 * @param $query
 * @return bool|mysqli_result
 */
function mq($query)
{
	global $mysqli;
	return $mysqli->query($query);
}

/**
 *
 * Продвинутый MySQL-query запрос с проверкой на успех!
 *
 * @param $query
 * @return bool|mysqli_result
 */
function mqc($query)
{
	global $QUERY_ARRAY;
	$query_item = array(
	    'query' => $query,
		'unix' => time(),
		'date_time' => date(DMYHIS, time()),
		'trace' => debug_backtrace()
	);
	$QUERY_ARRAY[] = $query_item;
	global $mysqli;
	$res = $mysqli->query($query);
	if (!$res) die('[MYSQL ERROR with request {'.$query.'}]: ' . mysqlierror() );
	return $res;
}

/**
 *
 * Возврат запроса без прерывателя die()
 *
 * @param $query
 * @return array
 */
function mqc_soft($query)
{
	$response = array(
	    'result' => false,
		'data' => ''
	);
	global $mysqli;
	$res = $mysqli->query($query);
	if ($res)
	{
		$response['result'] = true;
		$response['data'] = $res;
	} else
	{
		$response['data'] = '[MYSQL ERROR with request {'.$query.'}]: ' . mysqlierror();
	}
	
	return $response;
}

/**
 *
 * Возвращение количества строк в запросе!
 *
 * @param $result
 * @return mixed
 */
function mnr($result)
{
	return $result->num_rows;
}

/**
 *
 * Получение array из запроса!
 *
 * @param $result
 * @return mixed
 */
function mfa($result)
{
	return $result->fetch_assoc();
}

/**
 *
 * Результат запроса в массив через mfa()!
 *
 * @param $result
 * @param false $only1
 * @return array|mixed
 */
function mr2a($result, $only1=false)
{
    $arr = array();
    while( $elem = mfa($result) )
    {
    	$arr[] = $elem;
    }
    if ( $only1 ) return $arr[0];
    return $arr;
}

/**
 *
 * Добавление экранизации в добавляемое поле!
 *
 * @param $str
 * @return string
 */
function mres($str)
{
	//global $mysqli;
	return addslashes($str);
}

/**
 *
 * Возвращение последней ошибки!
 *
 * @return string
 */
function mysqlierror()
{
	global $mysqli;
	return $mysqli->error;
}

/**
 *
 * Получение ID последнего запроса!
 *
 * @return int|string
 */
function getInsertedId()
{
	global $mysqli;
	return $mysqli->insert_id;
}

/**
 *
 * Преобразование элемента поиска через = или LIKE!
 *
 * @param $item
 * @param string $tp
 * @return string
 */
function equalatorQuery($item, $tp='=')
{
    switch ($tp)
    {
        case "=":
            return '=' . "'$item'";
        break;
        case "like":
            return " LIKE '%$item%'";
        break;
    }
}

/**
 *
 * Формирование MySQL-запроса для массива!
 *
 * @param array $arr данные
 * @param string $type тип запроса insert|update|or
 * @param string $field поле
 * @param string $format эквалатор
 * @return false|string
 */
function arraytomysqlstring($arr, $type="update", $field='', $format='=')
{
	$str = "";
	switch($type)
	{
		case "update":
			foreach($arr as $p=>$v)
			{
				if ($p)
				{

					if (!$str) $str = $p . "='".$v."'";
					else $str .= ", " . $p . "='".$v."'";

				} else return false;
			}
			break;
		case "insert":
			$strPre = "";
			$strPost = "";
			foreach($arr as $p=>$v)
			{
				if ($p)
				{
					if (!$strPre) $strPre = $p;
					else $strPre .= ", " . $p;

					if (!$strPost) $strPost = "'".$v."'";
					else $strPost .= ", '".$v."'";

				} else return false;
			}
			$str = '('.$strPre.') VALUES ('.$strPost.')';
			break;
		case "or":
			if ( strlen($field) == 0 )
			{
				$str = "FIELD IS EMPTY";
			} else
			{
				foreach ($arr as $item)
				{
					if ( strlen($str) == 0 )
					{
						$str = $field . equalatorQuery($item, $format);
					} else
					{
						$str .= ' OR ' . $field . equalatorQuery($item, $format);
					}
				}
			}
			
		break;
	}
	return $str;
}

/**
 *
 * Обновление строки в $table из параметров $paramValues в строке $where с возвратом в массив $origArr!
 *
 * @param $table
 * @param $paramValues
 * @param $origArr
 * @param $where
 * @param false $debug
 * @return array stat==RES_SUCCESS
 */
function updateData($table, $paramValues, $origArr, $where, $debug=false)
{
	$update_string = arraytomysqlstring($paramValues);
	$queryUPD = "UPDATE $table SET $update_string WHERE $where";
	//echo $queryUPD;
	$resultUPD = mqc($queryUPD);
	$origArr['query'] = $queryUPD;
	if ($resultUPD) $origArr['stat'] = RES_SUCCESS;
//	if ($debug) $origArr['query'] = $queryUPD;
	return array_merge($origArr, $paramValues);
}

/**
 *
 * Функция обновления поля версии в таблице
 *
 * $options = array(
 *      'field_selector' => array(
 *          'field' => 'Поле сравнения',
 *          'value' => 'Значение из поля сравнения'
 *      ),
 *      'field_version' => 'Поле с версией',
 *      'field_params' => 'Массив с готовыми параметрами'
 *      'field_equal' => array(
 *          'field' => 'Поле дополнительного сравнения',
 *          'value' => 'Значение дополнительного сравнения'
 *      ),
 *      'field_id' => 'Поле с индексом'
 * );
 *
 * @param string $table таблица
 * @param array $options список параметров
 * @return array
 */
function appendVersion($table, $options)
{
	$go_next = true;
	
	$response['result'] = false;
	$response['msg'] = 'init';
	
	if ( $go_next )
	{
		$response['msg'] = 'check md5';
		$go_next = false;
		
		$CheckString = getarr($table, "{$options['field_selector']['field']}='{$options['field_selector']['value']}'", "ORDER BY {$options['field_version']} DESC LIMIT 0, 1");
		if ( count($CheckString) > 0 )
		{
			$response['msg'] = 'Такой файл уже есть на сервере!';
		} else
		{
			$go_next = true;
		}
	}
    
    if ( $go_next )
    {
	    $response['msg'] = 'check name';
	    
//    	$go_next = false;
	
	    $CheckName = getarr($table, "{$options['field_equal']['field']}='{$options['field_equal']['value']}' ORDER BY {$options['field_id']} DESC LIMIT 0, 1");
	    if ( count($CheckName) > 0 )
	    {
		    // такое название уже есть, поднимаем версию
		    $CheckName = $CheckName[0];
		    $last_version = $CheckName[$options['field_version']];
		    $last_version += 1;
//		    $go_next = true;
	    } else
	    {
		    // другой файл, как по содержимому, так и по названию
		    $last_version = 1;
//		    $go_next = true;
	    }
    }
    
    if ( $go_next )
    {
	    $response['msg'] = 'append';
    	
	    $options['field_params'][$options['field_version']] = $last_version;
	    $response['debug']['$options'] = $options;
	    $AppendVersion = appendData($table, $options['field_params']);
	    $response['data'] = $AppendVersion;
	    if ( $AppendVersion[ID] > 0 )
	    {
		    $response['result'] = true;
		    
	    } else
	    {
		    $response['msg'] = 'Ошибка при обновлении версии';
	    }
    }
    
    return $response;
}

/**
 *
 * Добавление строк в $table значений $paramValues!
 *
 * @param $table
 * @param $paramValues
 * @param array $ifNotExists
 * @return mixed
 */
function appendData($table, $paramValues, $ifNotExists = array())
{
	$insert_string = arraytomysqlstring($paramValues, "insert");

	$access = false;

	if ( count($ifNotExists) )
	{
		$paramValues['ifNotExists'] = 'ifNotExists';
		$queryCH = "SELECT {$ifNotExists['index']} FROM $table WHERE {$ifNotExists['query']} LIMIT 0,1";
		$paramValues['queryCH'] = $queryCH;
		$resultCH = mqc($queryCH);
		$rowCH = mnr($resultCH);
		$paramValues['rowCH'] = $rowCH;
		if ($rowCH >= 1)
		{
			$paramValues = mfa($resultCH);
			$paramValues['OPERATION'] = "EXISTS";
		} else $access = true;
	} else $access = true;

	if ($access)
	{
		$resultINS = mqc("INSERT INTO $table $insert_string");
		$paramValues[ID] = getInsertedId();
		$paramValues['OPERATION'] = "NEW";
	}
	return $paramValues;
}

/**
 *
 * Получение строк из $table по поиску $search с параметрами $addon!
 * При включённом debug - запрос к полю request, чтобы получить query
 *
 * @param string $table таблица
 * @param null $search WHERE
 * @param null $addon ORDER, LIMIT и прочее
 * @param false $debugger query
 * @param string $fields запрашиваемые поля
 * @return array
 */
function getarr($table, $search = null, $addon = null, $debugger=false, $fields='*')
{
	$arr = array();

	$where = is_null($search) ? " WHERE 1" : " WHERE " . $search;
	$order = is_null($addon) ? "" : " " . $addon;
	$queryLIST = "SELECT {$fields} FROM $table ".$where.$order;

	$resultLIST = mqc($queryLIST);
	while($item = mfa($resultLIST))
	{
		$arr[] = $item;
	}

	if ($debugger) $arr['request'] = $queryLIST;

	return $arr;
}

/**
 *
 * Получить количество строк $table по поиску $search с параметрами $addon!
 *
 * @param $table
 * @param null $search
 * @param $field
 * @param null $addon
 * @param false $debugger
 * @return array
 */
function getrows($table, $search = null, $field, $addon = null, $debugger=false)
{
	$arr = array();

	$where = is_null($search) ? " WHERE 1" : " WHERE " . $search;
	$order = is_null($addon) ? "" : " " . $addon;
	$queryLIST = "SELECT ".$field." FROM $table ".$where.$order;
//	debug($queryLIST);
	$resultLIST = mqc($queryLIST);
	$amount = mnr($resultLIST);
	$arr['count'] = $amount;

	if ($debugger) $arr['request'] = $queryLIST;

	return $arr;
}

/**
 *
 * Удалить строки из $table по поиску $search!
 *
 * @param $table
 * @param $search
 * @param false $debugger
 * @return array для проверки использовать ['result'] = true;
 */
function deleteitem($table, $search, $debugger=false)
{
    $Search = getarr($table, $search, null, 1);
//    debug($Search);
    if ( count($Search) > 0 )
    {
        $arr = array();
        $arr['result'] = false;
        $arr['msg'] = 'init';
        if (!$search)
        {
            $arr['msg'] = 'no query';
        } else
        {
            if ($search == "all") $where = " WHERE 1";
            else $where = " WHERE " . $search;
            $query = "DELETE FROM ".$table.$where;
            $arr['query'] = $query;
            $result = mqc($query);
            if ($result)
            {
                $arr['result'] = true;
            } else
            {
                $arr['msg'] = mysqli_error();
            }
        }
    } else
    {
        $arr['msg'] = 'Нет объекта для удаления';
    }


	return $arr;
}

/**
 *
 * Копирует строку $id по полю $field в таблице $table!
 *
 * @param string $table таблица
 * @param string $field_id поле с ID таблицы
 * @param string $id ID записи
 * @param string $dest_table целевая таблица
 * @return array
 */
function copyitem($table, $field_id, $id, $dest_table='')
{
	$response = [];
	
	$dest_table = (strlen($dest_table) == 0) ? $table : $dest_table;
	
	$COLUMNS = getcolumns2($table);
	unset($COLUMNS[$field_id]);
	$response['table'] = $table;
	$response['dest_table'] = $dest_table;
	$response['columns'] = $COLUMNS;
	
	
	$copy_query = getCopyQueryByFieldsAndData($COLUMNS);
	$copy_query = str_replace('[TABLE_DEST]', $dest_table, $copy_query);
	$copy_query = str_replace('[TABLE_SRC]', $table, $copy_query);
	$copy_query .= " WHERE {$field_id}='{$id}'";
	$response['$copy_query'] = $copy_query;
	
	$copy_result = mqc($copy_query);
	$response[ID] = getInsertedId();
	if ( $response[ID] > 0)
	{
		$response['result'] = true;
	}
	return $response;
}


/**
 *
 * Преобразует переменные в строке запроса на колонки
 *
 * @param $columns
 * @return array|string|string[]
 */
function getCopyQueryByFieldsAndData($columns)
{
	$response = "INSERT INTO [TABLE_DEST] ([FIELDS]) SELECT [FIELDS] FROM [TABLE_SRC]";
	$columns_string = implode(', ', array_keys($columns));
	$response = str_replace('[FIELDS]', $columns_string, $response);
	return $response;
}

/**
 *
 * Получение колонок $table!
 *
 * @param $table
 * @return array
 */
function getcolumns($table)
{
	$arr = array();

	$query = "SHOW columns FROM $table";
	$result = mqc($query);

	while($row = mysqli_fetch_array($result)){
//        debug($row);
		$arr2 = array();
		$arr2['field'] = $row['Field'];
		$arr2['type'] = $row['Type'];

		$arr[] = $arr2;
	}

	return $arr;
}

/**
 *
 * Получает ключи таблицы
 *
 * @param string $table таблица
 * @return false|mixed
 */
function getPrimaryKey($table)
{
    $primary_query = "SHOW KEYS FROM {$table} WHERE Key_name = 'PRIMARY'";
    $primary_result = mqc($primary_query);
	$primary = mfa($primary_result);
	if ( $primary['Key_name'] == 'PRIMARY' )
	{
		if ( strlen($primary['Column_name']) > 0 )
		{
		    return $primary['Column_name'];
		} else return false;
	} else return false;
}

/**
 *
 * Получение колонок $table
 *
 * @param $table
 * @return array
 */
function getcolumns2($table)
{
	$arr = array();

	$query = "SHOW columns FROM $table";
	$result = mqc($query);

	while($row = mysqli_fetch_array($result)){
		$arr[$row['Field']] = $row['Type'];
	}

	return $arr;
}

/**
 *
 * Получает структуру всех таблиц $tables!
 *
 * @param $tables
 * @param false $rtrn
 * @return array
 */
function tablestructure2($tables, $rtrn=false)
{
	$arr = array();
	foreach ($tables as $table) {
		$arr[$table] = getcolumns2($table);
	}
	if ($rtrn) return $arr;
	else debug($arr);
}

/**
 *
 * Получает таблицы $database!
 *
 * @param string $database
 * @param string $field
 * @return array
 */
function mysql_tables($database='', $field='Tables_in_caop')
{
	$tables = array();
	$list_tables_sql = "SHOW TABLES FROM {$database};";
//	debug($list_tables_sql);
	$result = mqc($list_tables_sql);
	if($result)
		while($table = mfa($result))
		{
			$tables[] = $table[$field];
		}
	return $tables;
}

/**
 *
 * Получает информацию о таблице в формате CREATE TABLE
 *
 * @param string $table таблица
 * @return false|array
 */
function table_info($table, $include_fields = ['table', 'alter'])
{
	if ( strlen($table) > 0 )
	{
	    $query = "SHOW CREATE TABLE {$table}";
	    $result = mqc($query);
	    if ( $result )
	    {
	    	$fields_separator_begin = ' (' . n();
	    	$fields_separator_end = n() . ') ';
	    	$fields_separator_item = ',' . n();
	    	$alter_separator = ' ';
	    	$info_data = mfa($result);
	    	$response['table'] = trim($info_data['Table']);
	    	$response['create'] = trim($info_data['Create Table']);
	    	
	    	$info_data_2 = explode($fields_separator_begin, $info_data['Create Table'], 2);
		    $info_data_3 = explode($fields_separator_end, $info_data_2[1]); unset($info_data_2);
	    	$fields_string = trim($info_data_3[0]);
	    	$fields_data = explode($fields_separator_item, $fields_string);
	    	$alter_string = trim($info_data_3[1]);
	    	$alter_data = explode($alter_separator, $alter_string, 5);
		    unset($info_data_3);
	    	
	    	$primary = trim(end($fields_data));
		    $response['primary'] = $primary;
	    	$response['alter'] = [];
		    foreach ($alter_data as $alter_datum)
		    {
			    if ( ifound($alter_datum, '=') )
			    {
			        $alter_item_data = explode('=', $alter_datum);
				    $response['alter'][$alter_item_data[0]] = $alter_item_data[1];
			    } else
			    {
			    	$response['alter'][$alter_datum] = $alter_datum;
			    }
	    	}
		    end($fields_data);
		    $key = key($fields_data);
	    	unset($fields_data[$key]);
	    	$response['fields'] = [];
		    foreach ($fields_data as $field)
		    {
			    $field_data = explode(' ', trim($field), 3);
			    $response['fields'][] = $field_data;
	    	}
		    
		    if ( count($include_fields) > 0 )
		    {
		    	$response_slice = [];
			    foreach ($response as $response_field => $response_data)
			    {
				    if ( in_array($response_field, $include_fields) )
				    {
					    $response_slice[$response_field] = $response_data;
				    }
		        }
			    return $response_slice;
		    } else return $response;
	    } else return false;
	} else return false;
}

/**
 *
 * Создание запроса с JOIN-структурами из $data!
 *
 * @param $data
 * @return string
 */
function table_joiner($data)
{
	$FIELDS = $data['fields'];
    $TABLES = $data['tables'];
    $MAIN_TABLE = $TABLES[0];
    $WHERE = $data['where'];
    $ADDON = $data['addon'];
    $query = "SELECT {$FIELDS} FROM ";
	foreach ($TABLES as $tableData)
	{
		if ( strlen($tableData['table_join']) > 0 )
		{
			$query .= " {$tableData['table_join']} ";
		}
		$query .= $tableData['table_name'];
		if ( strlen($tableData['table_join']) > 0 )
		{
			$query .= " ON {$MAIN_TABLE['table_name']}.{$tableData['table_main_field']}={$tableData['table_name']}.{$tableData['table_field_id']}";
		}
    }
	$query .= " WHERE {$WHERE} ";
	$query .= " {$ADDON} ";
	
	return $query;
}

