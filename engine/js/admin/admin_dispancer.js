var LOADER_HTML = '';



$( document ).ready(function()
{
    LOADER_HTML = $('#visitsPatientCardData').html();

    $('.btn_disp_apply').unbind('click');
    $('.btn_disp_apply').click(function(e)
    {
        e.preventDefault();
        var THIS = $(this);
        var DATE_FROM = $('#disp_visit_from').val();
        var DATE_TO = $('#disp_visit_to').val();
        var DOCTOR = $('#disp_doctor_id').val();
        $.ajax({
            url: '/processor/sessions',
            data: {acttype: "set", data_name: "dispancer", variables: {date_from: DATE_FROM, date_to: DATE_TO, doctor_id: DOCTOR} },
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

    $('.btn_disp_reset').unbind('click');
    $('.btn_disp_reset').click(function(e)
    {
        e.preventDefault();
        var THIS = $(this);
        $.ajax({
            url: '/processor/sessions',
            data: {acttype: "set", data_name: "dispancer", variables: {date_from: '', date_to: '', doctor_id: ''} },
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
});

