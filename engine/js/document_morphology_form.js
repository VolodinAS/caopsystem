$( document ).ready(function()
{
    $('.btn-addMarker').unbind('click');
    $('.btn-addMarker').click(function(e)
    {
        e.preventDefault();
        let THIS = $(this);
        let morph_id = THIS.data('morphology');
        $.ajax({
            url: '/processor/morphology_addMarker',
            data: {morph_id},
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
    });
});