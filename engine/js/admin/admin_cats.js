$( document ).ready(function()
{

    $('#cats_sorter').tablesorter({
        sortList : [[7,1]] // initial sort columns (2nd and 3rd)
    });

    $('.cat-assign').unbind('click');
    $('.cat-assign').click(function (e) {
        if ( confirm('Вы действительно хотите присвоить текущий ключ данному компьютеру?') )
        {
            var THIS = $(this);
            var CATID = THIS.data('catid');
            var dat = {catid: CATID};
            $.ajax({
                url: '/processor/adminCatAssign',
                data: dat,
                dataType: 'json',
                type: 'post',
                success: function(json){
                    if(json){

                        if (json.result === true)
                        {
                            alert(json.msg)
                        } else
                        {
                            alert(json.msg)
                        }
                    }
                }
            });
        }
    });

    $('.btn-approve').unbind('click');
    $('.btn-approve').click(function(e)
    {
        e.preventDefault();
        let THIS = $(this);
        let cat_id = THIS.data('id');
        console.log(THIS)
        $.ajax({
            url: '/processor/adminCatApprove',
            data: {cat_id},
            dataType: 'json',
            type: 'post',
            success: function (json) {
                if (json) {
                    if (json.result === true) {
                        let button = $('#approve_button_' + cat_id);
                        let icon = $('#approved_' + cat_id);
                        button.html('Одобрение: ' + json.button);
                        icon.html('<span class="nondisplay">'+json.unix+'</span><span '+json.date+'>'+json.icon+'</span>');
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

    $('.btn-autorenewal').unbind('click');
    $('.btn-autorenewal').click(function(e)
    {
        e.preventDefault();
        let THIS = $(this);
        let cat_id = THIS.data('id');
        console.log(THIS)
        $.ajax({
            url: '/processor/adminCatAutorenewal',
            data: {cat_id},
            dataType: 'json',
            type: 'post',
            success: function (json) {
                if (json) {
                    if (json.result === true) {
                        let button = $('#auto_renewal_button_' + cat_id);
                        let icon = $('#auto_renewal_' + cat_id);
                        button.html('Автопродление: ' + json.button);
                        icon.html('<span class="nondisplay">'+json.state+'</span>'+json.icon);
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