let CHECKBOX_DOM_birth_current;
let INPUT_DOM_birth_to;
$( document ).ready(function()
{
    CHECKBOX_DOM_birth_current = $('#birth_current');
    INPUT_DOM_birth_to = $('#birth_to');

    initDOM();

    $(function() {
        $(".allpatients").tablesorter();
    });
});

function initDOM()
{
    CHECKBOX_DOM_birth_current.unbind('change');
    CHECKBOX_DOM_birth_current.change(function(e)
    {
        e.preventDefault();
        var THIS = $(this);
        if ( THIS.prop('checked') === true )
        {
            INPUT_DOM_birth_to.hide();
            INPUT_DOM_birth_to.val('');
        } else
        {
            INPUT_DOM_birth_to.show();
        }
    });

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
                                $('#tr_patid__' + PATID_ID).remove();
                                $('#editPersonalDataCard').modal('hide');
                            } else
                            {

                            }
                        }
                    }
                });
            }
        }
    });
}

function paginator(selector, page, page_get='page')
{
    let FORM = $(selector);
    FORM.attr('action', '?' + page_get + '=' + page);
    FORM.submit();
}