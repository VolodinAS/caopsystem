var LOADER_HTML = '';


$(document).ready(function(){

    $("#patient_research").tablesorter({
        dateFormat : "ddmmyyyy", // default date format
    });

    LOADER_HTML = $('#researchPatientData').html();

    $('.deletebutton').click(function (e) {
        e.stopPropagation();
        e.preventDefault();
        if ( confirm('Вы действительно хотите удалить пациента из списка на обследование?') )
        {
            var INPUT = $(this);

            var PATIENT = INPUT.data("patient");


            var dat = {patid: PATIENT};
            $.ajax({
                url: '/processor/researchDelete',
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


        }
    });

    $('.openCard').click(function () {
        var THIS = $(this);
        var ID = THIS.data('patient');
        // alert(ID);
        $('#researchPatientData').html( LOADER_HTML );
        $('#researchPatientCard').modal('show');

        var dat = {patient_id: ID};
        $.ajax({
            url: '/processor/researchCard',
            // contentType: false, // важно - убираем форматирование данных по умолчанию
            // processData: false, // важно - убираем преобразование строк по умолчанию
            data: dat,
            dataType: 'json',
            type: 'post',
            success: function(json){
                if(json){

                    if (json.result === true)
                    {
                        $('#researchPatientData').html( json.htmlData );
                        MySQLEditorInit();
                        MaskedInput();
                        reInitVerif();
                    } else
                    {
                        $('#researchPatientData').html( json.msg );
                    }
                }
            }
        });
    });

    $('.research_type').click( function (e) {
        var THIS = $(this);
        var TYPE = THIS.data('typeid');
        var CHECKED = THIS.is(':checked')

        if ( e.ctrlKey )
        {

            $('.research_type').prop('checked', false);
            THIS.prop('checked', true);
        }

        if ( $('.research_type:checked').length === 0 )
        {
            $('.research_type').prop('checked', true);
        }

        var typesData = moveLabelerReturn('.research_type');

        var types_ids = [];
        for (let i = 0; i < typesData.length; i++) {
            var ITEM = typesData[i];
            var PATID = ITEM.data('typeid');
            types_ids.push(PATID);
        }





        GoFilterData(types_ids);

    });

    $('a[data-toggle="tab"]').on('click', function (e) {
         // newly activated tab
         // previous active tab


        // $(".patient_research").trigger('sorton')
        // $('.patient_research').trigger('updateAll');
        $(".patient_research").tablesorter({
            dateFormat : "ddmmyyyy", // default date format
        });

        $(".searchResearch").keyup(function(){
            _this = this;

            $.each($(".patient_research tbody tr"), function() {
                if ($(this).text().toLowerCase().indexOf($(_this).val().toLowerCase()) === -1) {
                    $(this).hide();
                } else {
                    $(this).show();
                }
            });
        });
    });

    $(".searchResearch").keyup(function(){
        _this = this;

        $.each($(".patient_research tbody tr"), function() {
            if ($(this).text().toLowerCase().indexOf($(_this).val().toLowerCase()) === -1) {
                $(this).hide();
            } else {
                $(this).show();
            }
        });
    });

    $('#btn-forceClose').unbind('click');
    $('#btn-forceClose').click(function(e)
    {
        e.preventDefault();
        let THIS = $(this);
        CITOLOGY_MODAL_ISOPENED = false;
        $('#researchPatientCard').modal('toggle')
    });

});



function GoFilterData(types_ids) {




    var RESEARCH = $('.research_type_selector');
    $('.patient-hidden').hide();
    for (let i = 0; i < RESEARCH.length; i++) {
        var SELECTOR = $(RESEARCH[i]);
        var SELECTOR_VALUE = parseInt(SELECTOR.val());
        var SELECTOR_PATID = SELECTOR.data('id');


        if ( types_ids.includes(SELECTOR_VALUE) )
        {

            $('.patient' + SELECTOR_PATID).show();
        }
    }

}

$('#researchPatientCard').on('hide.bs.modal', function (e)
{
    console.log(CITOLOGY_MODAL_ISOPENED)
    if ( CITOLOGY_MODAL_ISOPENED )
    {
        e.preventDefault()
        let research_id = $('#research_id').val();
        $.ajax({
            url: '/processor/citology_checkCancer',
            data: {research_id, type: 'research'},
            dataType: 'json',
            type: 'post',
            success: function (json) {
                if (json) {
                    if (json.result === true) {
                        CITOLOGY_MODAL_ISOPENED = false
                        $('#researchPatientCard').modal('toggle')
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
