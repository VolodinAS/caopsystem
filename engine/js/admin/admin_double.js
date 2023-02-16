$( document ).ready(function()
{

});

function doubleComplete(double_id)
{
    $.ajax({
        url: '/processor/admin_doubleComplete',
        data: {double_id},
        dataType: 'json',
        type: 'post',
        success: function (json) {
            if (json) {
                if (json.result === true) {
                    alert(json.msg);
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
}