<?php
spoiler_begin('Добавление заполняемого бланка в Систему', 'newPHPBlank', 'collapse', '', 'admin-spoiler');
?>
<h4>Обновлено: 25.10.2022 11:58 ТЛТ</h4>
<ul>
    <li>Добавить новую ссылку в Дневник <b>\engine\html\journal_patientlist_cards.php</b></li>
    <li>Создать php-скрипт <b>{название}.php</b> в разделе <b>auth</b></li>
    <li>Скопировать код из <b>__PHP_BLANK__.php</b> в {название}.php</li>
    <li>Добавить html-форму {название}_form.php в <b>\engine\html\print_forms\</b></li>
    <li>Добавить скрипт <b>{название}Print.php</b> в <b>engine/php/prints.php</b></li>
</ul>
<?php
spoiler_end();
?>