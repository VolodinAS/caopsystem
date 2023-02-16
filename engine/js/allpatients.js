var LOADER_HTML = '';
var ACTIVE_TAB = 'all_patients';

$(document).ready(function(e){
    LOADER_HTML = $('#visitsPatientCardData').html();
    $('.allpatients').tablesorter();

    $('#deletePatientData').click(function (e) {
        if ( confirm("ПОДТВЕРДИТЕ УДАЛЕНИЕ ПАЦИЕНТА ИЗ СПИСКА") )
        {
            if ( confirm("ТОЧНО УДАЛИТЬ ПАЦИЕНТА ИЗ СПИСКА?") )
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

    $('a[data-toggle="tab"]').on('click', function (e) {
        $('.allpatients').tablesorter();
        var THIS = $(this);
        var active = THIS.data('activetab');
        ACTIVE_TAB = active;
    });



    InitCaser();
    // reInitVerif();

}); // dred

/*function allVisits(patid_id, _hideCase = 0) {

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
}*/



function mypSetSession(act, values='all_patients')
{
    var obj = {showme: values};
    $.ajax({
        url: '/processor/sessions',
        data: {acttype: act, data_name: "my_patients", variables: obj},
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
