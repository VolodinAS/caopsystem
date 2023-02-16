let my_calendar = '';
let LOADER_calendar = '';
let shift_modal = '';
let shift_modal_body = '';
let LOADER_shift_modal = '';

// let uziCaop_time_submodal;
// let uziCaop_time_loader;
// let uziCaop_time_body;

let editDay, editDay_loader, editDay_body;

$( document ).ready(function()
{
    my_calendar = $('#calendar');
    LOADER_calendar = my_calendar.html();

    shift_modal = $('#shift');
    shift_modal_body = $('#shift_body');
    LOADER_shift_modal = shift_modal_body.html();

    editDay = $('#uziSchedule-editGraphic');
    editDay_body = $('#uziSchedule-editGraphic_body');
    editDay_loader = editDay_body.html();

    uziCaop_time_submodal = $('#uzi-caop-time-submodal');
    uziCaop_time_body = $('#uzi-caop-time-submodal_body');
    uziCaop_time_loader = uziCaop_time_body.html();

    $('.btn-addInList').unbind('click');
    $('.btn-addInList').click(function(e)
    {
        e.preventDefault();
        let THIS = $(this);
        let DOCTOR_ID = THIS.data('doctorid');
        uziDoctorList(DOCTOR_ID)
    });

    $('.btn-addRemoveFromList').unbind('click');
    $('.btn-addRemoveFromList').click(function(e)
    {
        e.preventDefault();
        let THIS = $(this);
        let DOCTOR_ID = THIS.data('doctorid');
        uziDoctorList(DOCTOR_ID)
    });

    $('.btn-addShift').unbind('click');
    $('.btn-addShift').click(function(e)
    {
        e.preventDefault();
        let THIS = $(this);
        let UZI_ID = THIS.data('uziid');
        let DOCTOR_ID = THIS.data('doctorid');

        $.ajax({
            url: '/processor/scheduleUzi_addShift',
            data: {doctor_id: DOCTOR_ID, uzi_id: UZI_ID},
            dataType: 'json',
            type: 'post',
            success: function (json) {
                if (json) {
                    if (json.result === true) {
                        alert(json.msg);
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

    $('.btn-addTime').unbind('click');
    $('.btn-addTime').click(function(e)
    {
        e.preventDefault();
        let THIS = $(this);
        let SHIFT_ID = THIS.data('shiftid');
        let TIME = $('#shift_'+SHIFT_ID+'_value').val();
        addNewTime(SHIFT_ID, TIME);
    });

    $('.enter-shift-new').unbind('keydown');
    $(".enter-shift-new").on("keydown", function(e) {
        if(e.which === 13)
        {
            e.preventDefault();
            let THIS = $(this);
            let SHIFT_ID = THIS.data('shiftid');
            let TIME = THIS.val();
            addNewTime(SHIFT_ID, TIME);
        }

    });

    $('.btn-removeTime').unbind('click');
    $('.btn-removeTime').click(function(e)
    {
        e.preventDefault();
        let THIS = $(this);
        let TIME_ID = THIS.data('timeid');
        $.ajax({
            url: '/processor/scheduleUzi_removeTime',
            data: {time_id: TIME_ID},
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
    });

    $('.btn-removeShift').unbind('click');
    $('.btn-removeShift').click(function(e)
    {
        if ( confirm('Вы действительно хотите удалить смену полностью вместе с расписанием?') )
        {
            e.preventDefault();
            let THIS = $(this);
            let SHIFT_ID = THIS.data('shiftid');
            $.ajax({
                url: '/processor/scheduleUzi_removeShift',
                data: {shift_id: SHIFT_ID},
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

    $('.btn-addTemp').unbind('click');
    $('.btn-addTemp').click(function(e)
    {
        e.preventDefault();
        let THIS = $(this);
        let UZI_ID = THIS.data('uziid');
        let DOCTOR_ID = THIS.data('doctorid');
        $.ajax({
            url: '/processor/scheduleUzi_addTemp',
            data: {uzi_id: UZI_ID, doctor_id: DOCTOR_ID},
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
    });

    $('.btn-removeTemp').unbind('click');
    $('.btn-removeTemp').click(function(e)
    {
        if ( confirm('Вы действительно хотите удалить шаблон со всеми настройками?') )
        {
            e.preventDefault();
            let THIS = $(this);
            let TEMP_ID = THIS.data('tempid');
            $.ajax({
                url: '/processor/scheduleUzi_removeTemp',
                data: {temp_id: TEMP_ID},
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

    $('.btn-copyShift').unbind('click');
    $('.btn-copyShift').click(function(e)
    {
        e.preventDefault();
        let THIS = $(this);
        let SHIFT_ID = THIS.data('shiftid');
        $.ajax({
            url: '/processor/scheduleUzi_copyShift',
            data: {type: 'single', shift_id: SHIFT_ID},
            dataType: 'json',
            type: 'post',
            success: function (json) {
                if (json) {
                                        if (json.result === true) {
                        alert(json.msg);
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

    $('.btn-copyOtherShift').unbind('click');
    $('.btn-copyOtherShift').click(function(e)
    {
        shift_modal_body.html(LOADER_shift_modal);
        shift_modal.modal('show');

        e.preventDefault();
        let THIS = $(this);
        let SHIFT_ID = THIS.data('shiftid');
        $.ajax({
            url: '/processor/scheduleUzi_duplicateShift',
            data: {shift_id: SHIFT_ID, type: 'otherOne'},
            dataType: 'json',
            type: 'post',
            success: function (json) {
                if (json) {
                    if (json.result === true) {
                        shift_modal_body.html(json.htmlData);
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

    $('.btn-copyFullShift').unbind('click');
    $('.btn-copyFullShift').click(function(e)
    {
        shift_modal_body.html(LOADER_shift_modal);
        shift_modal.modal('show');

        e.preventDefault();
        let THIS = $(this);
        let UZI_ID = THIS.data('uziid');
        let DOCTOR_ID = THIS.data('doctorid');
        $.ajax({
            url: '/processor/scheduleUzi_duplicateShift',
            data: {doctor_id: DOCTOR_ID, uzi_id: UZI_ID, type: 'otherFull'},
            dataType: 'json',
            type: 'post',
            success: function (json) {
                if (json) {
                    if (json.result === true) {
                        shift_modal_body.html(json.htmlData);
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

    $('.btn-refresh').unbind('click');
    $('.btn-refresh').click(function(e)
    {
        let UNIX = $('#calendar').data('unixstamp');
        loadCalendar(UNIX);
    });

    initScheduleButtons();
});

function addNewTime(shift_id, time)
{
    $.ajax({
        url: '/processor/scheduleUzi_addTime',
        data: {shift_id: shift_id, time: time},
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

function initScheduleButtons()
{

    $('.btn-printRecord').unbind('click');
    $('.btn-printRecord').click(function(e)
    {
        e.preventDefault();
        let THIS = $(this);
        let PATUZI_ID = THIS.data('patuziid');
        let JOURNAL_ID = THIS.data('journalid');
        let DATE_ID = THIS.data('dateid');

        $.ajax({
            url: '/processor/sessionPrint',
            data: {doctype: "uzicaop_talon", patuzi_id: PATUZI_ID, journal_id: JOURNAL_ID, date_id: DATE_ID},
            dataType: 'json',
            type: 'post',
            success: function (json) {
                if (json) {
                    if (json.result === true) {
                        window.open(json.url);
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

    $('.btn-removeRecord').unbind('click');
    $('.btn-removeRecord').click(function(e)
    {
        e.preventDefault();
        let THIS = $(this);
        let patuzi_id = THIS.data('patuziid');
        let journal_id = THIS.data('journalid');
        let date_id = THIS.data('dateid');

        if ( confirm('УДАЛИТЬ ТАЛОН ПАЦИЕНТА С УЗИ ЦАОП?') ) {
            $.ajax({
                url: '/processor/journal_uzicaop_removerecord',
                data: {patuzi_id: patuzi_id, journal_id: journal_id, date_id: date_id},
                dataType: 'json',
                type: 'post',
                success: function (json) {
                    if (json) {
                        if (json.result === true) {
                            alert(json.msg);
                            uziCaop_time_submodal.modal('hide');

                            let updateButton = $('.btn-updateShiftList');
                            if ( updateButton.length )
                            {
                                updateButton.click();
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
    });

    $('.btn-addNewRecord').unbind('click');
    $('.btn-addNewRecord').click(function(e)
    {
        e.preventDefault();
        let THIS = $(this);
        let formData = formSerializer('#newUZIRecord_form');

        $.ajax({
            url: '/processor/journal_uzicaop_newrecord',
            data: formData,
            dataType: 'json',
            type: 'post',
            success: function (json) {
                if (json) {
                    if (json.result === true) {
                        alert(json.msg);
                        // editDay.modal('hide');
                        uziCaop_time_submodal.modal('hide');

                        let updateButton = $('.btn-updateShiftList');
                        if ( updateButton.length )
                        {
                            updateButton.click();
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
    });

    $('.btn-grantedPatient').unbind('click');
    $('.btn-grantedPatient').click(function(e)
    {
        e.preventDefault();
        let THIS = $(this);
        let PATID_ID = THIS.data('patidid');
        let PATID_NAME = THIS.data('patidname');
        let PATID_BIRTH = THIS.data('patidbirth');

        $('#uziSearch_form_result').html('');

        $('#uziRecord_result').html('<b>ПАЦИЕНТ:</b> '+PATID_NAME+', ' + PATID_BIRTH + ' г.р. <div class="dropdown-divider"></div>');

        $('#patid_id').val(PATID_ID);
    });

    $('.btn-uziSearch').unbind('click');
    $('.btn-uziSearch').click(function(e)
    {
        e.preventDefault();
        let THIS = $(this);
        let formData = formSerializer('#uziSearch_form');
        $.ajax({
            url: '/processor/scheduleUzi_search',
            data: formData,
            dataType: 'json',
            type: 'post',
            success: function (json) {
                if (json) {
                    if (json.result === true) {
                        $('#uziSearch_form_result').html(json.htmlData);
                        initScheduleButtons();
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

    $('.btn-printList').unbind('click');
    $('.btn-printList').click(function(e)
    {
        e.preventDefault();
        let THIS = $(this);
        let DATES_ID = THIS.data('datesid');
        $.ajax({
            url: '/processor/sessionPrint',
            data: {doctype: "uzi_single_day", dates_id: DATES_ID},
            dataType: 'json',
            type: 'post',
            success: function (json) {
                if (json) {
                    if (json.result === true) {
                        window.open(json.url);
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

    $('.btn-updateShiftList').unbind('click');
    $('.btn-updateShiftList').click(function(e)
    {
        e.preventDefault();
        let THIS = $(this);
        let DATES_ID = THIS.data('datesid');

        reloadEditDay(DATES_ID);
    });

    $('.btn-removeShiftOfDate').unbind('click');
    $('.btn-removeShiftOfDate').click(function(e)
    {
        if ( confirm('ВЫ ДЕЙСТВИТЕЛЬНО ЖЕЛАЕТЕ УДАЛИТЬ СМЕНУ ИЗ ГРАФИКА?') )
        {
            e.preventDefault();
            let THIS = $(this);
            let DATES_ID = THIS.data('datesid');
            $.ajax({
                url: '/processor/scheduleUzi_removeShiftDay',
                data: {dates_id: DATES_ID},
                dataType: 'json',
                type: 'post',
                success: function (json) {
                    if (json) {
                        if (json.result === true) {
                            alert(json.msg);
                            editDay.modal('hide');
                            loadCalendar(json.unix);
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

    $('.btn-add1Day').unbind('click');
    $('.btn-add1Day').click(function(e)
    {
        e.preventDefault();
        let form_data = formSerializer('#graphicAddDay_form');
        $.ajax({
            url: '/processor/scheduleUzi_addDay',
            data: form_data,
            dataType: 'json',
            type: 'post',
            success: function (json) {
                if (json) {
                    if (json.result === true) {
                        alert(json.msg);
                        $('#graphicAddDay').modal('hide');
                        loadCalendar(json.unix);
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

    $('.btn-add1Week').unbind('click');
    $('.btn-add1Week').click(function(e)
    {
        e.preventDefault();
        let form_data = formSerializer('#graphicAddWeek_form');
        $.ajax({
            url: '/processor/scheduleUzi_addWeek',
            data: form_data,
            dataType: 'json',
            type: 'post',
            success: function (json) {
                if (json) {
                    if (json.result === true) {
                        alert(json.msg);
                        $('#graphicAddWeek').modal('hide');
                        loadCalendar(json.unix);
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

    $('.btn-removeGraphic').unbind('click');
    $('.btn-removeGraphic').click(function(e)
    {
        if ( confirm('ВЫ УВЕРЕНЫ, ЧТО ХОТИТЕ ОЧИСТИТЬ ГРАФИК ЗА УКАЗАННЫЙ ПЕРИОД!') )
        {
            if ( confirm('ПРОЦЕДУРА НЕОБРАТИМА! ГРАФИК БУДЕТ УТЕРЯН!') )
            {
                e.preventDefault();
                let form_data = formSerializer('#graphicClear_form');
                $.ajax({
                    url: '/processor/scheduleUzi_clearGraphic',
                    data: form_data,
                    dataType: 'json',
                    type: 'post',
                    success: function (json) {
                        if (json) {
                            if (json.result === true) {
                                alert(json.msg);
                                $('#graphicClear').modal('hide');
                                loadCalendar(json.unix);
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

    });

    $('.btn-copyShiftAction').unbind('click');
    $('.btn-copyShiftAction').click(function(e)
    {
        // НАЖАТИЕ "КОПИРОВАТЬ" В ОТКРЫТОМ МОДАЛЬНОМ ОКНЕ
        e.preventDefault();
        let THIS = $(this);
        let form_data = formSerializer('#copyshift_form');
        // form_data.type = 'otherOne';
        $.ajax({
            url: '/processor/scheduleUzi_copyShift',
            data: form_data,
            dataType: 'json',
            type: 'post',
            success: function (json) {
                if (json) {
                    if (json.result === true) {
                        alert(json.msg);
                        $('#shift').modal('hide');
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

    $('input[type=radio][name=first_add]').change(function() {
        let choose_date = $('#choose_date');
        if (this.value === 'closer') {
            choose_date.hide();
        }
        else if (this.value === 'by_date') {
            choose_date.show();
        }
    });
}


function uziCAOPInfo(journal_id, date_id, patuzi_id=-1, )
{
    uziCaop_time_body.html( uziCaop_time_loader );
    uziCaop_time_submodal.modal('show');

    $.ajax({
        url: '/processor/journal_uzicaop_record',
        data: {patuzi_id: patuzi_id, journal_id: journal_id, date_id: date_id},
        dataType: 'json',
        type: 'post',
        success: function (json) {
            if (json) {
                if (json.result === true) {
                    uziCaop_time_body.html(json.htmlData);
                    MySQLEditorInit();
                    initScheduleButtons();
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

function initCalendarButton()
{


    $('.openDayCalendar').unbind('click');
    $('.openDayCalendar').click(function(e)
    {
        editDay.modal('show');

        e.preventDefault();
        let THIS = $(this);
        let DATE_ID = THIS.data('datesid');

        reloadEditDay(DATE_ID);


    });
}

function reloadEditDay(date_id)
{
    editDay_body.html(editDay_loader);
    $.ajax({
        url: '/processor/scheduleUzi_editDay',
        data: {dates_id: date_id},
        dataType: 'json',
        type: 'post',
        success: function (json) {
            if (json) {
                if (json.result === true) {
                    editDay_body.html(json.htmlData);
                    initScheduleButtons()
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

function uziDoctorList(doctor_id)
{
    if ( confirm('ВЫ ПОДТВЕРЖДАЕТЕ ДАННОЕ ДЕЙСТВИЕ?') )
    {
        $.ajax({
            url: '/processor/scheduleUzi_toList',
            // contentType: false, // важно - убираем форматирование данных по умолчанию
            // processData: false, // важно - убираем преобразование строк по умолчанию
            data: {doctor_id: doctor_id},
            dataType: 'json',
            type: 'post',
            success: function(json){
                if(json){
                                        if (json.result === true)
                    {
                        window.location.reload();
                    } else
                    {
                        alert(json.msg);
                    }
                }
            }
        });
    }
}

function loadCalendar(unix)
{
    // my_calendar.html(LOADER_calendar);

    let UZI_ID = $('#calendar').data('uziid');

    $.ajax({
        url: '/processor/scheduleUzi_calendar',
        data: {unix_time: unix, uzi_id: UZI_ID},
        dataType: 'json',
        type: 'post',
        success: function(json) {
            if ( json.result === true )
            {
                my_calendar.html( json.htmlData );
                $('#calendar').attr('data-unixstamp', unix);
                setTimeout( function() {
                    initCalendarButton()
                }, 500 );
            } else
            {
                my_calendar.html( json.msg );
            }
        },
        error: function() {
        },
        complete: function() {
        }
    });

}