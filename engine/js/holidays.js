$( document ).ready(function()
{
    // $('.btn-addHoliday').unbind('click');
    // $('.btn-addHoliday').click(function(e)
    // {
    //     e.preventDefault();
    //     let THIS = $(this);
    //     let form_data = formSerializer('#form_newHoliday');
    //
    //     logger(form_data)
    // });

    $('#form_newHoliday').unbind('submit');
    $('#form_newHoliday').submit(function (e)
    {
        e.preventDefault();
        let form_data = formSerializer('#form_newHoliday');

        $.ajax({
            url: '/processor/newHoliday',
            data: {...form_data},
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
    })
});