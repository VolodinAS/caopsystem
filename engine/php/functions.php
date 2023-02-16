<?php
/**
 * Created by PhpStorm.
 * User: VolodinAS
 * Date: 17.03.2020
 * Time: 16:13
 * @param $var
 * @return bool
 */

require_once ROOT . "/engine/php/tools/PHPExcel-1.8/Classes/PHPExcel.php";

/**
 *
 * Проверяет тип переменной на null
 *
 * @param void $var переменная
 * @return bool
 */
function notnull($var)
{
	return (gettype($var) != "NULL");
}

/**
 *
 * strpos через ifound
 *
 * @param string $stack строка
 * @param string $needle подстрока
 * @return bool
 */
function ifound($stack, $needle)
{
	if (strpos($stack, $needle) !== FALSE) return true;
	else return false;
}

/**
 *
 * Удалить все пробелы из текста
 *
 * @param string $str
 * @param int $iter
 * @return string|string[]
 */
function nospaces($str, $iter = 10)
{
	$str = nodoublespaces($str);
	for ($i = 0; $i < $iter; $i++)
	{
		$str = str_replace(" ", "", $str);
	}
	
	return $str;
}

/**
 *
 * Убрать двойные пробелы, оставив одиночные
 *
 * @param $str
 * @param int $iter
 * @return string|string[]
 */
function nodoublespaces($str, $iter = 10)
{
	for ($i = 0; $i < $iter; $i++)
	{
		$str = str_replace("  ", " ", $str);
	}
	return $str;
}

/**
 *
 * По unix получить день недели
 *
 * @param int $time
 * @return string
 */
function getDayRus($time = 0)
{
	if ($time == 0) $time = time();
	$days = array(
		'Воскресенье', 'Понедельник', 'Вторник', 'Среда',
		'Четверг', 'Пятница', 'Суббота'
	);
	return $days[(date('w', $time))];
}

/**
 *
 * Получить день недели по индексу
 *
 * @param $index
 * @return string
 */
function getDayRusByIndex($index)
{
	$days = array(
		'Понедельник', 'Вторник', 'Среда',
		'Четверг', 'Пятница', 'Суббота', 'Воскресенье'
	);
	return $days[$index - 1];
}

/**
 *
 * Получить день недели по индексу
 *
 * @param $index
 * @return string
 */
function getDayRusShortByIndex($index)
{
	$days = array(
		'Пн', 'Вт', 'Ср',
		'Чт', 'Пт', 'Сб', 'Вс'
	);
	return $days[$index - 1];
}

/**
 *
 * Получить день недели по unix в коротком формате
 *
 * @param int $time
 * @return string
 */
function getDayRusShort($time = 0)
{
	if ($time == 0) $time = time();
	$days = array(
		'Вс', 'Пн', 'Вт', 'Ср',
		'Чт', 'Пт', 'Сб'
	);
	return $days[(date('w', $time))];
}

/**
 *
 * Панель уведомления bootstrap
 *
 * @param $msg
 * @param string $tp
 * @param bool $ret
 * @return string
 */
function bt_notice($msg, $tp = "info", $ret = false, $mb = ' mb-2')
{
	$div = '<div class="alert alert-' . $tp . ' p-2 ' . $mb . '" role="alert">' . $msg . '</div>';
	if (!$ret)
	{
		echo $div;
	} else
	{
		return $div;
	}
	
}

/**
 *
 * Значки bootstrap
 *
 * @param string $msg Текст
 * @param string $theme Тема из bootstrap
 * @param false $isPill более скруглённый стиль
 * @param false $isReturn вывести или вернуть
 * @return string
 */
function badge($msg, $theme = BT_THEME_WARNING, $isPill = false, $isReturn = false)
{
	$pill = ($isPill) ? ' badge-pill' : '';
	$html = '<span class="badge' . $pill . ' badge-' . $theme . '">' . $msg . '</span>';
	if (!$isReturn)
	{
		echo $html;
	} else
	{
		return $html;
	}
	
}

/**
 *
 * Разделитель bootstrap
 *
 */
function bt_divider($ret = false)
{
	$data = '<div class="dropdown-divider"></div>';
	if ($ret)
	{
		return $data;
	} else
	{
		echo $data;
	}
}

/**
 *
 * Проверка user-agent на мобильное устройство
 *
 * @return bool
 */
function isMobile()
{
	if (empty($_SERVER['HTTP_USER_AGENT']))
	{
		$is_mobile = false;
	} elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'Mobile') !== false // many mobile devices (all iPhone, iPad, etc.)
		|| strpos($_SERVER['HTTP_USER_AGENT'], 'Android') !== false
		|| strpos($_SERVER['HTTP_USER_AGENT'], 'Silk/') !== false
		|| strpos($_SERVER['HTTP_USER_AGENT'], 'Kindle') !== false
		|| strpos($_SERVER['HTTP_USER_AGENT'], 'BlackBerry') !== false
		|| strpos($_SERVER['HTTP_USER_AGENT'], 'Opera Mini') !== false
		|| strpos($_SERVER['HTTP_USER_AGENT'], 'Opera Mobi') !== false)
	{
		$is_mobile = true;
	} else
	{
		$is_mobile = false;
	}
	
	return $is_mobile;
}

/**
 *
 * Заглавные буквы каждого слова
 *
 * @param $str
 * @return string
 */
function mb_ucwords($str)
{
	$str = mb_convert_case($str, MB_CASE_TITLE, UTF8);
	return ($str);
}

/**
 *
 * Сортировка многомерного массива по полю внутри
 *
 * $массив, 'поле1', SORT_ASC|DESC, 'поле2', SORT_ASC|DESC, ...
 * @return mixed
 */
function array_orderby()
{
	$args = func_get_args();
	$data = array_shift($args);
	foreach ($args as $n => $field)
	{
		if (is_string($field))
		{
			$tmp = array();
			foreach ($data as $key => $row)
				$tmp[$key] = $row[$field];
			$args[$n] = $tmp;
		}
	}
	$args[] = &$data;
	call_user_func_array('array_multisort', $args);
	return array_pop($args);
}


/**
 *
 * Берет массив и преобразует данные внутри поля по указанной функции
 *
 * @param array $arr массив с данными
 * @param string $field поле, по которому надо сконвертировать данные
 * @param string $func функция, по которой надо сконвертировать данные
 * @return array
 */
function date_converter_for_array_order_by($arr, $field, $func = 'birthToUnix')
{
	$regex = '/[0-9]{2}.[0-9]{2}.[0-9]{4}/g';
	
	$response = array(
		'result' => false,
		'msg' => '',
		'options' => array(
//			'arr' => $arr,
			'field' => $field,
			'func' => $func,
			'regex' => $regex
		)
	);
	
	if (strlen($func) > 0)
	{
		if (strlen($field) > 0)
		{
			if (count($arr) > 0)
			{
				$empty_data = [];
				$full_data = [];
				$errors_data = [];
				
//				$response['debug']['showme'] = [];
				
				for ($index = 0; $index < count($arr); $index++)
				{
//					$response['debug']['showme'][] = $arr[$index];
				   if (isset($arr[$index][$field]))
				   {
					   if (strlen($arr[$index][$field]) > 0)
					   {
						   $dt = DateTime::createFromFormat("d.m.Y", $arr[$index][$field]);
						   if ($dt !== false && !array_sum($dt::getLastErrors()))
						   {
//						        $item[$field] = $$func($item[$field]);
							   $arr[$index][$field . '_index'] = call_user_func($func, $arr[$index][$field]);
							   $full_data[] = $arr[$index];
						   } else
						   {
//							   $response['msg'] = 'Дата не в формате';
//							   $response['debug']['date'] = $arr[$index][$field];
//							   break;
							   $errors_data[] = $arr[$index];
						   }
					   } else
					   {
						   $empty_data[] = $arr[$index];
					   }
				   } else
				   {
//					   $response['msg'] = 'Ключ не найден';
//					   $response['debug']['item'] = $arr[$index];
//					   break;
					   $errors_data[] = $arr[$index];
				   }
			    }
				$response['result'] = true;
//				$response['debug']['$errors_data'] = $errors_data;
//				$response['debug']['$empty_data'] = $empty_data;
//				$response['debug']['$full_data'] = $full_data;
				$response['data'] = array_merge($errors_data, $empty_data, $full_data);
			} else $response['msg'] = 'Не указан массив';
		} else $response['msg'] = 'Не указано поле';
	} else $response['msg'] = 'Функция не указана';
	return $response;
}

/**
 *
 * Форматирование имён по переданной строке
 *
 * @param $name
 * @param string $tp famio, famimot
 * @return string
 */
function shorty($name, $tp = "famio")
{
	$NAME = "";
	$arr = explode(" ", $name);
	switch ($tp)
	{
		case "famio":
			$fl1 = ($arr[1]) ? ' ' . firstLetter($arr[1]) . '.' : '';
			$fl2 = ($arr[2]) ? ' ' . firstLetter($arr[2]) . '. ' : '';
			$NAME = $arr[0] . ' ' . $fl1 . $fl2;
			break;
		case "famimot":
			$NAME = $name;
			break;
	}
	$NAME = str_replace("    ", "   ", $NAME);
	$NAME = str_replace("   ", "  ", $NAME);
	$NAME = str_replace("  ", " ", $NAME);
	$NAME = rtrim($NAME);
	return mb_ucwords(strtolower($NAME));
}

/**
 *
 * Окружить строку тегами
 *
 * @param $str
 * @param string $tag
 * @return string
 */
function wrapper($str, $tag = 'b')
{
	return '<' . $tag . '>' . $str . '</' . $tag . '>';
}

/**
 *
 * Заменить пробелы на неразрывные пробелы
 *
 * @param $str
 * @return string|string[]
 */
