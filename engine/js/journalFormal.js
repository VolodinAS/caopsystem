$( document ).ready(function(err)
{
    initButtons();
});

function initButtons()
{
    $('#btn-formalJournal-go').unbind('click');
    $('#btn-formalJournal-go').click(function(e)
    {
        e.preventDefault();

        var DATE_FROM = $('#fj_from').val();
        var DATE_TO = $('#fj_to').val();

        if ( DATE_FROM.length > 0 && DATE_TO.length > 0 )
        {
            $.ajax({
                url: '/processor/sessions',
                data: {acttype: "set", data_name: "formalJournal", variables: {date_from: DATE_FROM, date_to: DATE_TO} },
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
        } else alert('Не выбран период');
    });
}