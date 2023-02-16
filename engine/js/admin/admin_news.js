let NEWS_CURRENT_ID = -1;

$(document).ready(function (err) {
    initMe();
});

function initMe() {
    $("#form-new-news").submit(function (e) {
        e.preventDefault();

        var FORM = $('#form-new-news');
        var FORM_DATA = FORM.serializeArray();


        $.ajax({
            url: '/processor/admin_news_new',
            data: FORM_DATA,
            dataType: 'json',
            type: 'post',
            success: function (json) {
                if (json.result === true) {
                    alert(json.msg);
                    // window.location.reload();
                } else alert(json.msg);
            },
            error: function () {
            },
            complete: function () {
            }
        });

    });
}

function editNews(news_id) {

    $.ajax({
        url: '/processor/admin_news_edit',
        data: {table: news_id, act: "get"},
        dataType: 'json',
        type: 'post',
        success: function (json) {
            if (json.result === true) {
                NEWS_CURRENT_ID = news_id;
                // TODO Изменяем форму с добавлением table_id
                $('#news_id').val(json.data.news_id);
                $('#news-title').val(json.data.news_title);
                $('#news-subtitle').val(json.data.news_subtitle);
                $('#news-description').val(json.data.news_body);

                $('#news-spoiler').prop('checked', !!parseInt(json.data.news_isOpened));
                $('#news-publish').prop('checked', !!parseInt(json.data.news_publish));
                $('#news-breaking').prop('checked', !!parseInt(json.data.news_breaking));
                $('#news-version').val(json.data.news_version);
                $('#news-create-id').tab('show');


            } else alert(json.msg);
        },
        error: function () {
        },
        complete: function () {
        }
    });
}

function deleteNews(news_id) {
    if (confirm('Вы действительно желаете удалить таблицу? Она может быть НЕ ПУСТОЙ!')) {
        $.ajax({
            url: '/processor/admin_news_edit',
            data: {table: news_id, act: "remove"},
            dataType: 'json',
            type: 'post',
            success: function (json) {
                if (json.result === true) {
                    alert(json.msg);
                    window.location.reload();
                } else alert(json.msg);
            },
            error: function (e) {
                alert(e);

            },
            complete: function () {
                console.debug('deleteTable(' + table_id + '): done');
            }
        });
    }
}