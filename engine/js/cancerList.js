var viewRouteSheet_name = 'viewRouteSheet';
var DIV_LOADER_viewRouteSheet = '';
var HTML_LOADER_viewRouteSheet = '';

var editCancerData_name = 'editCancerData';
var DIV_LOADER_editCancerData = '';
var HTML_LOADER_editCancerData = '';

var chosenMKB = [];

$( document ).ready(function(err)
{
    DIV_LOADER_viewRouteSheet = $('#'+viewRouteSheet_name+'_body');
    if ( DIV_LOADER_viewRouteSheet.length === 1 ) HTML_LOADER_viewRouteSheet = DIV_LOADER_viewRouteSheet.html();

    DIV_LOADER_editCancerData = $('#'+editCancerData_name+'_body');
    if ( DIV_LOADER_editCancerData.length === 1 ) HTML_LOADER_editCancerData = DIV_LOADER_editCancerData.html();

    $('.cancerList').unbind('click');
    $('.cancerList').tablesorter({
        dateFormat : "ddmmyyyy", // default date format
    });

    $('#getSort').unbind('click');
    $('#getSort').click(function(e)
    {
        e.preventDefault();
        var THIS = $(this);
        var sortField_DOM = $('#sortField');
        var sortField_VALUE = sortField_DOM.val();

        if ( sortField_VALUE == "NONE" )
        {
            alert('Вы не выбрали тип выборки');
        } else
        {
            var sortFrom_DOM = $('#sortFrom');
            var sortTo_DOM = $('#sortTo');
            var sortFrom_VALUE = sortFrom_DOM.val();
            var sortTo_VALUE = sortTo_DOM.val();

            if ( sortFrom_VALUE.length===0 && sortTo_VALUE.length===0 )
            {
                alert('Вы не указали хотя бы один из пределов выборки')
            } else
            {


                var queryObject = {sortType: sortField_VALUE, sortFrom: sortFrom_VALUE, sortTo: sortTo_VALUE};

                $.ajax({
                    url: '/processor/sessions',
                    data: {acttype: "set", data_name: "cancerList", variables: queryObject},
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
    });

    $('#resetSort').unbind('click');
    $('#resetSort').click(function(e)
    {
        e.preventDefault();

        $.ajax({
            url: '/processor/sessions',
            data: {acttype: "reset", data_name: "cancerList"},
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

    });

    $('#letsPrint').unbind('click');
    $('#letsPrint').click(function(e)
    {
        e.preventDefault();
        var THIS = $(this);
        var routeType = THIS.data('routetype');
        var doctor = THIS.data('doctor');
        var sortType = THIS.data('sorttype');
        var sortFrom = THIS.data('sortfrom');
        var sortTo = THIS.data('sortto');
        var dat = {routeType: routeType, doctor: doctor, sortType: sortType, sortFrom: sortFrom, sortTo: sortTo}
        var datQuery = objectToQueryString(dat);


        window.open('https://'+DOMAIN+'/documentPrint/cancerList?' + datQuery);
    });

    $('.nosologyThis').unbind('click');
    $('.nosologyThis').click( function (e) {
        e.preventDefault();
        var THIS = $(this);
        var MKB = THIS.data('nosology');
        $('.move-labeler-mkb').prop('checked', false);
        $('#labeler_' + MKB).prop('checked', true);
        $('.nosologySelected').click();
    } );

    $('.nosologySelected').unbind('click');
    $('.nosologySelected').click( function (e) {
        e.preventDefault();
        var chosenMKB = moveLabelerReturn('.move-labeler-mkb');
        if ( chosenMKB.length > 0 )
        {
            var mkb_data = [];
            for (let i = 0; i < chosenMKB.length; i++) {
                var ITEM = chosenMKB[i];
                var MKB = ITEM.data('mkb');
                mkb_data.push(MKB);
            }
            var PRINT = mkb_data.join(';');
            // window.open('/documentPrint/cancerNosology/' + PRINT);
            // goPrint();

            $.ajax({
                url: '/processor/sessions',
                data: {acttype: "set", data_name: "nosologyPrint", variables: PRINT},
                dataType: 'json',
                type: 'post',
                success: function(json){
                    if(json){

                        if (json.result === true)
                        {
                            window.open('/documentPrint/cancerNosology');
                        } else
                        {
                            alert(json.msg);
                        }
                    }
                }
            });

        } else
        {
            alert('Нет выбранных заболеваний для распечатки');
        }
    } );

    $('.nosologyAll').unbind('click');
    $('.nosologyAll').click(function (e) {
        e.preventDefault();
        $('.move-labeler-mkb').prop('checked', true);
        $('.nosologySelected').click();
    });

    $('.nosologyUnselect').unbind('click');
    $('.nosologyUnselect').click(function (e) {
        e.preventDefault();

        $.ajax({
            url: '/processor/sessions',
            data: {acttype: "reset", data_name: "nosologyPrint"},
            dataType: 'json',
            type: 'post',
            success: function(json){
                if(json){

                    if (json.result === true)
                    {
                        $('.move-labeler-mkb').prop('checked', false);
                    } else
                    {
                        alert(json.msg);
                    }
                }
            }
        });
    });
});

function openRouteSheet(patid_id)
{
    var window_modal = $('#' + viewRouteSheet_name);
    if ( patid_id > 0 )
    {
        DIV_LOADER_viewRouteSheet.html( HTML_LOADER_viewRouteSheet );

        window_modal.modal('show');

        $.ajax({
            url: '/processor/cancerList_openRouteSheet',
            data: {patid_id: patid_id},
            dataType: 'json',
            type: 'post',
            success: function(json){
                if(json){

                    if (json.result === true)
                    {
                        DIV_LOADER_viewRouteSheet.html(json.htmlData);
                    } else
                    {
                        DIV_LOADER_viewRouteSheet.html(json.msg);
                    }
                }
            }
        });


    }
}

function editCancerData(patid_id)
{
    var window_modal = $('#' + editCancerData_name);
    if ( patid_id > 0 )
    {
        DIV_LOADER_editCancerData.html( HTML_LOADER_editCancerData );

        window_modal.modal('show');


    }
}

function objectToQueryString(obj) {
    var str = [];
    for (var p in obj)
        if (obj.hasOwnProperty(p)) {
            str.push(encodeURIComponent(p) + "=" + encodeURIComponent(obj[p]));
        }
    return str.join("&");
}