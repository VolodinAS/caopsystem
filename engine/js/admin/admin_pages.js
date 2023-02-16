$( document ).ready(function()
{
    $('.button-page-new').unbind('click');
    $('.button-page-new').click(function(e)
    {
        e.preventDefault();
        var THIS = $(this);
        let serializeArray = getSerializeArrayFromNotAForm('#form-new-page');

        $.ajax({
            url: '/processor/admin_page_new',
            data: serializeArray,
            dataType: 'json',
            type: 'post',
            success: function (json) {
                if (json.result === true) {
                    alert(json.msg);
                    window.location.reload();
                } else alert(json.msg);
            },
            error: function () {
            },
            complete: function () {
            }
        });

    });

    $('.button-page-delete').unbind('click');
    $('.button-page-delete').click(function(e)
    {
        e.preventDefault();
        let THIS = $(this);

        if ( confirm('Вы действительно хотите удалить данную страницу?') )
        {

            let page_id = THIS.data('pageid');

            $.ajax({
                url: '/processor/admin_page_delete',
                data: {page_id: page_id},
                dataType: 'json',
                type: 'post',
                success: function (json) {
                    if (json.result === true) {
                        alert(json.msg);
                        window.location.reload();
                    } else alert(json.msg);
                },
                error: function () {
                },
                complete: function () {
                }
            });
        }
    });
});