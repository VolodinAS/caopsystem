$( document ).ready(function(err)
{
    $.tablesorter.addWidget({
        id: "numbering",
        format: function(table) {
            var c = table.config;
            $("tr:visible", table.tBodies[0]).each(function(i) {
                $(this).find('td').eq(1).text(i + 1);
            });
        }
    });
    $('#table_id_1').tablesorter({
        dateFormat : "ddmmyyyy", // default date format
        headers: {
            0: { sorter: false },
            1: { sorter: false }
        },
        // apply custom widget
        widgets: ['numbering']
    });

    // $('.justOneClick').unbind('click');
    // $('.justOneClick').click(function(e)
    // {
    //     e.preventDefault();
    //     var THIS = $(this);
    //     var RECORD_ID = THIS.data('recordid');

    //     if ( $("#recordsItem").is(":checked") )
    //     {
    //         $("#recordsItem").prop("checked", false);
    //     } else $("#recordsItem").prop("checked", true);
    //     // $("#recordsItem" + RECORD_ID).click();
    // });

    InitCheckboxCheckAll();
});

