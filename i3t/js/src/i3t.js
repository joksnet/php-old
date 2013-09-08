(function( $ )
{
    $.noConflict();

    /**
     * El objeto principal del sistema, mantiene todo unido.
     *
     * @author Juan M Martinez <joksnet@gmail.com>
     */
    i3t = {
        /**
         * Direccion de IP, se pasa desde PHP, dado que por JS no se puede
         * obtenerla.
         *
         * @var string
         */
        ip : __ip__,

        /*
         * Configuracion general de la aplicacion
         *
         * intenal => Direccion IP interna
         * expires => Cada cuanto expira, si es cero, es la cantidad de horas
         *            asignadas a cada usuario
         * cookiePrefix => El prefijo del nombre de las cookies
         *
         * @var dictionary
         */
        config : __config__
    };

    /**
     * Almacena verdadero si se esta logueado, falso de cualquier otra manera.
     *
     * @var boolean
     */
    i3t.login = (function()
    {
        if ( $.isFunction($.cookie) )
            return ( $.cookie(i3t.config.cookiePrefix + 'sid') !== null );
        else
            return false;
    })();

    /**
     * Si se esta logueado, esta variable se guarda la informacion del usuario
     * actual.
     *
     * username => Nombre de usuario
     * viewOnly => Si esta en solo lectura
     * hours    => Cantidad de horas que le corresponde trabajar
     *
     * @var dictionary
     */
    i3t.data = (function()
    {
        if ( $.isFunction($.cookie) && i3t.login )
            return eval('(' + $.cookie(i3t.config.cookiePrefix + 'data') + ')' );
        else
            return null;
    })();

    /**
     * Contiene el ID del usuario, este se pasa como parametro a la API.
     *
     * @var integer
     */
    i3t.uid = (function()
    {
        if ( $.isFunction($.cookie) && i3t.login )
            return eval('(' + $.cookie(i3t.config.cookiePrefix + 'uid') + ')' );
        else
            return null;
    })();

    /**
     * Detecta por medios propios si se esta visitando en solo lectura.
     *
     * @var boolean
     */
    i3t.viewOnly = (function()
    {
        if ( i3t.data && i3t.data.viewOnly )
            return true;

        if ( i3t.ip.substr(0, i3t.config.internal.length) == i3t.config.internal )
            return false;

        return true;
    })();

    /**
     * Guarda la hora en la que se logueo y se la ultima actualizacion.
     */
    i3t.time = {
        login  : 0,
        logout : 0
    };

    /**
     * Para que mientras que se esta ejecutando una peticion AJAX aparezca un
     * elemento descriptivo.
     */
    $(document.body)

    /**
     * Agrego el elemento cuando se empieza con la peticion.
     */
    .ajaxStart(function()
    {
        $(document.body).append('<div id="loading">Loading...</div>').show();
    })

    /**
     * Y lo remuevo cuando la peticion finaliza.
     */
    .ajaxStop(function()
    {
        $('#loading').remove();
    });

    $(function()
    {
        /**
         * Pongo la IP en donde corresponde para verla.
         */
        $('#ip').html(i3t.ip);

        if ( i3t.viewOnly )
        {
            /**
             * Si es de solo lectura, se lo digo al mundo.
             */
            $('#foot-content ul').prepend('<li>view only</li>');

            if ( !( i3t.login ) )
            {
                /**
                 * Para esconder el campo de viewOnly en el formulario de login.
                 */
                $('#view').val(1).parents('dd').hide();
                $('[for="view"]').parent().hide();
            }
        }

        if ( i3t.login )
        {
            /*
             * Coloco el nombre de usuario
             */
            $('#userbar-content h3 span').html( i3t.data.username );

            /**
             * Le creo a todos los links que tengan en el href="#algo" un evento
             * para que funcionen.
             */
            $('a[href^=#]').click(function()
            {
                i3t.modular.auto();
            });

            setInterval(function()
            {
                if ( i3t.time.login == 0 )
                {
                    $.getJSON('/api/update/', { w : 'time' }, function( time )
                    {
                        var time = Number(time);

                        i3t.time.login  = time;
                        i3t.time.logout = new Date().getTime() / 1000;
                    });
                }
                else
                {
                    i3t.time.logout = new Date().getTime() / 1000;

                    var diff   = i3t.time.logout - i3t.time.login;
                    var format = Date.today().addSeconds(diff).toString('HH:mm:ss');

                    $('div#timer').html(format).append('<span>' + format + '</span>');
                }
            }, 1000);
        }
    });
})(jQuery);