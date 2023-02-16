var MAIN_CONTENT_ID = 'main_content';
var MAIN_CONTENT_INDENT = '#' + MAIN_CONTENT_ID;
var MAIN_CONTENT_DOM = '';
var FIELDS_HTML = '<div class="col p-1 bg-warning"><input type="text" class="form-control form-control-sm type_title" value="" placeholder="Название поля"></div>';
FIELDS_HTML += '<div class="col p-1 bg-warning"><input type="text" class="form-control form-control-sm type_enabled" value="1" placeholder="Значение включено(1) или нет(0)"></div>';
FIELDS_HTML += '<div class="col p-1 bg-warning"><input type="text" class="form-control form-control-sm type_order" value="" placeholder="Порядок сортировки"></div>';
FIELDS_HTML += '<div class="col p-1 bg-warning"><input type="text" class="form-control form-control-sm type_addon" value="" placeholder="Доп. параметр"></div>';
FIELDS_HTML += '<div class="col p-1 bg-warning"><input type="text" class="form-control form-control-sm type_addon_2" value="" placeholder="Доп. параметр 2"></div>';
FIELDS_HTML += '<div class="col p-1 bg-warning"><input type="text" class="form-control form-control-sm type_addon_3" value="" placeholder="Доп. параметр 3"></div>';
FIELDS_HTML += '<div class="col p-1 bg-warning"><input type="text" class="form-control form-control-sm type_addon_4" value="" placeholder="Доп. параметр 4"></div>';
FIELDS_HTML += '<div class="col p-1 bg-warning"><button type="button" class="btn btn-sm btn-danger deleteField"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x" viewBox="0 0 16 16"><path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"></path></svg></button></div>';

var LOADER_openDayCalendarModal = '';
var LOADER_openDayPatientModal = '';
var LOADER_openVisitPatientModal = '';
var LOADER_calendar = '';

var openDayCalendarModal_loader = '';
var openDayCalendarModal_modal = '';

var openDayPatientModal_loader = '';
var openDayPatientModal_modal = '';

var openVisitPatientModal_loader = '';
var openVisitPatientModal_modal = '';

var my_calendar = '';

var eventDispatcherButtons = {};