function nbsper($str)
{
	for ($i = 0; $i < 10; $i++)
	{
		$str = str_replace("  ", " ", $str);
	}
	
	$str = str_replace(" ", "&nbsp;", $str);
	return $str;
}

/**
 *
 * Вставка N разрывных пробелов
 *
 * @param int $num
 * @return string
 */
function _nbsp($num = 3)
{
	$str = "";
	for ($i = 0; $i < $num; $i++)
	{
		$str .= "&nbsp;";
	}
	return $str;
}

/**
 *
 * Получить первую строку (для кириллицы)
 *
 * @param $str
 * @return string
 */
function firstLetter($str)
{
	return mb_substr($str, 0, 1, UTF8);
}

/**
 *
 * Отладка с return (для htmlData в json-ответе)
 *
 * @param $arr
 * @return string
 */
function debug_ret($arr)
{
	global $USER_PROFILE;
	if ($USER_PROFILE['doctor_id'] == 1)
	{
		return debug($arr, null, null, 1);
	} else
	{
		return '';
	}
}

function debugn($arr, $title='')
{
	debug( $arr, true, false, false, $title);
}

function debugnr($arr, $title='')
{
	return debug( $arr, true, false, true, $title);
}

/**
 *
 * Вывести отладочные данные
 *
 * @param $arr
 * @param bool $beauty
 * @param bool $vardump
 * @param bool $ret
 * @return string
 */
function debug($arr, $beauty = true, $vardump = false, $ret = false, $title = '')
{
	global $USER_PROFILE;
	
	$CHECK_ME = 1;
	
	if ( strlen($title) > 0 )
	{
	    $title = wrapper($title . ': ');
	}
	
	if (1)
	{
		if (!$ret)
		{
			if (!$vardump)
			{
				if ($USER_PROFILE['doctor_id'] == $CHECK_ME)
				{
					if ($beauty) echo '<div class="alert alert-info" role="alert">';
					echo '<pre>' . $title;
					print_r($arr);
					echo '</pre>';
					if ($beauty) echo '</div>';
				}
			} else
			{
				if ($USER_PROFILE['doctor_id'] == $CHECK_ME)
				{
					if ($beauty) echo '<div class="alert alert-info" role="alert">';
					echo '<pre>' . $title;
					var_dump($arr);
					echo '</pre>';
					if ($beauty) echo '</div>';
				}
			}
		} else
		{
			$response = '';
			
			if (!$vardump)
			{
				if ($beauty) $response .= '<div class="alert alert-info" role="alert">';
				$response .= '<pre>' . $title;
				$response .= print_r($arr, 1);
				$response .= '</pre>';
				if ($beauty) $response .= '</div>';
			} else
			{
				if ($beauty) $response .= '<div class="alert alert-info" role="alert">';
				$response .= '<pre>' . $title;
				$response .= var_export($arr, 1);
				$response .= '</pre>';
				if ($beauty) $response .= '</div>';
			}
			
			return $response;
		}
		
	}
	
}

/**
 *
 * День рождения в unix
 *
 * @param $birth
 * @return false|int
 */
function birthToUnix($birth)
{
	return strtotime($birth); // dd.mm.yyyy
}

/**
 *
 * Преобразование массива в select-тег
 *
 * @param $arr
 * @param $valueField
 * @param $titleField
 * @param string $selectName
 * @param null $attrib
 * @param null $defaultArr
 * @param null $defaultSelect
 * @return array
 */
function array2select($arr, $valueField, $titleField, $selectName = "selector", $attrib = null, $defaultArr = null, $defaultSelect = null)
{
	$select = "";
	$response = array();
	$response['stat'] = "begin";
	if (is_array($arr))
	{
		
		if (count($arr) > 0)
		{
			
			if ($valueField != "")
			{
				if ($titleField != "")
				{
					
					$select .= "<select name='{$selectName}'{$attrib}>";
					
					if (count($defaultArr))
					{
						$select .= "<option value='{$defaultArr['key']}'>{$defaultArr['value']}</option>";
					}
					
					foreach ($arr as $item)
					{
						$itSelect = '';
						if (count($defaultSelect))
						{
							if ($defaultSelect['value'] == $item[$valueField])
							{
								$itSelect = ' selected';
							}
						}
						$response['debug']['$titleField'][] = $titleField;
						$OPTION_DATA = '';
						if (ifound($titleField, 'callback.'))
						{
							$func = str_replace('callback.', '', $titleField);
							$response['debug']['$func'][] = $func;
							
							if (strlen($func) > 0)
							{
//						        $OPTION_DATA = $$func();
								$OPTION_DATA = call_user_func($func, $item);

//							    $response['debug']['$OPTION_DATA'][] = $OPTION_DATA;
							}
						} else
						{
							$OPTION_DATA = $item[$titleField];
						}
						
						$select .= "<option value='{$item[$valueField]}'{$itSelect}>{$OPTION_DATA}</option>";
					}
					
					$select .= "</select>";
					
					$response['result'] = $select;
					$response['stat'] = RES_SUCCESS;
					
				} else
				{
					$response['stat'] = "error";
					$response['msg'] = "titleField is empty";
				}
			} else
			{
				$response['stat'] = "error";
				$response['msg'] = "valueField is empty";
			}
			
		} else
		{
			$response['stat'] = "error";
			$response['msg'] = "array is empty";
		}
		
	} else
	{
		$response['stat'] = "error";
		$response['msg'] = "1 argument not array";
	}
	
	return $response;
}


function getDataByField($doctor_arr, $field = 'doctor_id', $prefix = 'id', $main_field = null)
{
	$arr = array();
	foreach ($doctor_arr as $doctor)
	{
		if ( $main_field  )
		{
			$DATA = $doctor[$main_field];
			$arr[$prefix . $DATA[$field]][] = $doctor;
		} else
		{
			$arr[$prefix . $doctor[$field]][] = $doctor;
		}
	}
	return $arr;
}

/**
 *
 * Получить данные доктора из массива докторов по id
 *
 * @param array $doctor_arr массив с данными
 * @param string $field поле, относительно которого делается формирование нового массива
 * @param string $prefix добавочный префикс
 * @return array
 */
function getDoctorsById($doctor_arr, $field = 'doctor_id', $prefix = 'id', $main_field = null)
{
	$arr = array();
	foreach ($doctor_arr as $doctor)
	{
		if ( $main_field  )
		{
			$DATA = $doctor[$main_field];
			$arr[$prefix . $DATA[$field]] = $doctor;
		} else
		{
			$arr[$prefix . $doctor[$field]] = $doctor;
		}
	}
	return $arr;
}

/**
 *
 * Профиль пользователя по $_COOKIE
 *
 * @return array|mixed
 */
function getUserProfile()
{
	$response = array();
	$response['result'] = false;
	$response['ulogin'] = -1;
	$response['access_level'] = 0;
	$login = $_COOKIE['login'];
	$password = $_COOKIE['password'];
	
	if (strlen($login) > 0)
	{
		if (strlen($password) > 0)
		{
			$result = getarr('caop_doctor', "doctor_miac_login='{$login}' AND doctor_miac_pass='{$password}'", "LIMIT 1");
			if (count($result) == 1)
			{
				$response = $result[0];
				$response['result'] = true;
				$response['ulogin'] = 1;
				$response['access_level'] = $response['doctor_access'];
			} else
			{
				$response['msg'] = 'Неверный логин или пароль';
			}
		} else
		{
			$response['msg'] = 'Не указан пароль';
		}
	} else
	{
		$response['msg'] = 'Не указан логин';
	}
	return $response;
}

/**
 *
 * Получение меню пользователя
 *
 * @param $profile
 * @return array
 */
function getUserMenu2($profile)
{
	$access_array = explode(";", $profile['access_level']);
	//debug('$access_array');
	//debug($access_array);
	$main_array = array();
	$MenuKeeper = array();
	foreach ($access_array as $access_level)
	{
		$MenuAccess = getarr(CAOP_HEADMENU, "headmenu_access LIKE '%{$access_level}%' AND headmenu_enabled='1' AND headmenu_subid='0'", "ORDER BY headmenu_order ASC");
		$DiffMenu = array_diff($MenuAccess, $MenuKeeper);
		//debug('$DiffMenu');
		//debug($DiffMenu);
		if (count($DiffMenu) == 0)
		{
			// одинаково, ничего не делаем
		} else
		{
			// есть разница, добавляем только ее
			$main_array = array_merge($main_array, $DiffMenu);
			$MenuKeeper = $main_array;
		}
		
	}
	return $main_array;
}





/**
 *
 * Получить хэш-пароля
 *
 * @param $pwd
 * @return string
 */
function getHashPassword($pwd)
{
	$caopsystems_md5 = md5('caopsystems');
	$pwd_md5 = md5($pwd);
	return md5($caopsystems_md5 . $pwd_md5);
}

/**
 *
 * Получить данные дня по текущему unix. Получает начало дня, конец дня, завтра, предыдущий день, месяц и год.
 *
 * @param $time
 * @return array
 */
