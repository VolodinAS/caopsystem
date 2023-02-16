/**
 * Обработка текстовых данных:
 * - релативный поиск %
 * - перечисление поисков через ;
 */
const MEF_TEXT = 'text';

/**
 * Обработка числовых данных:
 * - фильтр ОТ
 * - фильтр ДО
 * - фильтр ОТ и ДО
 */
const MEF_NUMBER = 'number';

/**
 * Обработка даты:
 * - работа с unix-метками
 * - фильтр ОТ
 * - фильтр ДО
 * - фильтр ОТ и ДО
 */
const MEF_DATE = 'date';

/**
 * Единичный выбор из множества доступных
 */
const MEF_RADIO = 'radio';

/**
 * Множественный выбор из множества доступных
 */
const MEF_CHECKBOX = 'checkbox';

/**
 * Главный класс для обработки
 */
const main_class = '.mysqleditor-filters';

/**
 * Главный ID для формы
 */
const main_form = '#mysqleditor_filters_form';

/**
 * Кнопка "Применить"
 */
const form_button_apply = '#mysqleditor_filters_form_button_apply';

/**
 * Кнопка "Сбросить"
 */
const form_button_reset = '#mysqleditor_filters_form_button_reset';

const button_icon_reload = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-clockwise" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M8 3a5 5 0 1 0 4.546 2.914.5.5 0 0 1 .908-.417A6 6 0 1 1 8 2v1z"/><path d="M8 4.466V.534a.25.25 0 0 1 .41-.192l2.36 1.966c.12.1.12.284 0 .384L8.41 4.658A.25.25 0 0 1 8 4.466z"/></svg>';
const button_icon_ok = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-lg" viewBox="0 0 16 16"><path d="M13.485 1.431a1.473 1.473 0 0 1 2.104 2.062l-7.84 9.801a1.473 1.473 0 0 1-2.12.04L.431 8.138a1.473 1.473 0 0 1 2.084-2.083l4.111 4.112 6.82-8.69a.486.486 0 0 1 .04-.045z"/></svg>';
const button_icon_close = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-lg" viewBox="0 0 16 16"><path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8 2.146 2.854Z"/></svg>';
const button_icon_empty = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-files" viewBox="0 0 16 16"><path d="M13 0H6a2 2 0 0 0-2 2 2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h7a2 2 0 0 0 2-2 2 2 0 0 0 2-2V2a2 2 0 0 0-2-2zm0 13V4a2 2 0 0 0-2-2H5a1 1 0 0 1 1-1h7a1 1 0 0 1 1 1v10a1 1 0 0 1-1 1zM3 4a1 1 0 0 1 1-1h7a1 1 0 0 1 1 1v10a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V4z"/></svg>';

$( document ).ready(function()
{
    setTimeout(function ()
    {
        initFilterButtons();
    }, 200);
});

function submitForm()
{

    let formData = formSerializer(main_form)
    logger(formData);
    let data = {
        ...formData,
        act: "save"
    }

    $.ajax({
        url: '/processor/MySQLEditorFilters',
        data: data,
        dataType: 'json',
        type: 'post',
        success: function (json) {
            if (json) {
                if (json.result === true) {
                    window.location.reload();
                } else {
                    alert(json.msg);
                }
            }
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert(textStatus);
        }
    });
}

function initFilterButtons()
{
    $(main_form).unbind('submit');
    $(main_form).submit(function(e)
    {
        e.preventDefault();
        e.stopPropagation();
        submitForm();
        return false;
    });

    $(form_button_reset).unbind('click');
    $(form_button_reset).click(function(e)
    {
        e.preventDefault();
        let THIS = $(this);
        if ( confirm('Вы уверены, что хотите сбросить настройки данного фильтра?') )
        {
            let formData = formSerializer(main_form)
            logger(formData);
            let data = {
                ...formData,
                act: "reset"
            }
            $.ajax({
                url: '/processor/MySQLEditorFilters',
                data: data,
                dataType: 'json',
                type: 'post',
                success: function (json) {
                    if (json) {
                        if (json.result === true) {
                            window.location.reload();
                        } else {
                            alert(json.msg);
                        }
                    }
                },
                error: function (jqXHR, textStatus, errorThrown)
                {
                    alert(textStatus);
                }
            });
        }
    });

    $(main_class).unbind('click');
    $(main_class).click(function (e)
    {
        e.preventDefault()
        let THIS = $(this);
        let FILTER = THIS.data('filter');
        let ICON = $('span#'+FILTER+'_icon');
        let TITLE = $('span#'+FILTER+'_title');
        let error_field_stack = 'MysqlEditor.Filters';
        if (ICON.length > 0)
        {
            if ( TITLE.length > 0 )
            {
                if ( FILTER.length > 0 )
                {
                    error_field_stack += '.' + FILTER;
                    let TABLE = THIS.data('table');
                    if ( TABLE.length > 0 )
                    {
                        error_field_stack += '.' + TABLE;
                        let FIELD = THIS.data('field');
                        if ( FIELD.length > 0 )
                        {
                            let RELATED_FIELD = THIS.data('relatedfield');
                            if ( RELATED_FIELD !== undefined )
                            {
                                error_field_stack += '.' + FIELD;
                                let TYPE = THIS.data('type');
                                if ( TYPE.length > 0 )
                                {
                                    let PREPROCESSOR = THIS.data('preprocessor')
                                    let POSTPROCESSOR = THIS.data('postprocessor')
                                    let QUERYSET = THIS.data('queryset')
                                    let USE_EQUAL = THIS.data('useequal')
                                    let FIELD_HEADER = THIS.data('header')

                                    let data = {
                                        act: "modal",
                                        filter: FILTER,
                                        table: TABLE,
                                        field: FIELD,
                                        type: TYPE,
                                        related: RELATED_FIELD,
                                        queryset: QUERYSET,
                                        use_equal: USE_EQUAL,
                                        field_header: FIELD_HEADER,
                                        title: TITLE.html()
                                    }
                                    if ( PREPROCESSOR !== undefined )
                                        if ( PREPROCESSOR.length > 0 ) data.preprocessor = PREPROCESSOR;

                                    if ( POSTPROCESSOR !== undefined )
                                        if ( POSTPROCESSOR.length > 0 ) data.postprocessor = POSTPROCESSOR;

                                    $.ajax({
                                        url: '/processor/MySQLEditorFilters',
                                        data: data,
                                        dataType: 'json',
                                        type: 'post',
                                        success: function (json) {
                                            if (json) {
                                                if (json.result === true) {
                                                    mysqleditor_filters.modal('show');
                                                    mysqleditor_filters_body.html(json.htmlData);
                                                    setTimeout(function ()
                                                    {
                                                        MySQLEditorInit();
                                                        MaskedInput();
                                                        initFilterButtons();
                                                    }, 200)
                                                } else {
                                                    alert(json.msg);
                                                }
                                            }
                                        },
                                        error: function (jqXHR, textStatus, errorThrown)
                                        {
                                            alert(textStatus);
                                        }
                                    });

                                    // error_field_stack += '.' + TYPE;
                                    // alert(error_field_stack);
                                } else alert(error_field_stack + ': для поля не указан тип фильтра')
                            } else alert(error_field_stack + ': для поля не указана зависимость')
                        } else alert(error_field_stack + ': для поля не указан фильтр')
                    } else alert(error_field_stack + ': для фильтра не указана таблица')
                } else alert(error_field_stack + ': для кнопки не указано название фильтра')
            } else alert(error_field_stack + ': для кнопки не указано название')
        } else alert(error_field_stack + ': для кнопки не указана иконка')


    })
}