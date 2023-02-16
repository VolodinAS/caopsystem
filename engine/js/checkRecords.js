$(document).ready(function(){
    $('#checkRegButton').click( function () {
        $.ajax({
            url: '/processor/checkRegCards',
            // contentType: false, // важно - убираем форматирование данных по умолчанию
            // processData: false, // важно - убираем преобразование строк по умолчанию
            data: {field: $('#checkRegField').val()},
            dataType: 'json',
            type: 'post',
            success: function(json){
                if(json){

                    if (json.result === true)
                    {

                    } else
                    {

                    }
                }
            }
        });
    } )
});