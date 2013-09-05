<?php

final class Wax_Locale
{
    const PLACEHOLDER = '?';
    const PLACEHOLDER_URI = '#';

    private static $_currentLanguage = 'es_AR';
    private static $_lang = array();
    private static $_localePath = '';

    public static function __init()
    {
        self::appendFile('Index');
    }

    public static function setLocalePath( $localePath )
    {
        if ( strlen($localePath) > 0 )
            self::$_localePath = $localePath;
    }

    public static function setLanguage( $language )
    {
        if ( strlen($language) > 0 )
            self::$_currentLanguage = $language;
    }

    public static function appendFile( $langName )
    {
        if ( !( isset(self::$_lang[$langName]) ) )
        {
            $langFile = self::$_localePath
                      . '/' . self::$_currentLanguage
                      . '/' . str_replace('_', '/', $langName) . '.php';

            if ( file_exists($langFile) )
            {
                include_once $langFile;

                if ( isset($lang) )
                    self::$_lang[$langName] = $lang;
            }
        }
    }

    public static function getString( $langString, $parameters )
    {
        foreach ( self::$_lang as $langName => $currentLang )
        {
            if ( isset($currentLang[$langString]) )
                return self::parseString( $currentLang[$langString], $parameters );
        }

        return self::parseString( $langString, $parameters );
    }

    public static function parseString( $string, $parameters )
    {
        if ( strpos($string, '?') === false
          && strpos($string, '#') === false )
            return $string;

        $retval = '';

        $uri = '';
        $uriText = '';
        $uriFlag = false;

        for ( $i = 0, $l = strlen($string); $i < $l; $i++ )
        {
            $char = $string[$i];

            if ( $char == self::PLACEHOLDER )
            {
                $replace = array_shift($parameters);

                if ( strlen($replace) > 0 )
                {
                    if ( $uriFlag )
                        $uriText .= $replace;
                    else
                        $retval .= $replace;
                }
            }
            elseif ( $char == self::PLACEHOLDER_URI )
            {
                if ( $uriFlag )
                {
                    $uriFlag = false;
                    $uri = array_shift($parameters);

                    if ( strlen($uriText) > 0 )
                    {
                        $replace = Wax_Document::createElement('a')
                                 ->setAttribute('href', $uri)
                                 ->innerHTML( $uriText )
                                 ->__toString();

                        $retval .= $replace;
                    }
                }
                else
                {
                    $uriFlag = true;
                    $uriText = '';
                }
            }
            else
            {
                if ( $uriFlag )
                    $uriText .= $char;
                else
                    $retval .= $char;
            }
        }

        return $retval;
    }
}

function __( $langString )
{
    $langParams = func_get_args();
    $langString = array_shift($langParams);

    return Wax_Locale::getString($langString, $langParams);
}