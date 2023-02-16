var FILES_DOM = "";
var MESSAGES_DOM = "";
var TEXT_DOM = "";

var TIMER_CHAT = -1;
$( document ).ready(function()
{
    FILES_DOM = $('#files');
    MESSAGES_DOM = $('#messages');
    TEXT_DOM = $('#chat');
    initPreparedFiles();
    loadMessages();

    TIMER_CHAT = 1;

    initTimer();

    initChatButtons();
});

function initPreparedFiles()
{
    $.ajax({
        url: '/processor/getPreparedFiles',
        // data: {acttype: "set", data_name: "cancerList", variables: queryObject},
        dataType: 'json',
        type: 'post',
        success: function(json){
            if(json){

                if (json.result === true)
                {
                    FILES_DOM.html( json.htmlData )
                } else
                {
                    alert(json.msg);
                }
            }
        }
    });
}

function loadMessages()
{
    $.ajax({
        url: '/processor/getMessages',
        // data: {acttype: "set", data_name: "cancerList", variables: queryObject},
        dataType: 'json',
        type: 'post',
        success: function(json){
            if(json){

                if (json.result === true)
                {
                    MESSAGES_DOM.html( json.htmlData )
                } else
                {
                    alert(json.msg);
                }
            }
        }
    });
}

function SendMessage(e)
{
    if (e.keyCode === 13 && e.ctrlKey)
    {
        SendMe();
    }
}

function SendMe() {
    if ( TEXT_DOM.val().length > 0 )
    {
        TEXT_DOM.attr("disabled", true);

        $.ajax({
            url: '/processor/sendMessage',
            data: {message: TEXT_DOM.val()},
            dataType: 'json',
            type: 'post',
            success: function(json){
                if(json){

                    if (json.result === true)
                    {
                        // MESSAGES_DOM.html( json.htmlData )
                        TEXT_DOM.attr("disabled", false);
                        TEXT_DOM.val('');
                        initPreparedFiles();
                        loadMessages();
                    } else
                    {
                        TEXT_DOM.attr("disabled", false);
                        alert(json.msg);
                    }
                }
            }
        });

    } else
    {
        alert('Сообщение не должно быть пустым')
    }
}

function initTimer()
{
    TIMER_CHAT = setInterval(function ()
    {
        loadMessages();
    }, 5000)
}

function initChatButtons()
{
    $('#btn-send').unbind('click');
    $('#btn-send').click(function(e)
    {
        e.preventDefault();
        var THIS = $(this);
        SendMe();
    });
}