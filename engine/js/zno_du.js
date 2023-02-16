let zno_du_modal;
let zno_du_loader;
let zno_du_body;

$( document ).ready(function()
{
    zno_du_modal = $('#addPatientZNODU');
    zno_du_body = $('#addPatientZNODU_body');
    zno_du_loader = zno_du_body.html();

    initModalFunctionsZNO();

    $("#zno_du_table").tablesorter();
    
    // {
    //     dateFormat : "ddmmyyyy", // default date format
    // }
    
});

$('.btn-addPatientZNODU').unbind('click');
$('.btn-addPatientZNODU').click(function(e)
{
    e.preventDefault();
    let THIS = $(this);

    // zno_du_body.html(zno_du_loader);
    // zno_du_modal.modal('show');
    $.ajax({
        url: '/processor/zno_du_add',
        data: {},
        dataType: 'json',
        type: 'post',
        success: function (json) {
            if (json) {
                if (json.result === true) {
                    // zno_du_body.html(json.htmlData);
                    // initModalFunctionsZNO();
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

});

$('.btn-editZNODU').unbind('click');
$('.btn-editZNODU').click(function(e)
{
    e.preventDefault();
    let THIS = $(this);
    let ZNODUID = THIS.data('znodu');
    zno_du_body.html(zno_du_loader);
    zno_du_modal.modal('show');
    $.ajax({
        url: '/processor/zno_du_edit',
        data: {zno_id: ZNODUID},
        dataType: 'json',
        type: 'post',
        success: function (json) {
            if (json) {
                if (json.result === true) {
                    zno_du_body.html(json.htmlData);
                    initModalFunctionsZNO();
                    MySQLEditorInit();
                    MaskedInput();
                    // window.location.reload();
                } else {
                    zno_du_body.html(json.msg);
                }
            }
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert(textStatus);
        }
    });

});

function initModalFunctionsZNO()
{
    $('.btn-znoduSearch').unbind('click');
    $('.btn-znoduSearch').click(function(e)
    {
        e.preventDefault();
        let THIS = $(this);
        let formData = formSerializer('#znodu_form');
        $.ajax({
            url: '/processor/zno_du_search',
            data: formData,
            dataType: 'json',
            type: 'post',
            success: function (json) {
                if (json) {
                    if (json.result === true) {
                        $('#znoduRecord_result').html(json.htmlData);
                        initModalFunctionsZNO();
                        MySQLEditorInit();
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

    $('.btn-znoduPatient').unbind('click');
    $('.btn-znoduPatient').click(function(e)
    {
        e.preventDefault();
        let THIS = $(this);
        let PATID_ID = THIS.data('patidid');
        let PATID_NAME = THIS.data('patidname');
        let PATID_BIRTH = THIS.data('patidbirth');

        $('#znodu_form').hide();

        $('#znoduRecord_result').html('<b>ПАЦИЕНТ:</b> '+PATID_NAME+', ' + PATID_BIRTH + ' г.р.<div class="dropdown-divider"></div>' +
            '<button class="btn btn-sm btn-warning btn-znoduChange">Изменить</button> ' +
            '<button class="btn btn-sm btn-primary btn-findRS">Поиск маршрутных листов</button> ' +
            '<div class="dropdown-divider"></div>');

        $('#patid_id').val(PATID_ID);
        $("#patid_id").trigger("change")

        initModalFunctionsZNO();
    });

    $('.btn-znoduChange').unbind('click');
    $('.btn-znoduChange').click(function(e)
    {
        e.preventDefault();

        $('#znodu_form').show();

        $('#znoduRecord_result').html('');
        $('#znoduPRS_result').html('');

        $('#patid_id').val('-1');
    });

    $('.btn-findRS').unbind('click');
    $('.btn-findRS').click(function(e)
    {
        e.preventDefault();
        let THIS = $(this);
        let PATID_ID = $('#patid_id').val()
        if (PATID_ID > 0)
        {
            $.ajax({
                url: '/processor/zno_du_rs',
                data: {patient_id: PATID_ID},
                dataType: 'json',
                type: 'post',
                success: function (json) {
                    if (json) {
                        if (json.result === true) {
                            $('#znoduPRS_result').html(json.htmlData);
                            initModalFunctionsZNO();
                        } else {
                            $('#znoduPRS_result').html(json.msg);
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
            $('#znoduPRS_result').html( bt_notice('Не указан ID пациента', 'warning') )
        }
    });

    $('.btn-removeZNODU').unbind('click');
    $('.btn-removeZNODU').click(function(e)
    {
        e.preventDefault();
        let THIS = $(this);
        let ZNO_ID = THIS.data('znodu');
        if ( confirm('Вы действительно хотите удалить данную запись?\n\nДействие невозможно отменить!') )
        {
            $.ajax({
                url: '/processor/zno_du_remove',
                data: {zno_id: ZNO_ID},
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

    $('.btn-refreshZNODU').unbind('click');
    $('.btn-refreshZNODU').click(function(e)
    {
        e.preventDefault();
        let THIS = $(this);
        let ZNODUID = THIS.data('znodu');
        zno_du_body.html(zno_du_loader);
        // zno_du_modal.modal('show');
        $.ajax({
            url: '/processor/zno_du_edit',
            data: {zno_id: ZNODUID},
            dataType: 'json',
            type: 'post',
            success: function (json) {
                if (json) {
                    if (json.result === true) {
                        zno_du_body.html(json.htmlData);
                        initModalFunctionsZNO();
                        MySQLEditorInit();
                        MaskedInput();
                        // window.location.reload();
                    } else {
                        zno_du_body.html(json.msg);
                    }
                }
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert(textStatus);
            }
        });
    });

    $('.btn-importRS').unbind('click');
    $('.btn-importRS').click(function(e)
    {
        e.preventDefault();
        let THIS = $(this);
        let RS = THIS.data('rs')
        if ( RS > 0 )
        {
            logger(RS, 'RS')
            let PATID = $('#patid_id')
            let PATID_ID = PATID.val()
            if ( PATID_ID > 0 )
            {
                let ZNO = PATID.data('id')
                if ( ZNO > 0 )
                {
                    $.ajax({
                        url: '/processor/zno_du_import_rs',
                        data: {patid: PATID_ID, rs: RS, zno_id: ZNO},
                        dataType: 'json',
                        type: 'post',
                        success: function (json) {
                            if (json) {
                                if (json.result === true) {
                                    $('.btn-refreshZNODU').click();
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
                } else alert('Не указан ID ZNODU')
            } else alert('Не указан ID PATIENT')
        } else alert('Не указан ID маршрутного листа')

    });

    $('.btn-doneZNODU').unbind('click');
    $('.btn-doneZNODU').click(function(e)
    {
        e.preventDefault();
        let THIS = $(this);
        window.location.reload();
    });

    $('.btn-resetZNODU').unbind('click');
    $('.btn-resetZNODU').click(function(e)
    {
        e.preventDefault();
        let THIS = $(this);
        let zno_id = THIS.data('znodu')
        if ( zno_id > 0 )
        {
            if ( confirm('Данное действие полностью очистит форму добавления на Д-учет. Продолжить?') )
            {
                // logger(zno_id)
                $.ajax({
                    url: '/processor/zno_du_reset',
                    data: {zno_id},
                    dataType: 'json',
                    type: 'post',
                    success: function (json) {
                        if (json) {
                            if (json.result === true) {
                                $('.btn-refreshZNODU').click();
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
        } else alert('Не указан ID маршрутного листа')

    });

    // $('#zno_apk2').selectize({
    //     create: true,
    //     sortField: 'text',
    //     onChange: function (value)
    //     {
    //         console.log(value)
    //     }
    // });
}



// ДУБЛЁР ИЗ uziSchedule
