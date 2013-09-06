<?php

/**
 * @author Juan M Martinez <joksnet@gmail.com>
 */
class Theme
{
    /**
     * __construct()
     */
    public function __construct()
    {
        //
    }

    /**
     * @param string $key
     * @param mixed $value
     */
    public function set( $key, $value )
    {
        $this->{$key} = $value;
    }

    /**
     * @param string $theme
     */
    public function dispatch( $theme )
    {
        global $root;

        $name = Config::get('siteTheme');

        if ( is_readable("$root/themes/$name/$theme.php") )
            $include = "$root/themes/$name/$theme.php";
        elseif ( is_readable("$root/themes/default/$theme.php") )
            $include = "$root/themes/default/$theme.php";
        else
            return;

        require_once $include;
    }

    /**
     * @param string $theme
     * @param array $vars
     */
    public static function _( $theme, $vars = array() )
    {
        $instance = new Theme();

        foreach ( $vars as $key => $value )
            $instance->set($key, $value);

        $instance->dispatch($theme);
    }
}