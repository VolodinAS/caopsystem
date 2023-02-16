let TABLE_CURRENT_ID = -1;
let files;

let importProfile_modal;
let importProfile_loader;
let importProfile_body;

$(document).ready(function (err) {
    initMe();
    
    importProfile_modal = $('#tablesImportProfile');
    importProfile_body = $('#tablesImportProfile_body');
    importProfile_loader = importProfile_body.html();
});

function initMe() {

    $('.profile-selector').unbind('click');
    $('.profile-selector').change(function(e)
    {
        e.preventDefault();
        let THIS = $(this);
        let table_id = $('.btn-createProfile').data('table');
        let file_id = $('.btn-createProfile').data('file');
        // console.log(table_id);
        // console.log(file_id);
        actionFile(table_id, file_id, 'file_import', false, THIS.val());
    });

    $("#form-new-table").submit(function (e) {
        e.preventDefault();

        var FORM = $('#form-new-table');
        var FORM_DATA = FORM.serializeArray();


        $.ajax({
            url: '/processor/admin_tables_new',
            data: FORM_DATA,
            dataType: 'json',
            type: 'post',
            success: function (json) {
                if (json.result === true) {
                    alert(json.msg);
                    window.location.reload();
                } else alert(json.msg);
            },
            error: function () {
            },
            complete: function () {
            }
        });

    });

    $('input[type=file]').on('change', function () {
        files = this.files;
    });

    $('.upload_files').on('click', function (event) {
        event.stopPropagation(); // остановка всех текущих JS событий
        event.preventDefault();  // остановка дефолтного события для текущего элемента - клик для <a> тега
        // ничего не делаем если files пустой
        if (typeof files == 'undefined')
        {
            alert('Нет файлов для загрузки')
            return;
        }
        // создадим объект данных формы
        let data = new FormData();
        // заполняем объект данных файлами в подходящем для отправки формате
        $.each(files, function (key, value) {
            data.append(key, value);
        });

        let table_id = $('#table_import_id').val();
        // console.log(table_id);

        if ( parseInt(table_id) < 1 )
        {
            alert('Не выбрана таблица для импорта')
            return;
        }

        // добавим переменную для идентификации запроса
        data.append('my_file_upload', 1);
        data.append('table', table_id);
        data.append('act', 'upload');
        // AJAX запрос
        $.ajax({
            url: 'processor/admin_tables_edit',
            type: 'POST', // важно!
            data: data,
            cache: false,
            dataType: 'json',
            // отключаем обработку передаваемых данных, пусть передаются как есть
            processData: false,
            // отключаем установку заголовка типа запроса. Так jQuery скажет серверу что это строковой запрос
            contentType: false,
            // функция успешного ответа сервера
            success: function (json, status, jqXHR) {

                // ОК - файлы загружены
                if (typeof json.error === 'undefined') {
                    if (json.result === true)
                    {
                        if ( json.htmlData.length > 20 )
                        {
                            alert('load html');
                        } else
                        {
                            alert(json.msg)
                            window.location.href = '/admin_tables';
                        }
                    } else
                    {
                        alert(json.msg);
                    }
                }
                else {
                    console.log('ОШИБКА: ' + json.data);
                }
            },
            error: function (jqXHR, status, errorThrown) {
                console.log('ОШИБКА AJAX запроса: ' + status, jqXHR);
            }

        });

    });

    $('.btn-updateFileList').unbind('click');
    $('.btn-updateFileList').click(function(e)
    {
        e.preventDefault();
        let THIS = $(this);
        let table = THIS.data('table');
        filesTable(table);
    });
    
    $('.btn-import').unbind('click');
    $('.btn-import').click(function(e)
    {
        e.preventDefault();
        let THIS = $(this);
        let table_id = THIS.data('table');
        let file_id = THIS.data('file');
        let profile_id = $('#profile-selector').val();
        if ( profile_id > 0 )
        {

            if ( confirm('Вы уверены, что хотите произвести импорт таблицы с данным профилем?') )
            {
                let openAfter = $('#openTable:checkbox').prop('checked');
                // console.log(openAfter);
                $.ajax({
                    url: '/processor/admin_tablesImport',
                    data: {table_id, file_id, profile_id},
                    dataType: 'json',
                    type: 'post',
                    success: function (json) {
                        if (json) {
                            if (json.result === true) {
                                alert(json.msg);
                                if (openAfter) window.open('/table_viewer/' + table_id);
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

        } else alert('Сначала выберите профиль настроек столбцов')
    });

    $('.btn-updateImport').unbind('click');
    $('.btn-updateImport').click(function(e)
    {
        e.preventDefault();
        let THIS = $(this);
        let table_id = THIS.data('table');
        let file_id = THIS.data('file');
        actionFile(table_id, file_id, 'file_import', false);
    });
    
    $('.btn-removeProfile').unbind('click');
    $('.btn-removeProfile').click(function(e)
    {
        e.preventDefault();
        let THIS = $(this);
        let table_id = THIS.data('table');
        let file_id = THIS.data('file');
        let profile_selector = $('#profile-selector');
        let profile_id = profile_selector.val();
        // console.log(profile_id);
        if ( profile_id > 0 )
        {
            if ( confirm('Вы действительно желаете удалить данный профиль?') )
            {
                $.ajax({
                    url: '/processor/admin_tablesRemoveProfile',
                    data: {profile_id},
                    dataType: 'json',
                    type: 'post',
                    success: function (json) {
                        if (json) {
                            if (json.result === true) {
                                actionFile(table_id, file_id, 'file_import', false);
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

        } else alert('Для того, чтобы удалить профиль, выберите его из списка ниже');
    });

    $('.btn-createProfile').unbind('click');
    $('.btn-createProfile').click(function(e)
    {

        importProfile_body.html( importProfile_loader );
        importProfile_modal.modal('show')

        e.preventDefault();
        let THIS = $(this);
        let table_id = THIS.data('table');
        let file_id = THIS.data('file');
        let fromfile = THIS.data('fromfile');

        $.ajax({
            url: '/processor/admin_tablesCreateProfile',
            data: {table_id, file_id, fromfile},
            dataType: 'json',
            type: 'post',
            success: function (json) {
                if (json) {
                    if (json.result === true) {
                        importProfile_body.html( json.htmlData );
                        initMe();
                        InitCheckboxCheckAll();
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
    });

    $('.btn-addImportProfile').unbind('click');
    $('.btn-addImportProfile').click(function(e)
    {
        e.preventDefault();
        let THIS = $(this);
        let checks = checkboxCollector('.checks_createImportProfile');
        if (checks.length > 0)
        {
            let form_data = formSerializer('#form_createImportProfile');
            form_data.columns = checks;
            // logger(form_data, 'form_data');
            // logger(checks, 'checks');

            $.ajax({
                url: '/processor/admin_tablesNewProfile',
                data: {...form_data},
                dataType: 'json',
                type: 'post',
                success: function (json) {
                    if (json) {
                        if (json.result === true) {
                            alert(json.msg);
                            importProfile_modal.modal('hide');
                            actionFile(form_data.table_id, form_data.file_id, 'file_import', false);
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

        } else
        {
            alert('Выберите хотя бы один столбец для импорта')
        }

    });

    $('.btn-recountTable').unbind('click');
    $('.btn-recountTable').click(function(e)
    {
        e.preventDefault();
        let THIS = $(this);
        let table_id = THIS.data('table');
        let file_id = THIS.data('file');
        let fromfile = THIS.data('fromfile');
        let hs = $('#header_string').val();

        $.ajax({
            url: '/processor/admin_tablesCreateProfile',
            data: {table_id, file_id, fromfile, hs},
            dataType: 'json',
            type: 'post',
            success: function (json) {
                if (json) {
                    if (json.result === true) {
                        importProfile_body.html( json.htmlData );
                        initMe();
                        InitCheckboxCheckAll();
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

    });

}

function actionFile(table_id, file_id, action, is_confirm=true, profile_id=0)
{
    let go_next = false;
    if ( is_confirm )
    {
        if ( confirm('Вы действительно хотите совершить данное действие над файлом ['+action+']?') )
            go_next = true
    } else go_next = true;

    if ( go_next )
    {
        $.ajax({
            url: '/processor/admin_tables_edit',
            data: {table: table_id, file_id, act: action, profile_id},
            dataType: 'json',
            type: 'post',
            success: function (json) {
                if (json) {
                    if (json.result === true) {
                        if ( json.htmlData.length > 20 )
                        {
                            TABLE_CURRENT_ID = table_id;
                            var edit_DOM = $('#insert-table-href');
                            edit_DOM.html(json.htmlData);
                            $('#insert-table-id').tab('show');

                            MySQLEditorInit();
                            initMe();
                        } else
                        {
                            alert(json.msg);
                            filesTable(table_id);
                        }
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
}

function editTable(table_id) {

    $.ajax({
        url: '/processor/admin_tables_edit',
        data: {table: table_id, act: "get"},
        dataType: 'json',
        type: 'post',
        success: function (json) {
            if (json.result === true) {
                TABLE_CURRENT_ID = table_id;
                // TODO Изменяем форму с добавлением table_id
                $('#table_id').val(json.data.table_id);
                $('#table-title').val(json.data.table_title);
                $('#table-subtitle').val(json.data.table_subtitle);
                $('#table-description').val(json.data.table_description);
                $('#table-create-id').tab('show');


            } else alert(json.msg);
        },
        error: function () {
        },
        complete: function () {
        }
    });
}

function importTable(table_id) {
    $.ajax({
        url: '/processor/admin_tables_edit',
        data: {table: table_id, act: "import"},
        dataType: 'json',
        type: 'post',
        success: function (json) {
            if (json.result === true) {
                TABLE_CURRENT_ID = table_id;
                var edit_DOM = $('#import-table-href');
                edit_DOM.html(json.htmlData);
                $('#import-table-id').tab('show');

                MySQLEditorInit();
                initMe();
            } else alert(json.msg);
        },
        error: function () {
        },
        complete: function () {
        }
    });
}

function filesTable(table_id) {
    $.ajax({
        url: '/processor/admin_tables_edit',
        data: {table: table_id, act: "files"},
        dataType: 'json',
        type: 'post',
        success: function (json) {
            if (json.result === true) {
                TABLE_CURRENT_ID = table_id;
                var edit_DOM = $('#files-table-href');
                edit_DOM.html(json.htmlData);
                $('#files-table-id').tab('show');

                MySQLEditorInit();
                initMe();
            } else alert(json.msg);
        },
        error: function () {
        },
        complete: function () {
        }
    });
}

function deleteTable(table_id) {
    if (confirm('Вы действительно желаете удалить таблицу? Она может быть НЕ ПУСТОЙ!')) {
        $.ajax({
            url: '/processor/admin_tables_edit',
            data: {table: table_id, act: "remove"},
            dataType: 'json',
            type: 'post',
            success: function (json) {
                if (json.result === true) {
                    alert(json.msg);
                    window.location.reload();
                } else alert(json.msg);
            },
            error: function (e) {
                alert(e);

            },
            complete: function () {
                console.debug('deleteTable(' + table_id + '): done');
            }
        });
    }
}

function editFields(table_id) {
    $.ajax({
        url: '/processor/admin_tables_fields',
        data: {table: table_id, act: "edit"},
        dataType: 'json',
        type: 'post',
        success: function (json) {
            if (json.result === true) {
                TABLE_CURRENT_ID = table_id;
                var edit_DOM = $('#edit-fields-href');
                edit_DOM.html(json.htmlData);
                $('#edit-fields-id').tab('show');

                MySQLEditorInit();
                initMe();
            } else alert(json.msg);
        },
        error: function () {
        },
        complete: function () {
        }
    });
}

function updateEditField() {
    if (TABLE_CURRENT_ID > 0) {

        $.ajax({
            url: '/processor/admin_tables_fields',
            data: {table: TABLE_CURRENT_ID, act: "edit"},
            dataType: 'json',
            type: 'post',
            success: function (json) {
                if (json.result === true) {
                    var edit_DOM = $('#edit-fields-href');
                    edit_DOM.html(json.htmlData);
                    MySQLEditorInit();
                    initMe();
                } else alert(json.msg);
            },
            error: function () {
            },
            complete: function () {
            }
        });

    } else alert('Не указан ID таблицы');
}

function addNewField(e) {
    e.preventDefault();

    if (TABLE_CURRENT_ID > 0) {
        var FORM = $('#form-new-field');
        var FORM_DATA = FORM.serializeArray().reduce(function (obj, item) {
            obj[item.name] = item.value;
            return obj;
        }, {});


        $.ajax({
            url: '/processor/admin_tables_fields',
            data: {table: TABLE_CURRENT_ID, act: "new", fields: FORM_DATA},
            dataType: 'json',
            type: 'post',
            success: function (json) {

                if (json.result === true) {
                    // var edit_DOM = $('#edit-fields-href');
                    // edit_DOM.html(json.htmlData);
                    updateEditField();
                    MySQLEditorInit();
                } else alert(json.msg);
            },
            error: function () {
            },
            complete: function () {
            }
        });

    } else alert('Не указан ID таблицы');

}

function deleteField(e, field_id) {
    e.preventDefault();

    if (confirm("Вы действительно хотите удалить данное поле таблицы?")) {
        $.ajax({
            url: '/processor/admin_tables_fields',
            data: {table: TABLE_CURRENT_ID, act: "delete", field_id: field_id},
            dataType: 'json',
            type: 'post',
            success: function (json) {

                if (json.result === true) {
                    // var edit_DOM = $('#edit-fields-href');
                    // edit_DOM.html(json.htmlData);
                    updateEditField();
                    MySQLEditorInit();
                } else alert(json.msg);
            },
            error: function () {
            },
            complete: function () {
            }
        });
    }
}