<?php
$DivideSettings = array(
	'title' => 'Добавление отправки формы через jquery',
	'dom_title' => 'form_jquery',
	'updated_date' => '06.10.2022',
	'updated_time' => '10:30'
);

spoiler_begin($DivideSettings['title'], $DivideSettings['dom_title'], 'collapse', '', 'admin-spoiler');
?>
<h4>Обновлено: <?=$DivideSettings['updated_date'];?> <?=$DivideSettings['updated_time'];?> ТЛТ</h4>
<ul>
    <li>Создаем форму, даем <b>id="название_form"</b></li>
    <li>Размещаем кнопку, присваиваем класс (ид), по указанному атрибуту создаем событие</li>
    <li>Внутри события делаем <b>Event.preventDefault()</b> и добавляет коллектор формы <b>let form_data = formSerializer('#ИД_ФОРМЫ');</b></li>
</ul>
<?php
spoiler_end();
?>