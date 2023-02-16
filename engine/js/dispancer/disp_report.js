$(document).ready( function (e) {
    initButtons();
} );


function initButtons() {


    $('.btn_dispancer_report').unbind('click');
    $('.btn_dispancer_report').click( function (e) {
        e.preventDefault();

        var DATE_FROM = $('#dispancer_report_from').val();
        var DATE_TO = $('#dispancer_report_to').val();
        var DOCTOR_ID = $('#dispancer_report_doctor_id').val();
        var SHOW_REPEATS = ( $('#dispancer_report_showRepeats').is(':checked') ) ? 1 : 0;

        if ( DATE_FROM.length > 0 && DATE_TO.length > 0 )
        {

            $.ajax({
                url: '/processor/sessionPrint',
                data: {doctype: "dispancer_report", date_from: DATE_FROM, date_to: DATE_TO, doctor_id: DOCTOR_ID, showRepeats: SHOW_REPEATS},
                dataType: 'json',
                type: 'post',
                success: function(json) {
                    if ( json.result === true )
                    {
                        window.open(json.url);
                    } else
                    {
                        alert(json.msg);
                    }
                },
                error: function() {
                },
                complete: function() {
                }
            });

        } else alert('Неверно выбран период отчёта');

    } )

}