var LOADER_HTML = '';
var LOADER_HTML2 = '';
var LOADER_HTML3 = '';
var LOADER_HTML4 = '';
var LOADER_HTML5 = '';
var LOADER_HTML6 = '';
var TIMER_JSON_CHECK_BLOCKCHAIN = 1;
var FORM_ADD_PATIENT_TYPE = "checkcard";

var PATIENT_JOURNAL_ID = -1;
var PATIENT_DISPANSER_JOURNAL_ID = -1;

var JOURNAL_CARD_MODAL_ISOPENED = -1;

let PINNED_TAB;

let doctor_result_dom;
let doctor_result_loader;

// let uziCaop_modal;
// let uziCaop_loader;
// let uziCaop_body;

// let uziCaop_time_submodal;
// let uziCaop_time_loader;
// let uziCaop_time_body;

// let uziCaop_list_submodal;
// let uziCaop_list_loader;
// let uziCaop_list_body;

let I_CAN_CLOSE_FORM = true;

$(document).ready(function (e) {

    PINNED_TAB = 'daily';

    doctor_result_dom = $('#doctor_result');
    doctor_result_loader = '<div class="input-group input-group-sm"><div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div></div>';

    LOADER_HTML = $('#journalPatientData').html();
    LOADER_HTML2 = $('#addPatientResearchModal').html();
    LOADER_HTML3 = $('#addPatientCitologyModal').html();
    LOADER_HTML4 = $('#journalEditCitologyData').html();
    LOADER_HTML5 = $('#journalEditResearchData').html();
    LOADER_HTML6 = $('#journalFastMove_body').html();

    uziCaop_modal = $('#uzi-caop');
    uziCaop_body = $('#uzi-caop_body');
    uziCaop_loader = uziCaop_body.html();

    uziCaop_time_submodal = $('#uzi-caop-time-submodal');
    uziCaop_time_body = $('#uzi-caop-time-submodal_body');
    uziCaop_time_loader = uziCaop_time_body.html();

    uziCaop_list_submodal = $('#uzi-caop-list-submodal');
    uziCaop_list_body = $('#uzi-caop-list-submodal_body');
    uziCaop_list_loader = uziCaop_list_body.html();

    $('.selector-choose-doctor').unbind('change');
    $('.selector-choose-doctor').change(function(e)
    {
        e.preventDefault();
        let THIS = $(this);
        let doctor_id = THIS.val()
        doctor_result_dom.html(doctor_result_loader);
        cursor_wait();
        $.ajax({
            url: '/processor/journal_doctorDays',
            data: {doctor_id},
            dataType: 'json',
            type: 'post',
            success: function (json) {
                remove_cursor_wait();
                if (json) {
                    if (json.result === true) {
                        doctor_result_dom.html(json.htmlData);
                    } else {
                        doctor_result_dom.html(json.msg);
                    }
                }
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                remove_cursor_wait();
                alert(textStatus);
            }
        });
    });

    $("#newPat").validate();

    $('.button_journal_print').click(function () {
        var THIS = $(this);
        var DAY_ID = parseInt(THIS.data('dayid'));
        var TYPE = THIS.data('type');
        if (DAY_ID > 0) {
            window.open('/journalPrint/' + TYPE + '/' + DAY_ID);
        }
    });

    $("#patient_journal").tablesorter();

    $('#addNewDay').click(function () {
        $.ajax({
            url: '/processor/newday',
            contentType: false, // важно - убираем форматирование данных по умолчанию
            processData: false, // важно - убираем преобразование строк по умолчанию
            // data: {action: 'reg', form: formData},
            // data: formData,
            dataType: 'json',
            type: 'post',
            success: function (json) {
                if (json) {



                    if (json.result === true) {
                        $('#journalCurrentMessagePlace').html(json.msg);
                        $('#buttonjournalCurrentDismiss').hide();
                        $('#buttonjournalCurrentNext').show();
                        $('#journalCurrentMessageBox').modal('show');
                    } else {

                        $('#buttonjournalCurrentDismiss').show();
                        $('#buttonjournalCurrentNext').hide();
                        $('#journalCurrentMessagePlace').html(json.msg);
                        $('#journalCurrentMessageBox').modal('show');
                    }
                }
            }
        });
    });

    $('#button_journal_refresh').click(function () {
        window.location.href = '/journalCurrent';
    })

    $('#button_journal_newPat').click(function () {
        $('#newPatient').modal('show');
        setTimeout(function () {
            $('#cardid').focus();
        }, 500);
    })

    $('#button_journal_search4add').click(function () {
        $('#search4add').modal('show');
        setTimeout(function () {

            $('#searchString').focus();
        }, 500);
    })

    $('#addNew').click(function () {
        $('#newPat').submit();
    })

    $('#newPat').on('submit', function (e) {
        e.preventDefault();
        e.stopPropagation();

        var FORM_namePat = $('#namePat').val();
        var FORM_birth = $('#birth').val();
        var FORM_cardid = $('#cardid').val();
        var FORM_timer = $('#timer').val();
        var FORM_phone = $('#phone').val();
        var FORM_address = $('#address').val();
        var FORM_insurance_company = $('#insurance_company').val();
        var FORM_insurance_number = $('#insurance_number').val();

        if ( FORM_namePat.length>0 &&
            FORM_birth.length>0 &&
            FORM_cardid.length>0 &&
            FORM_timer.length>0 &&
            FORM_phone.length>0 &&
            FORM_address.length>0 &&
            FORM_insurance_company.length>0 &&
            FORM_insurance_number.length>0 )
        {
            var $that = $(this),
                formData = new FormData($that.get(0)); // создаем новый экземпляр объекта и передаем ему нашу форму (*)




            $.ajax({
                url: '/processor/newpat',
                contentType: false, // важно - убираем форматирование данных по умолчанию
                processData: false, // важно - убираем преобразование строк по умолчанию
                // data: {action: 'reg', form: formData},
                data: formData,
                dataType: 'json',
                type: 'post',
                success: function (json) {
                    if (json) {



                        if (json.result === true) {
                            window.location.reload();
                        } else {
                            $('#buttonjournalCurrentDismiss').show();
                            $('#buttonjournalCurrentNext').hide();
                            $('#journalCurrentMessagePlace').html(json.msg);
                            $('#journalCurrentMessageBox').modal('show');
                        }
                    }
                }
            });
        } else alert('ЗАПОЛНИТЕ ВСЕ ПОЛЯ');


    });

    $('#moveIt').click(function () {
        var DATEPICKER = $('#dateMovePatients');
        var DATER = DATEPICKER.val();
        if (DATER.length !== 10) {
            alert('Выберите дату для переноса');
        } else {
            var checkLabels = moveLabelerReturn();
            var patient_ids = [];
            for (let i = 0; i < checkLabels.length; i++) {
                var ITEM = checkLabels[i];
                var PATID = ITEM.data('patid');
                patient_ids.push(PATID);
            }
            var dat = {dater: DATER, patientsList: patient_ids};

            $.ajax({
                url: '/processor/datemover',
                data: dat,
                dataType: 'json',
                type: 'post',
                success: function (json) {
                    if (json) {

                        if (json.result === true) {
                            window.location.reload();
                        }
                    }
                }
            });

        }
    });



    $('#moveDocIt').click(function () {
        var DATEPICKER = $('#dateMoveDocPatients');
        var DATER = DATEPICKER.val();
        if (DATER.length !== 10) {
            alert('Выберите дату для переноса');
        } else {
            var checkLabels = moveLabelerReturn();
            var patient_ids = [];
            for (let i = 0; i < checkLabels.length; i++) {
                var ITEM = checkLabels[i];
                var PATID = ITEM.data('patid');
                patient_ids.push(PATID);
            }
            var DOCID = parseInt( $('#docid').val() );
            if ( DOCID > 0 )
            {
                var dat = {dater: DATER, patientsList: patient_ids, docid: DOCID};

                $.ajax({
                    url: '/processor/datemover',
                    data: dat,
                    dataType: 'json',
                    type: 'post',
                    success: function (json) {
                        if (json) {

                            if (json.result === true) {
                                window.location.reload();
                            }
                        }
                    }
                });
            } else
            {
                alert("Вы не выбрали врача для переноса!");
            }


        }
    });

    $('.openCard').click(function () {
        isReportedClickInit();
        var THIS = $(this);
        var ID = THIS.data('patient');
        // alert(ID);
        $('#journalPatientData').html(LOADER_HTML);
        $('#journalPatientCard').modal('show');
        JOURNAL_CARD_MODAL_ISOPENED = true;
        var dat = {patient_id: ID};
        $.ajax({
            url: '/processor/journalCard',
            // contentType: false, // важно - убираем форматирование данных по умолчанию
            // processData: false, // важно - убираем преобразование строк по умолчанию
            data: dat,
            dataType: 'json',
            type: 'post',
            success: function (json) {
                if (json) {

                    if (json.result === true) {
                        PATIENT_DISPANSER_JOURNAL_ID = ID;
                        PATIENT_JOURNAL_ID = ID
                        $('#journalPatientData').html(json.htmlData);
                        MySQLEditorInit();
                        MaskedInput();
                        reInitVerif();

                        setTimeout( function()
                        {
                            initModalFunctions();
                        }, 500);
                    } else {
                        $('#journalPatientData').html(json.msg);
                    }
                }
            }
        });
    });

    $('.openCardSPO').click(function () {
        isReportedClickInit();
        var THIS = $(this);
        var ID = THIS.data('patient');
        // alert(ID);
        $('#journalPatientData').html(LOADER_HTML);
        $('#journalPatientCard').modal('show');
        JOURNAL_CARD_MODAL_ISOPENED = true;
        var dat = {patient_id: ID};
        $.ajax({
            url: '/processor/journalCardSPO',
            // contentType: false, // важно - убираем форматирование данных по умолчанию
            // processData: false, // важно - убираем преобразование строк по умолчанию
            data: dat,
            dataType: 'json',
            type: 'post',
            success: function (json) {
                if (json) {

                    if (json.result === true) {
                        PATIENT_DISPANSER_JOURNAL_ID = ID;
                        $('#journalPatientData').html(json.htmlData);
                        MySQLEditorInit();
                        MaskedInput();
                        setTimeout( function()
                        {
                            initModalFunctions();
                        }, 500);
                    } else {
                        $('#journalPatientData').html(json.msg);
                    }
                }
            }
        });
    });



    $('.button_document_print').click(function (e) {
        var THIS = $(this);
        var DOCUMENT = THIS.data('document');
        var PATIENT = THIS.data('patient');
        window.open('/documentPrint/' + DOCUMENT + '/' + PATIENT);
    });

    $('#newMias').click(function (e) {
        $('#miasImportForm').submit();
    });

    $('#miasImportForm').on('submit', function (e) {
        e.stopPropagation();
        e.preventDefault();

        var $that = $(this),
            formData = new FormData($that.get(0)); // создаем новый экземпляр объекта и передаем ему нашу форму (*)
        $.ajax({
            url: '/processor/miasImport',
            contentType: false, // важно - убираем форматирование данных по умолчанию
            processData: false, // важно - убираем преобразование строк по умолчанию
            data: formData,
            dataType: 'json',
            type: 'post',
            success: function (json) {
                if (json) {

                    if (json.result === true) {
                        window.location.reload();
                    } else {
                    }
                }
            }
        });
    });

    $('#newMias2').unbind('click');
    $('#newMias2').click(function (e) {
        $('#miasImportForm2').submit();
    });

    $('#newMiasMain').unbind('click');
    $('#newMiasMain').click(function (e) {
        $('#miasImportWindowMainForm').submit();
    });

    $('#miasImportWindowMainForm').on('submit', function (e) {
        cursor_wait();
        //$('#miasTextMain').attr('disabled', true);
        e.stopPropagation();
        e.preventDefault();
        var $that = $(this),
            formData = new FormData($that.get(0));
        $('#miasTextMain').attr('disabled', true);
        $.ajax({
            url: '/processor/miasImportMain',
            contentType: false, // важно - убираем форматирование данных по умолчанию
            processData: false, // важно - убираем преобразование строк по умолчанию
            data: formData,
            dataType: 'json',
            type: 'post',
            success: function (json) {
                if (json) {

                    if (json.result === true) {
                        window.location.reload();
                    } else {
                        remove_cursor_wait();
                        $('#miasTextMain').attr('disabled', false);
                        alert(json.msg);
                    }
                }
            }
        });
    });

    $('#miasImportForm2').on('submit', function (e) {
        e.stopPropagation();
        e.preventDefault();

        var $that = $(this),
            formData = new FormData($that.get(0)); // создаем новый экземпляр объекта и передаем ему нашу форму (*)

        $.ajax({
            url: '/processor/miasImport2',
            contentType: false, // важно - убираем форматирование данных по умолчанию
            processData: false, // важно - убираем преобразование строк по умолчанию
            data: formData,
            dataType: 'json',
            type: 'post',
            success: function (json) {
                if (json) {

                    if (json.result === true) {
                        window.location.reload();
                    } else {
                    }
                }
            }
        });
    });

    $('#checkCard').click(function (e) {
        CARDID = $('#cardid');
        CARDID_VALUE = CARDID.val();
        $.ajax({
            url: '/processor/journalCheckCard',
            data: {patid_ident: CARDID_VALUE},
            dataType: 'json',
            type: 'post',
            success: function (json) {
                if (json) {

                    if (json.result === true) {
                        switch (json.foundtype) {
                            case "notfound":
                                // разблокируем поля, пусть добавляет
                                var msg = bt_notice('<b>ДОБАВЛЕНИЕ НОВОГО ПАЦИЕНТА</b>',BT_THEME_PRIMARY);
                                $('#patientStatus').html(msg);
                                $('.default-disabled').prop('disabled', false);
                                $('#addNew').removeClass('invisible');
                                $('#namePat').focus();
                                FORM_ADD_PATIENT_TYPE = "freeform";
                                break;
                            case "found":
                                // подгружаем данные из переменной patientData

                                var msg = bt_notice('<b>ПАЦИЕНТ НАЙДЕН</b>',BT_THEME_SECONDARY);
                                $('#patientStatus').html(msg);

                                $('.default-disabled').prop('disabled', false);
                                $('#namePat').val(json.patientData.patid_name);
                                $('#birth').val(json.patientData.patid_birth);
                                $('#phone').val(json.patientData.patid_phone);
                                $('#address').val(json.patientData.patid_address);
                                $('#insurance_number').val( json.patientData.patid_insurance_number );
                                $('#insurance_company').val( json.patientData.patid_insurance_company );
                                $('#addNew').removeClass('invisible');
                                FORM_ADD_PATIENT_TYPE = "freeform";
                                break;
                            case "multy":
                                // сообщаем, что пациентов с такой картой несколько

                                var msg = bt_notice('<b>ВНИМАНИЕ! НЕСКОЛЬКО ПАЦИЕНТОВ!</b>',BT_THEME_DANGER);
                                $('#patientStatus').html(msg);
                                break;

                        }


                    } else {
                    }
                }
            }
        });
    })

    if (CURRENT_PAGE === 'journalCurrent') {
        // TIMER_JSON_CHECK_BLOCKCHAIN = setInterval(function (e) {
        //     CheckBlockchainHash();
        // }, 5000);
        // CheckBlockchainHash();
    }

    $('#newPat').keypress(function (e) {
        if (e.which === 13) {
            if ( FORM_ADD_PATIENT_TYPE === "checkcard" )
            {
                $('#checkCard').click();
            } else
            {
                if ( FORM_ADD_PATIENT_TYPE === "freeform" )
                {
                    $('#addNew').click();
                }
            }
        }
    });

    $('.waiting-patient').click(function (e) {
        var THIS = $(this);
        var WAITING = THIS.data('waiting');
        $.ajax({
            url: '/processor/journalWaiting',
            data: {journal_id: WAITING},
            dataType: 'json',
            type: 'post',
            success: function (json) {
                if (json) {

                    if (json.result === true) {
                        var waitingPatient = $('#waitingPatient' + WAITING);
                        if ( json.waiting === 1 )
                        {
                            waitingPatient.addClass('table-success');
                        } else {
                            waitingPatient.removeClass('table-success');
                        }

                    } else {
                    }
                }
            }
        });
    });

    $('#addNewPatientCitology').unbind('click');
    $('#addNewPatientCitology').click(function () {
        let PATID = $('#patid').val();
        let PATVIS = $('#patvisit').val();
        let CITOTYPE = $('#citology_analise_type').val();
        let BIOMARK = $('#biomatmark').val();
        let RESULT = $('#result').val();
        let RESULT_DATE = $('#result_date').val();
        let RESULT_IDENT = $('#result_ident').val();
      	let IS_ADD = $('#is_add').val();
        let CANCER = $("input[name='research_cancer']:checked").val();

        let data = {
            patient_id: PATID,
            patvis: PATVIS,
            citotype: CITOTYPE,
            biomark: BIOMARK,
            result: RESULT,
            result_date: RESULT_DATE,
            result_ident: RESULT_IDENT,
            cancer: CANCER,
          	is_add: IS_ADD,
        }
        console.log(data)
        // /*
        $.ajax({
            url: '/processor/journalCitology2',
            data: data,
            dataType: 'json',
            type: 'post',
            success: function (json) {
                if (json) {
                    if (json.result === true) {
                        I_CAN_CLOSE_FORM = true;
                        alert('Пациент успешно записан на цитологическое исследование!');
                        // window.location.reload();
                        $('#addPatientCitology').modal('hide');
                        // $('#addPatientResearch').modal('dispose');
                        $('.openCard[data-patient="'+PATIENT_JOURNAL_ID+'"]').click();
                        $('#journalPatientCard').modal('handleUpdate');
                        openPinnedTab();

                        // PATIENT_JOURNAL_ID = -1;
                    } else alert(json.msg)
                }
            }
        });
        // */
    })

    $('#addNewPatientResearch').unbind('click');
    $('#addNewPatientResearch').click(function () {
        let PATID = $('#patid').val();
        let PATVIS = $('#patvisit').val();
        let RESTYPE = $('#research_type').val();
        let AREARES = $('#area').val();
        let DATERES = $('#dater').val();
        let RESULT = $('#result').val();
        let IS_ADD = $('#is_add').val();
        let CANCER = $("input[name='research_cancer']:checked").val();
        // console.log(CANCER);
        // console.log(CANCER.val())

        let data = {
            patient_id: PATID,
            patvis: PATVIS,
            restype: RESTYPE,
            areares: AREARES,
            dateres: DATERES,
            result: RESULT,
            cancer: CANCER,
          	is_add: IS_ADD,
        }

        console.log(data);
        // /*
        $.ajax({
            url: '/processor/journalResearch2',
            data: data,
            dataType: 'json',
            type: 'post',
            success: function (json) {
                if (json) {
                    if (json.result === true) {
                        I_CAN_CLOSE_FORM = true;
                        alert('Пациент успешно записан на обследование!');
                        $('#addPatientResearch').modal('hide');
                        // $('#addPatientResearch').modal('dispose');
                        $('.openCard[data-patient="'+PATIENT_JOURNAL_ID+'"]').click();
                        $('#journalPatientCard').modal('handleUpdate');
                        openPinnedTab();
                        // PATIENT_JOURNAL_ID = -1;
                    } else alert(json.msg);
                }
            }
        });
        // */
    })

    $('#dateShow').submit(function(e)
    {
        e.preventDefault();
        e.stopPropagation();
        var calendar = $('#calendar');
        if (calendar.length === 1)
        {
            var DATE = calendar.val();
            $.ajax({
                url: '/processor/journalGoDay',
                data: {dater: DATE},
                dataType: 'json',
                type: 'post',
                success: function (json) {
                    if (json) {
                        if (json.result === true) {
                            window.location.href = json.dater;
                        } else
                        {
                            alert(json.msg);
                        }
                    }
                }
            });
        }

    });
    
    $('#netPat_go').unbind('click');
    $('#netPat_go').click(function(e)
    {
        e.preventDefault();
        var THIS = $(this);

        $.ajax({
            url: '/processor/search4add_create',
            //data: {dater: DATE},
            dataType: 'json',
            type: 'post',
            success: function (json) {
                if (json) {
                    if (json.result === true) {
                        $('#search4add_result').html( json.htmlData );
                        MaskedInput();
                    } else
                    {
                        alert(json.msg);
                    }
                }
            }
        });
    });

    $('#search4add_button').click(function(e)
    {
        e.preventDefault();
        var THIS = $(this);

        $.ajax({
            url: '/processor/search4add_find',
            data: {finder: $('#searchString').val()},
            dataType: 'json',
            type: 'post',
            success: function (json) {
                if (json) {
                    if (json.result === true) {
                        $('#search4add_result').html( json.htmlData );
                    } else
                    {
                        alert(json.msg);
                    }
                }
            }
        });

    });

    $('#addPatientResearch').on('hide.bs.modal', function (e)
    {
        let research_type = $('#research_type').val()
        let area = $('#area').val()
        let dater = $('#dater').val()
        let result = $('#result').val()
        if ( parseInt(research_type) > 0 || parseInt(area) > 0 || dater.length > 0 || result.length > 0 )
            if ( !I_CAN_CLOSE_FORM )
                if ( !confirm('Имеются несохранённые введённые данные. Вы уверены, что хотите закрыть модальное окно?') )
                    return false
    });

    $('#addPatientCitology').on('hide.bs.modal', function (e)
    {
        let citology_analise_type = $('#citology_analise_type').val()
        let biomatmark = $('#biomatmark').val()
        if ( parseInt(citology_analise_type) > 0 || biomatmark.length > 0 )
            if ( !I_CAN_CLOSE_FORM )
                if ( !confirm('Имеются несохранённые введённые данные. Вы уверены, что хотите закрыть модальное окно?') )
                    return false
    });

    $('#journalPatientCard').on('hide.bs.modal', function (e) {
        if ( JOURNAL_CARD_MODAL_ISOPENED )
        {
            e.preventDefault(); // ТА САМАЯ ВАЖНАЯ ЧАСТЬ, КОТОРАЯ НЕ ДАЕТ ЗАКРЫТЬ МОДАЛКУ
            // PATIENT_DISPANSER_JOURNAL_ID = -1;
            // ПРОВЕРЯЕМ МЕТКУ ДИСПАНСЕРНОГО УЧЕТА
            $.ajax({
                url: '/processor/disp_checkDispanser',
                data: {journal_id: PATIENT_DISPANSER_JOURNAL_ID},
                dataType: 'json',
                type: 'post',
                success: function (json) {
                    if (json) {
                        if (json.result === true) {
                            JOURNAL_CARD_MODAL_ISOPENED = false;
                            $('#journalPatientCard').modal('toggle');
                        } else
                        {
                            alert(json.msg);

                            redirectOnTab(json.tab, json.focus)

                        }
                    }
                }
            });
        }
    });

    $('#journalEditResearch').on('hide.bs.modal', function (e)
    {
        if ( CITOLOGY_MODAL_ISOPENED )
        {
            e.preventDefault()
            let deleteResearch = $('*[data-researchid]');
            let research_id = deleteResearch.data('researchid');
            $.ajax({
                url: '/processor/citology_checkCancer',
                data: {research_id, type: 'research'},
                dataType: 'json',
                type: 'post',
                success: function (json) {
                    if (json) {
                        if (json.result === true) {
                            CITOLOGY_MODAL_ISOPENED = false
                            $('#journalEditResearch').modal('toggle')
                        } else
                        {
                            e.preventDefault()
                            alert(json.msg);
                            return false;
                        }
                    }
                }
            });
        }
    });

    $('#journalEditCitology').on('hide.bs.modal', function (e)
    {
        if ( CITOLOGY_MODAL_ISOPENED )
        {
            e.preventDefault()
            let deleteCitology = $('*[data-citologyid]');
            let citology_id = deleteCitology.data('citologyid');
            $.ajax({
                url: '/processor/citology_checkCancer',
                data: {citology_id, type: 'citology'},
                dataType: 'json',
                type: 'post',
                success: function (json) {
                    if (json) {
                        if (json.result === true) {
                            CITOLOGY_MODAL_ISOPENED = false
                            $('#journalEditCitology').modal('toggle')
                        } else
                        {
                            e.preventDefault()
                            alert(json.msg);
                            return false;
                        }
                    }
                }
            });
        }

    });

    
    $('.journal-check-data').unbind('click');
    $('.journal-check-data').click(function(e)
    {
        e.preventDefault();
        let THIS = $(this);
        DAY_ID = THIS.data('day')
        DOCTOR_ID = THIS.data('doctor')

        $.ajax({
            url: '/processor/journalCheckData',
            data: {day_id: DAY_ID, doctor_id: DOCTOR_ID},
            dataType: 'json',
            type: 'post',
            success: function (json) {
                if (json) {

                }
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert(textStatus);
            }
        });

    });


    $('.journal-check-moves').unbind('click');
    $('.journal-check-moves').click(function(e)
    {
        e.preventDefault();
        let THIS = $(this);
        DAY_ID = THIS.data('day')
        DOCTOR_ID = THIS.data('doctor')

        $.ajax({
            url: '/processor/journalCheckMoves',
            data: {day_id: DAY_ID, doctor_id: DOCTOR_ID},
            dataType: 'json',
            type: 'post',
            success: function (json) {
                if (json) {

                }
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert(textStatus);
            }
        });

    });

    $('.btn-moveAllPatients').unbind('click');
    $('.btn-moveAllPatients').click(function(e)
    {
        e.preventDefault();
        if ( confirm('Перенести всех подготовленных к переносу пациентов?') )
        {
            let THIS = $(this);
            let day_id = THIS.data('day');
            let doctor_id = THIS.data('doctor');
            $.ajax({
                url: '/processor/datemoverAll',
                data: {day_id, doctor_id},
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

    $('#check_all').unbind('change');
    $('#check_all').change(function(e)
    {
        e.preventDefault();
        let THIS = $(this);
        let is_checked = THIS.is(":checked");
        checkingAll(is_checked);
    });
    

    setTimeout( function()
    {
        initModalFunctions();
    }, 500);
    

}); // dred



function updateDaily()
{
    $('.openCard[data-patient="'+PATIENT_JOURNAL_ID+'"]').click();
    $('#journalPatientCard').modal('handleUpdate');
    openPinnedTab();
}

function deleteDaily(journal_id)
{
    $.ajax({
        url: '/processor/removeDaily',
        data: {journal_id},
        dataType: 'json',
        type: 'post',
        success: function (json) {
            if (json) {
                if (json.result === true) {
                    $('.openCard[data-patient="'+PATIENT_JOURNAL_ID+'"]').click();
                    $('#journalPatientCard').modal('handleUpdate');
                    openPinnedTab();
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

function addDaily(journal_id)
{
    $.ajax({
        url: '/processor/newDaily',
        data: {journal_id},
        dataType: 'json',
        type: 'post',
        success: function (json) {
            if (json) {
                if (json.result === true) {
                    $('.openCard[data-patient="'+PATIENT_JOURNAL_ID+'"]').click();
                    $('#journalPatientCard').modal('handleUpdate');
                    openPinnedTab();
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

function initModalFunctions()
{
    $('#deletePatientData').unbind('click');
    $('#deletePatientData').click(function (e) {
        if ( confirm("ПОДТВЕРДИТЕ УДАЛЕНИЕ ПАЦИЕНТА ИЗ БАЗЫ ДАННЫХ") )
        {
            if ( confirm("ТОЧНО УДАЛИТЬ ПАЦИЕНТА ИЗ БАЗЫ ДАННЫХ?") )
            {
                var UNIQUE = $('#UNIQUE');
                var PATID_ID = UNIQUE.val();
                alert('ID ПАЦИЕНТА: ' + PATID_ID);
                $.ajax({
                    url: '/processor/visitsPatientRemove',
                    // contentType: false, // важно - убираем форматирование данных по умолчанию
                    // processData: false, // важно - убираем преобразование строк по умолчанию
                    data: {patid_id: PATID_ID},
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
            }
        }
    });

    $('.btn-setVisitType').unbind('click');
    $('.btn-setVisitType').click(function(e)
    {
        if ( confirm('Вы уверены, что хотите произвести преобразование типа услуги?') )
        {
            e.preventDefault();
            let THIS = $(this);
            let journal_id = THIS.data('journal');
            let visit_type_id = THIS.data('type');
            $.ajax({
                url: '/processor/visitToType',
                data: {journal_id, visit_type_id},
                dataType: 'json',
                type: 'post',
                success: function (json) {
                    if (json) {
                        alert(json.msg);
                        if (json.result === true) {
                            $('#btn-journalCard-refresh').click();
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

    $('#btn-forceClose').unbind('click');
    $('#btn-forceClose').click(function(e)
    {
        e.preventDefault();
        let THIS = $(this);
        CITOLOGY_MODAL_ISOPENED = false;
        $('#journalEditCitology').modal('toggle')
    });

    $('#btn-journalCard-refresh').unbind('click');
    $('#btn-journalCard-refresh').click(function(e)
    {
        e.preventDefault();
        let THIS = $(this);

        $('.openCard[data-patient="'+PATIENT_JOURNAL_ID+'"]').click();
        $('#journalPatientCard').modal('handleUpdate');
        openPinnedTab();
    });

    $('.save-on-click').unbind('click');
    $('.save-on-click').click(function(e)
    {
        e.preventDefault();
        let THIS = $(this);
        let tab = THIS.data('saveonclick');
        PINNED_TAB = tab;
        // logger(PINNED_TAB, '.save-on-click > PINNED_TAB');
    });

    $('.btn-removeDispancer').unbind('click');
    $('.btn-removeDispancer').click(function(e)
    {
        if ( confirm('ВЫ УВЕРЕНЫ, ЧТО ХОТИТЕ УДАЛИТЬ ДИСПАНСЕРНЫЙ ДИАГНОЗ?') )
        {
            if ( confirm('ВЫ ТОЧНО УВЕРЕНЫ?') )
            {
                e.preventDefault();
                let THIS = $(this);
                let journal_id = THIS.data('journal');

                $.ajax({
                    url: '/processor/journal_removeDispancer',
                    data: {journal_id},
                    dataType: 'json',
                    type: 'post',
                    success: function (json) {
                        if (json) {
                            if (json.result === true) {
                                alert('Диспансерный диагноз успешно удалён!')
                                $('.openCard[data-patient="'+PATIENT_JOURNAL_ID+'"]').click();
                                $('#journalPatientCard').modal('handleUpdate');
                                openPinnedTab();
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

    $('.btn-addDispancer').unbind('click');
    $('.btn-addDispancer').click(function(e)
    {
        if ( confirm('Вы уверены, что хотите поставить пациента на диспансерный учет с этим диагнозом?') )
        {
            e.preventDefault();
            let THIS = $(this);
            let journal_id = THIS.data('journal');
            $.ajax({
                url: '/processor/journal_addDispancer',
                data: {journal_id},
                dataType: 'json',
                type: 'post',
                success: function (json) {
                    if (json) {
                        if (json.result === true) {
                            alert('Пациент успешно поставлен на диспансерный учёт!')
                            $('.openCard[data-patient="'+PATIENT_JOURNAL_ID+'"]').click();
                            $('#journalPatientCard').modal('handleUpdate');
                            openPinnedTab();
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

    $('.btn-printUziMany').unbind('click');
    $('.btn-printUziMany').click(function(e)
    {
        e.preventDefault();
        // let THIS = $(this);
        let print_checkboxes = $('.check-this-talon-for-print:checkbox:checked');
        let print_uzi_arr = [];
        if ( print_checkboxes.length > 0 )
        {
            // logger(print_uzi_arr, 'print_uzi_arr pre')
            print_checkboxes.each(function(){
                let THIS = $(this);
                let uzi_id = THIS.data('uziid');
                // logger(uzi_id, 'print_uzi_arr > uzi_id')
                print_uzi_arr.push(uzi_id);
            });
            // logger(print_uzi_arr, 'print_uzi_arr after')

            if (print_uzi_arr.length > 0)
            {
                // logger(print_uzi_arr, 'print_uzi_arr after length')
                $.ajax({
                    url: '/processor/sessionPrint',
                    data: {doctype: "uzicaop_talon", uzi_ids: print_uzi_arr},
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

            } else
            {
                alert('Нет талонов для распечатки!')
            }
        } else
        {
            alert('Для начала выберите талоны для распечатки!')
        }
    });
    
    // ДУБЛЁР ИЗ uziSchedule
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

    // ДУБЛЁР ИЗ uziSchedule
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
                        initModalFunctions()
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

    $('.btn-otherPatient').unbind('click');
    $('.btn-otherPatient').click(function(e)
    {
        e.preventDefault();
        let THIS = $(this);
        let DATES_ID = THIS.data('dateid')
        let TIME_ID = THIS.data('timeid')
        uziCAOPInfo(-1, DATES_ID, TIME_ID)

    });
    
    $('.btn-removeCitology').unbind('click');
    $('.btn-removeCitology').click(function(e)
    {
        e.preventDefault();
        let THIS = $(this);
        let CITOLOGY_ID = THIS.data('citologyid')
        if ( confirm('ВЫ ДЕЙСТВИТЕЛЬНО ХОТИТЕ УДАЛИТЬ ДАННУЮ ЦИТОЛОГИЮ?') )
        {
            $.ajax({
                url: '/processor/journalCitologyDelete',
                data: {patient_id: CITOLOGY_ID},
                dataType: 'json',
                type: 'post',
                success: function(json){
                    if(json){

                        if (json.result === true)
                        {
                            $('#journalEditCitology').modal('hide')
                            $('.openCard[data-patient="'+PATIENT_JOURNAL_ID+'"]').click();
                            $('#journalPatientCard').modal('handleUpdate');
                            openPinnedTab();
                        }
                    }
                }
            });
        }
    });

    $('.btn-removeResearch').unbind('click');
    $('.btn-removeResearch').click(function(e)
    {
        e.preventDefault();
        let THIS = $(this);
        let RESEARCH_ID = THIS.data('researchid')
        if ( confirm('ВЫ ДЕЙСТВИТЕЛЬНО ХОТИТЕ УДАЛИТЬ ДАННОЕ ОБСЛЕДОВАНИЕ?') )
        {
            $.ajax({
                url: '/processor/researchDelete',
                data: {patid: RESEARCH_ID},
                dataType: 'json',
                type: 'post',
                success: function (json) {
                    if (json) {
                        if (json.result === true) {
                            CITOLOGY_MODAL_ISOPENED = false;
                            $('#journalEditResearch').modal('hide')
                            $('.openCard[data-patient="'+PATIENT_JOURNAL_ID+'"]').click();
                            $('#journalPatientCard').modal('handleUpdate');
                            openPinnedTab();
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

    $('#form_newSPO').unbind('submit')
    $('#form_newSPO').on('submit', function (e)
    {
        e.preventDefault();
        e.stopPropagation();

        var $form = $(this);

        $.ajax({
            type: 'post',
            url: '/processor/newSPO',
            data: $form.serialize(),
            dataType: 'json',
        }).done(function(json) {
            if (json) {
                if (json.result === true)
                {
                    alert('СПО успешно добавлено!')

                    $('.openCardSPO[data-patient="'+json.journal_id+'"]').click();
                    $('#journalPatientCard').modal('handleUpdate');
                    openPinnedTab();
                } else
                {
                    alert(json.msg);
                }
            }
        }).fail(function() {
        });

    });

    $('.innerSPO-updateListSPO').unbind('click');
    $('.innerSPO-updateListSPO').click(function(e)
    {
        JOURNAL_CARD_MODAL_ISOPENED = true;
        e.preventDefault();
        var THIS = $(this);
        var JID = THIS.data('journal')
        $('.openCardSPO[data-patient="'+JID+'"]').click();
        $('#journalPatientCard').modal('handleUpdate');
        openPinnedTab();

    });

    $('.innerSPO-openCard').unbind('click');
    $('.innerSPO-openCard').click(function(e)
    {
        e.preventDefault();
        var THIS = $(this);
        var ID = THIS.data('journal')
        JOURNAL_CARD_MODAL_ISOPENED = true;
        var dat = {patient_id: ID};
        $.ajax({
            url: '/processor/journalCard',
            // contentType: false, // важно - убираем форматирование данных по умолчанию
            // processData: false, // важно - убираем преобразование строк по умолчанию
            data: dat,
            dataType: 'json',
            type: 'post',
            success: function (json) {
                if (json) {
                    if (json.result === true) {
                        PATIENT_DISPANSER_JOURNAL_ID = ID;
                        $('#visiteditor').html(json.htmlData);
                        MySQLEditorInit();
                        MaskedInput();
                    } else {
                        $('#visiteditor').html(json.msg);
                    }
                }
            }
        });
    });

    $('.innerSPO-changeSPO').unbind('click');
    $('.innerSPO-changeSPO').click(function(e)
    {
        e.preventDefault();
        var THIS = $(this);
        var SPO_ID = THIS.data("spo");
        var JID = THIS.data('journal')
        $.ajax({
            url: '/processor/setSPO',
            data: {spo_id: SPO_ID, journal_id: JID},
            dataType: 'json',
            type: 'post',
            success: function (json) {
                if (json) {
                    if (json.result === true) {
                        $('.openCardSPO[data-patient="'+JID+'"]').click();
                        $('#journalPatientCard').modal('handleUpdate');
                        openPinnedTab();
                    } else {

                    }
                }
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert(textStatus);
            }
        });
    });

    $('.editSPO').unbind('click');
    $('.editSPO').click(function(e)
    {
        e.preventDefault();
        var THIS = $(this);
        var SPO_DATA = THIS.data("spodata");
        SPO_JSON = atob(SPO_DATA);
        SPO = JSON.parse(SPO_JSON);
    });

    // $('.dirStacHideClick').unbind('click');
    // $('.dirStacHideClick').click(function(e)
    // {
    //     e.preventDefault();
    //     var THIS = $(this);
    //     var JID = THIS.data('lpudirjid');
    // });

    $('#choose_doctor_uzicaop').unbind('change');
    $('#choose_doctor_uzicaop').change(function(e)
    {
        e.preventDefault();
        let THIS = $(this);
        let DOCTOR_ID = THIS.val();
        let JOURNAL_ID = $('#copyshift_form').data('journalid');

        chooseDoctorGraphic(DOCTOR_ID, JOURNAL_ID, -1);
        setTimeout( function()
        {
            initModalFunctions();
        }, 500);
    });

    $('.btn-otherWeek').unbind('click');
    $('.btn-otherWeek').click(function(e)
    {
        e.preventDefault();
        let THIS = $(this);
        let UNIX = THIS.data('unix');
        let DOCTOR_ID = THIS.data('doctorid');
        let JOURNAL_ID = $('#copyshift_form').data('journalid');

        chooseDoctorGraphic(DOCTOR_ID, JOURNAL_ID, UNIX);

        setTimeout( function()
        {
            initModalFunctions();
        }, 500);
    });

    $('.btn-addNewRecord').unbind('click');
    $('.btn-addNewRecord').click(function(e)
    {
        e.preventDefault();
        let THIS = $(this);
        let form_data = formSerializer('#newUZIRecord_form');

        $.ajax({
            url: '/processor/journal_uzicaop_newrecord',
            data: form_data,
            dataType: 'json',
            type: 'post',
            success: function (json) {
                if (json) {
                    if (json.result === true) {
                        alert(json.msg);
                        // uziCaop_modal.modal('hide');
                        uziCaop_time_submodal.modal('hide');
                        let updateButton = $('#btn-updateAfterRecord');
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
                            let updateButton = $('#btn-updateAfterRecord');
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
                    initModalFunctions();
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

function chooseDoctorGraphic(DOCTOR_ID, JOURNAL_ID, UNIX)
{
    if (DOCTOR_ID > 0)
    {
        $.ajax({
            url: '/processor/journal_uzicaop_schedule',
            data: {doctor_id: DOCTOR_ID, journal_id: JOURNAL_ID, UNIX: UNIX},
            dataType: 'json',
            type: 'post',
            success: function (json) {
                if (json) {
                    if (json.result === true) {
                        $('#uzi-caop_result').html(json.htmlData);
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
        $('#uzi-caop_result').html('Сначала выберите врача...');
    }
}

function closeLPUPanel(jid)
{
    // $('#accordion_lpudir'+jid+'tables').collapse('hide');
    $("button[data-anchor='anchor_lpudir"+jid+"']").click();
}

function addInVisit(patid_id) {
    if ( patid_id > 0 )
    {
        $.ajax({
            url: '/processor/search4add_addVisit',
            data: {patid_id: patid_id},
            dataType: 'json',
            type: 'post',
            success: function (json) {
                if (json) {
                    if (json.result === true) {
                        //$('#search4add_result').html( json.htmlData );
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

function miasImport() {
    $('#miasImportWindow').modal('show');
}

function miasImport2() {
    $('#miasImportWindow2').modal('show');
}

function miasImportMain() {
    $('#miasImportWindowMain').modal('show');
}

function moveAll() {
    $('.move-labeler').prop('checked', true);
    moveLabeler();
}

function checkingAll(check_status=true)
{
    $('.move-labeler').prop('checked', check_status);
}

function moveDoctorAll() {
    $('.move-labeler').prop('checked', true);
    moveDoctorLabeler();
}

function moveLabeler() {
    var LABELER = $('.move-labeler');
    if (LABELER.length > 0) {
        var checkedLabels = [];
        LABELER.each(function () {
            var THIS = $(this);
            var CHECKED = THIS.is(':checked')
            if (CHECKED) {
                checkedLabels.push(THIS);
            }
        });

        if (checkedLabels.length > 0) {
            $('#movePatients').modal('show')
        } else {
            alert('Для переноса нужно выбрать хотя бы одного пациента');
        }

    } else {
        alert('В списке нет пациентов');
    }

}
function moveDoctorLabeler() {
    var LABELER = $('.move-labeler');
    if (LABELER.length > 0) {
        var checkedLabels = [];
        LABELER.each(function () {
            var THIS = $(this);
            var CHECKED = THIS.is(':checked')
            if (CHECKED) {
                checkedLabels.push(THIS);
            }
        });

        if (checkedLabels.length > 0) {
            $('#moveDocPatients').modal('show')
        } else {
            alert('Для переноса нужно выбрать хотя бы одного пациента');
        }

    } else {
        alert('В списке нет пациентов');
    }

}

function uziCAOP(journal_id)
{
    uziCaop_body.html(uziCaop_loader);
    uziCaop_modal.modal('show');

    $.ajax({
        url: '/processor/journal_uzicaop_graphic',
        data: {journal_id: journal_id},
        dataType: 'json',
        type: 'post',
        success: function (json) {
            if (json) {
                if (json.result === true) {
                    uziCaop_body.html(json.htmlData);
                    setTimeout( function()
                    {
                        initModalFunctions();
                    }, 500);

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


function deleteLabeler() {
    var LABELER = $('.move-labeler');
    if (LABELER.length > 0) {
        var checkedLabels = [];
        LABELER.each(function () {
            var THIS = $(this);
            var CHECKED = THIS.is(':checked')
            if (CHECKED) {
                checkedLabels.push(THIS);
            }
        });

        if (checkedLabels.length > 0) {
            if (confirm('Вы действительно желаете удалить отмеченных пациентов?')) {
                var checkLabels = moveLabelerReturn();
                var patient_ids = [];
                for (let i = 0; i < checkLabels.length; i++) {
                    var ITEM = checkLabels[i];
                    var PATID = ITEM.data('patid');
                    patient_ids.push(PATID);
                }
                var dat = {patientsList: patient_ids};
                $.ajax({
                    url: '/processor/deletePatients',
                    data: dat,
                    dataType: 'json',
                    type: 'post',
                    success: function (json) {
                        if (json) {
                            if (json.result === true) {
                                window.location.reload();
                            }
                        }
                    }
                });
            }
        } else {
            alert('Для удаления нужно выбрать хотя бы одного пациента');
        }

    } else {
        alert('В списке нет пациентов');
    }
}

function clearList() {
    if (confirm('ВЫ ДЕЙСТВИТЕЛЬНО ЖЕЛАЕТЕ ОЧИСТИТЬ ЖУРНАЛ?')) {
        $.ajax({
            url: "/processor/clear",
            type: "post",
            dataType: "json",
            // data: {unix: PATIENTS},
            error: function (jqXHR, textStatus, errorThrown) {
                // will fire when timeout is reached
                alert(textStatus);
            },
            success: function (json) {
                //do something
                if (json.result === true) {
                    window.location.href = "";
                }
            },
            timeout: 5000 // sets timeout to 3 seconds
        });
    }
}

function deleteDay(day) {
    if (confirm('ВЫ ДЕЙСТВИТЕЛЬНО ЖЕЛАЕТЕ ПОЛНОСТЬЮ УДАЛИТЬ ДЕНЬ?')) {
        $.ajax({
            url: "/processor/deleteDay",
            type: "post",
            dataType: "json",
            data: {day_id: day},
            error: function (jqXHR, textStatus, errorThrown) {
                // will fire when timeout is reached
                alert(textStatus);
            },
            success: function (json) {
                //do something
                if (json.result === true) {
                    alert('Журнал очищен, День приёма удалён.');
                    window.location.href = "/journalAlldays";
                }
            },
            timeout: 5000 // sets timeout to 3 seconds
        });
    }
}

function journalRemovePatient(patient_id) {
    if ( confirm('Вы действительно хотите удалить пациента из списка?\n\nВозможно, у него есть заполненные данные!') )
    {
        $.ajax({
            url: '/processor/remove',
            data: {patient: patient_id},
            dataType: 'json',
            type: 'post',
            success: function (json) {
                if (json) {
                    if (json.result === true) {
                        window.location.reload();
                    }
                }
            }
        });
    }
}

function journalResearch(patient_id) {
    $.ajax({
        url: '/processor/research',
        data: {patient: patient_id},
        dataType: 'json',
        type: 'post',
        success: function (json) {
            if (json) {
                if (json.result === true) {
                    // window.location.reload();
                    $('#journalCurrentMessagePlace').html(json.msg);
                    $('#buttonjournalCurrentDismiss').hide();
                    $('#buttonjournalCurrentNext').show();
                    $('#journalCurrentMessageBox').modal('show');
                }
            }
        }
    });
}

function journalSignature(day_id) {
    if (confirm("Вы действительно хотите подписать журнал?")) {

        if (confirm("Если Вы подпишете журнал, то больше не сможете менять данные пациента на этот день!!!")) {

            $.ajax({
                url: '/processor/journalSignature',
                data: {day_id: day_id},
                dataType: 'json',
                type: 'post',
                success: function (json) {
                    if (json) {
                        if (json.result === true) {
                            alert('ЖУРНАЛ УСПЕШНО ПОДПИСАН. Вы больше не сможете менять информацию в этом приёме!');
                            window.location.reload();
                        }
                    }
                }
            });

        }

    }
}



function journalCitology(patient_id) {
    $.ajax({
        url: '/processor/journalCitology',
        data: {patient_id: patient_id},
        dataType: 'json',
        type: 'post',
        success: function (json) {
            if (json) {
                if (json.result === true) {
                    alert('Пациент успешно записан на цитологическое исследование!');
                    // window.location.reload();
                }
            }
        }
    });
}

function CheckBlockchainHash() {
    $.ajax({
        url: '/processor/journalCheckBlockchain',
        // data: {patient: patient_id},
        dataType: 'json',
        type: 'post',
        success: function (json) {
            if (json) {
                                if (json.result === true) {
                    if (json.patientsServerHash === JSON_HASH) {
                    } else {
                        if (USE_JSON !== 'none')
                        {
                            $('#updateNotifier').show(1000);
                        }
                    }
                }
            }
        }
    });
}

function journalZNO(patient_id) {
    $.ajax({
        url: '/processor/journalZNO',
        data: {patient_id: patient_id},
        dataType: 'json',
        type: 'post',
        success: function (json) {
            if (json) {
                if (json.result === true) {
                    alert('Пациент добавлен в список со злокачественным новообразованием');
                    // window.location.reload();
                } else
                {
                    alert( json.msg );
                }
            }
        }
    });
}

function journalResearch2(patient_id, is_add=false) {
    $('#addPatientResearchModal').html(LOADER_HTML);
    $('#addPatientResearch').modal('show');
    $.ajax({
        url: '/processor/journalResearchNew',
        data: {patient_id, is_add},
        dataType: 'json',
        type: 'post',
        success: function (json) {
            if (json) {
                $('#addPatientResearchModal').html(json.htmlData);
                MaskedInput();
                PATIENT_JOURNAL_ID = patient_id;
                I_CAN_CLOSE_FORM = false;
            }
        }
    });
}

function journalCitology2(patient_id, is_add=false) {
    $('#addPatientCitologyModal').html(LOADER_HTML);
    $('#addPatientCitology').modal('show');
    $.ajax({
        url: '/processor/journalCitologyNew',
        data: {patient_id, is_add},
        dataType: 'json',
        type: 'post',
        success: function (json) {
            if (json) {
                $('#addPatientCitologyModal').html(json.htmlData);
                MaskedInput();
                PATIENT_JOURNAL_ID = patient_id;
                I_CAN_CLOSE_FORM = false;
            }
        }
    });
}

function setNurseToday(nurseId)
{
    //global TODAY_DAYID;
    $.ajax({
        url: '/processor/setNurseToday',
        data: {nurseId: nurseId, day_id: TODAY_DAYID},
        dataType: 'json',
        type: 'post',
        success: function (json)
        {
            if (json)
            {
                if (json.result === true)
                {
                    window.location.reload();
                }
            }
        }
    });
}
function openResearchCard(research_id) {
    $('#journalEditCitologyData').html(LOADER_HTML5);
    $('#journalEditResearch').modal('toggle');

    var dat = {research_id: research_id};
    $.ajax({
        url: '/processor/showResearchData',
        // contentType: false, // важно - убираем форматирование данных по умолчанию
        // processData: false, // важно - убираем преобразование строк по умолчанию
        data: dat,
        dataType: 'json',
        type: 'post',
        success: function (json) {
            if (json) {
                if (json.result === true) {
                    CITOLOGY_MODAL_ISOPENED = true;
                    $('#journalEditResearchData').html(json.htmlData);
                    MySQLEditorInit();
                    MaskedInput();
                    initModalFunctions()
                } else {
                    $('#journalEditResearchData').html(json.msg);
                }
            }
        }
    });
}
/*

function allVisits(patid_id, _hideCase = 0) {

    $('#visitsPatientCardData').html( LOADER_HTML );
    $('#visitsPatientCard').modal('show');

    $.ajax({
        url: '/processor/visitsPatient',
        // contentType: false, // важно - убираем форматирование данных по умолчанию
        // processData: false, // важно - убираем преобразование строк по умолчанию
        data: {patid_id: patid_id, hideCase: _hideCase},
        dataType: 'json',
        type: 'post',
        success: function(json){
            if(json){
                if (json.result === true)
                {
                    $('#visitsPatientCardData').html( json.htmlData );
                    MySQLEditorInit();
                    InitCaser();
                } else
                {
                    $('#visitsPatientData').html( json.msg );
                }
            }
        }
    });
}
*/

function createPatient()
{
    var FORM = $('#search4add_add');
    var FORM_DATA = FORM.serializeArray();

    $.ajax({
        url: '/processor/search4add_add',
        data: FORM_DATA,
        dataType: 'json',
        type: 'post',
        success: function(json){
            if(json){
                if (json.result === true)
                {
                    alert(json.msg);
                    window.location.reload();
                } else
                {
                    alert(json.msg);
                    if ( json.ident )
                    {
                        $('#searchString').val(json.ident);
                        setTimeout(function (e)
                        {
                            $('#search4add_button').click();
                        }, 200)
                    }
                }
            }
        }
    });
}

function AddQuickResearch(patient_id)
{
    // $('#journalPatientCard').modal('hide');
    journalResearch2(patient_id);
}

function AddQuickCitology(patient_id)
{
    journalCitology2(patient_id, false);
}

function isReportedClickInit()
{
    // $('.isReportedClick').change(function () {
    //     var THIS = $(this);
    // });
}

function isReportedClicked(journal_id)
{
    $('#disp_'+journal_id + '_spoiler').collapse('toggle');
}

function toggleDispancerStatus(journal_id)
{
    // var MKB_DISP = $('#journal_disp_mkb').val();
    // var MKB_MAIN = $('#journal_ds').val();
    $.ajax({
        url: '/processor/checkDispancerMKB',
        data: {"journal_id": journal_id},
        dataType: 'json',
        type: 'post',
        success: function(json){
            if(json){
                if (json.result === true)
                {
                    if ( json.notify.length > 0 )
                    {
                        $('#dispancer_status_dom').show();
                        $('#dispancer_status').html( json.notify );
                    } else
                    {
                        $('#dispancer_status_dom').hide();
                    }
                    
                } else
                {
                    alert(json.msg);
                }
            }
        }
    });
}

function submit_addPatientResearch(e)
{
    $('#addNewPatientResearch').click();
    e.preventDefault();
}
function addPatientCitology(e)
{
    $('#addNewPatientCitology').click();
    e.preventDefault();
}

function suspicio(journal_id)
{
    $('#suspicio').modal('toggle');
    $.ajax({
        url: '/processor/suspicio',
        data: {"journal_id": journal_id},
        dataType: 'json',
        type: 'post',
        success: function(json){
            if(json){
                if (json.result === true) {

                    $('#suspicio_body').html(json.htmlData);
                    MySQLEditorInit();
                    MaskedInput();
                } else {
                    $('#suspicio_body').html(json.msg);
                }
            }
        }
    });
}

function journal_fastMove(journal_id)
{
    $('#journalFastMove_body').html(LOADER_HTML6);
    $('#journalFastMove').modal('toggle');

    var dat = {journal_id: journal_id};
    $.ajax({
        url: '/processor/journal_calendarFastMove',
        // contentType: false, // важно - убираем форматирование данных по умолчанию
        // processData: false, // важно - убираем преобразование строк по умолчанию
        data: dat,
        dataType: 'json',
        type: 'post',
        success: function (json) {
            if (json) {
                if (json.result === true) {
                    $('#journalFastMove_body').html(json.htmlData);
                    MySQLEditorInit();
                    MaskedInput();
                    reInitVerif();
                } else {
                    $('#journalFastMove_body').html(json.msg);
                }
            }
        }
    });
}

function openPinnedTab()
{
    setTimeout(function ()
    {
        let pinned_tab = $('*[data-saveonclick="'+PINNED_TAB+'"]');
        if ( pinned_tab.length === 1 )
        {
            let tab_link = $('*[data-saveonclick="'+PINNED_TAB+'"] a');
            if ( tab_link.length === 1 )
            {
                tab_link.click();
            }
        }
    }, 500);

}

function journalSPO_addNew(journal_id)
{
    $.ajax({
        url: '/processor/journal_SPO_addNew',
        data: {journal_id},
        dataType: 'json',
        type: 'post',
        success: function (json) {
            if (json) {
                if (json.result === true) {
                    alert('СПО успешно создано!')
                    $('.openCard[data-patient="'+PATIENT_JOURNAL_ID+'"]').click();
                    $('#journalPatientCard').modal('handleUpdate');
                    openPinnedTab();
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

function spo_loader(spo_id, journal_id)
{
    let spo_result = $('#spo_result');
    if ( spo_result.length > 0 )
    {
        if (parseInt(spo_id) === 0)
        {
            spo_result.html('Выберите СПО...')
        } else
        {
            $.ajax({
                url: '/processor/journal_SPO_getForm',
                data: {spo_id, journal_id},
                dataType: 'json',
                type: 'post',
                success: function (json) {
                    if (json) {
                        if (json.result === true) {
                            spo_result.html(json.htmlData);
                            MySQLEditorInit();
                            MaskedInput();
                        } else {
                            spo_result.html(json.msg);
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

}

function removeSPO(spo_id)
{
    if ( confirm('ВЫ УВЕРЕНЫ, ЧТО ХОТИТЕ УДАЛИТЬ ДАННОЕ СПО?') )
    {
        if ( confirm('ЕСЛИ УДАЛИТЕ СПО, ВИЗИТЫ, КОТОРЫЕ К НЕМУ ПРИВЯЗАНЫ, ПОТЕРЯЮТ ПРИВЯЗКУ. ПРОДОЛЖИТЬ?') )
        {
            if ( confirm('ЭТО ДЕЙСТВИЕ НЕОБРАТИМО! ПРОДОЛЖИТЬ?') )
            {
                $.ajax({
                    url: '/processor/journal_SPO_remove',
                    data: {spo_id},
                    dataType: 'json',
                    type: 'post',
                    success: function (json) {
                        if (json) {
                            if (json.result === true) {
                                alert('СПО успешно удалено!')
                                $('.openCard[data-patient="'+PATIENT_JOURNAL_ID+'"]').click();
                                $('#journalPatientCard').modal('handleUpdate');
                                openPinnedTab();
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
    }
}

function setAccountDiagnosis(journal_id)
{
    if ( confirm('Вы уверены, что хотите поставить на Д-учет по данному диагнозу?') )
    {
        $.ajax({
            url: '/processor/journal_SPO_set',
            data: {journal_id},
            dataType: 'json',
            type: 'post',
            success: function (json) {
                if (json) {
                    if (json.result === true) {
                        alert(json.msg);
                        $('.openCard[data-patient="'+PATIENT_JOURNAL_ID+'"]').click();
                        $('#journalPatientCard').modal('handleUpdate');
                        openPinnedTab();
                    } else {
                        alert(json.msg);
                        redirectOnTab(json.tab, json.focus)
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

function redirectOnTab(_tab, _focus)
{
    let click_tab = $('*[data-saveonclick="'+_tab+'"] a');
    if ( click_tab.length > 0 )
    {
        // logger(click_tab, 'click_tab');
        click_tab.click();
        setTimeout(function ()
        {
            let field = $('#' + _focus);
            if ( field.length > 0 )
            {
                field.focus();
            }
        }, 500);
    }
}

$(document).on('hidden.bs.modal', '.submodal', function (event) {
    $('.modal').css('overflow-y', 'auto');
});
// $(document).on('hidden.bs.modal', '.submodal', function (event) {
//     if($('.modal.in').length > 0)
//     {
//         $('body').addClass('modal-open');
//     }
// });
//
// $(document).on('hidden.bs.modal', '.submodal', function (event) {
//     $('#journalPatientCard').modal({
//         focus: this
//     });
// });