function getCurrentDay($time)
{
	$response = array();
	$response['year'] = date("Y", $time);
	$response['month'] = date("m", $time);
	$response['day'] = date("d", $time);
	$response['hour'] = date("H", $time);
	$response['minute'] = date("i", $time);
	$response['second'] = date("s", $time);
	$response['week_long'] = getDayRus($time);
	$response['week_short'] = getDayRusShort($time);
	$today_string = $response['year'] . '-' . $response['month'] . '-' . $response['day'];
	$timestamp_today = strtotime($today_string);
	$response['unix'] = $timestamp_today;
	$response['full_unix'] = $time;
	
	$day_start_date = date('Y-m-d 00:00:00', $timestamp_today);
	$day_end_date = date('Y-m-d 23:59:59', $timestamp_today);
	
	$day_start = strtotime($day_start_date);
	$day_end = strtotime($day_end_date);
	
	$week_start = date('Y-m-d', strtotime('last monday', strtotime('tomorrow')));
	$week_end = date('Y-m-d', strtotime('this sunday'));
	
	$month_start = date("Y-m-1", strtotime("first day of this month"));
	$month_end = date("Y-m-t", strtotime("last day of this month"));
	
	$year_start = date("Y-m-d", strtotime("first day of january this year"));
	$year_end = date("Y-m-d", strtotime("last day of december this year"));
	
	$week_start_unix = date('Y-m-d', strtotime('last monday', strtotime('tomorrow', $timestamp_today)));
	$week_end_unix = date('Y-m-d', strtotime('this sunday', $timestamp_today));
	
	$month_start_unix = date("Y-m-01", strtotime("first day of this month", $timestamp_today));
	$month_end_unix = date("Y-m-t", strtotime("last day of this month", $timestamp_today));
	
	$year_start_unix = date("Y-m-d", strtotime("first day of january this year", $timestamp_today));
	$year_end_unix = date("Y-m-d", strtotime("last day of december this year", $timestamp_today));
	
	$response['begins'] = array(
		'week' => array(
			'date' => $week_start,
			'unix' => strtotime($week_start)
		),
		'month' => array(
			'date' => $month_start,
			'unix' => strtotime($month_start)
		),
		'year' => array(
			'date' => $year_start,
			'unix' => strtotime($year_start)
		)
	);
	$response['ends'] = array(
		'week' => array(
			'date' => $week_end,
			'unix' => strtotime($week_end) + 86399
		),
		'month' => array(
			'date' => $month_end,
			'unix' => strtotime($month_end) + 86399
		),
		'year' => array(
			'date' => $year_end,
			'unix' => strtotime($year_end) + 86399
		)
	);
	
	$response['by_timestamp']['begins'] = array(
		'day' => array(
			'date' => date('Y-m-d H:i:s', $day_start),
			'unix' => $day_start
		),
		'week' => array(
			'date' => $week_start_unix,
			'unix' => strtotime($week_start_unix)
		),
		'month' => array(
			'date' => $month_start_unix,
			'unix' => strtotime($month_start_unix)
		),
		'year' => array(
			'date' => $year_start_unix,
			'unix' => strtotime($year_start_unix)
		)
	);
	$response['by_timestamp']['ends'] = array(
		'day' => array(
			'date' => date('Y-m-d H:i:s', $day_end),
			'unix' => $day_end
		),
		'week' => array(
			'date' => $week_end_unix,
			'unix' => strtotime($week_end_unix) + 86399
		),
		'month' => array(
			'date' => $month_end_unix,
			'unix' => strtotime($month_end_unix) + 86399
		),
		'year' => array(
			'date' => $year_end_unix,
			'unix' => strtotime($year_end_unix) + 86399
		)
	);
	
	$response['format'] = array(
		'dd.mm.yyyy' => $response['day'] . '.' . $response['month'] . '.' . $response['year'],
		'dd.mm.yyyy hh:mm:ss' => $response['day'] . '.' . $response['month'] . '.' . $response['year'] . ' ' . $response['hour'] . ':' . $response['minute'] . ':' . $response['second']
	);
	return $response;
}

/**
 *
 * Окружить строку неразрывным пробелом
 *
 * @param $str
 * @param int $num
 * @return string
 */
function str_nbsp($str, $num = 0)
{
	$nbb = '';
	$nba = '';
	for ($i = 0; $i < $num; $i++)
	{
		if ($nbb == '') $nbb = "&nbsp;";
		else $nbb .= "&nbsp;";
		
		if ($nba == '') $nba = "&nbsp;";
		else $nba .= "&nbsp;";
	}
	return $nbb . $str . $nba;
}

/**
 *
 * Начало спойлера
 *
 * @param string $title название
 * @param string $id id блока
 * @param string $collapse закрыт или открыт
 */
function spoiler_begin($title, $id, $collapse = 'collapse', $bg = '', $addon_class = '')
{
	$expanded = ($collapse === '') ? 'true' : 'false';
	$isShow = ($collapse === '') ? 'show' : '';
	$isCollapsed = ($collapse === '') ? '' : 'collapsed';
	echo '
<div id="accordion_' . $id . 'tables" class="accordion-spoiler ' . $addon_class . '">
	<div class="card">
		<div class="card-header p-0 ' . $bg . '">
			<h5>
				<button type="button" class="btn btn-link scroller-button ' . $isCollapsed . '" data-toggle="collapse" data-target="#' . $id . '_spoiler" aria-expanded="' . $expanded . '" aria-controls="' . $id . '_spoiler" data-anchor="anchor_' . $id . '">
					' . $title . '
				</button>
				<a name="anchor_' . $id . '"></a>
			</h5>
		</div>
		<div id="' . $id . '_spoiler" class="collapse ' . $isShow . '" aria-labelledby="' . $id . '" data-parent="#accordion_' . $id . 'tables">
			<div class="card-body p-2">
    ';
}

/**
 *
 * Конец спойлера
 *
 */
function spoiler_end()
{
	echo '
			</div>
		</div>
	</div>
</div>
    ';
}

/**
 *
 * Начало спойлера с return (для htmlData в json-ответе)
 *
 * @param $title
 * @param $id
 * @param string $collapse
 * @param bool $isCenter
 * @return string
 */
function spoiler_begin_return($title, $id, $collapse = 'collapse', $isCenter = false)
{
	$expanded = ($collapse === '') ? 'true' : 'false';
	$isShow = ($collapse === '') ? 'show' : '';
	$isCollapsed = ($collapse === '') ? '' : 'collapsed';
	$centered = ($isCenter) ? ' text-center' : '';
	return '
<div id="accordion_' . $id . 'tables" class="accordion-spoiler">
	<div class="card">
		<div class="card-header p-0">
			<h5 class="mb-0' . $centered . '">
				<button type="button" class="btn btn-link scroller-button ' . $isCollapsed . '" data-toggle="collapse" data-target="#' . $id . '_spoiler" aria-expanded="' . $expanded . '" aria-controls="' . $id . '_spoiler" data-anchor="anchor_' . $id . '">
					' . $title . '
				</button>
				<a name="anchor_' . $id . '"></a>
			</h5>
		</div>
		<div id="' . $id . '_spoiler" class="collapse ' . $isShow . '" aria-labelledby="' . $id . '" data-parent="#accordion_' . $id . 'tables">
			<div class="card-body p-2">
    ';
}

/**
 *
 * Конец спойлера с return (для htmlData в json-ответе)
 *
 * @return string
 */
function spoiler_end_return()
{
	return '
			</div>
		</div>
	</div>
</div>
    ';
}

/**
 *
 * Вставка элемента с мануалом
 *
 * @param $html
 * @param string $img
 * @param bool $isLast
 */
function manual_item($html, $img = '', $isLast = false)
{
	echo '
    <div class="h6">' . $html . '</div>
    ';
	if (strlen($img) > 0)
	{
		echo '
    	<div class="align-center">
			<!--<img src="' . $img . '" class="img-fluid manual-img img-zoom" alt="">-->
			<a href="' . $img . '" data-lightbox="image-' . rand(0, 1000000) . '" data-title="Фотография">
                <img src="' . $img . '" class="img-fluid manual-img" alt="">			
            </a>
		</div>
    	';
	}
	if ($isLast === false)
	{
		echo '<br/>';
	}
}

function mysqleditor_modal_coder($data, $encode=true)
{
    if ( $encode )
    {
    	return base64_encode( json_encode($data) );
    } else
    {
    	return json_decode(base64_decode( $data ), 1);
    }
}

/**
 *
 * Генератор полей для mysqleditor
 *
 * @param string $data_table Название таблицы
 * @param string $data_fieldid Название Autoincrement-поля (AIF)
 * @param string $data_id Содержимое ID AIF
 * @param string $data_field Название изменяемого поля
 * @param string $data_return Что-то должно возвратиться (0-нет, 1-да)
 * @param string $data_loader ID поля, где отображается прелоадер
 * @param string $addon_attr Добавочные атрибуты
 * @param string $tag Тэг возврата данных
 * @param string $type Тип html-полня
 * @param string $class CLASS-html
 * @param string $id ID-html
 * @param string $placeholder Текст плейсхолдера
 * @param string $value Значение
 * @param string $data_action Тип действия
 * @param string $data_assoc Ассоцированная таблица (0-нет, 1-да)
 * @return string HTML-генерированное поле
 */
function mysqleditor_field_generator($data_table,
                                     $data_fieldid,
                                     $data_id,
                                     $data_field,
                                     $data_return = '0',
                                     $data_loader = '',
                                     $addon_attr = '',
                                     $tag = 'input',
                                     $type = 'text',
                                     $class = '',
                                     $id = '',
                                     $placeholder = '',
                                     $value = '',
                                     $data_action = 'edit',
                                     $data_assoc = '0')
{
	$default_class = 'mysqleditor';
	if ($class == '') $class = $default_class;
	$attr_id = ($id == '') ? '' : 'id="' . $id . '"';
	$attr_placeholder = ($placeholder == '') ? '' : 'placeholder="' . $placeholder . '"';
	$attr_value = ($value == '') ? '' : 'value="' . htmlspecialchars($value) . '"';
	$attr_loader = ($data_loader != null) ? $attr_loader = 'data-loader="' . $data_loader . '"' : '';
	$arr[$tag] = '
<' . $tag . '
    type="' . $type . '"
    class="' . $class . '"
    ' . $attr_id . '
    ' . $attr_placeholder . '
    ' . $attr_value . '
    data-action="' . $data_action . '" x
    data-table="' . $data_table . '"
    data-assoc="' . $data_assoc . '"
    data-fieldid="' . $data_fieldid . '"
    data-id="' . $data_id . '"
    data-field="' . $data_field . '"
    data-return="' . $data_return . '"
    ' . $attr_loader . ' ' . $addon_attr . '>
    ';
	
	return $arr;
	/**/
}

