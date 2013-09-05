
$(document).ready(function()
{
    $('form').each(function( form )
    {
        var container = $('.messages', form);

        $(form).validate({
            event : 'keyup',
            errorClass : 'invalid',
            errorContainer : container,
            errorLabelContainer : $('ul', container),
            wrapper : 'li'
        });
    });
});