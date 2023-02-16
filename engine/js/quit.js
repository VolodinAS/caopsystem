$(document).ready(function(){

    $('#button_quit').click(function () {
        $.removeCookie('login');
        $.removeCookie('password');
        window.location.href = '/news';
    });

    $('#button_back').click(function () {
        window.location.href = '/profile';
    });

});