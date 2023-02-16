$(document).ready(function(){

    // $('#button_auth').click(function ()
    // {
    //     $('#form_auth').submit();
    // });

    $('#form_auth').on('submit', function(e){
        e.preventDefault();
        e.stopPropagation();

        var $that = $(this),
            formData = new FormData($that.get(0)); // создаем новый экземпляр объекта и передаем ему нашу форму (*)




        $.ajax({
            url: '/processor/auth',
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
                        window.location.href = json.redirect;
                        // $('#authMessagePlace').html('Авторизация завершена!');
                        // $('#buttonAuthDismiss').hide();
                        // $('#buttonGoProfile').show();
                        // $('#authMessageBox').modal('show');
                        // setTimeout(function (e)
                        // {
                        //     console.log(1)
                        //     $('#buttonGoProfile').focus();
                        // }, 500)
                    } else
                    {
                        $('#authMessagePlace').html(json.msg);
                        $('#authMessageBox').modal('show');
                    }
                }
            }
        });
    });

});
