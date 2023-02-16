$( document ).ready(function()
{
    $('.button-menu-delete').unbind('click');
    $('.button-menu-delete').click(function(e)
    {
        e.preventDefault();
        let THIS = $(this);

        if ( confirm('Вы действительно хотите удалить данное меню?') )
        {

            let menu_id = THIS.data('menuid');

            $.ajax({
                url: '/processor/admin_menu_delete',
                data: {menu_id: menu_id},
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

    $('.button-menu-new').unbind('click');
    $('.button-menu-new').click(function(e)
    {
        e.preventDefault();
        var THIS = $(this);
        let serializeArray = getSerializeArrayFromNotAForm('#form-new-menu');

        $.ajax({
            url: '/processor/admin_menu_new',
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
});