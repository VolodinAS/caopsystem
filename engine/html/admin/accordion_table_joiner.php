<?php
spoiler_begin('Работа с table_joiner()', 'tableJoiner', 'collapse', '', 'admin-spoiler');
?>
<h4>Обновлено: 07.07.2022 22:44 ТЛТ</h4>
<ul>
    <li>Создаем массив <b>$data</b></li>
    <li>Внутри создаем два ключа-массива <b>'tables'</b> и <b>'fields'</b></li>
    <li>Главная таблица  в массиве <b>'tables'</b> должна быть с индексом 0, остальные идут в JOIN</li>
    <li>Массив 'tables' состоит из индексированных массивов с ключами: <ul>
            <li><b>'table_name'</b> - название таблицы</li>
            <li><b>'table_join'</b> - JOIN-тип (кроме нулевой таблицы)</li>
            <li><b>'table_field_id'</b> - поле, по которому будут связываться таблицы (указывается для каждой таблицы)</li>
            <li><b>'table_main_field'</b> - поле из главной таблицы, к которому идет сравнение с подключаемой таблицей</li>
        </ul></li>
    <li>В ключ-строку <b>'fields'</b> прописываем запрашиваемые поля (или *)</li>
    <li>В ключ-строку <b>'where'</b> прописываем критерии отбора</li>
    <li>В ключ-строку <b>'addon'</b> прописываем прочие критерии (GROUP, ORDER BY и т.д.)</li>
    <li><b>Образец:</b><pre>
$data = array(
	'tables' => array(
        array(
            'table_name' => CAOP_JOURNAL,
            'table_field_id' => 'journal_day'
        ),
        array(
            'table_name' => CAOP_DAYS,
            'table_join' => 'LEFT JOIN',
            'table_main_field' => 'journal_day',
            'table_field_id' => $PK[CAOP_DAYS]
        ),
        array(
            'table_name' => CAOP_PATIENTS,
            'table_join' => 'LEFT JOIN',
            'table_main_field' => 'journal_patid',
            'table_field_id' => $PK[CAOP_PATIENTS]
        )
    ),
    'fields' => '*',
    'where' => CAOP_JOURNAL . ".journal_disp_isDisp=2",
    'addon' => 'ORDER BY '.CAOP_DAYS.'.day_unix ASC'
);
$tj_query = table_joiner($data);

// SELECT * FROM caop_journal LEFT JOIN caop_days ON caop_journal.journal_day=caop_days.day_id LEFT JOIN caop_patients ON caop_journal.journal_patid=caop_patients.patid_id WHERE caop_journal.journal_disp_isDisp=2  ORDER BY caop_days.day_unix ASC
						</pre></li>
</ul>
<?php
spoiler_end();
?>