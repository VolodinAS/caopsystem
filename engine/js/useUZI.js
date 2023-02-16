$(document).ready(function(e){

    $('#tabler-uzi').tablesorter();

    $('#button_uziNote_refresh').click(function () {
        window.location.reload();
    });

    $('#button_uziNote_newNote').click(function () {
        $('#newNoteRecord').modal('show');
    });

    $('#addNewUzi').click(function (e) {
        $('#newUzi').submit();
    });

    $('#newUzi').on('submit', function (e) {
        e.preventDefault();
        e.stopPropagation();

        var $that = $(this),
            formData = new FormData($that.get(0));

        $.ajax({
            url: '/processor/newUzi',
            contentType: false, // важно - убираем форматирование данных по умолчанию
            processData: false, // важно - убираем преобразование строк по умолчанию
            // data: {action: 'reg', form: formData},
            data: formData,
            dataType: 'json',
            type: 'post',
            success: function(json){
                if(json){


                    if (json.result === true)
                    {
                        window.location.reload();
                    } else
                    {
                    }
                }
            }
        });
    });

    $('.deleteUzi').click(function (e) {
        var THIS = $(this);
        var UZIID = THIS.data('uziid');
        if ( confirm('Вы действительно желаете удалить запись из журнала?') )
        {
            $.ajax({
                url: '/processor/deleteUzi',
                // contentType: false, // важно - убираем форматирование данных по умолчанию
                // processData: false, // важно - убираем преобразование строк по умолчанию
                // data: {action: 'reg', form: formData},
                data: {uzi_id: UZIID},
                dataType: 'json',
                type: 'post',
                success: function(json){
                    if(json){


                        if (json.result === true)
                        {
                            window.location.reload();
                        } else
                        {
                        }
                    }
                }
            });
        }
    });
});