let modal_identifier = '.mysqleditor-modal-form';
let modal_dom;
let modal_window_identifier = '#myemodal';
let modal_window_title_identifier = '#myemodal_header_string';
let modal_window_body_identifier = '#myemodal_body';
let modal_window_result_identifier = '#myemodal_result';
let modal_window_footer_identifier = '#myemodal_footer';
let modal_window_loader;
let modal_form_add_identifier = '#modal_form_add';
let btn_refresh_identifier = '#mf_button_refresh';

let modal_window_identifier_dom,
    modal_window_title_identifier_dom,
    modal_window_body_identifier_dom,
    modal_window_result_identifier_dom,
    modal_window_footer_identifier_dom,
    modal_form_add_identifier_dom,
    btn_refresh_identifier_dom;

$( document ).ready(function()
{
    console.info('MySQLEditor.ModalEditor.js init')
    modal_window_loader = $(modal_window_body_identifier).html()
    MYEModalFormInit();
});

function MYEModalFormInit()
{
    console.info('MySQLEditor.ModalEditor.MYEModalFormInit init')
    modal_dom = $(modal_identifier)
    if (modal_dom.length > 0)
    {
        modal_dom.unbind('click');
        modal_dom.click(function(e)
        {
            e.preventDefault();
            let THIS = $(this);
            let myemf_action, table, field_id, id, title, fields;

            myemf_action = THIS.data('action');
            if (myemf_action)
            {
                table = THIS.data('table');
                if (table)
                {
                    if ( myemf_action === "edit" || myemf_action === "add" ) {
                        field_id = THIS.data('fieldid');
                        if (field_id)
                        {
                            id = THIS.data('id');
                            modal_window_identifier_dom = $(modal_window_identifier);
                            if (modal_window_identifier_dom.length === 1)
                            {
                                modal_window_title_identifier_dom = $(modal_window_title_identifier);
                                if (modal_window_title_identifier_dom.length === 1)
                                {
                                    modal_window_body_identifier_dom = $(modal_window_body_identifier);
                                    if (modal_window_body_identifier_dom.length === 1)
                                    {
                                        modal_window_footer_identifier_dom = $(modal_window_footer_identifier);
                                        if (modal_window_footer_identifier_dom.length === 1)
                                        {

                                            btn_refresh_identifier_dom = $(btn_refresh_identifier);
                                            if ( btn_refresh_identifier_dom.length === 1 )
                                            {
                                                title = THIS.data('title');
                                                fields = THIS.data('fields');
                                                options = THIS.data('options');
                                                callbacks = THIS.data('callbacks');

                                                if (title)
                                                {
                                                    if (title.length)
                                                    {
                                                        modal_window_title_identifier_dom.html(title);
                                                    } else title = '';
                                                } else title = '';



                                                modal_window_body_identifier_dom.html(modal_window_loader);
                                                modal_window_identifier_dom.modal('show')
                                                let data = {myemf_action, table, field_id};
                                                if (id) data = {...data, id}
                                                if (fields) data = {...data, fields}
                                                if (options) data = {...data, options}
                                                if (callbacks) data = {...data, callbacks}

                                                let button_params = {
                                                    ...data,
                                                    title,
                                                    fields,
                                                    id,
                                                    options,
                                                    callbacks
                                                }



                                                $.ajax({
                                                    url: '/processor/MySQLEditorModalForm',
                                                    data: data,
                                                    dataType: 'json',
                                                    type: 'post',
                                                    success: function (json) {
                                                        if (json) {
                                                            if (json.result === true) {
                                                                modal_window_body_identifier_dom.html(json.htmlData);

                                                                set_button_refresh_parameters(btn_refresh_identifier_dom, button_params)

                                                                setTimeout(function ()
                                                                {
                                                                    MySQLEditorInit();
                                                                    MaskedInput();
                                                                    // MYEModalFormInit();
                                                                }, 1000)
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
                                            } else alert('Для MySQLEditor.ModalEditor.'+modal_window_identifier+' не настроено обновление');
                                        } else alert('Для MySQLEditor.ModalEditor.'+modal_window_identifier+' не настроен подвал');
                                    } else alert('Для MySQLEditor.ModalEditor.'+modal_window_identifier+' не настроен контент');
                                } else alert('Для MySQLEditor.ModalEditor.'+modal_window_identifier+' не настроен заголовок');
                            } else alert('Для MySQLEditor.ModalEditor не настроено модальное окно');
                        } else alert('Для MySQLEditor.ModalEditor.Button.'+table+' не настроено поле индекса');
                    } else
                    {
                        if ( myemf_action === "save" )
                        {
                            modal_form_add_identifier_dom = $(modal_form_add_identifier)
                            if (modal_form_add_identifier_dom.length === 1)
                            {
                                let form_data = formSerializer(modal_form_add_identifier)
                                console.log(form_data)
                            } else alert('Для MySQLEditor.ModalEditor не настроена форма');
                        }
                    }
                } else alert('Для MySQLEditor.ModalEditor.Button не настроена таблица');
            } else alert('Для MySQLEditor.ModalEditor.Button не настроено действие');
        });
    }
}

function SaveForm(object)
{
    let THIS = $(object);

    let myemf_action, table;

    myemf_action = THIS.data('action');
    if (myemf_action)
    {
        table = THIS.data('table');
        if (table)
        {
            if ( myemf_action === "save" )
            {
                modal_form_add_identifier_dom = $(modal_form_add_identifier)
                if (modal_form_add_identifier_dom.length === 1)
                {
                    // logger(modal_form_add_identifier_dom, 'modal_form_add_identifier_dom')

                    // let form_data = $(modal_form_add_identifier).serializeObject()
                    let form_data = formSerializer(modal_form_add_identifier)

                    if ( form_data !== false )
                    {
                        let data = {
                            myemf_action: 'save',
                            table,
                            ...form_data
                        }

                        $.ajax({
                            url: '/processor/MySQLEditorModalForm',
                            data: data,
                            dataType: 'json',
                            type: 'post',
                            success: function (json) {
                                if (json) {
                                    if (json.result === true) {
                                        let callbacks = $('#callbacks')
                                        if ( callbacks.length )
                                        {
                                            let callbacks_data = callbacks.val();
                                            if (typeof callbacks_data !== "undefined") {
                                                // safe to use the function
                                                let func = callbacks_data + "("+json['record']['INSERTED_ID']+")";;
                                                eval(func);
                                            }

                                        } else window.location.reload();
                                        //
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
                    } else alert('Указаны ОПЦИИ! Вы не заполнили ВАЖНЫЕ (красные) поля!');

                    logger(form_data, 'form_data')



                } else alert('Для MySQLEditor.ModalEditor не настроена форма');
            } else alert('Для MySQLEditor.ModalEditor.Button указано неизвестное действие');
        } else alert('Для MySQLEditor.ModalEditor.Button не настроена таблица');
    } else alert('Для MySQLEditor.ModalEditor.Button не настроено действие');

    return false;
}

function set_button_refresh_parameters(btn, data)
{
    btn.attr('data-action', data.myemf_action);
    btn.attr('data-table', data.table);
    btn.attr('data-fieldid', data.field_id);
    btn.attr('data-id', data.id);
    btn.attr('data-title', data.title);
    btn.attr('data-fields', data.fields);
    btn.attr('options', data.options);
    btn.attr('callbacks', data.callbacks);

    btn.data('action', data.myemf_action);
    btn.data('table', data.table);
    btn.data('fieldid', data.field_id);
    btn.data('id', data.id);
    btn.data('title', data.title);
    btn.data('fields', data.fields);
    btn.data('options', data.options);
    btn.data('callbacks', data.callbacks);
}