$( document ).ready(function(err)
{
    setTimeout( function ()
    {
        openDayCalendarModal_loader = $('#openDayCalendarModal');
        openDayCalendarModal_modal = $('#openDayCalendar');

        openDayPatientModal_loader = $('#openDayPatientModal');
        openDayPatientModal_modal = $('#openDayPatient');

        openVisitPatientModal_loader = $('#openVisitPatientModal');
        openVisitPatientModal_modal = $('#openVisitPatient');

        my_calendar = $('#calendar');
        LOADER_calendar = my_calendar.html();

        LOADER_openDayCalendarModal = openDayCalendarModal_loader.html();
        LOADER_openDayPatientModal = openDayPatientModal_loader.html();
        LOADER_openVisitPatientModal = openVisitPatientModal_loader.html();




        if ( MAIN_CONTENT_DOM == '' )
        {
            MAIN_CONTENT_DOM = $(MAIN_CONTENT_INDENT);
            if ( MAIN_CONTENT_DOM.length > 0 )
            {
            } else
            {
                alert('MAIN_CONTENT_DOM не загружен');
            }
        }
        $('.saveFields').unbind('click');
        $('.saveFields').click(function (e) {
            var THIS = $(this);
            var TABLE = THIS.data('table');
            if ( TABLE.length > 0 )
            {
                var FIELDS_DIV = $('#id_'+TABLE);
                if ( FIELDS_DIV.length == 1 )
                {
                    var FIELDS = FIELDS_DIV.find('.row_'+TABLE);
                    if ( FIELDS.length > 0 )
                    {
                        var FORM_DATA = [];
                        FIELDS.each( function (index) {
                            var FIELD = $(this);
                            var FORM_ARRAY = {};
                            var TYPE_TITLE = FIELD.find('.type_title');
                            var TYPE_ENABLED = FIELD.find('.type_enabled');
                            var TYPE_ORDER = FIELD.find('.type_order');
                            var TYPE_ADDON = FIELD.find('.type_addon');
                            var TYPE_ADDON_2 = FIELD.find('.type_addon_2');
                            var TYPE_ADDON_3 = FIELD.find('.type_addon_3');
                            var TYPE_ADDON_4 = FIELD.find('.type_addon_4');
                            if ( TYPE_TITLE.length==1 && TYPE_ENABLED.length==1 && TYPE_ORDER.length==1 )
                            {
                                FORM_ARRAY.TYPE_TITLE = TYPE_TITLE.val();
                                FORM_ARRAY.TYPE_ENABLED = TYPE_ENABLED.val();
                                FORM_ARRAY.TYPE_ORDER = TYPE_ORDER.val();
                                FORM_ARRAY.TYPE_ADDON = TYPE_ADDON.val();
                                FORM_ARRAY.TYPE_ADDON_2 = TYPE_ADDON_2.val();
                                FORM_ARRAY.TYPE_ADDON_3 = TYPE_ADDON_3.val();
                                FORM_ARRAY.TYPE_ADDON_4 = TYPE_ADDON_4.val();
                                FORM_DATA.push( FORM_ARRAY );
                            }
                        } );
                        if ( FORM_DATA.length > 0 )
                        {
                            var JSON_String = JSON.stringify(FORM_DATA);

                            var save_data = {table: TABLE, params: JSON_String};
                            $.ajax({
                                url: '/processor/dayStac_saveFields',
                                data: save_data,
                                dataType: 'json',
                                type: 'post',
                                success: function(json) {
                                    if ( json.result === true )
                                    {
                                        alert('Поля успешно сохранены!');
                                        window.location.reload();
                                    }
                                },
                                error: function() {
                                },
                                complete: function() {
                                }
                            });

                        } else
                        {
                            alert('ПРОБЛЕМА СОХРАНЕНИЯ ПОЛЕЙ')
                        }
                    } else
                    {
                        alert('Нет полей для сохранения');
                    }

                }
            }
        });

        $('.addField').unbind('click');
        $('.addField').click(function (e)
        {
            var THIS = $(this);
            var TABLE = THIS.data('table');
            if ( TABLE.length > 0 )
            {
                var FIELDS_DIV = $('#id_'+TABLE);
                if ( FIELDS_DIV.length == 1 )
                {
                    var FIELDS = FIELDS_DIV.find('.row_'+TABLE);
                    if ( FIELDS.length > 0 )
                    {
                        FIELDS_DIV.append('<div class="row row_'+TABLE+'">'+FIELDS_HTML+'</div>');
                    } else
                    {
                        FIELDS_DIV.html('');
                        FIELDS_DIV.append('<div class="row row_'+TABLE+'">'+FIELDS_HTML+'</div>');
                    }
                    initDynamicFunctions();
                }
            }
        });

        $('#importPatientData').unbind('click');
        $('#importPatientData').click(function (e)
        {
            //75730
            var PATIENT_IDENT_DOM = $('#patient_ident_search');
            var PATIENT_IDENT = PATIENT_IDENT_DOM.val();
            if ( PATIENT_IDENT.length > 0 )
            {
                $.ajax({
                    url: '/processor/dayStac_importPatient',
                    data: {patient_ident: PATIENT_IDENT},
                    dataType: 'json',
                    type: 'post',
                    success: function(json) {
                        if ( json.result === true )
                        {
                            $('#patient_ident').val( json.data.patid_ident );
                            $('#patient_fio').val( json.data.patid_name );
                            $('#patient_birth').val( json.data.patid_birth );
                            $('#patient_address').val( json.data.patid_address );
                            $('#patient_insurance_number').val( json.data.patid_insurance_number );
                            $('#patient_insurance_company_id').val( json.data.patid_insurance_company ).change();

                            alert('Импорт завершен');
                        } else
                        {
                            alert(json.msg);
                        }
                    },
                    error: function() {
                    },
                    complete: function() {
                    }
                });
            } else
            {
                alert('Вы не ввели номер амбулаторной карты');
            }

        });

        $('#savePatient').unbind('click');
        $('#savePatient').click(function (e)
        {
            //e.stopPropagation();
            e.preventDefault();
            var FORM = $('#form-newPatient');
            var FORM_DATA = FORM.serializeArray();

            $.ajax({
                url: '/processor/dayStac_savePatient',
                data: FORM_DATA,
                dataType: 'json',
                type: 'post',
                success: function(json) {
                    if ( json.result === true )
                    {
                        alert('Данные пациента сохранены');
                        window.location.href = '/dayStac/newPatient/' + json.patient_id;
                    } else
                    {
                        alert(json.msg);
                        if ( json.hasOwnProperty('patient_id') )
                        {
                            window.location.href = '/dayStac/newPatient/' + json.patient_id;
                        }
                    }
                },
                error: function() {
                },
                complete: function() {
                }
            });
        });

        $('#deletePatient').unbind('click');
        $('#deletePatient').click(function (e)
        {
            e.preventDefault();
            if ( confirm('ВЫ ДЕЙСТВИТЕЛЬНО ХОТИТЕ УДАЛИТЬ ПАЦИЕНТА ИЗ СПИСКА ДНЕВНОГО СТАЦИОНАРА?') )
            {
                if ( confirm('ВЫ ТОЧНО УВЕРЕНЫ?') )
                {
                    var THIS = $(this);
                    var PATIENT_ID = THIS.data('patient');
                    if ( PATIENT_ID > 0 )
                    {
                        $.ajax({
                            url: '/processor/dayStac_deletePatient',
                            data: {patient_id: PATIENT_ID},
                            dataType: 'json',
                            type: 'post',
                            success: function(json) {
                                if ( json.result === true )
                                {
                                    alert('ПАЦИЕНТ УДАЛЁН ИЗ СПИСКА ДНЕВНОГО СТАЦИОНАРА');
                                    window.location.href = '/dayStac/patientsList';
                                } else
                                {
                                    alert(json.msg);
                                }
                            },
                            error: function() {
                            },
                            complete: function() {
                            }
                        });

                    }
                }
            }
        });

        $('#addDirection').unbind('click');
        $('#addDirection').click(function (e)
        {
            var THIS = $(this);
            var PATIENT_ID = THIS.data('patient');
            if ( PATIENT_ID > 0 )
            {
                $.ajax({
                    url: '/processor/dayStac_addDirectlist',
                    data: {patient_id: PATIENT_ID},
                    dataType: 'json',
                    type: 'post',
                    success: function(json) {
                        if ( json.result === true )
                        {
                            alert('Новое направление успешно добавлено!');
                            window.location.href = '/dayStac/newPatient/' + PATIENT_ID;
                        } else
                        {
                            alert(json.msg);
                        }
                    },
                    error: function() {
                    },
                    complete: function() {
                    }
                });

            }
        });

        $('.saveDirlist').unbind('click');
        $('.saveDirlist').click(function (e)
        {
            e.preventDefault();
            var THIS = $(this);
            var DIRLIST_ID = THIS.data('dirlist');
            if ( DIRLIST_ID > 0 )
            {
                var FORM_ID = 'form_dirlist_' + DIRLIST_ID;
                var FORM_DOM = $('#'+FORM_ID);
                var FORM_DATA = FORM_DOM.serializeArray();

                $.ajax({
                    url: '/processor/dayStac_saveDirlist',
                    data: FORM_DATA,
                    dataType: 'json',
                    type: 'post',
                    success: function(json) {
                        if ( json.result === true )
                        {
                            alert('Данные направления сохранены');
                        } else
                        {
                            alert(json.msg);
                        }
                    },
                    error: function() {
                    },
                    complete: function() {
                    }
                });

            }
        });

        $('.deleteDirlist').unbind('click');
        $('.deleteDirlist').click(function (e)
        {
            e.preventDefault();
            if ( confirm('Вы действительно хотите удалить направление?') )
            {
                var THIS = $(this);
                var DIRLIST_ID = THIS.data('dirlist');
                if ( DIRLIST_ID > 0 )
                {
                    var PATIENT_ID = THIS.data('patient');
                    if ( PATIENT_ID > 0 )
                    {
                        $.ajax({
                            url: '/processor/dayStac_deleteDirlist',
                            data: {dirlist_id: DIRLIST_ID, patient_id: PATIENT_ID},
                            dataType: 'json',
                            type: 'post',
                            success: function(json) {
                                if ( json.result === true )
                                {
                                    alert('Направление удалено');
                                    window.location.href = '/dayStac/newPatient/' + PATIENT_ID;
                                } else
                                {
                                    alert(json.msg);
                                }
                            },
                            error: function() {
                            },
                            complete: function() {
                            }
                        });
                    }
                }
            }
        });

        $('#addProcedure').unbind('click');
        $('#addProcedure').click(function (e)
        {
            var THIS = $(this);
            var PATIENT_ID = THIS.data('patient');
            if ( PATIENT_ID > 0 )
            {
                $.ajax({
                    url: '/processor/dayStac_addVisitRegimen',
                    data: {patient_id: PATIENT_ID},
                    dataType: 'json',
                    type: 'post',
                    success: function(json) {
                        if ( json.result === true )
                        {
                            alert('Новое назначение успешно добавлено!');
                            window.location.href = '/dayStac/newPatient/' + PATIENT_ID;
                        } else
                        {
                            alert(json.msg);
                        }
                    },
                    error: function() {
                    },
                    complete: function() {
                    }
                });

            }
        });

        $('.createRegimen').unbind('click');
        $('.createRegimen').click(function (e)
        {
            e.preventDefault();
            var THIS = $(this);
            var DIRLIST_ID = THIS.data('dirlist');
            if ( DIRLIST_ID > 0 )
            {
                $.ajax({
                    url: '/processor/dayStac_createRegimen',
                    data: {dirlist_id: DIRLIST_ID},
                    dataType: 'json',
                    type: 'post',
                    success: function(json) {
                        if ( json.result === true )
                        {
                            alert('Назначение создано');
                            window.location.href = '/dayStac/newPatient/' + json.patient_id;
                        } else
                        {
                            alert(json.msg);
                        }
                    },
                    error: function() {
                    },
                    complete: function() {
                    }
                });
            }
        });

        $('.saveVisreg').unbind('click');
        $('.saveVisreg').click(function (e)
        {
            e.preventDefault();
            var THIS = $(this);
            var VISREG_ID = THIS.data('visreg');

            var FORM_ID = 'form_visreg_' + VISREG_ID;
            var FORM_DOM = $('#'+FORM_ID);
            var FORM_DATA = FORM_DOM.serializeArray();

            $.ajax({
                url: '/processor/dayStac_saveVisreg',
                data: FORM_DATA,
                dataType: 'json',
                type: 'post',
                success: function(json) {
                    if ( json.result === true )
                    {
                        alert(json.msg);
                        window.location.href = '/dayStac/newPatient/' + json.patient_id;
                    } else
                    {
                        alert(json.msg);
                    }
                },
                error: function() {
                },
                complete: function() {
                }
            });

        });

        $('.deleteVisreg').unbind('click');
        $('.deleteVisreg').click(function (e)
        {
            e.preventDefault();
            if ( confirm('Вы действительно хотите удалить назначение?') )
            {
                var THIS = $(this);
                var VISREG_ID = THIS.data('visreg');
                if ( VISREG_ID > 0 )
                {
                    var PATIENT_ID = THIS.data('patient');
                    if ( PATIENT_ID > 0 )
                    {
                        $.ajax({
                            url: '/processor/dayStac_deleteVisreg',
                            data: {visreg_id: VISREG_ID, patient_id: PATIENT_ID},
                            dataType: 'json',
                            type: 'post',
                            success: function(json) {
                                if ( json.result === true )
                                {
                                    alert('Назначение удалено');
                                    window.location.href = '/dayStac/newPatient/' + PATIENT_ID;
                                } else
                                {
                                    alert(json.msg);
                                }
                            },
                            error: function() {
                            },
                            complete: function() {
                            }
                        });
                    }
                }
            }
        });

        $('.importTemplate').unbind('click');
        $('.importTemplate').click(function (e)
        {
            e.preventDefault();
            var THIS = $(this);
            var VISREG_ID = THIS.data('visreg');
            var TEMP_DIV = 'visreg_template_' + VISREG_ID;
            var TEMP_DOM = $('#'+TEMP_DIV);
            var TEMP_ID = parseInt( TEMP_DOM.val() );
            if ( TEMP_ID > 0 )
            {
                var FIND_TEMP = VISREG_TEMPLATE.find( o => o.regimen_id === TEMP_DOM.val() );
                var FORM_DIV = 'form_visreg_' + VISREG_ID;
                var FORM_DOM = $('#' + FORM_DIV);

                var visreg_title = FORM_DOM.find( "[huid='visreg_title']" );
                visreg_title.val( FIND_TEMP.regimen_title );

                var visreg_drug = FORM_DOM.find( "[huid='visreg_drug']" );
                visreg_drug.val( FIND_TEMP.regimen_drug );

                var visreg_dose = FORM_DOM.find( "[huid='visreg_dose']" );
                visreg_dose.val( FIND_TEMP.regimen_dose );

                var visreg_freq_amount = FORM_DOM.find( "[huid='visreg_freq_amount']" );
                visreg_freq_amount.val( FIND_TEMP.regimen_freq_amount );

                var visreg_freq_period_amount = FORM_DOM.find( "[huid='visreg_freq_period_amount']" );
                visreg_freq_period_amount.val( FIND_TEMP.regimen_freq_period_amount );

                var visreg_dasigna = FORM_DOM.find( "[huid='visreg_dasigna']" );
                visreg_dasigna.val( FIND_TEMP.regimen_dasigna );

                var visreg_dose_measure_type = FORM_DOM.find( "[huid='visreg_dose_measure_type']" );
                visreg_dose_measure_type.val( FIND_TEMP.regimen_dose_measure_type ).change();

                var visreg_dose_period_type = FORM_DOM.find( "[huid='visreg_dose_period_type']" );
                visreg_dose_period_type.val( FIND_TEMP.regimen_dose_period_type ).change();

                var visreg_freq_period_type = FORM_DOM.find( "[huid='visreg_freq_period_type']" );
                visreg_freq_period_type.val( FIND_TEMP.regimen_freq_period_type ).change();


            } else
            {
                alert('Выберите шаблон!');
            }

        });

        $('.clearTemplate').unbind('click');
        $('.clearTemplate').click(function (e)
        {
            e.preventDefault();
            var THIS = $(this);
            var VISREG_ID = THIS.data('visreg');
            var FORM_ID = 'form_visreg_' + VISREG_ID;
            var FORM_DOM = $('#'+FORM_ID);
            FORM_DOM.trigger("reset");
        });


        $('.deleteRegimen').unbind('click');
        $('.deleteRegimen').click(function (e)
        {
            e.preventDefault();
            if ( confirm('Вы действительно хотите удалить данный шаблон?') )
            {
                var THIS = $(this);
                var REGIMEN = THIS.data('regimen');
                if ( REGIMEN > 0 )
                {
                    $.ajax({
                        url: '/processor/dayStac_deleteRegimen',
                        data: {regimen_id: REGIMEN},
                        dataType: 'json',
                        type: 'post',
                        success: function(json) {
                            if ( json.result === true )
                            {
                                window.location.reload();
                            } else
                            {
                                alert(json.msg);
                            }
                        },
                        error: function() {
                        },
                        complete: function() {
                        }
                    });
                }
            }
        });

        $('.saveRegimen').unbind('click');
        $('.saveRegimen').click(function(e)
        {
            e.preventDefault();
            var THIS = $(this);
            var REGIMEN = THIS.data('regimen');
            if ( REGIMEN > 0 )
            {
                var FORM_ID = 'form_regimen_' + REGIMEN;
                var FORM_DOM = $('#'+FORM_ID);
                var FORM_DATA = FORM_DOM.serializeArray();
                $.ajax({
                    url: '/processor/dayStac_saveRegimen',
                    data: FORM_DATA,
                    dataType: 'json',
                    type: 'post',
                    success: function(json) {
                        if ( json.result === true )
                        {
                            alert(json.msg);
                        } else
                        {
                            alert(json.msg);
                        }
                    },
                    error: function() {
                    },
                    complete: function() {
                    }
                });
            }
        });

        $('.dspatients').tablesorter({
            dateFormat : "ddmmyyyy", // default date format
        });

        $('.deleteResearch').unbind('click');
        $('.deleteResearch').click(function (e)
        {
            e.preventDefault();
            if ( confirm('Вы действительно хотите удалить данный результат анализов?') )
            {
                var THIS = $(this);
                var RESEARCH_ID = THIS.data('researchid');
                //data-researchid="346"
                if ( RESEARCH_ID > 0 )
                {
                    $.ajax({
                        url: '/processor/dayStac_deleteResearch',
                        data: {research_id: RESEARCH_ID},
                        dataType: 'json',
                        type: 'post',
                        success: function(json) {
                            if ( json.result === true )
                            {
                                window.location.reload();
                            } else
                            {
                                alert(json.msg);
                            }
                        },
                        error: function() {
                        },
                        complete: function() {
                        }
                    });
                }
            }
        });

        // initDynamicFunctions();
    }, 100 );





});

