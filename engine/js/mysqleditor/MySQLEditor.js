var FIELD_NAME = 'mysqleditor';
var LOADER_FIELD_PREFIX = 'fieldloader';
var PHP_WORKER = '/processor/mysqleditor';

$(document).ready(function(){

    MySQLEditorInit();

});

function MySQLEditorInit() {

    $("." + FIELD_NAME).unbind('change');
    $("." + FIELD_NAME + '-realtime').unbind('dblclick');

    $("." + FIELD_NAME + '-realtime').dblclick(function(e) {
        e.preventDefault();
        e.stopPropagation();

        var INPUT = $(this);

        var MYSQLEDITOR_INPUT = '<input type="[INPUTTYPE]" class="form-control form-control-sm mysqleditor mysqleditor-getback [ADDONCLASS]" [ADDININPUT] data-action="[ACTION]" data-table="[TABLE]" data-assoc="0" data-fieldid="[FIELDID]" data-id="[ID]" data-field="[FIELD]" data-preset="[PRESET]" data-return="[RETURN]" data-returntype="[RETURNTYPE]" data-callbackfunc="[CALLBACKFUNC]" data-callbackparams="[CALLBACKPARAMS]" data-callbackcond="[CALLBACKCOND]" data-updatedefaultvalue="[UPDATEDEFAULTVALUE]" data-updatedefaultvaluedom="[UPDATEDEFAULTVALUEDOM]" value="[VALUE]">';
        var RT_ACTION = INPUT.data('rtaction');
        var RT_TABLE = INPUT.data('rttable');
        var RT_FIELDID = INPUT.data('rtfieldid');
        var RT_ID = INPUT.data('rtid');
        var RT_FIELD = INPUT.data('rtfield');
        var RT_PRESET = INPUT.data('rtpreset');
        var RT_RETURN = INPUT.data('rtreturn');
        var RT_RETURNTYPE = INPUT.data('rtreturntype');
        var RT_VALUE = INPUT.data('defaultvalue');
        var RT_ADDONCLASS = INPUT.data('rtaddonclass');
        var RT_CALLBACKFUNC = INPUT.data('rtcallbackfunc');
        var RT_CALLBACKPARAMS = INPUT.data('rtcallbackparams');
        var RT_CALLBACKCOND = INPUT.data('rtcallbackcond');
        var RT_UPDATEDEFAULTVALUE = INPUT.data('rtupdatedefaultvalue');
        var RT_UPDATEDEFAULTVALUEDOM = INPUT.data('rtupdatedefaultvaluedom');
        var RT_ADDININPUT = INPUT.data('addininput');
        var RT_INPUTTYPE = INPUT.data('inputtype');
        var RT_SEPARATOR = INPUT.data('separator');

        if ( typeof RT_INPUTTYPE === "undefined" || RT_INPUTTYPE === "undefined")
        {
            RT_INPUTTYPE = "text"
        } else
        {
            if ( RT_INPUTTYPE === "text" || RT_INPUTTYPE === "date" )
            {

            } else RT_INPUTTYPE = "text";
        }

        MYSQLEDITOR_INPUT = MYSQLEDITOR_INPUT.replace('[ACTION]', RT_ACTION);
        MYSQLEDITOR_INPUT = MYSQLEDITOR_INPUT.replace('[INPUTTYPE]', RT_INPUTTYPE);
        MYSQLEDITOR_INPUT = MYSQLEDITOR_INPUT.replace('[ADDININPUT]', RT_ADDININPUT);
        MYSQLEDITOR_INPUT = MYSQLEDITOR_INPUT.replace('[TABLE]', RT_TABLE);
        MYSQLEDITOR_INPUT = MYSQLEDITOR_INPUT.replace('[FIELDID]', RT_FIELDID);
        MYSQLEDITOR_INPUT = MYSQLEDITOR_INPUT.replace('[ID]', RT_ID);
        MYSQLEDITOR_INPUT = MYSQLEDITOR_INPUT.replace('[FIELD]', RT_FIELD);
        MYSQLEDITOR_INPUT = MYSQLEDITOR_INPUT.replace('[PRESET]', RT_PRESET);
        MYSQLEDITOR_INPUT = MYSQLEDITOR_INPUT.replace('[RETURN]', RT_RETURN);
        MYSQLEDITOR_INPUT = MYSQLEDITOR_INPUT.replace('[RETURNTYPE]', RT_RETURNTYPE);
        MYSQLEDITOR_INPUT = MYSQLEDITOR_INPUT.replace('[VALUE]', RT_VALUE);
        MYSQLEDITOR_INPUT = MYSQLEDITOR_INPUT.replace('[ADDONCLASS]', RT_ADDONCLASS);
        MYSQLEDITOR_INPUT = MYSQLEDITOR_INPUT.replace('[CALLBACKFUNC]', RT_CALLBACKFUNC);
        MYSQLEDITOR_INPUT = MYSQLEDITOR_INPUT.replace('[CALLBACKPARAMS]', RT_CALLBACKPARAMS);
        MYSQLEDITOR_INPUT = MYSQLEDITOR_INPUT.replace('[CALLBACKCOND]', RT_CALLBACKCOND);
        MYSQLEDITOR_INPUT = MYSQLEDITOR_INPUT.replace('[UPDATEDEFAULTVALUE]', RT_UPDATEDEFAULTVALUE);
        MYSQLEDITOR_INPUT = MYSQLEDITOR_INPUT.replace('[UPDATEDEFAULTVALUEDOM]', RT_UPDATEDEFAULTVALUEDOM);
        MYSQLEDITOR_INPUT = MYSQLEDITOR_INPUT.replace('[SEPARATOR]', RT_SEPARATOR);

        // $(MYSQLEDITOR_INPUT).change(function(e)
        // {
        //     e.preventDefault();
        //     e.stopPropagation();
        //     let THIS = $(this);
        //     MySQLEditorAction( THIS );
        // });

        INPUT.html(MYSQLEDITOR_INPUT);

        MaskedInput();
        setTimeout(function ()
        {
            MySQLEditorInit();
        }, 100)
    });
    $("." + FIELD_NAME).change(function(e)
    {
        e.preventDefault();
        e.stopPropagation();
        let THIS = $(this);
        MySQLEditorAction( THIS, false, e );
    });
}

