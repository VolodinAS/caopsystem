<?php
spoiler_begin('Добавление нового документа в распечатку /documentPrint/', 'newPrintedDocument', 'collapse', '', 'admin-spoiler');
?>
<h4>Обновлено: 05.07.2022 09:29 ТЛТ</h4>
<ul>
    <li>Открыть файл <b>engine/html/document_print.php</b></li>
    <li>Добавить в конструкцию <b>switch ($PrintParams[0])</b> новый документ</li>
    <li>В папку <b>engine/html</b> добавить новый php-файл с именем <b>document_{название_документа}.php</b></li>
</ul>
<?php
spoiler_end();
?>