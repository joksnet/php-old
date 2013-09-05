
$(document).ready(function()
{
    $('.location ul li').hover(
        function() { $(this).addClass('selected'); }
      , function() { $(this).removeClass('selected'); }
    );

    /**
     * Height del Sidebar para que sea del mismo tamanio que el document
     *
     * $('.sidebar').css('height', document.body.clientHeight - parseInt( $('.location').css('height') ) );
     */

    /**
     * Agrego la funcionalidad para ocultar los mensajes
     */
    $('.messages a.dismiss').click(function(){
        $(this).parent('.messages').slideUp('slow');
    });
});