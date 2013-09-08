(function( $ )
{
    /**
     * Contiene todos los modulos.
     *
     * @package i3t.modules
     * @var dictionary
     */
    i3t.modules = {};

    /**
     * Agrega funcionalidad para agregar modulos de JS dinamicamente.
     *
     * @author Juan M Martinez <joksnet@gmail.com>
     * @package i3t.modules
     */
    i3t.modular = {

        /**
         * Incluye un archivo JS al documento.
         *
         * @author Juan M Martinez <joksnet@gmail.com>
         * @package i3t.modules
         * @param string component
         */
        include : function( component )
        {
            $('head').append('<script type="text/javascript" src="/*/' + component + '/"></script>');
        },

        /**
         * Incluye el fichero si aun no ha sido incluido en el script.
         *
         * @author Juan M Martinez <joksnet@gmail.com>
         * @package i3t.modules
         * @see i3t.modules.include
         * @param string component
         */
        includeOnce : function( component )
        {
            $('script').each(function()
            {
                if ( this.src !== null && this.src.indexOf('/*/' + component + '/') > -1 )
                    return;
            });

            i3t.modular.include(component);
        },

        /**
         * @var dictionary
         */
        modules : {},

        /**
         * Carga el archivo JS si es que se cargo antes, y agrega el item en el
         * menu de navegacion.
         *
         * @author Juan M Martinez <joksnet@gmail.com>
         * @package i3t.modules
         * @param string name
         * @param string filename
         */
        append : function( name, filename )
        {
            if ( name in this.modules && this.modules[name].loaded )
                return false;

            this.modules[name] = {
                loaded      : true,
                initialized : false,
                filename    : filename
            };

            this.includeOnce(filename);

            /**
             * Lo agrego al menu de navegacion.
             */
            i3t.ui.appendNav(name);
        },

        /**
         * Initiliza el modulo si es que no fue iniciado ya.
         *
         * @author Juan M Martinez <joksnet@gmail.com>
         * @package i3t.modules
         * @param string name
         * @param array params
         */
        init : function( name, params )
        {
            if ( name in this.modules && name in i3t.modules )
            {
                if ( !( this.modules[name].initialized ) )
                {
                    this.modules[name].initialized = true;
                     i3t.modules[name].init.apply(i3t.modules[name], params || []);
                }
            }
        },

        call : function( name, params )
        {
            this.init(name);

            if ( name in i3t.modules )
            {
                i3t.modules[name].exec.apply(i3t.modules[name], params || []);
                i3t.ui.controls(i3t.modules[name].controls || {});
            }

            i3t.ui.title(name);
        },

        auto : function()
        {
            /**
             * Busco a ver que modulo se esta llamando directamente desde la
             * barra de direcciones. Lo hago con un setTimeout porque no se
             * porque hasta no salir de esta function $(function) no se setea
             * la variable i3t.modules.
             */
            setTimeout(function()
            {
                var uriString = window.location.toString();
                var uriAction = uriString.indexOf('#') != -1 ? uriString.substr( uriString.indexOf('#') + 1 ) : '';

                if ( uriAction.indexOf('/') != -1 )
                {
                    var uriParams = uriAction.split('/');
                    var uriAction = uriParams.shift();
                }

                if ( uriAction.length > 0 )
                {
                    $('div#nav li a').removeClass('selected');
                    $('div#nav li a[href="#' + uriAction + '"]').addClass('selected');

                    i3t.modular.call(uriAction, uriParams);
                }
            }, 1);
        }
    };

    if ( i3t.login )
    {
        $(function()
        {
            var modules = i3t.data.modules;

            for ( var moduleId in modules )
                i3t.modular.append(modules[moduleId].name, modules[moduleId].filename);

            i3t.modular.auto();
        });
    }
})(jQuery);