let home_visit, home_visit_loader, home_visit_body;


$( document ).ready(function()
{
    home_visit = $('#home_visit');
    home_visit_body = $('#home_visit_body');
    home_visit_loader = home_visit_body.html();
});

function homeVisitProcess(home_id, from_doctor=0)
{
    home_visit.modal('show');

    $.ajax({
        url: '/processor/homeVisitProcess',
        data: {act: "get", home_id, from_doctor},
        dataType: 'json',
        type: 'post',
        success: function (json) {
            if (json) {
                if (json.result === true) {
                    $('#btn_home_visit_refresh').attr('onclick', 'homeVisitProcess('+home_id+', '+from_doctor+')')
                    home_visit_body.html(json.htmlData)
                } else {
                    home_visit_body.html(json.msg);
                }
            }
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert(textStatus);
        }
    });

}

function setHomeVisitStatus(home_id, action_id, from_doctor=0)
{
    let action_comment = $('#action_comment').val();
    $.ajax({
        url: '/processor/homeVisitProcess',
        data: {act: "set_status", home_id, action_id, action_comment},
        dataType: 'json',
        type: 'post',
        success: function (json) {
            if (json) {
                if (json.result === true) {
                    homeVisitProcess(home_id, from_doctor)
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
}

function openHomeVisitEditor(home_id)
{
    let editButton = $('#myemf_button_' + home_id);
    editButton.click();
}

function removeHomeVisitActions(home_id)
{
    $.ajax({
        url: '/processor/homeVisitProcess',
        data: {act: "remove", home_id},
        dataType: 'json',
        type: 'post',
        success: function (json) {
            if (json) {
                alert(json.msg);
                if (json.result === true) {
                    window.location.reload();
                }
            }
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert(textStatus);
        }
    });
}