<?php
spoiler_begin('Работа с MySQLEditor.ModalEditor.js', 'mysqleditor_modal', 'collapse', '', 'admin-spoiler');
?>
<h4>Обновлено: 22.01.2023 16:25:49 ТЛТ</h4>
<ul>
    <li>Редактирование записи:
        <pre>
            <code>
&lt;button
        class="btn btn-info mysqleditor-modal-form"
        data-action="edit"
        data-table="<?=CAOP_CAT_SYSTEM;?>"
        data-fieldid="<?=$PK[CAOP_CAT_SYSTEM];?>"
        data-id="{record_id}"
        data-title="Редактирование меню"
&gt;
    РЕДАКТИРОВАНИЕ ЗАПИСИ
&lt;/button&gt;
            </code>
        </pre>
    </li>
    <li>Добавление записи:
        <pre>
            <code>
&lt;button
        class="btn btn-primary mysqleditor-modal-form"
        id="myemf_button_add"
        type="button"
        data-action="add"
        data-table="<?=CAOP_CAT_SYSTEM;?>"
        data-fieldid="<?=$PK[CAOP_CAT_SYSTEM];?>"
        data-title="Добавление CAT"
&gt;
    Добавление записи
&gt;M&lt;/button&gt;
            </code>
        </pre>
    </li>
    
</ul>
<?php
spoiler_end();
?>