function MySQLEditorAction(THIS, imFromButton=false, parent_event = false)
{
    var BIG_DATER = {};

    if ( imFromButton )
    {
        THIS = $(THIS);
    }

    var INPUT = THIS;

    var MYSQL_TABLE = INPUT.data("table");

    var MYSQL_ASSOC = INPUT.data("assoc");

    var MYSQL_PRESET = INPUT.data("preset");

    var MYSQL_JSON = INPUT.data("jsonarray");

    var MYSQL_CHECK = INPUT.data("checkarray");

    var MYSQL_ID = INPUT.data("id");

    var MYSQL_FID = INPUT.data("fieldid");

    var MYSQL_ACTION = INPUT.data("action");

    var MYSQL_FIELD = INPUT.data("field");

    var MYSQL_RETURN = INPUT.data("return");

    var MYSQL_RETURN_TYPE = INPUT.data("returntype");

    var MYSQL_SEPARATOR = INPUT.data("separator");

    var MYSQL_RETURN_FUNC = INPUT.data("returnfunc");

    var MYSQL_LOADER = INPUT.data("loader");

    var MYSQL_ADUQUATE = INPUT.data("adequate");

    var MYSQL_CALLBACK_DONE = false;
    var MYSQL_CALLBACKFUNC = INPUT.data("callbackfunc");
    if ( typeof MYSQL_CALLBACKFUNC === "undefined" || MYSQL_CALLBACKFUNC === "" || MYSQL_CALLBACKFUNC === "undefined" )
    {
        MYSQL_CALLBACKFUNC = false;
    }
    var MYSQL_CALLBACKPARAMS = INPUT.data("callbackparams"); // в виде json-строки
    if ( typeof MYSQL_CALLBACKPARAMS === "undefined" || MYSQL_CALLBACKPARAMS === "" || MYSQL_CALLBACKPARAMS === "undefined" )
    {
        MYSQL_CALLBACKPARAMS = false;
    } else
    {

    }
    var MYSQL_CALLBACKCOND = INPUT.data("callbackcond"); // условия выполнения callback-функции: always, success, error (по умолчанию success)
    if ( typeof MYSQL_CALLBACKCOND === "undefined" || MYSQL_CALLBACKCOND === "" || MYSQL_CALLBACKCOND === "undefined" )
    {
        MYSQL_CALLBACKCOND = "success";
    } else
    {
        if ( MYSQL_CALLBACKCOND === "success" || MYSQL_CALLBACKCOND === "error" || MYSQL_CALLBACKCOND === "always" )
        {

        } else MYSQL_CALLBACKCOND = "success";
    }

    var MYSQL_UPDATEDEFAULTVALUE = INPUT.data("updatedefaultvalue");
    if ( typeof MYSQL_UPDATEDEFAULTVALUE === "undefined" || MYSQL_UPDATEDEFAULTVALUE === "" || MYSQL_UPDATEDEFAULTVALUE === "undefined" )
    {
        MYSQL_UPDATEDEFAULTVALUE = false;
    } else
    {
        if ( MYSQL_UPDATEDEFAULTVALUE === "1" )
        {
            MYSQL_UPDATEDEFAULTVALUE = true;
        }
    }

    var MYSQL_UPDATEDEFAULTVALUEDOM = INPUT.data("updatedefaultvaluedom");
    if ( typeof MYSQL_UPDATEDEFAULTVALUEDOM === "undefined" || MYSQL_UPDATEDEFAULTVALUEDOM === "" || MYSQL_UPDATEDEFAULTVALUEDOM === "undefined" )
    {
        MYSQL_UPDATEDEFAULTVALUEDOM = false;
    }

    var MYSQL_TYPE = INPUT.attr('type');

    if (MYSQL_TYPE === "text" || MYSQL_TYPE === "number" || MYSQL_TYPE === "date" || MYSQL_TYPE === "hidden")
    {
        var MYSQL_VALUE = INPUT.val();
    } else
    if (MYSQL_TYPE === "checkbox")
    {
        MYSQL_VALUE = '0';
        if (INPUT.is(':checked')) MYSQL_VALUE = '1';
    } else
    if (MYSQL_TYPE === "radio")
    {
        MYSQL_VALUE = '0';
        if (INPUT.is(':checked')) MYSQL_VALUE = INPUT.val();
    } else
    {
        var NODE = INPUT.get(0).tagName;
        if (NODE === "SELECT")
        {
            MYSQL_VALUE = INPUT.val();
        }
        if (NODE === "TEXTAREA")
        {
            MYSQL_VALUE = INPUT.val();
        }
    }

    if ( MYSQL_JSON==="1" || MYSQL_JSON===1 )
    {
        var ALL_FIELDS = $('[data-jsonarray=1]');
        var ARRAY_VALUES = [];
        for (let i = 0; i < ALL_FIELDS.length; i++) {
            var ITEM_ARRAY = $(ALL_FIELDS[i]);
            if ( ITEM_ARRAY.val().length > 0 )
            {
                ARRAY_VALUES.push(ITEM_ARRAY.val());
            }
        }
        MYSQL_VALUE = JSON.stringify(ARRAY_VALUES);
    }

    if ( MYSQL_CHECK === "1" || MYSQL_CHECK === 1 )
    {
        var ALL_FIELDS = $('[data-checkarray=1]');
        var ARRAY_VALUES = [];
        for (let i = 0; i < ALL_FIELDS.length; i++) {
            var ITEM_ARRAY = $(ALL_FIELDS[i]);
            var obj = {}
            obj.key = ITEM_ARRAY.attr("id")
            obj.value = ITEM_ARRAY.is(':checked')
            ARRAY_VALUES.push(obj);
        }
        MYSQL_VALUE = JSON.stringify(ARRAY_VALUES);
    }

    var MYSQL_UPDATE_UNIX_FIELD = INPUT.data("unixfield");

    if ( imFromButton )
    {
        MYSQL_VALUE = INPUT.data("buttonvalue");
    }

    console.log('MySQLEditor value: ' + MYSQL_VALUE);

    let MYSQL_CALLBACKPARAMS_DATA = [];
    // logger(MYSQL_CALLBACKPARAMS_DATA, 'init MYSQL_CALLBACKPARAMS_DATA')
    MYSQL_CALLBACKPARAMS = MYSQL_CALLBACKPARAMS.toString();
    // logger(MYSQL_CALLBACKPARAMS, 'init MYSQL_CALLBACKPARAMS');
    if (MYSQL_CALLBACKPARAMS)
    {
        let MYSQL_CALLBACKPARAMS_ARR = MYSQL_CALLBACKPARAMS.toString().split(';');
        // logger(MYSQL_CALLBACKPARAMS_ARR, 'MYSQL_CALLBACKPARAMS_ARR');
        if ( MYSQL_CALLBACKPARAMS_ARR.length > 0 )
        {
            for (let i = 0; i < MYSQL_CALLBACKPARAMS_ARR.length; i++) {
                let PARAM = MYSQL_CALLBACKPARAMS_ARR[i];
                PARAM = PARAM.toString();
                if ( PARAM.length > 0 )
                {
                    // logger(PARAM, 'PARAM in for')
                    if (PARAM === "self")
                    {
                        // logger(PARAM, 'PARAM IS SELF')
                        MYSQL_CALLBACKPARAMS_DATA.push(MYSQL_VALUE)
                    } else
                    {
                        let param_dom = $(PARAM);
                        if ( param_dom.length > 0 )
                        {
                            // logger(PARAM, 'PARAM IS DOM')
                            MYSQL_CALLBACKPARAMS_DATA.push(param_dom.val());
                        } else
                        {
                            // logger(PARAM, 'PARAM IS SIMPLE')
                            MYSQL_CALLBACKPARAMS_DATA.push(PARAM)
                        }
                    }
                }
            }
        } else MYSQL_CALLBACKPARAMS = false;
    } else MYSQL_CALLBACKPARAMS = false;


    // logger(MYSQL_CALLBACKPARAMS_DATA, 'MYSQL_CALLBACKPARAMS_DATA')

    if ( MYSQL_CALLBACKPARAMS_DATA.length > 0 )
    {
        MYSQL_CALLBACKPARAMS = MYSQL_CALLBACKPARAMS_DATA.join(', ');
        // console.log(MYSQL_CALLBACKPARAMS);
    } else MYSQL_CALLBACKPARAMS = false;

    var LOADER_CLASS = $('#' + MYSQL_LOADER);
    if ( LOADER_CLASS.length === 1 )
    {
        LOADER_CLASS.removeClass('invisible');
    }
    var dat = {
        data_action: MYSQL_ACTION,
        data_table: MYSQL_TABLE,
        data_assoc: MYSQL_ASSOC,
        data_fieldid: MYSQL_FID,
        data_id: MYSQL_ID,
        data_field: MYSQL_FIELD,
        data_value: MYSQL_VALUE,
        data_preset: MYSQL_PRESET,
        data_unixfield: MYSQL_UPDATE_UNIX_FIELD,
        data_adequate: MYSQL_ADUQUATE,
        data_return_func: MYSQL_RETURN_FUNC,
        data_separator: MYSQL_SEPARATOR
    };
    $('html, body').css("cursor", "wait");

    // logger(dat, 'MYSQLEDITOR DAT');

    // if( parent_event.isTrigger >= 0 && typeof parent_event.isTrigger !== "undefined" )
    // if ( parent_event.timeStamp >= 1000000000000 )
    // if ( parent_event.hasOwnProperty('originalEvent') )
    if ( 1 )
    {
        $.ajax({
            url: PHP_WORKER,
            data: dat,
            dataType: 'json',
            type: 'post',
            success: function(json)
            {
                $('html, body').css("cursor", "auto");
                if(json){
                    if ( LOADER_CLASS.length === 1 )
                    {
                        LOADER_CLASS.addClass('invisible');
                    }
                    if (json.result === true)
                    {

                        if ( MYSQL_UPDATEDEFAULTVALUE )
                        {
                            var DOM_VALUE = $(MYSQL_UPDATEDEFAULTVALUEDOM);
                            if ( DOM_VALUE.length > 0 )
                            {
                                DOM_VALUE.data("defaultvalue", MYSQL_VALUE);
                            }
                            if ( DOM_VALUE.data("id") !== "undefined" )
                            {
                                DOM_VALUE.data("id", json.ID);
                            }
                            if ( DOM_VALUE.data("rtid") !== "undefined" )
                            {
                                DOM_VALUE.data("rtid", json.ID);
                            }

                        }
                        if ( MYSQL_CALLBACKCOND === "success" )
                        {
                            var func = '';
                            if (MYSQL_CALLBACKFUNC !== false)
                            {
                                if ( MYSQL_CALLBACKPARAMS !== false )
                                {
                                    func = MYSQL_CALLBACKFUNC + "(\""+MYSQL_CALLBACKPARAMS+"\")";
                                } else
                                {
                                    func = MYSQL_CALLBACKFUNC + "()";
                                }
                                eval(func);
                                MYSQL_CALLBACK_DONE = true;
                            }
                        }
                        if (!!MYSQL_RETURN)
                        {
                            if (!!MYSQL_RETURN_TYPE)
                            {
                                var ReturnElement = $(MYSQL_RETURN);
                                if ( ReturnElement.length > 0 )
                                {
                                    switch (MYSQL_RETURN_TYPE) {
                                        case "html":
                                            ReturnElement.html(json.return_data);
                                            break;
                                        case "input":
                                            ReturnElement.val(json.return_data);
                                            break;
                                    }

                                    if (typeof json['return_value'] !== "undefined")
                                    {
                                        if ( imFromButton )
                                        {
                                            INPUT.data('buttonvalue', json['return_value']);
                                        } else
                                        {
                                            INPUT.val(json['return_value']);
                                        }
                                    }
                                }
                            }
                        }
                    } else {
                        if ( MYSQL_CALLBACKCOND === "error" )
                        {
                            var func = '';
                            if (MYSQL_CALLBACKFUNC !== false)
                            {
                                if ( MYSQL_CALLBACKPARAMS !== false )
                                {
                                    func = MYSQL_CALLBACKFUNC + "('"+MYSQL_CALLBACKPARAMS+"')";
                                } else
                                {
                                    func = MYSQL_CALLBACKFUNC;
                                }
                                eval(func);
                                MYSQL_CALLBACK_DONE = true;
                            }
                        }
                        alert(json.msg);
                        if ( json.needFocus )
                        {
                            INPUT.focus();
                        }
                    }
                }
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                if ( MYSQL_CALLBACKCOND === "error" )
                {
                    var func = '';
                    if (MYSQL_CALLBACKFUNC !== false)
                    {
                        if ( MYSQL_CALLBACKPARAMS !== false )
                        {
                            func = MYSQL_CALLBACKFUNC + "('"+MYSQL_CALLBACKPARAMS+"')";
                        } else
                        {
                            func = MYSQL_CALLBACKFUNC;
                        }
                        eval(func);
                        MYSQL_CALLBACK_DONE = true;
                    }
                }
                $('html, body').css("cursor", "auto");
                alert(textStatus);
            },
            timeout: 5000
        });
    }


}

function ChangeMySQLEditorActionToEdit(dom_id, data_id)
{
    var DOM = $(dom_id);
    let go_next = true;
    if ( DOM.length > 0 )
    {
        let DATA_ACTION = "action";
        let ACTION = DOM.data(DATA_ACTION);
        if ( typeof ACTION === "undefined" )
        {
            DATA_ACTION = "rtaction";
            ACTION = DOM.data(DATA_ACTION);
            if ( typeof ACTION === "undefined" )
            {
                go_next = false;
            }
        }
        if ( go_next )
        {
            $(dom_id).data(DATA_ACTION, "edit");
        }


    }
}