$( document ).ready(function()
{
    // InitCheckboxCheckAll();

    InitMe();
});

function InitMe()
{
    $(".checkbox-checkall").multiselectCheckbox({
        checkboxes: ".recordsItemCB",
        sync: "table tr",
        syncEvent:"click",
        handleShiftForCheckbox: false
    });

    $('.btn-newRecord').unbind('click');
    $('.btn-newRecord').click(function(e)
    {
        e.preventDefault();
        let THIS = $(this);
        let table_id = THIS.data('table');
        $.ajax({
            url: '/processor/tableViewer',
            data: {table_id, act: 'newRecord'},
            dataType: 'json',
            type: 'post',
            success: function (json) {
                if (json) {
                    if (json.result === true) {
                        $('#table_id_' + table_id).append(json.new_record);
                        let scroll_to = '#viewer_row_' + json.record_id
                        let scroll_to_dom = $(scroll_to);
                        // console.log(scroll_to_dom);
                        // console.log(scroll_to_dom.offset());
                        $([document.documentElement, document.body]).animate({
                            scrollTop: $(scroll_to).offset().top
                        }, 100);

                        MySQLEditorInit();
                        InitMe();
                    } else {
                        alert(json.msg);
                    }
                }
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert(textStatus);
            }
        });
    });

    $('.btn-removeSelected').unbind('click');
    $('.btn-removeSelected').click(function(e)
    {
        e.preventDefault();
        let THIS = $(this);
        let checks = checkboxCollector('.recordsItemCB');
        if (checks.length > 0)
        {
            let table_id = THIS.data('table');



            $.ajax({
                url: '/processor/tableViewer',
                data: {table_id, checks, act: 'removeRecord'},
                dataType: 'json',
                type: 'post',
                success: function (json) {
                    if (json) {
                        if (json.result === true) {

                            let check = checks[0];
                            let scroll_to = $('#viewer_row_' + check)
                            $([document.documentElement, document.body]).animate({
                                scrollTop: $(scroll_to).offset().top
                            }, 100);

                            for (let index=0; index<checks.length; index++)
                            {
                                let check = checks[index]
                                // console.log(check)
                                let row = $('#viewer_row_' + check)
                                row.remove();
                            }

                        } else {
                            alert(json.msg);
                        }
                    }
                },
                error: function (jqXHR, textStatus, errorThrown)
                {
                    alert(textStatus);
                }
            });

        } else alert('Выберите строки, которые хотите удалить');
    });
}