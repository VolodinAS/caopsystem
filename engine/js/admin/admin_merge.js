$( document ).ready(function(err)
{
    $('#button-hide').hide();

    $('#equalizer').click(function(e)
    {
        e.preventDefault();
        var base = $('#base').val();
        var target = $('#target').val();

        if ( base.length > 0 && target.length > 0 )
        {
            $.ajax({
                url: '/processor/admin_mergeEquals',
                data: {base: base, target: target},
                dataType: 'json',
                type: 'post',
                success: function(json){
                    if(json){

                        if (json.result === true)
                        {
                            $('#button-hide').show();

                            $('#button-merge').attr('data-base', base);
                            $('#button-merge').attr('data-target', target);

                            $('#baseHtml').html('');
                            $('#targetHtml').html('');

                            $('#baseData').html(json.patient_base.debug.data);
                            $('#targetData').html(json.patient_target.debug.data);

                            for( var prop in json.patient_base )
                            {
                                $('#baseHtml').html( $('#baseHtml').html() + json.patient_base[prop].htmlData );
                            }

                            for( var prop in json.patient_target )
                            {
                                $('#targetHtml').html( $('#targetHtml').html() + json.patient_target[prop].htmlData );
                            }


                            // $('#baseHtml').html(json.patient_base.citology.htmlData);
                            // $('#baseHtml').html(json.patient_base.journal.htmlData);
                            // $('#baseHtml').html(json.patient_base.research.htmlData);
                            // $('#baseHtml').html(json.patient_base.route.htmlData);

                        } else
                        {
                            alert(json.msg);
                        }
                    }
                }
            });
        }

    });

    $('#button-merge').click(function(e)
    {
        e.preventDefault();
        let THIS = $(this);
        let BASE_ID = THIS.data('base');
        let TARGET_ID = THIS.data('target');
        let formSettings = formSerializer('#form_mergeSettings')
        if ( BASE_ID > 0 && TARGET_ID > 0 )
        {
            if ( confirm("ВНИМАНИЕ! СЛИЯНИЕ ПРИВЕДЕТ К УДАЛЕНИЮ ИНФОРМАЦИИ О КАРТЕ TARGET["+TARGET_ID+"]!\n\nПРОДОЛЖИТЬ?") )
            {
                if ( confirm("ВЫ УВЕРЕНЫ В СЛИЯНИИ ДВУХ КАРТ BASE["+BASE_ID+"] И TARGET["+TARGET_ID+"]? ИНФОРМАЦИЯ О TARGET ПЕРЕЙДЕТ К BASE!") )
                {
                    $.ajax({
                        url: '/processor/admin_mergeProcessNew',
                        data: {base: BASE_ID, target: TARGET_ID, patientData: formSettings},
                        dataType: 'json',
                        type: 'post',
                        success: function(json){
                            if(json){

                                if (json.result === true)
                                {
                                    alert('ДАННЫЕ ПАЦИЕНТОВ ОБЪЕДИНЕНЫ');
                                } else
                                {
                                    alert(json.msg);
                                }
                            }
                        }
                    });
                }
            }
        }
    });

    $('.swapIDs').click(function(e)
    {
        e.preventDefault();
        var THIS = $(this);
        var base = $('#base').val();
        var target = $('#target').val();
        $('#base').val(target);
        $('#target').val(base);
    });
});