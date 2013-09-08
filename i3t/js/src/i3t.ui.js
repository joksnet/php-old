(function( $ )
{
    /**
     * Se encarga de la modificacion de toda la parte estetica del sitio.
     *
     * @author Juan M Martinez <joksnet@gmail.com>
     */
    i3t.ui = {};

    /**
     * Agrega un nuevo item al menu de navegacion.
     *
     * @author Juan M Martinez <joksnet@gmail.com>
     * @package i3t.ui
     * @param string name
     * @return element <li>
     */
    i3t.ui.appendNav = function( name, text, type )
    {
        var text = text || name;
        var type = type || 'nav';

        var container = $('div#' + type + ' ul');
        var item = container.find('li a[href="#' + name + '"]');

        if ( container.length == 0 )
            var container = $('<ul></ul>').appendTo( $('div#' + type) );

        if ( item.length == 0 )
        {
            var item = $('<li>')
                .appendTo(container)
                .append(
                    $('<a href="#' + name + '"></a>')
                    .append( $('<span>' + text + '</span>') )
                    .click(function()
                    {
                        var name = $(this).attr('href').substr(1);

                        $('div#' + type + ' li a').removeClass('selected');
                        $('div#' + type + ' li a[href="#' + name + '"]').addClass('selected');

                        i3t.modular.auto();
                    })
                );
        }

        return item;
    };

    i3t.ui.title = function( title )
    {
        document.title = 'i3t :: ' + title + ' :: ' + i3t.data.username;

        $('div#page-title h3').attr('class', title).html(title);
        $('div#container').attr('class', title);
    };

    i3t.ui.controls = function( controls )
    {
        $('div#sidebar ul').remove();

        for ( var control in controls )
        {
            i3t.ui.appendNav(control, controls[control], 'sidebar');
        }
    };

    /**
     * Crea un nueva Tabla
     *
     * @author Juan M Martinez <joksnet@gmail.com>
     * @package i3t.ui
     * @param integer cellspacing
     * @param integer cellpadding
     * @return i3t.ui.Table
     */
    i3t.ui.Table = function( cellspacing, cellpadding )
    {
        return this instanceof i3t.ui.Table
            ? this.__init__(cellspacing, cellpadding)
            : new i3t.ui.Table(cellspacing, cellpadding);
    };

    i3t.ui.Table.prototype = {

        /**
         * @var element <table>
         */
        table : null,

        /**
         * @var element <thead>
         */
        tHead : null,

        /**
         * @var element <tbody>
         */
        tBody : null,

        /**
         * Constructor
         *
         * @author Juan M Martinez <joksnet@gmail.com>
         * @package i3t.ui.Table
         * @param integer cellspacing
         * @param integer cellpadding
         * @return i3t.ui.Table
         */
        __init__ : function( cellspacing, cellpadding )
        {
            cellspacing = cellspacing || 0;
            cellpadding = cellpadding || 0;

            console.log('New Table (' + cellspacing + ', ' + cellpadding + ')');

            this.table = $('<table cellspacing="' + cellspacing + '" cellpadding="' + cellpadding + '">');
            this.tHead = $('<thead>').appendTo(this.table);
            this.tBody = $('<tbody>').appendTo(this.table);
        }
    };
})(jQuery);