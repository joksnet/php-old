
$(function()
{
    var selected = window.location.href.substr( window.location.href.indexOf('#') + 1 );

    if ( selected )
    {
        $('a[href^="#' + selected + '"]').parent().addClass('active');
        $('div#' + selected + '').css('display', 'block');
        $('input#type').val(selected);
    }

    $('a[href^="#colors"], a[href^="#css"]').click(function()
    {
        $('a[href^="#colors"], a[href^="#css"]').each(function()
        {
            $(this).parent().removeClass('active');
            $('div#' + $(this).attr('href').substr( $(this).attr('href').indexOf('#') + 1 ) + '').css('display', 'none');
        });

        var type = $(this).attr('href').substr( $(this).attr('href').indexOf('#') + 1 );

        $(this).parent().addClass('active');
        $('div#' + type + '').css('display', 'block');
        $('input#type').val(type);

        return false;
    });
});