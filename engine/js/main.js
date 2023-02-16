/*Array.prototype.forEach.call(document.body.querySelectorAll("*[data-mask]"), applyDataMask);

function applyDataMask(field) {
    var mask = field.dataset.mask.split('');

    // For now, this just strips everything that's not a number
    function stripMask(maskedData) {
        function isDigit(char) {
            return /\d/.test(char);
        }
        return maskedData.split('').filter(isDigit);
    }

    // Replace `_` characters with characters from `data`
    function applyMask(data) {
        return mask.map(function(char) {
            if (char != '_') return char;
            if (data.length == 0) return char;
            return data.shift();
        }).join('')
    }

    function reapplyMask(data) {
        return applyMask(stripMask(data));
    }

    function changed() {
        var oldStart = field.selectionStart;
        var oldEnd = field.selectionEnd;

        field.value = reapplyMask(field.value);

        field.selectionStart = oldStart;
        field.selectionEnd = oldEnd;
    }

    field.addEventListener('click', changed)
    field.addEventListener('keyup', changed)
}*/

let DOMAIN = 'apk7caop.ru';

var TIMER_REQUIRED = 1;
var TIME_DISAUTOCOMPLETE = 1;
var CARD_LOADER = 1;
var LOADER_HTML_openCitology = '';
var LOADER_HTML_openPatterns = '';
var LOADER_HTML = '';

let CITOLOGY_MODAL_ISOPENED = -1;

let uziCaop_modal;
let uziCaop_loader;
let uziCaop_body;

let uziCaop_time_submodal;
let uziCaop_time_loader;
let uziCaop_time_body;

let uziCaop_list_submodal;
let uziCaop_list_loader;
let uziCaop_list_body;

let window_spo_modal;
let window_spo_loader;
let window_spo_body;

let mysqleditor_filters;
let mysqleditor_filters_loader;
let mysqleditor_filters_body;

