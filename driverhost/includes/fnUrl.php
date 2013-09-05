<?php

function dhUrl( $action, $params = array() )
{
    if ( dhConfig('useModRewrite') )
    {
        if ( $action == 'index' )
            $uri = "/";
        else
            $uri = "/$action";

        if ( $params && $action != 'index' )
            $uri .= '/' . implode('/', $params);
    }
    else
    {
        $uri = "/$action.php";

        if ( $params )
            $uri .= '?' . http_build_query($params);
    }

    return $uri;
}

function dhIsUrl( $action, $params = array() )
{
    return ( strpos($_SERVER['REQUEST_URI'], dhUrl($action, $params)) !== false );
}

function dhIsUrlArray( $array )
{
    foreach ( $array as $key => $value )
    {
        if ( is_numeric($key) && is_array($value) && isset($value['url']) )
        {
            if ( dhIsUrl($value['url'], $value['params']) )
                return $key;
        }
        elseif ( is_string($key) && is_array($value) )
        {
            if ( dhIsUrl($key, $value) )
                return $key;
        }
    }

    return false;
}

function dhRedirect( $action, $params = array() )
{
    header("Location: " . dhUrl($action, $params));
}