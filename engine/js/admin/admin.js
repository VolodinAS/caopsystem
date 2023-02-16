var LOADER_HTML = '';

$(document).ready(function(){

    LOADER_HTML = $('#adminTableNewBody').html();

    $('.btn-restructMySQLiTables').unbind('click');
    $('.btn-restructMySQLiTables').click(function(e)
    {
        if ( confirm('ВЫ УВЕРЕНЫ, ЧТО ХОТИТЕ ПЕРЕСОБРАТЬ MYSQLI-TABLES.PHP?') )
        {
            e.preventDefault();

            $.ajax({
                url: '/processor/admin_mainTablesRestruct',
                data: {},
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

    $('.table_new_item').unbind('click');
    $('.table_new_item').click( function () {
        $('#adminTableNewBody').html( LOADER_HTML );
        var THIS = $(this);
        var TABLE = THIS.data('table');
        $('#adminTableNewItemHeader').html('Добавление записи в таблицу «'+TABLE+'»‎');

        $('#adminTableNewItem').modal('show');

        var dat = {table: TABLE};
        $.ajax({
            url: '/processor/adminNewRecord',
            // contentType: false, // важно - убираем форматирование данных по умолчанию
            // processData: false, // важно - убираем преобразование строк по умолчанию
            data: dat,
            dataType: 'json',
            type: 'post',
            success: function(json){
                if(json){

                    if (json.result === true)
                    {
                        $('#adminTableNewBody').html( json.htmlData );
                    } else
                    {
                        $('#adminTableNewBody').html( json.msg );
                    }
                }
            }
        });
    } );

    $('#addNewItem').unbind('click');
    $('#addNewItem').click(function (e) {
        // $('#formNewItem').submit();

        var FORM = $('#formNewItem');

        // var $that = $('#formNewItem'),
        var formData = new FormData(document.querySelector('#formNewItem'))



        $.ajax({
            url: '/processor/adminAddRecord',
            contentType: false, // важно - убираем форматирование данных по умолчанию
            processData: false, // важно - убираем преобразование строк по умолчанию
            // data: {action: 'reg', form: formData},
            data: formData,
            dataType: 'json',
            type: 'post',
            success: function(json){
                if(json){



                    if (json.result === true)
                    {
                        window.location.reload();
                    } else
                    {
                        $('#adminTableNewBody').html( json.msg );
                    }
                }
            }
        });


    });

    $('.table_delete_item').unbind('click');
    $('.table_delete_item').click(function (e) {
        var THIS = $(this);
        var TABLE = THIS.data('table');
        var ID = THIS.data('id');
        var FIELDID = THIS.data('fieldid');

        var dat = {table: TABLE, item_id: ID, field_id: FIELDID};
        $.ajax({
            url: '/processor/adminDeleteRecord',
            // contentType: false, // важно - убираем форматирование данных по умолчанию
            // processData: false, // важно - убираем преобразование строк по умолчанию
            data: dat,
            dataType: 'json',
            type: 'post',
            success: function(json){
                if(json){

                    if (json.result === true)
                    {
                        window.location.reload();
                    } else
                    {

                    }
                }
            }
        });

    })



    $('.btn-searchByData').unbind('click');
    $('.btn-searchByData').click(function (e) {
        var name_table = $('#name_table').val();
        var name_field = $('#name_field').val();
        var name_value = $('#name_value').val();
        var name_patient_id_field = $('#name_patient_id_field').val();

        var dat = {table: name_table, field: name_field, value: name_value, patient_id_field: name_patient_id_field};
        $.ajax({
            url: '/processor/admin_searchTables',
            data: dat,
            dataType: 'json',
            type: 'post',
            success: function(json){
                if(json){
                    if (json.result === true)
                    {
                        $('#adminTableSearchResults').html(json.htmlData);
                    } else
                    {
                        $('#adminTableSearchResults').html(json.msg);
                    }
                }
            }
        });
    });


    $('.admin-spoiler').on('hide.bs.collapse', function (e) {
        $.ajax({
            url: '/processor/admin_spoiler',
            data: {act: 'hide'},
            dataType: 'json',
            type: 'post',
            success: function (json) {
                if (json) {
                    if (json.result === true) {

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
    })


    $('.admin-spoiler').on('show.bs.collapse', function (e) {
        let THIS = $(this)
        let ID = THIS.attr('id')
        $.ajax({
            url: '/processor/admin_spoiler',
            data: {spoiler_id: ID, act: 'pin'},
            dataType: 'json',
            type: 'post',
            success: function (json) {
                if (json) {
                    if (json.result === true) {

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
    })

});