<?php
spoiler_begin('Работа с MySQLEditor.Filters.js', 'mysqleditor_filters', 'collapse', '', 'admin-spoiler');
?>
<h4>Обновлено: 28.01.2023 15:33:41 ТЛТ</h4>
<?php
spoiler_begin('Список используемых файлов', 'mysqleditor_filters_files', '');
?>
<ul>
	<li><b>mysqleditor.filters.php</b> - основной файл с логикой работы фильтров</li>
	<li><b>mysqleditor.filters.config.php</b> - файл конфигураций и функций, подключается в начале приложения</li>
	<li><b>mysqleditor.filters/filters.php</b> - основное модальное окно с подгружаемыми настройками</li>
	<li><b>папка filter_type</b> - формы ввода, подстроенные под типы фильтров</li>
	<li><b>mysqleditor.filters/processors.php</b> - функции-процессоры и функции-препроцессоры (пользовательские)</li>
	<li><b>MySQLEditor.Filters.js</b> - рабочий JS-файл</li>
</ul>
<?php
spoiler_end();
?>
<b>Начало работы с MySQLEditor.Filters</b>
<ul>
	<li>Подключить все выше указанные файлы</li>
	<li>Добавить кнопку с параметрами
		<code>
			<pre>
$options_patid_name = array(
	'class' => 'btn btn-secondary', // добавочные классы
	'filter' => 'patid_name', // название фильтра
	'table' => CAOP_ZNO_DU, // фильтруемая таблица
	'field' => 'patid_name', // поле, по которому проходит фильтрация
	'relatedfield' => 1, // относится ли указанное поле к таблице: 1 - поле другой таблицы, 2 - поле указанной таблицы
	'type' => MEF_TEXT, // тип обработки фильтра
	'default_icon' => BT_ICON_REFRESH, // иконка по умолчанию (если никаких обработок нет)
	'default_icon_ok' => BT_ICON_OK, // если фильтр включен и присутствуют данные
	'default_icon_close' => BT_ICON_CLOSE_LG, // фильтр выключен
	'default_icon_empty' => BT_ICON_COPY, // фильтр включён, но данных нет
	'title' => 'По Ф.И.О.' // заголовок кнопки
);
$button_patid_name = <b>mysqleditor_filters_button</b>($options_patid_name); // вызов функции создания кнопки
echo $button_patid_name; // вывод кнопки
			</pre>
		</code>
	</li>
</ul>
<?php
spoiler_end();
?>