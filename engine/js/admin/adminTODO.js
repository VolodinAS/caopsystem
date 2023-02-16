$(document).ready(function () {

    setTimeout(function ()
    {
        $("#task").focus();
    }, 500);


    $('#form_newTask').on('submit', function (e) {
        e.preventDefault();
        e.stopPropagation();
        var TASK = $('#task').val();
        $.ajax({
            url: '/processor/adminNewTask',
            data: {task_text: TASK},
            dataType: 'json',
            type: 'post',
            success: function (json) {
                if (json) {

                    if (json.result === true) {
                        window.location.reload();
                    } else {
                        alert(json.msg);
                    }
                }
            }
        });
    });

    $('.prior').click(function(e)
    {
        e.preventDefault();
        var THIS = $(this);
        var TYPE = THIS.data("type");
        var ID = THIS.data("id");
        if ( ID > 0 )
        {
            $.ajax({
                url: '/processor/adminSetPrior',
                data: {prior_type: TYPE, item_id: ID},
                dataType: 'json',
                type: 'post',
                success: function (json) {
                    if (json) {

                        if (json.result === true) {
                            window.location.reload();
                        } else {
                            alert(json.msg);
                        }
                    }
                }
            });
        }
    });

    $('#selectAll').click(function () {
        $('.todoItem').prop('checked', this.checked);
    });

    $('.deleteTasks').click(function(e)
    {
        e.preventDefault();
        var THIS = $(this);
        var checkLabels = moveLabelerReturn('.todoItem');

        var tasks_ids = [];
        for (let i = 0; i < checkLabels.length; i++) {
            var ITEM = checkLabels[i];
            var PATID = ITEM.data('id');
            tasks_ids.push(PATID);
        }


        if ( tasks_ids.length > 0 )
        {
            if ( confirm('Вы действительно хотите удалить отмеченные таски?') )
            {
                $.ajax({
                    url: '/processor/adminRemoveTasks',
                    data: {tasks: tasks_ids},
                    dataType: 'json',
                    type: 'post',
                    success: function (json) {
                        if (json) {

                            if (json.result === true) {
                                window.location.reload();
                            }
                        }
                    }
                });
            }
        } else
        {
            alert('Сначала отметьте таски для удаления');
        }


    });
});