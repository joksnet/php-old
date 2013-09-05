<?php

/**
 * @author Juan M Martinez <joksnet@gmail.com>
 */
class Theme
{
    /**
     * @var string
     */
    protected $name = '';

    /**
     * __construct()
     */
    public function __construct()
    {
        $this->name = Config::get('siteTheme');
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

        if ( is_readable("$root/themes/$this->name/$theme.php") )
            require_once "$root/themes/$this->name/$theme.php";
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