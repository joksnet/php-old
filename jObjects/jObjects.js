(function(){
/**
 * jObject Framework
 */

jObject = function( name, args )
{
    return ( this instanceof jObject )
        ? this.__init__(name, args)
        : new jObject(name, args);
};

jObject.extend = function( source, destination )
{
    destination = destination || this;

    for ( var property in source )
        destination[property] = source[property];

    return destination;
}

//-->

jObject.extend({
    id : 0,
    name : '',
    args : {},

    __init__ : function( name, args )
    {
        this.name = name;
        this.args = args;

        var params = {'jObjects' : 1, 'name' : name }

        for ( var i in args )
            params['args[' + i + ']'] = args[i];

        var result = jObject.Ajax(jObject.options.url, params, {}, { 'asynchronous' : false })
            .request()
            .getResponse();

        if ( result )
        this.id = result['result'];
    },

    call : function( method, args )
    {
        if ( this.id > 0 )
        {
            var params = { 'jObjects' : 1, 'id' : this.id, 'name' : method }

            for ( var i in args )
                params['args[' + i + ']'] = args[i];

            var result = jObject.Ajax(jObject.options.url, params, {}, { 'asynchronous' : false })
                .request()
                .getResponse();

            return result['result'];
        }
    },

    val : function( variable, value )
    {
        if ( this.id > 0 )
        {
            var params = { 'jObjects' : 1, 'id' : this.id, 'var' : variable }

            if ( typeof value != 'undefined' )
                params['value'] = value;

            var result = jObject.Ajax(jObject.options.url, params, {}, { 'asynchronous' : false })
                .request()
                .getResponse();

            if ( typeof value == 'undefined' )
                return result['result'];
        }
    },

    die : function()
    {
        jObject.Ajax(jObject.options.url, { 'jObjects' : 1, 'id' : this.id, 'die' : 1 }, {})
            .request();
    }
}, jObject.prototype);

//-->

jObject.extend({
    version : {
        minor : 0,
        major : 3
    },
    options : {
        url : 'jObjects.php'
    }
});

//-->

jObject.Ajax = function( url, parameters, events, options )
{
    return ( this instanceof jObject.Ajax )
        ? this.__init__(url, parameters, events, options)
        : new jObject.Ajax(url, parameters, events, options);
}

jObject.extend({
    __init__ : function( url, parameters, events, options )
    {
        this.headers = { 'X-Requested-With' : 'XMLHttpRequest', 'Accept' : 'text/javascript, text/html, application/json, application/xml, text/xml, */*' }
        this.options = {
            'url'          : url,
            'parameters'   : parameters,
            'events'       : events,
            'method'       : 'post',
            'asynchronous' : true,
            'contentType'  : 'application/x-www-form-urlencoded',
            'encoding'     : 'utf-8',
            'responseType' : 'json'
        }
        this.events = {
            'onUninitialized' : function() {},
            'onLoading'       : function() {},
            'onLoaded'        : function() {},
            'onInteractive'   : function() {},
            'onComplete'      : function() {},
            'onError'         : function() {}
        }

        this.url = url;
        this.parameters = parameters;
        this.transport = jObject.Ajax.getTransport();
        this.responses = { 'text' : null, 'xml' : null, 'json' : null };
        this.complete = false;

        jObject.extend(options, this.options);
        jObject.extend(events, this.events);
    },

    setHeader    : function( name, value ) { this.headers[name] = value; return this; },
    setOption    : function( name, value ) { this.options[name] = value; return this; },
    setParameter : function( name, value ) { this.parameters[name] = value; return this; },
    setEvent     : function( name, value ) { this.events[name] = value; return this; },

    request : function()
    {
        var params = jObject.Ajax.toQueryString(this.parameters);

        if ( this.options.method == 'get' )
            this.url += ( this.url.test('?') ? '&' : '?' ) + params;
        else if ( /Konqueror|Safari|KHTML/.test(navigator.userAgent) )
            params += '&_=';

        try {
            var this_ = this;

            this.transport.open(
                this.options.method.toUpperCase(),
                this.url,
                this.options.asynchronous
            );

            this.transport.onreadystatechange = function()
            {
                var readyState = -1;

                try {
                    readyState = this_.transport.readyState;
                }
                catch ( e ) {}

                if ( readyState > jObject.Ajax.XML_READY_STATE_LOADING )
                {
                    var events = ['Uninitialized', 'Loading', 'Loaded', 'Interactive', 'Complete'];
                    var state = events[readyState];

                    switch ( readyState )
                    {
                        case jObject.Ajax.XML_READY_STATE_COMPLETED:
                            this_.complete = true;
                            this_.events['onComplete'](this_.getResponse());
                            this_.transport.onreadystatechange = function() {}
                            break;
                        default:
                            this_.events['on' + state]();
                            break;
                    }
                }
            };
            this.requestHeaders();

            this.body = ( this.options.method == 'post' ) ? ( this.options.postBody || params ) : null;
            this.transport.send(this.body);
        }
        catch ( e ) {}

        return this;
    },

    requestHeaders : function()
    {
        if ( this.options.method == 'post' )
        {
            this.headers['Content-type'] = this.options.contentType +
                ( this.options.encoding ? '; charset=' + this.options.encoding : '' );
        }

        for ( var name in this.headers )
            this.transport.setRequestHeader(name, this.headers[name]);

        return this;
    },

    getHeader : function( name )
    {
        try {
            return this.transport.getResponseHeader(name);
        }
        catch ( e ) { return null; }
    },

    getResponse : function()
    {
        var this_ = this;

        this.responses.text = this.transport.responseText;
        this.responses.xml = this.transport.responseXML;
        this.responses.json = (function ()
        {
            try {
                return ( this_.getHeader('Content-Type').search('json') )
                    ? eval('(' + this_.transport.responseText + ')')
                    : null;
            }
            catch ( e ) { return null; }
        })();

        return this.responses[this.options.responseType];
    }
}, jObject.Ajax.prototype);

jObject.extend({
    XML_READY_STATE_UNINITIALIZED : 0,
    XML_READY_STATE_LOADING       : 1,
    XML_READY_STATE_LOADED        : 2,
    XML_READY_STATE_INTERACTIVE   : 3,
    XML_READY_STATE_COMPLETED     : 4,

    getTransport : function()
    {
        var transport = null;
        var ACTIVE_X_IE_CANDIDATES = [
            "MSXML2.xmlHttpObject.5.0",
            "MSXML2.xmlHttpObject.4.0",
            "MSXML2.xmlHttpObject.3.0",
            "MSXML2.XMLHTTP",
            "MICROSOFT.xmlHttpObject.1.0",
            "MICROSOFT.xmlHttpObject.1",
            "MICROSOFT.XMLHTTP"
        ]

        if ( typeof XMLHttpRequest != 'undefined' )
            transport = new XMLHttpRequest();
        else if ( typeof ActiveXObject != 'undefined' )
        {
            for ( var i = 0; i < ACTIVE_X_IE_CANDIDATES.length; i++ )
            {
                var candidate = ACTIVE_X_IE_CANDIDATES[i];

                try {
                    transport = new ActiveXObject(candidate);
                    break;
                }
                catch ( e ) {}
            }
        }

        return transport;
    },

    toQueryString : function( source )
    {
        var queryString = [];

        for ( var property in source )
        {
            queryString.push(
                  encodeURIComponent(property) + '='
                + encodeURIComponent(source[property])
            );
        }

        return queryString.join('&');
    }
}, jObject.Ajax);

})();