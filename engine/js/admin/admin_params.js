$( document ).ready(function()
{
    let form_id = '#form_add_admin_param'
    let form_dom = $(form_id)

    form_dom.validate({
        errorClass: "error small",
        submitHandler: function() {
            submit_addAdminParam()
        }
    })

    $('.btn-addAdminParam').unbind('click');
    $('.btn-addAdminParam').click(function(e)
    {
        e.preventDefault();
        let THIS = $(this);
        form_dom.submit();
    });
});

function submit_addAdminParam()
{
    console.log('im here')
    let form_id = '#form_add_admin_param'
    let formAdminParams = formSerializer(form_id)

    logger(formAdminParams, 'formAdminParams')

    $.ajax({
        url: '/processor/admin_params_addparam',
        data: {...formAdminParams},
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
}