/**
 *
 * Вставка подсказки bootstrap
 *
 * @param $text
 * @return string
 */
function super_bootstrap_tooltip($text)
{
//    return 'data-toggle="tooltip" data-original-title="'.$text.'"';
	return 'data-toggle="tooltip" data-original-title="' . $text . '"';
}

/**
 *
 * Генерация textarea для mysqleditor
 *
 * @param string $data_table Название таблицы
 * @param string $data_fieldid Название Autoincrement-поля (AIF)
 * @param string $data_id Содержимое ID AIF
 * @param string $data_field Название изменяемого поля
 * @param string $data_return Что-то должно возвратиться (0-нет, 1-да)
 * @param string $data_loader ID поля, где отображается прелоадер
 * @param string $addon_attr Добавочные атрибуты
 * @param string $tag Тэг возврата данных
 * @param string $class CLASS-html
 * @param string $id ID-html
 * @param string $placeholder Текст плейсхолдера
 * @param string $value Значение
 * @param string $data_action Тип действия
 * @param string $data_assoc Ассоцированная таблица (0-нет, 1-да)
 * @return string HTML-генерированное поле
 */
function mysqleditor_textarea_generator($data_table,
                                        $data_fieldid,
                                        $data_id,
                                        $data_field,
                                        $data_return = '0',
                                        $data_loader = '',
                                        $addon_attr = '',
                                        $tag = 'textarea',
                                        $class = '',
                                        $id = '',
                                        $placeholder = '',
                                        $value = '',
                                        $data_action = 'edit',
                                        $data_assoc = '0')
{
	$default_class = 'mysqleditor';
	if ($class == '') $class = $default_class;
	$attr_id = ($id == '') ? '' : 'id="' . $id . '"';
	$attr_placeholder = ($placeholder == '') ? '' : 'placeholder="' . $placeholder . '"';
//    $attr_value = ($value == '') ? '' : 'value="'.htmlspecialchars($value).'"';
	$attr_loader = ($data_loader != null) ? $attr_loader = 'data-loader="' . $data_loader . '"' : '';
	$arr[$tag] = '
<textarea
    class="' . $class . '"
    ' . $attr_id . '
    ' . $attr_placeholder . '
    data-action="' . $data_action . '"
    data-table="' . $data_table . '"
    data-assoc="' . $data_assoc . '"
    data-fieldid="' . $data_fieldid . '"
    data-id="' . $data_id . '"
    data-field="' . $data_field . '"
    data-return="' . $data_return . '"
   ' . $attr_loader . ' ' . $addon_attr . '>' . htmlspecialchars($value) . '</textarea>
    ';
	
	return $arr;
	/**/
}

/**
 *
 * Форматирование имени пациента для запроса в поиске
 *
 * @param $str
 * @return array
 */
function name_for_query($str)
{
	$PatientNameData = explode(" ", $str);
	$PatientNameDataFormatted = array();
	$isFamilia = true;
	for ($i = 0; $i < count($PatientNameData); $i++)
	{
		$data = $PatientNameData[$i];
		if (strlen($data) > 0)
		{
			$data = mb_strtolower($data, UTF8);
			$data = nospaces($data);
			$data = str_replace(".", "", $data);
			$data = str_replace(",", "", $data);
			if (strlen($data) > 0)
			{
				if ($isFamilia) $isFamilia = false;
				else $data = firstLetter($data);
				
				$PatientNameDataFormatted[] = $data;
			}
		}
	}
	$querySearchPercent = '%' . $PatientNameDataFormatted[0] . '%%' . $PatientNameDataFormatted[1] . '%%' . $PatientNameDataFormatted[2] . '%';
	$querySearchPercentSpaces = '%' . $PatientNameDataFormatted[0] . '% %' . $PatientNameDataFormatted[1] . '% %' . $PatientNameDataFormatted[2] . '%';
	$querySearchPercentSpacesDefault = '%' . $PatientNameData[0] . '% %' . $PatientNameData[1] . '% %' . $PatientNameData[2] . '%';
	$querySearchPercentOneSpacesDefault = '%' . $PatientNameData[0] . '%' . $PatientNameData[1] . '%' . $PatientNameData[2] . '%';
	$patientClearName = $PatientNameDataFormatted[0] . ' ' . $PatientNameDataFormatted[1] . ' ' . $PatientNameDataFormatted[2];
	
	$arr = array(
		'PatientNameDataFormatted' => $PatientNameDataFormatted,
		'querySearchPercent' => $querySearchPercent,
		'querySearchPercentSpaces' => $querySearchPercentSpaces,
		'querySearchPercentSpacesDefault' => $querySearchPercentSpacesDefault,
		'querySearchPercentOneSpacesDefault' => $querySearchPercentOneSpacesDefault,
		'patientClearName' => $patientClearName,
		'nameStrToLower' => mb_strtolower($str, UTF8),
		'NameDataArray' => $PatientNameData
	);
	
	return $arr;
}

/**
 *
 * Генерация ссылки для открытия карточки пациента
 *
 * @param $name
 * @param $patid
 * @param string $addon
 * @return string
 */
function editPersonalDataLink($name, $patid, $addon = '')
{
	$name = nbsper($name);
	$a = '
    <a ' . $addon . ' href="javascript:editPersonalData(\'' . $patid . '\')"><b>' . $name . '</b></a>
    ';
	return $a;
}

/**
 *
 * Склонение существительных с числительными
 *
 * @param int $n число
 * @param string $form1 Единственная форма: 1 секунда
 * @param string $form2 Двойственная форма: 2 секунды
 * @param string $form5 Множественная форма: 5 секунд
 * @return string Правильная форма
 */
function pluralForm($n, $form1, $form2, $form5)
{
	$n = abs($n) % 100;
	$n1 = $n % 10;
	if ($n > 10 && $n < 20) return $form5;
	if ($n1 > 1 && $n1 < 5) return $form2;
	if ($n1 == 1) return $form1;
	return $form5;
}

/**
 *
 * Получить рандомный ключ
 *
 * @param $n
 * @return string
 */
function GetRandomCat($n)
{
	$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$randomString = '';
	
	for ($i = 0; $i < $n; $i++)
	{
		$index = rand(0, strlen($characters) - 1);
		$randomString .= $characters[$index];
	}
	
	return $randomString;
}

/**
 *
 * JS-скрипт перехода на страницу
 *
 * @param string $url
 */
function jsrefresh($url = '')
{
	echo '
<script>
window.location.href=\'' . $url . '\';
</script>
    ';
}

/**
 *
 * Форматирование имени доктора из массива данных о докторе
 *
 * @param $docarr
 * @param string $tp
 * @return string
 */
function docNameShort($docarr, $tp = "famio")
{
	$doc_name = $docarr['doctor_f'] . ' ' . $docarr['doctor_i'] . ' ' . $docarr['doctor_o'];
	$doc_name_fio = shorty($doc_name, $tp);
	return $doc_name_fio;
}

/**
 *
 * Замена двойных повторений элементов на одиночные
 *
 * @param $str
 * @param $fromWhat
 * @return string|string[]
 */
function ClearIt($str, $fromWhat)
{
	$doubleFromWhat = $fromWhat . $fromWhat;
	for ($i = 0; $i < 20; $i++)
	{
		$str = str_replace($doubleFromWhat, $fromWhat, $str);
	}
	return $str;
}

/**
 *
 * Получение данных записи из таблицы по id
 *
 * @param $record_id
 * @param $table
 * @param $record_field
 * @param bool $isMultiply
 * @return array
 */
function RecordManipulation($record_id, $table, $record_field, $isMultiply = false)
{
	$ret = array(
		'result' => false,
		'msg' => 'begin'
	);
	
	if (strlen($record_id) > 0)
	{
		$RecordData = getarr($table, "{$record_field}='{$record_id}'");
		if (count($RecordData) == 0)
		{
			$ret['msg'] = 'Записи с таким ID в таблице "' . $table . '" не найдено!';
		} else
		{
			if (count($RecordData) > 1)
			{
				if ($isMultiply)
				{
					$ret['result'] = true;
					$ret['data'] = $RecordData;
					$ret['amount'] = count($RecordData);
				} else
				{
					$ret['msg'] = 'Больше одного пациента относятся к данному ID в таблице "' . $table . '"';
					$ret['debug']['$RecordData'] = $RecordData;
				}
				
			} else
			{
				$ret['result'] = true;
				$ret['data'] = $RecordData[0];
				$ret['amount'] = count($RecordData);
			}
		}
	} else $ret['msg'] = 'Не выбрана запись';
	
	return $ret;
}

/**
 *
 * Получение линейных данных из массива
 *
 * @param $mysqlArray
 * @param $mysqlIndexField
 * @return string
 */
function getLinearIds($mysqlArray, $mysqlIndexField)
{
	$str = '';
	if ($mysqlIndexField == 999)
	{
		$str = implode(', ', $mysqlArray);
	} else
	{
		foreach ($mysqlArray as $mysqlItem)
		{
			if ($str == '')
			{
				$str = $mysqlItem[$mysqlIndexField];
			} else
			{
				$str .= ',' . $mysqlItem[$mysqlIndexField];
			}
		}
	}
	
	return '(' . $str . ')';
}

/**
 *
 * Получить ключи массива из данных ('key'=>'keydata', 'value'=>'valuedata')
 *
 * @param $arr
 * @param $keyField
 * @param $valueField
 * @return array
 */
function getLinkFields($arr, $keyField, $valueField)
{
	$result = [];
	foreach ($arr as $data)
	{
		$keyF = $data[$keyField];
		$valueF = $data[$valueField];
		$result[$keyF] = $valueF;
	}
	return $result;
}

/**
 *
 * Специфическая функция генерации доступов для врачей
 *
 * @param string $access_string строка доступа
 * @param string $access_field название поля для запроса
 * @param string $delimiter разделитель
 * @return bool|string
 */