function initDynamicFunctions()
{
    $('.deleteField').unbind('click');
    $('.deleteField').click(function (e)
    {
        var THIS = $(this);
        var FIELD_DATA = THIS.parent().parent();
        if ( FIELD_DATA.length == 1 )
        {
            FIELD_DATA.remove();
        }
    });

    $('.openDayCalendar').unbind('click');
    $('.openDayCalendar').click(function(e)
    {
        openDayCalendarModal_loader.html( LOADER_openDayCalendarModal );
        openDayCalendarModal_modal.modal('show');
        // e.preventDefault();
        var THIS = $(this);
        var UNIX = THIS.data('day');
        var JSON_DIV_DOM = $('#hiddenJson_' + UNIX);
        var JSON_STRING = JSON_DIV_DOM.text();

        $.ajax({
            url: '/processor/dayStac_openDayCalendar',
            data: {json_data: JSON_STRING},
            dataType: 'json',
            type: 'post',
            success: function(json) {
                if ( json.result === true )
                {
                    // alert(json.msg);
                    openDayCalendarModal_loader.html( json.htmlData );
                    // MaskedInput();
                } else
                {
                    alert(json.msg);
                }
            },
            error: function() {
            },
            complete: function() {
            }
        });
    });

    $('.openDayPatient').unbind('click');
    $('.openDayPatient').click(function(e)
    {
        openDayPatientModal_loader.html( LOADER_openDayPatientModal );
        openDayPatientModal_modal.modal('show');
        // e.preventDefault();
        var THIS = $(this);
        var PATIENT = THIS.data('patient');
        var DIRLIST = THIS.data('dirlist');
        var WEEKUNIX = THIS.data('weekunix');
        var JSON_DIV_DOM = $('#hiddenJson_' + WEEKUNIX);
        var JSON_STRING = JSON_DIV_DOM.text();

        $.ajax({
            url: '/processor/dayStac_openDayPatient',
            data: {patient_id: PATIENT, dirlist_id: DIRLIST, json_data: JSON_STRING, weekunix: WEEKUNIX},
            dataType: 'json',
            type: 'post',
            success: function(json) {
                if ( json.result === true )
                {
                    openDayPatientModal_loader.html( json.htmlData );
                    MaskedInput();
                    initDynamicFunctions2();
                } else
                {
                    openDayPatientModal_loader.html(json.msg);
                }
            },
            error: function() {
            },
            complete: function() {
            }
        });
    });

    $('.openVisitPatient').unbind('click');
    $('.openVisitPatient').click(function(e)
    {
        e.preventDefault();

        openVisitPatientModal_loader.html( LOADER_openVisitPatientModal );
        openVisitPatientModal_modal.modal('show');

        var THIS = $(this);
        var _weekunix = THIS.data('weekunix');
        var _dirlist = THIS.data('dirlist');
        var _patient = THIS.data('patient');
        var _visit = THIS.data('visit');

        $.ajax({
            url: '/processor/dayStac_openVisitPatient',
            data: {patient_id: _patient, dirlist_id: _dirlist, visit: _visit, weekunix: _weekunix},
            dataType: 'json',
            type: 'post',
            success: function(json) {
                if ( json.result === true )
                {
                    openVisitPatientModal_loader.html( json.htmlData );
                    setTimeout(function ()
                    {
                        initDynamicFunctions2();
                    }, 100)
                } else
                {
                    openVisitPatientModal_loader.html(json.msg);
                }
            },
            error: function() {
            },
            complete: function() {
            }
        });
    });
}

