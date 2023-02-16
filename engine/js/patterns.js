$( document ).ready(function()
{

    $('.button-pattern-delete').unbind('click');
    $('.button-pattern-delete').click(function(e)
    {
        e.preventDefault();
        let THIS = $(this);

        if ( confirm('Вы действительно хотите удалить данный паттерн?') )
        {

            let pattern_id = THIS.data('patternid');

            $.ajax({
                url: '/processor/pattern_delete',
                data: {pattern_id: pattern_id},
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

    $('.button-pattern-new').unbind('click');
    $('.button-pattern-new').click(function(e)
    {
        e.preventDefault();
        var THIS = $(this);
        let serializeArray = getSerializeArrayFromNotAForm('#form-new-pattern');

        $.ajax({
            url: '/processor/pattern_new',
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