function AccessString2QueryORs($access_string, $access_field, $delimiter = ';', $equalator = 'like')
{
	$query = '';
	$access_arr = explode($delimiter, $access_string);
	if (count($access_arr) > 0)
	{
		foreach ($access_arr as $access_str)
		{
			if ($equalator == 'like')
			{
				$q = "{$access_field} LIKE '%{$access_str}%'";
			} elseif ($equalator == '=')
			{
				$q = "{$access_field}='{$access_str}'";
			}
			
			if ($query == '')
			{
				$query = $q;
			} else
			{
				$query .= " OR " . $q;
			}
		}
		if (strlen($query) > 0)
		{
			return "({$query})";
		} else return false;
	} else return false;
}

/**
 *
 * Получаем строку для mysql-запроса из диагнозов
 *
 * @param string $diagnosis
 * @param string $field
 * @param string $delimiter
 * @param string $expander
 * @return false|string
 */
function getQueryByPattern($diagnosis, $field, $delimiter = ';', $expander = '+')
{
	if (strlen($diagnosis) > 0)
	{
		$diagnosis_arr = explode($delimiter, $diagnosis);
//		debug($diagnosis_arr);
		if (count($diagnosis_arr) > 0)
		{
			$query = '';
			foreach ($diagnosis_arr as $dg)
			{
//				debug('$dg: ' . $dg);
				$isExpanded = ifound($dg, $expander);
				
				$dg = str_replace($expander, '', $dg);
				$MKB_DATA = CheckMKBCode($dg);
				$MKB = $MKB_DATA['value'];
				
				if ($MKB !== false)
				{
					if ($isExpanded)
					{
						$sub_query = " {$field} LIKE '%{$dg}%' ";
//						debug('$sub_query+: ' . $sub_query);
					} else
					{
						$sub_query = " {$field}='{$dg}' ";
//						debug('$sub_query: ' . $sub_query);
					}
					
					if (strlen($query) == 0)
					{
						$query = $sub_query;
					} else
					{
						$query .= ' OR ' . $sub_query;
					}
					
				} else return false;
			}
//			debug($query);
			return $query;
		} else return false;
	} else return false;
}

/**
 *
 * Нормализация имени, очистка, удаление повторений
 *
 * @param $str
 * @return string
 */
function NAME_MORMALIZER($str)
{
	$str = nodoublespaces($str, 5);
	$str = trim($str);
	$str = mb_strtolower($str, UTF8);
	return $str;
}

/**
 *
 * Указанные периоды в массив с данными в unix
 *
 * @param $period
 * @return array|bool
 */
function PeriodToSec($period)
{
	$Periods = array(
		'24h' => array(
			'title' => '24 часа',
			'interval' => TIME_DAY
		),
		'7d' => array(
			'title' => '7 дней',
			'interval' => 7 * TIME_DAY
		),
		'14d' => array(
			'title' => '14 дней',
			'interval' => 14 * TIME_DAY
		),
		'21d' => array(
			'title' => '21 день',
			'interval' => 21 * TIME_DAY
		),
		'30d' => array(
			'title' => '30 дней',
			'interval' => 30 * TIME_DAY
		),
		'3mon' => array(
			'title' => '3 месяца',
			'interval' => 90 * TIME_DAY
		),
		'6mon' => array(
			'title' => '6 месяцев',
			'interval' => 180 * TIME_DAY
		),
		'1y' => array(
			'title' => '1 год',
			'interval' => 365 * TIME_DAY
		)
	);
	$Data = $Periods[$period];
	if (count($Data) > 0)
	{
		return $Data;
	} else return false;
}

/**
 *
 * Поиск одного значения по двумерному массиву stack в поле field значения needle
 *
 * @param array $stack
 * @param string $field
 * @param string|int $needle
 * @return array С одним значением
 */
function searchArray($stack, $field, $needle)
{
	$arr = array();
	$key = array_search($needle, array_column($stack, $field));

//	$arr['params'] = array(
//		'stack' => $stack,
//		'field' => $field,
//		'needle' => $needle
//	);
	if ($key !== false)
	{
		$arr['status'] = RES_SUCCESS;
		$arr['key'] = $key;
		$arr['data'] = $stack[$key];
	} else
	{
		$arr['status'] = "fail";
		$arr['key'] = $key;
	}
	
	return $arr;
}

/**
 *
 * Поиск всех значений по двумерному массиву stack в поле field значения needle
 *
 * @param array $stack массив с данными
 * @param string $field по какому полю искать
 * @param int|string $needle содержимое этого поля
 * @return array массив с данными
 */
function searchArrayMany($stack, $field, $needle)
{
	$arr = [];
	$results = [];
	foreach ($stack as $item)
	{
		if ($item[$field] == $needle)
			$results[] = $item;
	}

//	$arr['params'] = array(
//		'stack' => $stack,
//		'field' => $field,
//		'needle' => $needle
//	);
	if (count($results) > 0)
	{
		$arr['status'] = RES_SUCCESS;
		$arr['data'] = $results;
	} else
	{
		$arr['status'] = "fail";
	}
	
	return $arr;
}

/**
 *
 * Найти ссылки в тексте с заменой на a-тег
 *
 * @param $str
 * @return string|string[]|null
 */
function getLinksFromMessage($str)
{
	$pattern = '(?xi)\b((?:https?://|www\d{0,3}[.]|[a-z0-9.\-]+[.][a-z]{2,4}/)(?:[^\s()<>]+|\(([^\s()<>]+|(\([^\s()<>]+\)))*\))+(?:\(([^\s()<>]+|(\([^\s()<>]+\)))*\)|[^\s`!()\[\]{};:\'".,<>?«»“”‘’]))';
	return preg_replace_callback("#$pattern#i", function ($matches) {
		$input = $matches[0];
		$url = preg_match('!^https?://!i', $input) ? $input : "http://$input";
		return '<a href="' . $url . '" rel="nofollow" target="_blank">' . "<b>$input</b></a>";
	}, $str);
}

/**
 *
 * Получить теги из {tagname}
 *
 * @param $tag
 * @param $str
 * @return mixed
 */
function getTagValues($tag, $str)
{
	$re = sprintf("/\{(%s)\}(.+?)\{\/\\1\}/", preg_quote($tag));
	preg_match_all($re, $str, $matches);
	return $matches[2];
}

/**
 *
 * Проверяет, является ли диагноз $mkb диспансерным по списку $mkb_disp
 *
 * @param string $mkb
 * @param array $mkb_disp
 * @return array ['result'] = true;
 */
function CheckMKBDispancer($mkb, $mkb_disp)
{
	$response['result'] = false;
	$response['params']['$mkb'] = $mkb;
	$response['params']['$mkb_disp'] = $mkb_disp;
	foreach ($mkb_disp as $disp_mkb)
	{
		$debug_arr = array();
		$debug_arr['$disp_mkb'] = $disp_mkb;
		if (ifound($disp_mkb, '+'))
		{
			$debug_arr['isplus'] = true;
			$disp_mkb_clear = str_replace('+', '', $disp_mkb);
			$mkb_data = explode('.', $mkb);
			$debug_arr['$mkb_data'] = $mkb_data;
			$mkb_main = $mkb_data[0];
			$debug_arr['$mkb_main'] = $mkb_main;
			
			if ($mkb_main == $disp_mkb_clear)
			{
				$response['result'] = true;
				break;
			} else $response['result'] = false;
		} else
		{
			$debug_arr['isplus'] = false;
			$debug_arr['$disp_mkb'] = $disp_mkb;
			$debug_arr['$mkb'] = $mkb;
			$debug_arr['$disp_mkb == $mkb'] = $disp_mkb == $mkb;
			
			if ($disp_mkb == $mkb)
			{
				$response['result'] = true;
				break;
			} else $response['result'] = false;
		}
		$response['debug'][] = $debug_arr;
	}
	return $response;
}

/**
 *
 * Проверка строки на содержание МКБ с корректностью
 *
 * @param string $mkb код МКБ для проверки
 * @param bool $personal проверка только для меня
 * @return array
 */
function CheckMKBCode($mkb, $personal = false)
{
	
	$response['debug'] = [];
//	$VALUE = trim($mkb);
//	$VALUE = nodoublespaces($VALUE);
//	$VALUE = nospaces($VALUE);
//	$VALUE = str_replace(',', '.', $VALUE);
//	$VALUE = mb_strtoupper($VALUE, UTF8);
	
	$response['debug']['$personal'] = $personal;
	
	if (!$personal)
	{
		$isMatched = preg_match('/\b([A-Z]\d{2})(\.\d)?\b/', $mkb, $matches);
		
		if (count($matches) == 0)
		{
			$VALUE = false;
		} else
		{
			if ($matches[0] == $mkb)
			{
				$VALUE = $matches[0];
			} else
			{
				$VALUE = false;
			}
			
		}
	} else
	{
		$isMatched = preg_match('/\b([A-Z]\d{2})(\.\d)?\b/', $mkb, $matches);
		
		$response['debug']['$isMatched'] = $isMatched;
		$response['debug']['$matches'] = $matches;
		
		if (count($matches) == 0)
		{
			$MKB_CHAR = $mkb[0];
			$mkb_number = str_replace($MKB_CHAR, '', $mkb);
			$VALUE = NormalizeNumber($mkb_number, false);
			
			$response['debug']['$mkb_number'] = $mkb_number;
			$response['debug']['NormalizeNumber'] = $VALUE;
			
			if (strlen($VALUE) == 3)
			{
				$VALUE = chunk_split($VALUE, 2, '.');
				$VALUE = substr($VALUE, 0, -1);
				$VALUE = $MKB_CHAR . $VALUE;
			} else
			{
				$VALUE = false;
			}
		} else
		{
			if ($matches[0] == $mkb)
			{
				$VALUE = $matches[0];
			} else
			{
				$VALUE = false;
			}
			
		}
	}


//	$nodoth_Matched = preg_match('/\b([A-Z]\d{3})\b/', $mkb, $matches_nodoth);
//
//	if (count($matches_nodoth) == 0)
//	{
//
//	} else
//	{
//
//		$VALUE = substr($VALUE, 0, -1);
//	}
	
	$response['value'] = $VALUE;
	return $response;
//	return $VALUE;
}