function initDynamicFunctions2() {
    $('.createVisitRegimen').unbind('click');
    $('.createVisitRegimen').click(function(e)
    {
        e.preventDefault();
        var THIS = $(this);
        var VISREG_ID = THIS.data('visreg');
        var STATUS = THIS.data('status');

        var CURRENT_DAY = $('#currentDay_'+VISREG_ID).val();

        var JSON_STRING = '';
        if ( STATUS == "all" )
        {
            var JSON_DIV_DOM = $('#alldaysvisit');
            JSON_STRING = JSON_DIV_DOM.text();

            CURRENT_DAY = $('#allDays').val();
        }


        if ( VISREG_ID > 0 )
        {

            if ( CURRENT_DAY.length > 0 )
            {
                $.ajax({
                    url: '/processor/dayStac_createVisitRegimen',
                    data: {visreg_id: VISREG_ID, current_date: CURRENT_DAY, json_data: JSON_STRING},
                    dataType: 'json',
                    type: 'post',
                    success: function(json) {
                        if ( json.result === true )
                        {
                            alert(json.msg);
                            openDayPatientModal_modal.modal('hide');
                            loadCalendar(json.unix);
                        } else
                        {
                            alert(json.msg);
                        }
                    },
                    error: function() {
                    },
                    complete: function() {
                    }
                });
            }
        }
    });

    $('.saveNotes').unbind('click');
    $('.saveNotes').click(function(e)
    {
        e.preventDefault();

        var THIS = $(this);
        var VISIT = THIS.data('visit');
        if (VISIT>0)
        {
            var FORM = $('#from_visreg_result_' + VISIT);
            var FORM_DATA = FORM.serializeArray();

            $.ajax({
                url: '/processor/dayStac_saveNotes',
                data: FORM_DATA,
                dataType: 'json',
                type: 'post',
                success: function(json) {
                    if ( json.result === true )
                    {
                        alert('Заметки сохранены');
                    } else
                    {
                        alert(json.msg);
                    }
                },
                error: function() {
                },
                complete: function() {
                }
            });
        }

    });

    $('.deleteVisit').unbind('click');
    $('.deleteVisit').click(function(e)
    {
        e.preventDefault();
        if ( confirm('ВЫ ДЕЙСТВИТЕЛЬНО ХОТИТЕ УДАЛИТЬ ВИЗИТ?') )
        {
            var THIS = $(this);
            var VISIT = THIS.data('visit');
            if (VISIT>0)
            {
                $.ajax({
                    url: '/processor/dayStac_deleteVisit',
                    data: {visreg_id: VISIT},
                    dataType: 'json',
                    type: 'post',
                    success: function(json) {
                        if ( json.result === true )
                        {
                            alert('Процедура успешно удалена');
                            openVisitPatientModal_modal.modal('hide');
                            loadCalendar(json.unix);
                        } else
                        {
                            alert(json.msg);
                        }
                    },
                    error: function() {
                    },
                    complete: function() {
                    }
                });
            }
        }
    });

    $('.saveResearch').unbind('click');
    $('.saveResearch').click(function(e)
    {
        e.preventDefault();
        var THIS = $(this);
        var PATID = THIS.data('patient');
        if ( PATID > 0 )
        {
            var FORM = $('#research_' + PATID);
            var FORM_DATA = FORM.serializeArray();
            $.ajax({
                url: '/processor/dayStac_saveResearch',
                data: FORM_DATA,
                dataType: 'json',
                type: 'post',
                success: function(json) {
                    if ( json.result === true )
                    {
                        alert('Анализы сохранены');
                    } else
                    {
                        alert(json.msg);
                    }
                },
                error: function() {
                },
                complete: function() {
                }
            });
        }

    });

    $('.saveVisitAmount').unbind('click');
    $('.saveVisitAmount').click(function(e)
    {
        e.preventDefault();
        var THIS = $(this);
        var WEEKUNIX = THIS.data('weekunix');
        if ( WEEKUNIX > 0 )
        {
            var FORM = $('#visit_amount_' + WEEKUNIX);
            var FORM_DATA = FORM.serializeArray();
            $.ajax({
                url: '/processor/dayStac_saveVisitAmount',
                data: FORM_DATA,
                dataType: 'json',
                type: 'post',
                success: function(json) {
                    if ( json.result === true )
                    {
                        alert('Данные визита сохранены');
                    } else
                    {
                        alert(json.msg);
                    }
                },
                error: function() {
                },
                complete: function() {
                }
            });
        }
    });
}

function loadPage(url, post=[])
{

    $.ajax({
        url: '',
        // contentType: false, // важно - убираем форматирование данных по умолчанию
        // processData: false, // важно - убираем преобразование строк по умолчанию
        //data: {diag_id: diag_id},
        dataType: 'json',
        type: 'post',
        success: function() {
        },
        error: function() {
        },
        complete: function() {
        }
    });
}

function loadCalendar(unix)
{
    // my_calendar.html(LOADER_calendar);
    $.ajax({
        url: '/processor/dayStac_calendar',
        data: {unix_time: unix},
        dataType: 'json',
        type: 'post',
        success: function(json) {
            if ( json.result === true )
            {
                my_calendar.html( json.htmlData );
                setTimeout( function() {
                    initDynamicFunctions();
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