var LOADER_HTML = '';
$(document).ready(function(e){

    LOADER_HTML = $('#citologyMarkerData').html();

    $('#maincitology').tablesorter({
        dateFormat : "ddmmyyyy", // default date format
    });

    $("#search").keyup(function(){
        _this = this;

        $.each($("#maincitology tbody tr"), function() {
            if ($(this).text().toLowerCase().indexOf($(_this).val().toLowerCase()) === -1) {
                $(this).hide();
            } else {
                $(this).show();
            }
        });
    });

    // console.log($('#journalEditCitology'))
    $('#btn-forceClose').unbind('click');
    $('#btn-forceClose').click(function(e)
    {
        e.preventDefault();
        let THIS = $(this);
        CITOLOGY_MODAL_ISOPENED = false;
        $('#journalEditCitology').modal('toggle')
    });
});

function citologyRemovePatient(patient_id)
{
    if ( confirm('Вы действительно желаете удалить пациента из списка на цитологическое исследование?') )
    {

        $.ajax({
            url: '/processor/journalCitologyDelete',
            data: {patient_id: patient_id},
            dataType: 'json',
            type: 'post',
            success: function(json){
                if(json){

                    if (json.result === true)
                    {
                        // alert('Пациент успешно записан на цитологическое исследование!');
                        // window.location.reload();
                        $('#stringPatient' + patient_id).hide(200);
                    }
                }
            }
        });

    }
}

function citologyPrintDirection(patient_id) {
    window.open('/citologyPrintDirection/' + patient_id);
}

function citologyMark(citology_id) {
    $('#citologyMarkerData').html(LOADER_HTML);
    $('#citologyMarker').modal('show');

    var dat = {citology_id: citology_id};
    $.ajax({
        url: '/processor/citologyMarkerEdit',
        // contentType: false, // важно - убираем форматирование данных по умолчанию
        // processData: false, // важно - убираем преобразование строк по умолчанию
        data: dat,
        dataType: 'json',
        type: 'post',
        success: function (json) {
            if (json) {

                if (json.result === true) {
                    $('#citologyMarkerData').html(json.htmlData);
                    MySQLEditorInit();
                    MaskedInput();
                } else {
                    $('#citologyMarkerData').html(json.msg);
                }
            }
        }
    });

}


$('#journalEditCitology').on('hide.bs.modal', function (e)
{
    console.log(CITOLOGY_MODAL_ISOPENED)
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
