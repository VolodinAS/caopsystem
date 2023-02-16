let cartridgeNewAction_modal;
let cartridgeNewAction_loader;
let cartridgeNewAction_body;

$( document ).ready(function()
{
    cartridgeNewAction_modal = $('#newAction');
    cartridgeNewAction_body = $('#newAction_body');
    cartridgeNewAction_loader = cartridgeNewAction_body.html();
    
    initButtons();
});

function initButtons()
{
    $('.btn-actionRemove').unbind('click');
    $('.btn-actionRemove').click(function(e)
    {
        e.preventDefault();
        let THIS = $(this);
        if ( confirm('ВЫ ДЕЙСТВИТЕЛЬНО ХОТИТЕ УДАЛИТЬ ДАННОЕ ДЕЙСТВИЕ?') )
        {
            let CARTRIDGE_ID = THIS.data('cartridge')
            $.ajax({
                url: '/processor/cartridge_removeAction',
                data: {cartridge_id: CARTRIDGE_ID},
                dataType: 'json',
                type: 'post',
                success: function (json) {
                    if (json) {
                        if (json.result === true) {
                            alert(json.msg)
                            window.location.reload()
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
    });

    $('.btn-addNewAction').unbind('click');
    $('.btn-addNewAction').click(function(e)
    {
        e.preventDefault();
        let THIS = $(this);
        let form = formSerializer('#newAction_form')
        $.ajax({
            url: '/processor/cartridge_addAction',
            data: form,
            dataType: 'json',
            type: 'post',
            success: function (json) {
                if (json) {
                    if (json.result === true) {
                        alert(json.msg)
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

    $('.btn-addCartridge').unbind('click');
    $('.btn-addCartridge').click(function(e)
    {
        e.preventDefault();
        let THIS = $(this);
        let data = formSerializer('#newCartridge_form')

        $.ajax({
            url: '/processor/cartridge_add',
            data: data,
            dataType: 'json',
            type: 'post',
            success: function (json) {
                if (json) {
                    if (json.result === true) {
                        alert(json.msg)
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
    
    $('.btn-addAction').unbind('click');
    $('.btn-addAction').click(function(e)
    {
        e.preventDefault();
        let THIS = $(this);
        let CARTRIDGE_ID = THIS.data('cartridge')
        cartridgeNewAction_body.html( cartridgeNewAction_loader )
        cartridgeNewAction_modal.modal('show');

        $.ajax({
            url: '/processor/newAction_modal',
            data: {cartridge_id: CARTRIDGE_ID},
            dataType: 'json',
            type: 'post',
            success: function (json) {
                if (json) {
                    if (json.result === true) {
                        cartridgeNewAction_body.html(json.htmlData)
                        MaskedInput();
                        initButtons();
                    } else {
                        cartridgeNewAction_body(json.msg)
                    }
                }
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert(textStatus);
            }
        });

    });
}