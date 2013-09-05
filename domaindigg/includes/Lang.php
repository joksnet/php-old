<?php

class Lang
{
    protected static $current = null;

    protected static $charset = 'utf-8';
    protected static $lang = array();

    /**
     * Returns the translated text.
     *
     * @param string $code
     * @return string
     */
    public static function _( $key, $vars = array() )
    {
        if ( isset(self::$lang[$key]) )
        {
            $string = self::$lang[$key];

            if ( empty($vars) )
                return $string;

            foreach ( $vars as $i => $var )
            {
                if ( strpos($string, "\$$i") !== false )
                    $string = str_replace("\$$i", $var, $string);
                elseif ( ( $pos = strpos($string, "#$i") ) !== false )
                {
                    for ( $n = $pos + 1, $l = strlen($string); $n < $l; $n++ )
                    {
                        if ( $string[$n] == '#' )
                        {
                            $string = substr($string, 0, $n) . '</a>'
                                    . substr($string, $n + 1);

                            break;
                        }
                    }

                    $string = str_replace("#$i", '<a href="' . $var . '">', $string);
                }
            }

            return $string;
        }

        return $key;
    }

    public static function charset( $charset = null )
    {
        if ( $charset )
            self::$charset = $charset;

        return self::$charset;
    }

    /**
     * Loads a language, and sets as current.
     *
     * @param string $code
     * @return boolean
     */
    public static function load( $code )
    {
        global $root;

        if ( file_exists("$root/lang/$code.php") )
        {
            require_once "$root/lang/$code.php";

            if ( isset($charset) && isset($lang) )
            {
                self::$charset = $charset;
                self::$lang = $lang;

                return true;
            }
        }

        return false;
    }

    public static function current( $current = null )
    {
        global $root;

        if ( $current )
            self::$current = $current;
        elseif ( isset($_GET['lang']) && file_exists("$root/lang/{$_GET['lang']}.php") )
            self::$current = $_GET['lang'];

        return self::$current;
    }
}

/**
 * Alias for Lang::_().
 *
 * @param string $key
 * @return string
 */
function __( $key )
{
    return Lang::_($key);
}

/**
 * Print the translated text.
 *
 * @param string $key
 */
function _e( $key )
{
    $vars = array();

    if ( func_num_args() > 0 )
    {
        $vars = func_get_args(); array_shift($vars);
    }

    String::e( Lang::_($key, $vars) );
}