/**
 *
 * Возвращает, является ли диагноз злокачественным или in situ
 *
 * @param string $mkb
 * @return bool
 */
function DiagnosisCancer($mkb)
{
	$mkb_first = $mkb[0];
	if ($mkb_first == "C")
	{
		return true;
	} else
	{
		if ($mkb_first == "D")
		{
			$mkb_number = str_replace($mkb_first, '', $mkb);
			if (ifound($mkb_number, '.'))
			{
				$mkb_data = explode('.', $mkb_number);
				$mkb_clear = (int)$mkb_data[0];
			} else
			{
				$mkb_clear = (int)$mkb_number;
			}
			if ($mkb_clear >= 0 && $mkb_clear <= 9)
			{
				return true;
			} else
			{
				return false;
			}
		} else
		{
			return false;
		}
	}
}

/**
 *
 * Приводит строку к число, убирая вообще ВЕСЬ мусор, включая точки и запятые
 *
 * @param $str
 * @param bool $set_intval
 * @return int
 */
function NormalizeNumber($str, $set_intval = true)
{
	$data = trim(preg_replace("/[^0-9]/", '', $str));
	if ($set_intval) return intval($data);
	else return $data;
	
}

/**
 *
 * Числовое окончание слов
 * getUnitCase(33, 'рубль', 'рубля', 'рублей');
 *
 * @param $value int
 * @param $unit1 string одна ШТУКА
 * @param $unit2 string две, три, четыре ШТУКИ
 * @param $unit3 string пять и более ШТУК
 * @return string
 */
function wordEnd($value, $unit1, $unit2, $unit3)
{
	$value = abs((int)$value);
	if (($value % 100 >= 11) && ($value % 100 <= 19))
	{
		return $unit3;
	} else
	{
		switch ($value % 10)
		{
			case 1:
				return $unit1;
			case 2:
			case 3:
			case 4:
				return $unit2;
			default:
				return $unit3;
		}
	}
}

/**
 *
 * Проверка заданных полей на заполненность
 *
 * @param $requiredFields array требуемые поля
 * @param $targetArray array проверяемый массив
 * @return array|bool
 */
function checkRequiredFields($requiredFields, $targetArray)
{
	$isOk = false;
	$required = [];
	foreach ($requiredFields as $requiredField)
	{
		if (strlen($targetArray[$requiredField]) > 0)
		{
			continue;
		} else
		{
			$required[] = $requiredField;
		}
	}
	if (count($required) > 0)
		return $required;
	else
		return true;
}

/**
 *
 * Анализирует код МКБ и подбирает цвет
 *
 * @param string $mkb код по МКБ-10
 * @return false|string
 */
function mkbAnalizer($mkb)
{
	$MKB_DATA = CheckMKBCode($mkb);
	$mkb_correct = $MKB_DATA['value'];
	if ($mkb_correct !== false)
	{
		$mkb_main = $mkb_correct;
		if (ifound($mkb_correct, '.'))
		{
			$mkb_arr = explode('.', $mkb_correct);
			$mkb_main = $mkb_arr[0];
		}
		
		if (strlen($mkb_main) > 0)
		{
			
			if (ifound($mkb_main, 'C'/*английская СИ*/) || ifound($mkb_main, 'С'/*русская ЭС*/))
			{
				return BT_THEME_DANGER; /*CANCER*/
			} else
			{
				if (ifound($mkb_main, 'D'))
				{
					
					$MKB_NUMBER = (int)str_replace('D', '', $mkb_main);
					
					if ($MKB_NUMBER >= 0 && $MKB_NUMBER <= 9)
					{ /*CANCER IN SITU*/
						return BT_THEME_SECONDARY;
					} elseif ($MKB_NUMBER >= 10 && $MKB_NUMBER <= 36)
					{ /*NOT A CANCER*/
						return BT_THEME_INFO;
					} elseif ($MKB_NUMBER >= 37 && $MKB_NUMBER <= 48)
					{ /*SUSPICIO*/
						return BT_THEME_WARNING;
					} else
					{ /*WRONG DIAGNOSIS*/
						return BT_THEME_PRIMARY;
					}
					
				} else
				{
					return 'primary'; /*Любые другие диагнозы*/
				}
			}
			
		} else return false;
		
	} else return false;
}

/**
 *
 * Преобразование doctor_f, doctor_i и doctor_o в единую строку full_name
 *
 * @param array $docsArr массив с данными врача
 * @param bool $isMB создать нормальную строку
 * @param int $onlyName только имя ID
 * @return array
 */
function docFtoFIO($docsArr, $isMB = false, $onlyName = null)
{
	$new_docsArr = [];
	foreach ($docsArr as $docId => $doc)
	{
		$full_name = $doc['doctor_f'] . ' ' . $doc['doctor_i'] . ' ' . $doc['doctor_o'];
		if ($isMB)
		{
			$full_name = mb_ucwords($full_name);
		}
		$doc['doctor_fio'] = $full_name;
		$new_docsArr[$docId] = $doc;
	}
	
	if ($onlyName > 0)
	{
		return $new_docsArr['id' . $onlyName]['doctor_fio'];
	} else
	{
		return $new_docsArr;
	}
}

/**
 *
 * Получить возраст человека из строки в формате DD.MM.YYYY
 *
 * @param $birth_string
 * @return array|false|int|mixed|string|string[]
 */
function ageByBirth($birth_string)
{
	$birthDate = str_replace(".", "/", $birth_string);
	//explode the date to get month, day and year
	$birthDate = explode("/", $birthDate);
	//get age from date or birthdate
	return (date("md", date("U", mktime(0, 0, 0, $birthDate[0], $birthDate[1], $birthDate[2]))) > date("md")
		? ((date("Y") - $birthDate[2]) - 1)
		: (date("Y") - $birthDate[2]));
}

/**
 *
 * Преобразование массива объектов key=>value, полученного в результате json_decode в массив key=>value
 *
 * @param array $arr массив объектов из json-строки
 * @return array
 */
function array2KeyValueArray($arr)
{
	$result = [];
	foreach ($arr as $item)
	{
		$result[$item['key']] = $item['value'];
	}
	return $result;
}

/**
 *
 * Преобразует строку в таблицу (типа КОД ОГРН)
 *
 * @param string $str преобразуемая строка
 * @return false|string
 */
function strInTD($str)
{
	$result = '';
	$arr = str_split($str);
	if (count($arr) > 0)
	{
		foreach ($arr as $index => $item)
		{
			$result .= '<td data="' . $index . '">' . $item . '</td>';
		}
		return $result;
	} else return false;
}

/**
 *
 * Преобразует дату в русский формат с месяцем
 *
 * @param $str
 * @param string $sep
 * @return string
 */
function processRussianDate($str, $sep = ' ')
{
	$monthArray = array(
		'Января' => '01',
		'Февраля' => '02',
		'Марта' => '03',
		'Апреля' => '04',
		'Мая' => '05',
		'Июня' => '06',
		'Июля' => '07',
		'Августа' => '08',
		'Сентября' => '09',
		'Октября' => '10',
		'Ноября' => '11',
		'Декабря' => '12'
	);
	$dateData = explode($sep, $str);
	$day = $dateData[0];
	$year = $dateData[2];
	$monthStr = $dateData[1];
	$month = $monthArray[$monthStr];
	return $day . '.' . $month . '.' . $year;
	
}

/**
 *
 * Преобразует числовой формат в дату "12" Ноября 2023
 *
 * @param $str
 * @param string $sep
 * @param string $ins
 * @param bool $hqm
 * @return string
 */
function processRussianDateReverse($str, $sep = ' ', $ins = '.', $hqm = true)
{
	$monthArray = array(
		'01' => 'Января',
		'02' => 'Февраля',
		'03' => 'Марта',
		'04' => 'Апреля',
		'05' => 'Мая',
		'06' => 'Июня',
		'07' => 'Июля',
		'08' => 'Августа',
		'09' => 'Сентября',
		'10' => 'Октября',
		'11' => 'Ноября',
		'12' => 'Декабря'
	);
	$dateData = explode($sep, $str);
	$day = $dateData[0];
	$year = $dateData[2];
	$monthStr = $dateData[1];
	$month = $monthArray[$monthStr];
	if ($hqm)
	{
		$day = "«" . $day . "»";
	}
	return $day . $ins . $month . $ins . $year;
	
}

/**
 *
 * Формирует строку гистологии по полученному массиву
 *
 * @param $research_data
 * @return string
 */
function getMorphOfResearch($research_data)
{
	$morph = '';
	if (strlen($research_data['research_morph_text']) > 0)
	{
		$morph = '. Гистология';
		if (strlen($research_data['research_morph_ident']) > 0)
		{
			$morph .= ' №' . $research_data['research_morph_ident'];
		}
		if (strlen($research_data['research_morph_date']) > 0)
		{
			$morph .= ' от ' . $research_data['research_morph_date'];
		}
		$morph .= ' - ' . $research_data['research_morph_text'];
	}
	
	return $morph;
}

/**
 *
 * Проверяет данные дневника пациента
 *
 * @param array $patients список пациентов
 * @return array
 */