$(document).ready(function (e) {

    /*window.onbeforeunload = function (e) {
        e = e || window.event;
        // For IE and Firefox prior to version 4
        if (e) {
            e.returnValue = "Sure?";
        }
        // For Safari
        return "Sure?";
    };*/

    window_spo_modal = $('#spo_viewer');
    window_spo_body = $('#spo_viewer_body');
    window_spo_loader = window_spo_body.html();

    CARD_LOADER = $('#editPersonalDataCardBody').html();
    LOADER_HTML_openCitology = $('#journalEditCitologyData').html();
    LOADER_HTML_openPatterns = $('#patternsList_result').html();
    LOADER_HTML = $('#visitsPatientCardData').html();

    uziCaop_modal = $('#uzi-caop');
    uziCaop_body = $('#uzi-caop_body');
    uziCaop_loader = uziCaop_body.html();

    uziCaop_time_submodal = $('#uzi-caop-time-submodal');
    uziCaop_time_body = $('#uzi-caop-time-submodal_body');
    uziCaop_time_loader = uziCaop_time_body.html();

    uziCaop_list_submodal = $('#uzi-caop-list-submodal');
    uziCaop_list_body = $('#uzi-caop-list-submodal_body');
    uziCaop_list_loader = uziCaop_list_body.html();

    mysqleditor_filters = $('#mysqleditor-filters');
    mysqleditor_filters_body = $('#mysqleditor-filters_body');
    mysqleditor_filters_loader = mysqleditor_filters_body.html();

    // $('[data-toggle="tooltip"]').tooltip();

    MaskedInput();

    $(".img-zoom").on("click", function () {
        var THIS = $(this);
        // var SRC = THIS.attr('src');
        $('#imageZoomPreview').attr('src', THIS.attr('src'));
        $('#imageZoomModal').modal('show');

    });

    var defaults = {
        containerID: 'toTop', // fading element id
        containerHoverID: 'toTopHover', // fading element hover id
        scrollSpeed: 1200,
        easingType: 'linear'
    };

    $().UItoTop({easingType: 'easeOutQuart'});

    $('.scroller-button').click(function () {
        var THIS = $(this);
        var ANCHOR = THIS.data('anchor');
        scrollToAnchor(ANCHOR);
    });

    TIMER_REQUIRED = setInterval(function () {
        var FIELDS = $('.required-field');
        // console.log(FIELDS)
        for (let i = 0; i < FIELDS.length; i++) {
            var FIELD = $(FIELDS[i]);
            if (FIELD.val().length === 0) {
                FIELD.css('border', 'solid 2px #ff0000');
            } else {
                let NODE = FIELD.get(0).tagName;
                if ( NODE === "SELECT") {
                    if ( FIELD.val() == -1 )
                    {
                        FIELD.css('border', 'solid 2px #ff0000');
                    } else
                    {
                        FIELD.css('border', 'solid 1px #ced4da');
                    }
                } else
                {
                    FIELD.css('border', 'solid 1px #ced4da');
                }

            }
        }

        autosize($('textarea.autosizer'));
        $('[data-toggle="tooltip"]').tooltip();
        //
        // $('textarea.autosizer').click(function () {
        //     var cont = $(this).html();
        //     $(this).html('');
        //     $(this).html(cont);
        // })
    }, 500);

    TIME_DISAUTOCOMPLETE = setInterval(function () {
        DisAutoComplete();
    }, 500)

    $('.clickForCopy').click(function (e) {
        var THIS = $(this);
        var TARGET = THIS.data('target');
    });


    $('#editPersonalDataCard').on('hidden.bs.modal', function (e) {
        // do something...

    })

    // var tableOffset = $("#header-fixed").offset().top;
    // var $header = $("#header-fixed > thead").clone();
    // var $fixedHeader = $("#header-fixed").append($header);
    //
    // $(window).bind("scroll", function() {
    //     var offset = $(this).scrollTop();
    //
    //     if (offset >= tableOffset && $fixedHeader.is(":hidden")) {
    //         $fixedHeader.show();
    //     }
    //     else if (offset < tableOffset) {
    //         $fixedHeader.hide();
    //     }
    // });

    setTimeout(function () {
        $(".searchByTableField").attr('disabled', false);
        $(".searchByTableField").keyup(function () {

            let THIS = $(this);
            let _this = this;
            let classname = THIS.data('classname');
            let tablename = THIS.data('tablename');

            $.each($("." + tablename + " tbody tr ." + classname), function () {
                if ($(this).text().toLowerCase().indexOf($(_this).val().toLowerCase()) === -1) {
                    $(this).parent().hide();
                } else {
                    $(this).parent().show();
                }
            });
        });
    }, 2000);

    InitCheckboxCheckAll();

}); // dred

function DisAutoComplete() {
    var ALL_INPUTS = $('input').not('.indiraaccessable');

    for (let i = 0; i < ALL_INPUTS.length; i++) {
        var CURRENT_INPUT = $(ALL_INPUTS[i]);
        CURRENT_INPUT.attr("autocomplete", "off");
    }
}

function MaskedInput() {
    $(".russianPhone").mask("9-999-999-99-99");
    $(".snils").mask("999-999-999-99");
    $(".russianBirth").mask("99.99.9999");
    $(".russianBirthDayMonth").mask("99.99");
    $(".russianTime").mask("99:99");
    $(".cartridge-ident").mask("99999")

    // var regexp = /шаблон/;
    // $('.mkbDiagnosis').inputmask({regex: "[A-Z]{1}[0-9]*[.]{1}[0-9]*"});
    $('.mkbDiagnosis').keyup(function (key) {

        var THIS = $(this);
        var TXT = THIS.val();
        TXT = Auto(TXT);
        TXT = TXT.toUpperCase();
        TXT = TXT.replace(',', '.');
        THIS.val(TXT);
    })
}

function Auto(str) {
    var replace = new Array(
        "й", "ц", "у", "к", "е", "н", "г", "ш", "щ", "з", "х", "ъ",
        "ф", "ы", "в", "а", "п", "р", "о", "л", "д", "ж", "э",
        "я", "ч", "с", "м", "и", "т", "ь", "б", "ю"
    );
    var search = new Array(
        "q", "w", "e", "r", "t", "y", "u", "i", "o", "p", "\\[", "\\]",
        "a", "s", "d", "f", "g", "h", "j", "k", "l", ";", "'",
        "z", "x", "c", "v", "b", "n", "m", ",", "\\."
    );

    for (var i = 0; i < replace.length; i++) {
        var reg = new RegExp(replace[i], 'mig');
        str = str.replace(reg, function (a) {
            return a == a.toLowerCase() ? search[i] : search[i].toUpperCase();
        })
    }
    return str
}

