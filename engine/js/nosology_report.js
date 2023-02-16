$(document).ready( function () {
    initButtons();
} );

function initButtons() {


    $('.btn-nosology-report-caop').unbind('click');
    $('.btn-nosology-report-caop').click( function (e) {
        e.preventDefault();

        var CAOP_DATE_FROM = $('#nosology_report_caop_from').val();
        var CAOP_DATE_TO = $('#nosology_report_caop_to').val();
        var CAOP_DOCTOR_ID = $('#nosology_report_caop_doctor_id').val();
        var CAOP_DIAGNOSIS = $('#nosology_report_caop_diagnosis').val();

        $.ajax({
            url: '/processor/sessionPrint',
            data: {doctype: "nosology_report", caop_date_from: CAOP_DATE_FROM, caop_date_to: CAOP_DATE_TO, caop_doctor_id: CAOP_DOCTOR_ID, caop_diagnosis: CAOP_DIAGNOSIS, filter: 'caop'},
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
    } )

    $('.btn-nosology-report-ds').unbind('click');
    $('.btn-nosology-report-ds').click( function (e) {
        e.preventDefault();

        var CAOP_DATE_FROM = $('#nosology_report_ds_from').val();
        var CAOP_DATE_TO = $('#nosology_report_ds_to').val();
        var CAOP_DOCTOR_ID = $('#nosology_report_ds_doctor_id').val();
        var CAOP_DIAGNOSIS = $('#nosology_report_ds_diagnosis').val();

        $.ajax({
            url: '/processor/sessionPrint',
            data: {doctype: "nosology_report", ds_date_from: CAOP_DATE_FROM, ds_date_to: CAOP_DATE_TO, ds_doctor_id: CAOP_DOCTOR_ID, ds_diagnosis: CAOP_DIAGNOSIS, filter: 'ds'},
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
    } )

}