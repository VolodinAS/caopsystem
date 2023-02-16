<?php
$convert_direction = $RequestData[1];

if ( strlen($convert_direction) > 0 )
{
	
	switch ($convert_direction)
	{
	    case "tomyisam":
		    $database_tables = mysql_tables(DB_NAME);
		    foreach ($database_tables as $table_title)
		    {
			    $table_info = table_info($table_title);
			    $table_engine = $table_info['alter']['ENGINE'];
			    spoiler_begin($table_info['table'] . ' | ' . wrapper('ENGINE: ') . $table_engine, 'id_' . $table_info['table'], '');
			    {
				    if ( $table_engine == ENGINE_INNODB )
				    {
					    bt_notice('ТАБЛИЦУ МОЖНО КОНВЕРТИРОВАТЬ', BT_THEME_SUCCESS);
					    debug($table_info);
					    $NewEngine_query = "ALTER TABLE {$table_info['table']} ENGINE=".ENGINE_MYISAM;
					    debug($NewEngine_query);
					    $NewEngine_result = mqc($NewEngine_query);
					    if ( $NewEngine_result )
					    {
						    bt_notice('ТАБЛИЦА ТЕПЕРЬ ' . ENGINE_MYISAM, BT_THEME_SUCCESS);
					    } else
					    {
						    bt_notice('ОШИБКА ИЗМЕНЕНИЯ ДВИГАТЕЛЯ!', BT_THEME_DANGER);
					    }
					
					
				    } else
				    {
					    if ( $table_engine == ENGINE_MYISAM )
					    {
						    bt_notice('ТАБЛИЦУ КОНВЕРТИРОВАТЬ НЕЛЬЗЯ. ОНА УЖЕ ' . $table_engine, BT_THEME_WARNING);
					    } else
					    {
						    bt_notice('ТАБЛИЦА С НЕИЗВЕСТНЫМ ДВИГАТЕЛЕМ: ' . $table_engine, BT_THEME_DANGER);
						    debug(table_info($table_title, []));
					    }
				    }
			    }
			    spoiler_end();
			    echo '<br>';
//			    break;
		    }
	    break;
	    case "toinnodb":
	        $database_tables = mysql_tables(DB_NAME);
		    foreach ($database_tables as $table_title)
		    {
			    $table_info = table_info($table_title);
			    $table_engine = $table_info['alter']['ENGINE'];
			    spoiler_begin($table_info['table'] . ' | ' . wrapper('ENGINE: ') . $table_engine, 'id_' . $table_info['table'], '');
			    {
				    if ( $table_engine == ENGINE_MYISAM )
				    {
					    bt_notice('ТАБЛИЦУ МОЖНО КОНВЕРТИРОВАТЬ', BT_THEME_SUCCESS);
					    debug($table_info);
					    $NewEngine_query = "ALTER TABLE {$table_info['table']} ENGINE=".ENGINE_INNODB;
					    debug($NewEngine_query);
					    $NewEngine_result = mqc($NewEngine_query);
					    if ( $NewEngine_result )
					    {
						    bt_notice('ТАБЛИЦА ТЕПЕРЬ ' . ENGINE_INNODB, BT_THEME_SUCCESS);
					    } else
					    {
						    bt_notice('ОШИБКА ИЗМЕНЕНИЯ ДВИГАТЕЛЯ!', BT_THEME_DANGER);
					    }
					    
					    
				    } else
				    {
					    if ( $table_engine == ENGINE_INNODB )
					    {
						    bt_notice('ТАБЛИЦУ КОНВЕРТИРОВАТЬ НЕЛЬЗЯ. ОНА УЖЕ ' . $table_engine, BT_THEME_WARNING);
					    } else
					    {
						    bt_notice('ТАБЛИЦА С НЕИЗВЕСТНЫМ ДВИГАТЕЛЕМ: ' . $table_engine, BT_THEME_DANGER);
						    debug(table_info($table_title, []));
					    }
				    }
			    }
			    spoiler_end();
		    	echo '<br>';
//			    break;
	        }
	    break;
	    default:
	        bt_notice('Неизвестное направление конвертации', BT_THEME_WARNING);
	    break;
	}
    
} else bt_notice('Не выбрано направление конвертации', BT_THEME_WARNING);