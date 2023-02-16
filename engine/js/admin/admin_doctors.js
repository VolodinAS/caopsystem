$( document ).ready(function()
{
    $('.btn-quickAccess').unbind('click');
    $('.btn-quickAccess').click(function(e)
    {
        e.preventDefault();
        let THIS = $(this);
        let quick = THIS.data('quick');
        console.log(quick);
        //https://apk7caop.ru/quick.php?4ae9fdf804e337dde182f188169fef98fc485ce79284a34b5c44a0fab2faf483e678b6e010a151e88b51639498caa1adeb8b7a302c5ef97a2215bb2939fd3f92
        window.open('/quick.php?' + quick);

    });
});