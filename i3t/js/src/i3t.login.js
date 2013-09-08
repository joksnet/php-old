
(function( $ )
{
    $(function()
    {
        $('#username').focus();

        /**
         * Le da funcionalidad a todos los "checkbox"s
         */
        $('.checkbox').click(function()
        {
            var input = $('input', this);

            if ( input.val() == 1 )
                $(this).removeClass('checked');
            else
                $(this).addClass('checked');

            input.val( input.val() == 0 ? 1 : 0 );
        });

        /**
         * Un lindo form submit, lastima que asi no puede tener foco.
         */
        $('.submit').click(function()
        {
            $(this).parents('form')
                   .submit();

            return false;
        });
    });
})(jQuery);