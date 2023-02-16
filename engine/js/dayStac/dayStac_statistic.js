$(document).ready( function (e) {
	initButtons();
} );

function initButtons() {


	$('.btn_stat_howmany_phys_face_cured').unbind('click');
	$('.btn_stat_howmany_phys_face_cured').click( function (e) {
		e.preventDefault();


		var DATE_FROM = $('#stat_howmany_phys_face_cured_from').val();
		var DATE_TO = $('#stat_howmany_phys_face_cured_to').val();

		if ( DATE_FROM.length > 0 && DATE_TO.length > 0 )
		{

			$.ajax({
                url: '/processor/sessionPrint',
                data: {doctype: "stat_howmany_phys_face_cured", date_from: DATE_FROM, date_to: DATE_TO},
                dataType: 'json',
                type: 'post',
                success: function(json) {

                    if ( json.result === true )
                    {
            			window.open(json.url + '/'+DATE_FROM+'/'+DATE_TO);
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

    $('.btn_stat_howmany_visits').unbind('click');
    $('.btn_stat_howmany_visits').click( function (e) {
        e.preventDefault();


        var DATE_FROM = $('#stat_howmany_visits_from').val();
        var DATE_TO = $('#stat_howmany_visits_to').val();

        if ( DATE_FROM.length > 0 && DATE_TO.length > 0 )
        {

            $.ajax({
                url: '/processor/sessionPrint',
                data: {doctype: "stat_howmany_visits", date_from: DATE_FROM, date_to: DATE_TO},
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