<?php
spoiler_begin('Работа с session_print', 'sessionPrint', 'collapse', '', 'admin-spoiler');
?>
<h4>Обновлено: 14.09.2022 17:38 ТЛТ</h4>
<ul>
    <li>Создаем ajax-запрос на <b>/processor/sessionPrint</b></li>
    <li>В аттрибуте data указываем объекты с нужными параметрами и добавляем <b>doctype: "НАЗВАНИЕ_ОТЧЁТА"</b></li>
    <li>В <b>json.result==true</b> вствляем <b>window.open(json.url);</b></li>
    <li>В скрипте <b>engine/php/processor/sessionPrint.php</b> добавляем новый кейс</li>
    <li>Внутри кейса при необходимости проводим обработку данных, но главное - добавить переданные параметры в <b>$_SESSION[$doctype]['параметр'] = 'значение'</b></li>
    <li>Также, добавить <b>$response['result'] = true</b></li>
    <li>Готово. Происходит переход на <b>'/documentPrint/'.$doctype</b>, внутри которого при запросе <b>$_SESSION</b> выведутся указанные переменные</li>
</ul>
<?php
spoiler_end();
?>