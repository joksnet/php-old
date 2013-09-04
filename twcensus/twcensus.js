/**
 * (C) TwCensus 2010
 */

Mappy.api.map.shape.Point = Mappy.api.utils.Class(Mappy.api.geo.Coordinates,
{
    initialize : function( bP, bJ )
    {
        this.Placemark = bJ;

        var bK = bP.coordinates;
        var bG = bK.split(",");
        var x = 0, y = 0;

        if ( bG.length === 2 || bG.length === 3 )
        {
            x = parseFloat(bG[0], 10)
            y = parseFloat(bG[1], 10);
        }

        Mappy.api.geo.Coordinates.prototype.initialize.call(this, x, y);
    }
});


Mappy.api.map.shape.kml.KmlReader.prototype.points = [];

Mappy.api.map.shape.kml.KmlReader.prototype.getPoints = function( bF, bI )
{
    if ( bI !== true )
    {
        this.points = [];
    }

    for ( var bE in bF )
    {
        if ( bF.hasOwnProperty(bE) )
        {
            if ( bE === "Document" || bE === "Folder" )
            {
                var bH = $.makeArray(bF[bE]);

                for ( var bG = 0; bG < bH.length; bG += 1 )
                {
                    this.getPoints(bH[bG], true);
                }
            }

            if ( bE === "Placemark" )
            {
                this._computePlacemarkPoint(bF[bE]);
            }
        }
    }

    if ( bI !== true )
    {
        return this.points;
    }
};

Mappy.api.map.shape.kml.KmlReader.prototype._computePlacemarkPoint = function( bJ )
{
    var bF = $.makeArray(bJ);

    for ( var bH = 0; bH < bF.length; bH += 1 )
    {
        if ( typeof bF[bH].Point !== 'undefined' && bF[bH].Point !== null ) // aE()
        {
            this.points.push(
                new Mappy.api.map.shape.Point(bF[bH].Point, bF[bH])
            );
        }
    }
};