function patientCheck($patients)
{
	
	$response = [];
	$JOURNAL_ERRORS = 'journal_errors';
	$PERSONAL_ERRORS = 'personal_errors';
	$MOVES = 'moves';
	$GRANTED = 'granted';
//    $response[$JOURNAL_ERRORS] = [];
//    $response[$PERSONAL_ERRORS] = [];
//    $response[$MOVES] = [];
//    $response[$GRANTED] = [];
	foreach ($patients as $patient)
	{
		
		if (strlen($patient['journal_need_move']) > 1)
		{
          	//debug($patient);
			$patient['ERROR_FIELD'] = 'journal_need_move';
			$response[$MOVES][] = $patient;
		}
      	//debug($patient);
		if ( $patient['journal_visit_type'] == 1 )
		{
			// 1 - время приёма
			if (strlen($patient['journal_time']) == 0)
			{
				$patient['ERROR_FIELD'] = 'journal_time';
				$response[$JOURNAL_ERRORS][] = $patient;
				continue;
			}
			// 2 - диагноз по МКБ
			if (strlen($patient['journal_ds']) == 0)
			{
				$patient['ERROR_FIELD'] = 'journal_ds';
				$response[$JOURNAL_ERRORS][] = $patient;
				continue;
			}
			// 3 - текст диагноза
			if (strlen($patient['journal_ds_text']) == 0)
			{
				$patient['ERROR_FIELD'] = 'journal_ds_text';
				$response[$JOURNAL_ERRORS][] = $patient;
				continue;
			}
			// 4 - рекомендации по диагнозу
			if (strlen($patient['journal_ds_recom']) == 0)
			{
				$patient['ERROR_FIELD'] = 'journal_ds_recom';
				$response[$JOURNAL_ERRORS][] = $patient;
				continue;
			}
			// 5 - рекомендации по исходу
			if (strlen($patient['journal_recom']) == 0)
			{
				$patient['ERROR_FIELD'] = 'journal_recom';
				$response[$JOURNAL_ERRORS][] = $patient;
				continue;
			}
			// 6 - место карты
			if (strlen($patient['journal_cardplace']) == 0)
			{
				$patient['ERROR_FIELD'] = 'journal_cardplace';
				$response[$JOURNAL_ERRORS][] = $patient;
				continue;
			}
			// 7 - случай
			if ($patient['journal_infirst'] < 1)
			{
				$patient['ERROR_FIELD'] = 'journal_infirst';
				$response[$JOURNAL_ERRORS][] = $patient;
				continue;
			}
			// 8 - направление в другое отделение
			if ($patient['journal_dirstac'] < 0)
			{
				$patient['ERROR_FIELD'] = 'journal_dirstac';
				$response[$JOURNAL_ERRORS][] = $patient;
				continue;
			} else
			{
				if ($patient['journal_dirstac'] == 6)
				{
					if (strlen($patient['journal_dirstac_desc']) == 0)
					{
						$patient['ERROR_FIELD'] = 'journal_dirstac_desc';
						$response[$JOURNAL_ERRORS][] = $patient;
						continue;
					}
				} else continue;
			}
		}
		
		/*
		// 9 - диспансерный
		if ( $patient['journal_disp_isDisp'] == 0 )
		{
			$patient['ERROR_FIELD'] = 'journal_disp_isDisp';
			$response[$JOURNAL_ERRORS][] = $patient;
			continue;
		} elseif ($patient['journal_disp_isDisp'] > 1)
		{
			if ( $patient['journal_disp_lpu'] < 1 )
			{
				$patient['ERROR_FIELD'] = 'journal_disp_lpu';
				$response[$JOURNAL_ERRORS][] = $patient;
				continue;
			}
			if (strlen($patient['journal_disp_mkb']) == 0 )
			{
				
				$patient['ERROR_FIELD'] = 'journal_disp_mkb';
				$response[$JOURNAL_ERRORS][] = $patient;
				continue;
			}
		}
		*/
	}
	//debug($response);
	return $response;
}

/**
 *
 * Получает данные из массива $arr по ключам, где ключ совпадает с искомой частью ключа $partOfKey!
 *
 * @param array $arr массив с данными
 * @param string $partOfKey название ключа или его часть
 * @param bool $indexed искать по key
 * @return array
 */
function extractValueByKey($arr, $partOfKey, $indexed = false)
{
	$newArr = [];
	if ($indexed)
	{
		foreach ($arr as $item)
		{
			$newArrItem = [];
			foreach ($item as $key => $elem)
			{
				if (str_starts_with($key, $partOfKey) === true)
				{
					$newArrItem[$key] = $elem;
				}
			}
			$newArr[] = $newArrItem;
		}
	} else
	{
		foreach ($arr as $key => $value)
		{
			if (str_starts_with($key, $partOfKey) === true)
			{
				$newArr[$key] = $value;
			}
		}
	}
	
	
	return $newArr;
}

/**
 *
 * Преобразует dd.mm (без года) в ssboy-формат (количество секунд с начала месяца)
 *
 * @param string $str строка с датой
 * @param bool $is_end_of_day конечная дата? (если да - прибавляет СУТКИ минус 1 сек.)
 * @param bool $sbboy_to_unix true - преобразует строку в sbboy, false - преобразует sbboy в unix
 * @param string $sep разделитель
 * @return false|float|int
 */
function ssboy($str, $is_end_of_day = false, $sbboy_to_unix = true, $sep = '.')
{
	$current_year = date("Y", time());
	$begin_of_year = '01.01.' . $current_year;
	$begin_of_year_unix = birthToUnix($begin_of_year);
	
	if ($sbboy_to_unix)
	{
		// получить sbboy от даты
		$str_data = explode($sep, $str);
		if (count($str_data) == 2)
		{
			$date = $str . '.' . $current_year;
			$date_unix = birthToUnix($date);
			
			if ($is_end_of_day)
			{
				return ($date_unix - $begin_of_year_unix) + (TIME_DAY - 1);
			} else
			{
				return $date_unix - $begin_of_year_unix;
			}
			
		} else return false;
		
		
	} else
	{
		// получить дату по sbboy
		if (intval($str) || "" . $str == "0")
		{
			$sbboy = (int)$str;
			return $begin_of_year_unix + $sbboy;
		} else return false;
	}
	
}

/**
 *
 * Получает полный путь до файла с таблицей
 *
 * @param array $FileData
 * @return false|string
 */
function getFullPathOfTableFile($FileData)
{
	$file_name = $FileData['file_md5'] . '.' . $FileData['file_ext'];
	$file_path = TABLES_FILES_PATH . $file_name;
	if (file_exists($file_path))
	{
		return $file_path;
	} else return false;
}

/**
 *
 * Парсит данные из файла $file_path и преобразует в массив
 *
 * @param string $file_path путь к файлу
 * @return array
 * @throws PHPExcel_Exception
 * @throws PHPExcel_Reader_Exception
 */
function Xls2Array($file_path, $preview = false)
{
	$excelReader = PHPExcel_IOFactory::createReaderForFile($file_path);
	$excelObj = $excelReader->load($file_path);
	$worksheet = $excelObj->getSheet(0);
	
	$table = array();
	$col = 1;
	foreach ($worksheet->getRowIterator() as $row)
	{
		
		$cellIterator = $row->getCellIterator();
		$cellIterator->setIterateOnlyExistingCells(FALSE);
		$row = 1;
		foreach ($cellIterator as $cell)
		{
			$VALUE = $cell->getValue();
			if (PHPExcel_Shared_Date::isDateTime($cell))
			{
				$VALUE = date(DMY, PHPExcel_Shared_Date::ExcelToPHP($VALUE));
			} else
			{
				$VALUE = (ifound($VALUE, '=')) ? $cell->getOldCalculatedValue() : $cell->getValue();
			}
			
			$table[$col][$row] = trim($VALUE);
			$row++;
		}
		$col++;
		
		if ($preview !== false && count($table) >= $preview) break;
	}
	
	return $table;
}

/**
 *
 * Вызов тега редиректа meta
 *
 * @param string $url url
 * @param int $timeout время таймаута для редиректа
 * @param false $ret возвращать или печатать?
 * @return string
 */
function meta($url = '', $timeout = 0, $ret = false)
{
	$meta = '<meta http-equiv="refresh" content="' . $timeout . '"; ' . $url . ' />';
	if ($ret)
	{
		return $meta;
	} else
	{
		echo $meta;
	}
}

/**
 *
 * Конвертирует размер байтов до ближайшего большего размера
 *
 * @param integer $size
 * @return string
 */
function memorySizeConvert($size)
{
	$i = 0;
	while (floor($size / 1024) > 0)
	{
		$i++;
		$size /= 1024;
	}
	
	$name = array('байт', 'КБ', 'МБ', 'ГБ', 'ТБ');
	return round($size, 2) . ' ' . $name[$i];
}

/**
 *
 * Получает доступный размер памяти из ini_get()
 *
 * @return float|int
 */
function MemoryLimit()
{
	return ini_get('memory_limit') * 1024 * 1024;
}

/**
 *
 * Ставит $num переносов строк \n
 *
 * @param int $num
 * @return string
 */
function n($num = 1)
{
	return str_repeat("\n", $num);
}

/**
 *
 * Создаем хэш для быстрого входа
 *
 * @param array $doc_arr данные врача
 * @param integer $hash_unix unix, от которого создать хэш
 * @return array
 */
function createNewQuickHash($doc_arr, $hash_unix)
{
	global $PK;
	
	$fio_emias_hash_arr = [$doc_arr['doctor_f'], $doc_arr['doctor_i'], $doc_arr['doctor_o'], $doc_arr['doctor_miac_login'], $hash_unix];
	$fio_emias_hash_string = implode('_', $fio_emias_hash_arr);
	$fio_emias_hash = hash(SHA512, $fio_emias_hash_string);
	
	$param_update_doctor_quick = array(
		'doctor_quick' => $fio_emias_hash,
		'doctor_quick_unix' => $hash_unix
	);
	return updateData(CAOP_DOCTOR, $param_update_doctor_quick, $doc_arr, $PK[CAOP_DOCTOR] . "='{$doc_arr[$PK[CAOP_DOCTOR]]}'");
}