function moveLabelerReturn(selector = '.move-labeler') {
    var LABELER = $(selector);
    if (LABELER.length > 0) {
        var checkedLabels = [];
        LABELER.each(function () {
            var THIS = $(this);
            var CHECKED = THIS.is(':checked')
            if (CHECKED) {
                checkedLabels.push(THIS);
            }
        });

        return checkedLabels;
    }
}

function logger(msg, varname = '') {
    if (varname === '') varname = 'VARIABLE';
    console.log('============================== <' + varname + '> ==============================')
    console.log(msg);
    console.log('============================== </' + varname + '> ==============================')
}

function scrollToAnchor(selector) {
    var aTag = $("a[name='" + selector + "']");
    $('html,body').animate({scrollTop: aTag.offset().top}, 'slow');
}

function bt_notice(msg, typemsg = 'info') {
    return '<div class="alert alert-' + typemsg + ' p-2" role="alert">' + msg + '</div>';
}

function editPersonalData(patid_str) {
    $('#editPersonalDataCardBody').html(CARD_LOADER);
    $('#editPersonalDataCard').modal('show');

    if (patid_str.length > 0) {
        $.ajax({
            url: '/processor/personalDataCheck',
            data: {patid_id: patid_str},
            dataType: 'json',
            type: 'post',
            success: function (json) {
                if (json) {
                    if (json.result === true) {
                        $('#editPersonalDataCardBody').html(json.htmlData);
                        MySQLEditorInit();
                        MaskedInput();
                    } else {
                    }
                }
            }
        });
    }
}

function copyToClipboard(elem) {
    // create hidden text element, if it doesn't already exist
    var targetId = "_hiddenCopyText_";
    var isInput = elem.tagName === "INPUT" || elem.tagName === "TEXTAREA";
    var origSelectionStart, origSelectionEnd;
    if (isInput) {
        // can just use the original source element for the selection and copy
        target = elem;
        origSelectionStart = elem.selectionStart;
        origSelectionEnd = elem.selectionEnd;
    } else {
        // must use a temporary form element for the selection and copy
        target = document.getElementById(targetId);
        if (!target) {
            var target = document.createElement("textarea");
            target.style.position = "absolute";
            target.style.left = "-9999px";
            target.style.top = "0";
            target.id = targetId;
            document.body.appendChild(target);
        }
        target.textContent = elem.textContent;
    }
    // select the content
    var currentFocus = document.activeElement;
    target.focus();
    target.setSelectionRange(0, target.value.length);

    // copy the selection
    var succeed;
    try {
        succeed = document.execCommand("copy");
    } catch (e) {
        succeed = false;
    }
    // restore original focus
    if (currentFocus && typeof currentFocus.focus === "function") {
        currentFocus.focus();
    }

    if (isInput) {
        // restore prior selection
        elem.setSelectionRange(origSelectionStart, origSelectionEnd);
    } else {
        // clear temporary content
        target.textContent = "";
    }
    return succeed;
}

function cancerMorph(diag_id, morph) {
    $.ajax({
        url: '/processor/cancerMorph',
        // contentType: false, // важно - убираем форматирование данных по умолчанию
        // processData: false, // важно - убираем преобразование строк по умолчанию
        data: {diag_id: diag_id, morph: morph},
        dataType: 'json',
        type: 'post',
        success: function (json) {
            if (json) {

                if (json.result === true) {
                    alert('Диагноз обновлён')
                } else {

                }
            }
        }
    });
}

