$( document ).ready(function()
{

    $('.btn-getJournalDilutionMeans').unbind('click');
    $('.btn-getJournalDilutionMeans').click(function(e)
    {
        e.preventDefault();
        let THIS = $(this);
        let data = formSerializer('#dilutionMeans_form')

        $.ajax({
            url: '/processor/sessionPrint',
            data: {...data, doctype: "dilutionMeans"},
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

    $('.btn-getJournalQuartzTime').unbind('click');
    $('.btn-getJournalQuartzTime').click(function(e)
    {
        e.preventDefault();
        let THIS = $(this);
        let data = formSerializer('#quartzingTime_form')

        $.ajax({
            url: '/processor/sessionPrint',
            data: {...data, doctype: "quartzingTime"},
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

});