/**
 *
 * Получаем или обновляем хэш для врача
 *
 * @param $doc_arr
 * @param string $hash
 * @param false $create_new
 * @return array|bool|mixed
 */
function quickHashByDocarr($doc_arr, $hash = '', $create_new = false)
{
	global $PK;
	
	$result = false;
	
	if ($create_new)
	{
		$hash_unix = time();
		$result = createNewQuickHash($doc_arr, $hash_unix);
	} else
	{
		if (strlen($doc_arr['doctor_quick']) > 0)
		{
			$result = $doc_arr;
		} else
		{
			$hash_unix = time();
			$result = createNewQuickHash($doc_arr, $hash_unix);
		}
	}
	
	if (strlen($hash) > 0)
	{
		$hash_unix = $doc_arr['doctor_quick_unix'];
		
		$fio_emias_hash_arr = [$doc_arr['doctor_f'], $doc_arr['doctor_i'], $doc_arr['doctor_o'], $doc_arr['doctor_miac_login'], $hash_unix];
		$fio_emias_hash_string = implode('_', $fio_emias_hash_arr);
		$fio_emias_hash = hash(SHA512, $fio_emias_hash_string);
		
		if ($hash == $fio_emias_hash) $result = true;
		else $result = false;
	}
	
	return $result;
}

// source: Laravel Framework
// https://github.com/laravel/framework/blob/8.x/src/Illuminate/Support/Str.php
if (!function_exists('str_starts_with'))
{
	/**
	 *
	 * Строка начинается С
	 *
	 * @param $haystack
	 * @param $needle
	 * @return bool
	 */
	function str_starts_with($haystack, $needle)
	{
		return (string)$needle !== '' && strncmp($haystack, $needle, strlen($needle)) === 0;
	}
}
if (!function_exists('str_ends_with'))
{
	/**
	 *
	 * Строка заканчивается НА
	 *
	 * @param $haystack
	 * @param $needle
	 * @return bool
	 */
	function str_ends_with($haystack, $needle)
	{
		return $needle !== '' && substr($haystack, -strlen($needle)) === (string)$needle;
	}
}

/**
 *
 * Получить пол пациента по отчеству
 *
 * @param string $fio Ф.И.О. пациента
 * @return false|int
 */
function getGender($fio)
{
	if (strlen($fio) > 0)
	{
		if (str_ends_with($fio, "вич"))
		{
			return 1;
		} elseif (str_ends_with($fio, "вна"))
		{
			return 2;
		} else
		{
			return 0;
		}
	} else return false;
}

/**
 *
 * Парсит стадийность по Mysql-данным
 *
 * @return array
 */
function stnmg_parser()
{
	global $STNMG;
	
	$tnm_s = searchArrayMany($STNMG, "stnmg_type", "s");
	$tnm_t = searchArrayMany($STNMG, "stnmg_type", "t");
	$tnm_n = searchArrayMany($STNMG, "stnmg_type", "n");
	$tnm_m = searchArrayMany($STNMG, "stnmg_type", "m");
	$tnm_g = searchArrayMany($STNMG, "stnmg_type", "g");
	
	
	return array(
		's' => $tnm_s['data'],
		't' => $tnm_t['data'],
		'n' => $tnm_n['data'],
		'm' => $tnm_m['data'],
		'g' => $tnm_g['data'],
	);
}

/**
 *
 * Преобразует unix в дату по указанному формату
 *
 * @param string $format формат
 * @param int $timestamp unix
 * @param string $err текст ошибки, если надо вернуть
 * @return false|string
 */
function dateme($format, $timestamp, $err = '')
{
	if ($timestamp > 0) return date($format, $timestamp);
	else return $err;
}

function group_array_by_field($arr, $fields, $prefix='id')
{
    if ( count($arr) > 0 )
    {
        if ( count($fields) > 0 )
        {
        	$new_arr = [];
            foreach ($arr as $item)
            {
	            foreach ($fields as $field)
	            {
		            $value = $item[$field];
		            if ( $value !== null )
		            {
			            $new_arr[$prefix . $value][] = $item;
			            break;
		            }
            	}
            }
            return $new_arr;
        }
    }
    return false;
}

/**
 *
 * Логирование действий врачей
 *
 * @param integer $action id действия
 * @param string $IP IP
 * @param string $cat CA-токен
 * @param integer $doctor_id ид доктора
 * @param string $info1 доп. инфа 1
 * @param string $info2 доп. инфа 2
 * @param string $info3 доп. инфа 3
 * @param string $info4 доп. инфа 4
 * @param string $info5 доп. инфа 5
 */
function LoggerGlobal($action, $IP, $cat, $doctor_id, $info1='', $info2='', $info3='', $info4='', $info5='')
{
	$LOGGER_VALUES = array(
		'log_ip' => $IP,
		'log_cat_id' => $cat,
		'log_action_id' => $action,
		'log_action_doctor_id' => $doctor_id,
		'log_date_unix' => time()
	);
	if ( strlen($info1) > 0 ) $LOGGER_VALUES['log_target_info_1'] = $info1;
	if ( strlen($info2) > 0 ) $LOGGER_VALUES['log_target_info_2'] = $info2;
	if ( strlen($info3) > 0 ) $LOGGER_VALUES['log_target_info_3'] = $info3;
	if ( strlen($info4) > 0 ) $LOGGER_VALUES['log_target_info_4'] = $info4;
	if ( strlen($info5) > 0 ) $LOGGER_VALUES['log_target_info_5'] = $info5;
	
	$LOGGER = appendData(CAOP_LOG, $LOGGER_VALUES);
}

function pages_access_filter($user, $pages_list)
{
	$pages_granted = [];
    if ( strlen($user['doctor_access']) > 0 )
    {
	    if (count($pages_list) > 0)
	    {
			$user_access = explode(';', $user['doctor_access']);
			if ( count($user_access) > 0 )
			{
				foreach ($pages_list as $page)
				{
					$page_test = [];
					
					$page_access = explode(';', $page['pages_access']);
					if ( count($page_access) > 0 )
					{
						if ( array_intersect($user_access, $page_access) )
						{
//							$page_test['user'] = $user_access;
//							$page_test['page'] = $page_access;
//							$page_test['data'] = $page;
//							$pages_granted[] = $page_test;
							$pages_granted[] = $page;
						}
					}
			    }
			}
	    }
    }
    return $pages_granted;
}

function getDoctorRedirectPath($DoctorHash)
{
	global $PK;
	
	$redirect_path = '\'/news\'';
	
	$go_next = false;
	$defaultRedirect = doctor_param('get', $DoctorHash['doctor_id'], 'defaultRedirect');
	if ( $defaultRedirect['stat'] == RES_SUCCESS )
	{
		$go_next = true;
	} else
	{
		$defaultRedirect = doctor_param('set', $DoctorHash['doctor_id'], 'defaultRedirect', '8');
		$defaultRedirect = doctor_param('get', $DoctorHash['doctor_id'], 'defaultRedirect');
		$go_next = true;
	}
	$Redirect = $defaultRedirect['data']['settings_param_value'];
	$PageData = getarr(CAOP_PAGES, "{$PK[CAOP_PAGES]}='$Redirect'");
	if ( count($PageData) > 0 )
	{
		$PageData = $PageData[0];
		$redirect_path = '\'/'.$PageData['pages_link'].'\'';
	}
	
	return $redirect_path;
}

function getHomeVisits($home_id=null, $doctor_id=null, $only_doctor=false, $only_last=false)
{
	$HOME_ID = ( $home_id ) ? " AND home_id='{$home_id}'" : '';
	$DOCTOR_ID = ( $doctor_id ) ? " AND home_doctor_id='{$doctor_id}'" : '';
	$new_arr = [];
//	$HomeVisits = getarr(CAOP_HOME_VISIT, 1, "ORDER BY home_patient_full_name ASC");
	$HomeVisits_query = "
	SELECT * FROM ".CAOP_HOME_VISIT."
	LEFT JOIN ".CAOP_DOCTOR." ON ".CAOP_DOCTOR.".doctor_id=".CAOP_HOME_VISIT.".home_doctor_id
	WHERE 1
	{$HOME_ID}
	{$DOCTOR_ID}
	ORDER BY home_patient_full_name ASC
	";
	$HomeVisits_result = mqc($HomeVisits_query);
	$HomeVisits = mr2a($HomeVisits_result);
	if ( count($HomeVisits) > 0 )
	{
		
		foreach ($HomeVisits as $homeVisit)
		{
			$LIMIT = ( $only_doctor || $only_last ) ? 'LIMIT 1' : '';
			$Actions_query = "
			SELECT * FROM ".CAOP_HOME_VISIT_ACTIONS."
			LEFT JOIN ".CAOP_HOME_VISIT_TYPES." ON ".CAOP_HOME_VISIT_TYPES.".type_id=".CAOP_HOME_VISIT_ACTIONS.".action_type_id
			LEFT JOIN ".CAOP_DOCTOR." ON ".CAOP_DOCTOR.".doctor_id=".CAOP_HOME_VISIT_ACTIONS.".action_personal_id
			WHERE action_home_id='{$homeVisit['home_id']}'
			ORDER BY action_unix DESC
			{$LIMIT}
			";
			$Actions_result = mqc($Actions_query);
			$Actions = mr2a($Actions_result);
			
			
			if ( $only_doctor )
			{
				$last_action = $Actions[0];
				if ( $last_action['action_type_id'] == 3 )
				{
					$new_arr[] = $homeVisit;
				}
				
			} else
			{
				if ( $only_last )
				{
					$homeVisit['actions'] = $Actions[0];
				} else $homeVisit['actions'] = $Actions;
				
				$new_arr[] = $homeVisit;
			}
		}
	}
	return $new_arr;
}

function var_dump_ret($mixed = null) {
	ob_start();
	var_dump($mixed);
	$content = ob_get_contents();
	ob_end_clean();
	return $content;
}