function cancerRemove(diag_id) {
    if (confirm('ВЫ ДЕЙСТВИТЕЛЬНО ХОТИТЕ УДАЛИТЬ ДИАГНОЗ?')) {

        $.ajax({
            url: '/processor/cancerRemove',
            // contentType: false, // важно - убираем форматирование данных по умолчанию
            // processData: false, // важно - убираем преобразование строк по умолчанию
            data: {diag_id: diag_id},
            dataType: 'json',
            type: 'post',
            success: function (json) {
                if (json) {

                    if (json.result === true) {
                        alert('Диагноз удалён');
                        $('#editPersonalDataCard').modal('hide');
                    } else {

                    }
                }
            }
        });

    }
}

function openCitologyCard(citology_id) {

    $('#journalEditCitologyData').html(LOADER_HTML_openCitology);
    $('#journalEditCitology').modal('toggle');

    var dat = {citology_id: citology_id};
    $.ajax({
        url: '/processor/showCitologyData',
        // contentType: false, // важно - убираем форматирование данных по умолчанию
        // processData: false, // важно - убираем преобразование строк по умолчанию
        data: dat,
        dataType: 'json',
        type: 'post',
        success: function (json) {
            if (json) {

                if (json.result === true) {
                    CITOLOGY_MODAL_ISOPENED = true;
                    $('#journalEditCitologyData').html(json.htmlData);
                    MySQLEditorInit();
                    MaskedInput();
                    if (typeof initModalFunctions === "function") {
                        // safe to use the function
                        initModalFunctions();
                    }

                } else {
                    $('#journalEditCitologyData').html(json.msg);
                }
            }
        }
    });
}


