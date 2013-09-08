
(function( $ )
{
    /**
     * Numero de version sin los puntos y solo los primeros 5 caracteres
     * para facilitar el chequeo de la version del navegador.
     *
     * @var integer
     */
    $.browser.versionNumber = Number( $.browser.version.replace(/\./g, '').substr(0, 5) );

    /**
     * Verdadero si el navegador esta soportado, falso de cualquier otra manera
     *
     * @var boolean
     */
    i3t.isBrowserSuported = (function()
    {
        /**
         * IE no esta soportado y nunca lo va a estar.
         */
        if ( $.browser.msie )
            return false;

        /**
         * Solo Firefox 2 para arriba, no queremos ver al 1.5
         */
        if ( $.browser.mozilla )
            return ( $.browser.versionNumber >= 18100 );

        /**
         * Demas navegadores, abstenerse.
         */
        return false;
    })();

    $(function()
    {
        var location = '/i3t/';

        /**
         * Si no esta loguado, a loguearse se ah dicho...
         */
        if ( !( i3t.login ) )
            location = '/login/';

        /**
         * Creo una cookie y la busco despues, si no existe, es que las
         * cookies estan deshabilitadas en el navegador.
         */

        $.cookie('jscookietest', 'valid');

        if ( $.cookie('jscookietest') === null )
            location = '/nocookies/';

        $.cookie('jscookietest', null);

        /**
         * Si no se chequea el navegador, ya vamos a donde deberiamos.
         */
        if ( window.location.href.indexOf('nocheckbrowser') != -1 )
            window.location.href = location;
        else
        {
            /**
             * Si el navegador no esta soportado...
             */
            if ( !( i3t.isBrowserSuported ) )
                location = '/nobrowser/';

            /**
             * Por ultimo vamos a donde nos lleva el viento...
             */
            window.location.href = location;
        }
    });
})(jQuery);