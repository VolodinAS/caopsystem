$(document).ready(function(){

    $('#button_reg').click(function ()
    {
        $('#form_reg').submit();
    });

    $('#form_reg').on('submit', function(e){
        e.preventDefault();
        e.stopPropagation();

        var $that = $(this),
            formData = new FormData($that.get(0)); // создаем новый экземпляр объекта и передаем ему нашу форму (*)




        $.ajax({
            url: '/processor/reg',
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
                        $('#regMessagePlace').html('Вы успешно зарегистрированы!');
                        $('#buttonRegDismiss').hide();
                        $('#buttonGoAuth').show();
                        $('#regMessageBox').modal('show');
                    } else
                    {
                        $('#regMessagePlace').html(json.msg);
                        $('#regMessageBox').modal('show');
                    }
                }
            }
        });
    });

});