function reInitVerif() {


    $('.patientDiagVerif').unbind('click');
    $('.patientDiagVerif').click(function () {
        var THIS = $(this);
        var DIAGID = THIS.data('diagid');
        var VEFIR = THIS.data('morphverif');
    });

    $('#patientDiag').unbind('click');
    $('#patientDiag').click(function () {
        var THIS = $(this);
        var DIAGID = THIS.data('diagid');
    });

    $('.clickForCopy').unbind('click');
    $('.clickForCopy').click(function (e) {
        var THIS = $(this);
        var OLD_THIS = THIS.html();
        var TARGET = THIS.data('target');
        var COPIED = copyToClipboard(document.getElementById(TARGET));
        if (COPIED) {
            THIS.html('ОК!');
            setTimeout(function () {
                THIS.html(OLD_THIS);
            }, 2000);
        }

    });
    $('#moveItFast').unbind('click');
    $('#moveItFast').click(function () {
        var THIS = $(this);
        var journal_id = THIS.data('journalid');
        // var patient_id = THIS.data('patientid');
        var DATEPICKER = $('#fastMove');
        var DATER = DATEPICKER.val();
        if (DATER.length !== 10) {
            alert('Выберите дату для переноса');
        } else {
            var patient_ids = [];
            patient_ids.push(journal_id);
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

    $('#cancelFastMove').unbind('click');
    $('#cancelFastMove').click(function (e) {
        e.preventDefault();
        var THIS = $(this);
        var DATEPICKER = $('#fastMove');
        DATEPICKER.val('');
        DATEPICKER.trigger('change');
        $('#journalFastMove').modal('toggle');
    });
}

$(document).on('show.bs.modal', '.modal', function (event) {
    var zIndex = 1040 + (10 * $('.modal:visible').length);
    $(this).css('z-index', zIndex);
    setTimeout(function () {
        $('.modal-backdrop').not('.modal-stack').css('z-index', zIndex - 1).addClass('modal-stack');
    }, 0);
});

$(document).on('shown.bs.modal', '.modal', function (event) {
    reInitVerif();
});

$(document).on('shown.bs.collapse', '.accordion-spoiler', function (event) {
    if (typeof initDynamicFunctions2 == 'function') {
        initDynamicFunctions2();
    }
});

cursor_wait = function () {
    // switch to cursor wait for the current element over
    var elements = $(':hover');
    if (elements.length) {
        // get the last element which is the one on top
        elements.last().addClass('cursor-wait');
    }
    // use .off() and a unique event name to avoid duplicates
    $('html').off('mouseover.cursorwait').on('mouseover.cursorwait', function (e) {
        // switch to cursor wait for all elements you'll be over
        $(e.target).addClass('cursor-wait');
    });
}

remove_cursor_wait = function () {
    $('html').off('mouseover.cursorwait'); // remove event handler
    $('.cursor-wait').removeClass('cursor-wait'); // get back to default
}

function InitCaser() {
    $('.setPatientCase').click(function () {
        var THIS = $(this);
        var CASEID = THIS.data('caseid');
        var CASE = THIS.data('case');
        var PATID = THIS.data('patid');
        $.ajax({
            url: '/processor/visitsPatientCase',
            // contentType: false, // важно - убираем форматирование данных по умолчанию
            // processData: false, // важно - убираем преобразование строк по умолчанию
            data: {patid: PATID, caseid: CASEID},
            dataType: 'json',
            type: 'post',
            success: function (json) {
                if (json) {
                    if (json.result === true) {
                        $('#visitsPatientCard').modal('hide');
                        var TR = $('#tr_patid_' + CASE + '_' + PATID);
                        //var TR = $('#tr_patid_casestatus'+CASEID+'_' + PATID);
                        TR.addClass('table-dark');
                    } else {

                    }
                }
            }
        });
    })
}

function allVisits(patid_id, _hideCase = 0) {

    $('#visitsPatientCardData').html(LOADER_HTML);
    $('#visitsPatientCard').modal('show');

    $.ajax({
        url: '/processor/visitsPatient',
        // contentType: false, // важно - убираем форматирование данных по умолчанию
        // processData: false, // важно - убираем преобразование строк по умолчанию
        data: {patid_id: patid_id, hideCase: _hideCase},
        dataType: 'json',
        type: 'post',
        success: function (json) {
            if (json) {
                if (json.result === true) {
                    $('#visitsPatientCardData').html(json.htmlData);
                    MySQLEditorInit();
                    InitCaser();
                } else {
                    $('#visitsPatientData').html(json.msg);
                }
            }
        }
    });
}

function getSerializeArrayFromNotAForm(selector) {
    let htmlObject = $(selector).find(':input');
    let result = [];
    if (htmlObject.length > 0) {
        htmlObject.each(function (index) {
            let obj = {};
            let htmlItem = $(this);
            let tag = htmlItem.prop('tagName');
            if (tag === "INPUT") {
                let inputName = htmlItem.attr('name');
                let inputValue = htmlItem.val();
                obj.name = inputName;
                obj.value = inputValue;
                result.push(obj);
            }

        });
        return result;
    }
    return false;
}

function openPatterns() {

    $('#patternsList_result').html(LOADER_HTML_openPatterns);
    $('#patternsList').modal('show');

    $.ajax({
        url: '/processor/pattern_list',
        dataType: 'json',
        type: 'post',
        success: function (json) {
            if (json) {
                if (json.result === true) {
                    $('#patternsList_result').html(json.htmlData);
                    MySQLEditorInit();
                    InitCaser();
                } else {
                    $('#patternsList_result').html(json.msg);
                }
            }
        }
    });
}

function uzicaoplist(patient_id) {

    uziCaop_list_body.html(uziCaop_list_loader);
    uziCaop_list_submodal.modal('show');

    $.ajax({
        url: '/processor/scheduleUzi_list',
        data: {patient_id},
        dataType: 'json',
        type: 'post',
        success: function (json) {
            if (json) {
                if (json.result === true) {
                    // console.log('here')
                    uziCaop_list_body.html(json.htmlData);
                    MySQLEditorInit();
                    initModalFunctions();
                } else {
                    alert(json.msg);
                }
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert(textStatus);
        }
    });
}

/*(function($){
    $.fn.serializeObject = function(){

        var self = this,
            json = {},
            push_counters = {},
            patterns = {
                "validate": /^[a-zA-Z][a-zA-Z0-9_]*(?:\[(?:\d*|[a-zA-Z0-9_]+)\])*$/,
                "key":      /[a-zA-Z0-9_]+|(?=\[\])/g,
                "push":     /^$/,
                "fixed":    /^\d+$/,
                "named":    /^[a-zA-Z0-9_]+$/
            };


        this.build = function(base, key, value){
            base[key] = value;
            return base;
        };

        this.push_counter = function(key){
            if(push_counters[key] === undefined){
                push_counters[key] = 0;
            }
            return push_counters[key]++;
        };

        $.each($(this).serializeArray(), function(){

            // Skip invalid keys
            if(!patterns.validate.test(this.name)){
                return;
            }

            var k,
                keys = this.name.match(patterns.key),
                merge = this.value,
                reverse_key = this.name;

            while((k = keys.pop()) !== undefined){

                // Adjust reverse_key
                reverse_key = reverse_key.replace(new RegExp("\\[" + k + "\\]$"), '');

                // Push
                if(k.match(patterns.push)){
                    merge = self.build([], self.push_counter(reverse_key), merge);
                }

                // Fixed
                else if(k.match(patterns.fixed)){
                    merge = self.build([], k, merge);
                }

                // Named
                else if(k.match(patterns.named)){
                    merge = self.build({}, k, merge);
                }
            }



            json = $.extend(true, json, merge);
        });

        return json;
    };
})(jQuery);*/

function formSerializer(selector) {

    let is_block = false

    return $(selector).serializeArray().reduce(function (obj, item) {

        console.log('is_block', is_block)

        if ( (item.name.indexOf('[') > 0) && (item.name.indexOf(']') > 0) )
        {
            // variable-array
            if ( item.value )
            {
                if ( !obj[item.name] ) obj[item.name] = [];
                obj[item.name].push(item.value)
            }

        } else
        {
            if ( item.value )
                obj[item.name] = item.value;
        }

        let elem = $('#' + item.name);
        console.log(elem)
        if (elem.length)
        {
            let req = elem.prop('required')
            if (req !== undefined)
            {
                if (req)
                {
                    if (!item.value.length)
                    {
                        is_block = true;
                    }
                }
            }
        }

        if (is_block)
            return false
        else
            return obj;
    }, {});
}

function checkboxCollector(selector) {
    let checkbox_dom = $(selector);
    // console.log(checkbox_dom);
    if (checkbox_dom.length > 0) {
        let checkboxes = [];
        checkbox_dom.each(function () {
            let THIS = $(this);
            let is_checked = THIS.is(':checked')
            if (is_checked) {
                checkboxes.push(THIS.val());
            }
        });

        if (checkbox_dom.length > 0) {
            return checkboxes;
        } else {
            console.log('Список выбранного пуст')
            return false;
        }

    } else {
        console.log('Такого элемента не существует')
        return false
    }
}

function InitCheckboxCheckAll() {
    let cbca = $('.checkbox-checkall');
    if (cbca.length > 0) {
        cbca.click(function () {
            let THIS = $(this);
            let target = THIS.data('target');
            $(target + ':checkbox').not(this).prop('checked', this.checked);
        })
    }
}

function showSPO(spo_id, patient_id)
{
    window_spo_body.html( window_spo_loader );
    window_spo_modal.modal('show');

    $.ajax({
        url: '/processor/journal_SPO_view',
        data: {spo_id, patient_id},
        dataType: 'json',
        type: 'post',
        success: function (json) {
            if (json) {
                if (json.result === true) {
                    window_spo_body.html(json.htmlData)
                } else {
                    window_spo_body.html(json.msg);
                }
            }
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert(textStatus);
        }
    });
}

function setCardPlace(title, journal_id)
{
    // console.log(title);
    // console.log(journal_id);
    let input_place_dom = $('#journal_cardplace_' + journal_id);
    if ( input_place_dom.length > 0 )
    {
        input_place_dom.val(title)
        input_place_dom.trigger("change");

        let btn_close = $('.close');
        console.log(btn_close);

        let visit_patient_card = $('#visitsPatientCard');
        visit_patient_card.modal('toggle')
    }
}

function addFirstVisitAction(visit_id)
{
    $.ajax({
        url: '/processor/homeVisitFirstAction',
        data: {visit_id},
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

//
// $.fn.enterKey = function (fnc) {
//     return this.each(function () {
//         $(this).keypress(function (ev) {
//             var keycode = (ev.keyCode ? ev.keyCode : ev.which);
//             if (keycode === '13') {
//                 fnc.call(this, ev);
//             }
//         })
//     })
// }