var TwCensus = {

    config : {
        root    : '',
        country : 0
    },

    map : null,
    mapMarkers : null,
    mapMarker : null,

    coordinates : [2.396579, 47.082892],

    init : function( config )
    {
        for ( var i in config )
        {
            if ( i in TwCensus.config )
                TwCensus.config[i] = config[i];
        }

        TwCensus.initMap();
    },

    initMap : function()
    {
        TwCensus.map = new Mappy.api.map.Map({
            container : '#map'
        });

        TwCensus.map.setCenter(
            new Mappy.api.geo.Coordinates(
                TwCensus.coordinates[0],
                TwCensus.coordinates[1]
            ), 3
        );

        TwCensus.map.addTool(
            new Mappy.api.map.tools.ToolBar({ move : true, zoom : true },
                new Mappy.api.map.tools.ToolPosition('lt',
                    new Mappy.api.types.Point(15, 10)
                )
            )
        );

        TwCensus.mapMarkers = new Mappy.api.map.layer.MarkerLayer(90);
        TwCensus.map.addLayer(TwCensus.mapMarkers);
    },

    initMapMarker : function( coordinates )
    {
        var coords = ( coordinates && coordinates[0] + coordinates[1] > 0 ) ? coordinates : TwCensus.coordinates;

        TwCensus.mapMarker = new Mappy.api.map.Marker(
            new Mappy.api.geo.Coordinates(coords[0], coords[1])
        );

        TwCensus.mapMarkers.addMarker(TwCensus.mapMarker);

        TwCensus.mapMarker.addDraggable();
        TwCensus.mapMarker.addListener('dragstop', function()
        {
            TwCensus.mapMarker.geocode(TwCensus.geocode);
        });

        TwCensus.mapMarker.geocode(TwCensus.geocode);
    },

    paginator : null,
    paginatorPage : 1,
    paginatorPages : 1,

    initMapList : function()
    {
        // TwCensus.map.disableScrollWheelZoom();
        // TwCensus.map.disableDraggable();
        // TwCensus.map.disableDblClickZoom();

        TwCensus.mapShapes = new Mappy.api.map.layer.ShapeLayer(80);
        TwCensus.map.addLayer(TwCensus.mapShapes);

        $.get(
            TwCensus.config.root + '/kml/' +
            TwCensus.config.country,
            TwCensus.kml
        );
    },

    initMapListArea : function( area )
    {
        if ( area.length == 0 )
            return false;

        TwCensus.mapShapes = new Mappy.api.map.layer.ShapeLayer(80);
        TwCensus.map.addLayer(TwCensus.mapShapes);

        var changePage = function()
        {
            if ( $(this).hasClass('disabled') )
                return false;

            if ( $(this).attr('id') == 'pagination_prev' )
                var newPage = ( TwCensus.paginatorPage - 1 <= 0 ) ? 1 : TwCensus.paginatorPage - 1;
            else
                var newPage = ( TwCensus.paginatorPage + 1 >= TwCensus.paginatorPages ) ? TwCensus.paginatorPages : TwCensus.paginatorPage + 1;

            $.get(
                TwCensus.config.root + '/kml/' +
                TwCensus.config.country +
                '/' + area +
                '/page' + newPage,
                TwCensus.kmlArea
            );

            return false;
        };

        TwCensus.paginator = $('<div class="paginator"></div>')
        .insertBefore(TwCensus.map.div)
        .append('<p>Il y a <b id="pagination_count">0</b> membres dans cette r&eacute;gion.</p>')
        .append(
            $('<p></p>')
            .append( $('<a href="#" id="pagination_prev" class="disabled">Ant&eacute;rieure</a>').click(changePage) )
            .append('<span id="pagination_current">1</span>')
            .append('<span> sur </span>')
            .append('<span id="pagination_pages">1</span>')
            .append( $('<a href="#" id="pagination_next" class="disabled">Suivante</a>').click(changePage) )
        );

        $.get(
            TwCensus.config.root + '/kml/' +
            TwCensus.config.country + '/' + area,
            TwCensus.kmlArea
        );

        return true;
    },

    kmlName : '',
    kmlShapes : [],
    kmlPoints : [],

    kml : function( data )
    {
        TwCensus.mapShapes.clean();
        TwCensus.mapMarkers.clean();

        var kmlJSON = Mappy.api.utils.xml2json(data);
        var kmlReader = new Mappy.api.map.shape.kml.KmlReader();

        TwCensus.kmlName = kmlJSON.kml.Document.name;
        TwCensus.kmlShapes = kmlReader.getShapes(kmlJSON.kml);

        TwCensus.mapShapes.addShape(
            TwCensus.kmlShapes[0]
        );

        var icon = new Mappy.api.ui.Icon(Mappy.api.ui.Icon.DEFAULT);

        for ( var i in TwCensus.kmlShapes )
        {
            if ( 0 == i )
                continue;

            TwCensus.mapShapes.addShape(
                TwCensus.kmlShapes[i]
            );

            TwCensus.kmlShapes[i].info = {};

            for ( var e in TwCensus.kmlShapes[i].Placemark.ExtendedData.Data )
            {
                TwCensus.kmlShapes[i].info[
                    TwCensus.kmlShapes[i].Placemark.ExtendedData.Data[e]['@attributes'].name
                ] = TwCensus.kmlShapes[i].Placemark.ExtendedData.Data[e].value;
            }

            icon.label = TwCensus.kmlShapes[i].info['total'];

            TwCensus.kmlShapes[i].marker = new Mappy.api.map.Marker(
                TwCensus.kmlShapes[i].getBounds().center, icon
            );

            TwCensus.kmlShapes[i].marker.addToolTip(TwCensus.kmlShapes[i].Placemark.name);
            TwCensus.kmlShapes[i].marker.addListener('click', (function( i )
            {
                return function()
                {
                    for ( var a in TwCensus.kmlShapes )
                        if ( 0 < a )
                            TwCensus.kmlShapes[a].marker.closePopUp();

                    TwCensus.kmlShapes[i].marker.openPopUp(
                        '<div style="height: 90px;">' +
                        '<h2>' + TwCensus.kmlShapes[i].Placemark.name + '</h2>' +
                        '<dl>' +
                        '<dt>Population</dt><dd>' + TwCensus.kmlShapes[i].info['total'] + '</dd>' +
                        '<dt>Moyenne d\'&acirc;ge</dt><dd>' + TwCensus.kmlShapes[i].info['age'] + '</dd>' +
                        '</dl>' +
                        '<p class="footer"><a href="' + TwCensus.config.root + '/' + TwCensus.kmlShapes[i].Placemark['@attributes'].id + '">Voir</a> tous les inscrits.</p>' +
                        '</div>'
                    );
                };
            })(i));

            TwCensus.mapMarkers.addMarker(TwCensus.kmlShapes[i].marker);
        }

        var bounds = TwCensus.kmlShapes[0].getBounds();

        TwCensus.map.setCenter(bounds.center,
            TwCensus.map.getBoundsZoomLevel(bounds)
        );
    },

    kmlArea : function( data )
    {
        TwCensus.mapShapes.clean();
        TwCensus.mapMarkers.clean();

        var kmlJSON = Mappy.api.utils.xml2json(data);
        var kmlReader = new Mappy.api.map.shape.kml.KmlReader();

        TwCensus.kmlName = kmlJSON.kml.Document.name;
        TwCensus.kmlShapes = kmlReader.getShapes(kmlJSON.kml);
        TwCensus.kmlPoints = kmlReader.getPoints(kmlJSON.kml);

        TwCensus.kmlShapes[0].info = {};

        for ( var e in TwCensus.kmlShapes[0].Placemark.ExtendedData.Data )
        {
            TwCensus.kmlShapes[0].info[
                TwCensus.kmlShapes[0].Placemark.ExtendedData.Data[e]['@attributes'].name
            ] = TwCensus.kmlShapes[0].Placemark.ExtendedData.Data[e].value;
        }

        TwCensus.paginatorPage = parseInt(TwCensus.kmlShapes[0].info['page']) + 0;
        TwCensus.paginatorPages = parseInt(TwCensus.kmlShapes[0].info['pages']) + 0;

        if ( TwCensus.kmlShapes[0].info['pages'] == 1 )
            TwCensus.paginator.hide();
        else
        {
            TwCensus.paginator.show();

            $('#pagination_count').html( TwCensus.kmlShapes[0].info['count'] );
            $('#pagination_current').html( TwCensus.paginatorPage );
            $('#pagination_pages').html( TwCensus.paginatorPages );

            if ( TwCensus.paginatorPage == 1 )
                $('#pagination_prev').addClass('disabled');
            else
                $('#pagination_prev').removeClass('disabled');

            if ( TwCensus.paginatorPage >= TwCensus.paginatorPages )
                $('#pagination_next').addClass('disabled');
            else
                $('#pagination_next').removeClass('disabled');
        }

        TwCensus.mapShapes.addShape(
            TwCensus.kmlShapes[0]
        );

        var iconNormal = new Mappy.api.ui.Icon({ cssClass : '', image : 'http://chart.apis.google.com/chart?chst=d_map_pin_letter_withshadow&chld=|FF6633', size : new Mappy.api.types.Size(40, 37), iconAnchor : new Mappy.api.types.Point(10, 37), popUpAnchor : new Mappy.api.types.Point(10, 0), label : '' });
        var iconFemale = new Mappy.api.ui.Icon({ cssClass : '', image : 'http://chart.apis.google.com/chart?chst=d_map_pin_icon_withshadow&chld=wc-female|FF3366', size : new Mappy.api.types.Size(40, 37), iconAnchor : new Mappy.api.types.Point(10, 37), popUpAnchor : new Mappy.api.types.Point(10, 0), label : '' });
        var iconMale   = new Mappy.api.ui.Icon({ cssClass : '', image : 'http://chart.apis.google.com/chart?chst=d_map_pin_icon_withshadow&chld=wc-male|3366FF', size : new Mappy.api.types.Size(40, 37), iconAnchor : new Mappy.api.types.Point(10, 37), popUpAnchor : new Mappy.api.types.Point(10, 0), label : '' });

        for ( var i in TwCensus.kmlPoints )
        {
            TwCensus.kmlPoints[i].info = {};

            for ( var e in TwCensus.kmlPoints[i].Placemark.ExtendedData.Data )
            {
                TwCensus.kmlPoints[i].info[
                    TwCensus.kmlPoints[i].Placemark.ExtendedData.Data[e]['@attributes'].name
                ] = TwCensus.kmlPoints[i].Placemark.ExtendedData.Data[e].value;
            }

            if ( TwCensus.kmlPoints[i].info['sex'] == '0' )
                var icon = iconNormal;
            else
                var icon = ( TwCensus.kmlPoints[i].info['sex'] == 'f' ) ? iconFemale : iconMale;

            TwCensus.kmlPoints[i].marker = new Mappy.api.map.Marker(
                TwCensus.kmlPoints[i], icon
            );

            TwCensus.kmlPoints[i].marker.addToolTip(TwCensus.kmlPoints[i].Placemark.name);
            TwCensus.kmlPoints[i].marker.addListener('click', (function( i )
            {
                return function()
                {
                    for ( var a in TwCensus.kmlPoints )
                        if ( 0 < a )
                            TwCensus.kmlPoints[a].marker.closePopUp();

                    TwCensus.kmlPoints[i].marker.openPopUp(
                        '<div style="width: 270px;">' +
                        '<div class="user">' +
                        '<a href="http://twitter.com/' + TwCensus.kmlPoints[i].Placemark['@attributes'].id + '">' +
                        '<img src="' + TwCensus.kmlPoints[i].info['image'] + '" alt="' + TwCensus.kmlPoints[i].Placemark['@attributes'].id + '" />' +
                        '<p class="name">' + TwCensus.kmlPoints[i].Placemark.name + '</p>' +
                        '</a>' +
                        '<p class="url"><a href="' + TwCensus.kmlPoints[i].info['url'] + '">' + TwCensus.kmlPoints[i].info['url'] + '</a></p>' +
                        '</div>' +
                        '<dl>' +
                        ( TwCensus.kmlPoints[i].info['sex'] != '0' ? '<dt>Sexe</dt><dd>' + ( TwCensus.kmlPoints[i].info['sex'] == 'm' ? 'Masculin' : 'F&eacute;menin' ) + '</dd>' : '' ) +
                        ( TwCensus.kmlPoints[i].info['age'] > 0 ? '<dt>&Acirc;ge</dt><dd>' + TwCensus.kmlPoints[i].info['age'] + ' ans</dd>' : '' ) +
                        '<dt>Ville</dt><dd>' + TwCensus.kmlPoints[i].info['locality_name'] + '</dd>' +
                        // '<dt>Localisation</dt><dd>' + TwCensus.kmlPoints[i].info['location'] + '</dd>' +
                        ( TwCensus.kmlPoints[i].info['description'] ? '<dt>Bio</dt><dd>' + TwCensus.kmlPoints[i].info['description'] + '</dd>' : '' ) +
                        '</dl>' +
                        '</div>'
                    );
                };
            })(i));

            TwCensus.mapMarkers.addMarker(TwCensus.kmlPoints[i].marker);
        }

        var bounds = TwCensus.kmlShapes[0].getBounds();

        TwCensus.map.setCenter(bounds.center,
            TwCensus.map.getBoundsZoomLevel(bounds)
        );
    },

    geocode : function( event )
    {
        var placemark = event[0].Placemark;

        if ( TwCensus.config.country == placemark.AddressDetails.Country.CountryNameCode.value )
        {
            var address    = placemark.name;
            var country    = placemark.AddressDetails.Country.CountryName;
            var countryISO = placemark.AddressDetails.Country.CountryNameCode.value;
            var area       = placemark.AddressDetails.Country.AdministrativeArea.AdministrativeAreaName;
            var locality   = placemark.AddressDetails.Country.AdministrativeArea.Locality.LocalityName;

            $('#address').val(address);

            $('#country').val('0');
            $('#country_name').val(country);
            $('#country_iso').val(countryISO);

            $('#area').val('0');
            $('#area_name').val(area);
            $('#area_label').html(area);

            $('#locality').val('0');
            $('#locality_name').val(locality);
            $('#locality_label').html(locality);

            $('#coord_x').val( placemark.Point.coordinates[0] );
            $('#coord_y').val( placemark.Point.coordinates[1] );

            $('#warning_country').hide();
        }
        else
        {
            $('#warning_country').show();

            /**
             * Cant't change Marker coordinates.
             *
             * TwCensus.mapMarker.coordinates = new Mappy.api.geo.Coordinates(
             *     $('#coord_x').val(),
             *     $('#coord_y').val()
             * );
             */
        }
    }
};