<?php

if ( !( isset($realPath) ) )
    $realPath = realpath(dirname(__FILE__)) . '/';

include_once $realPath . 'lib/inc/common.php';
include_once $realPath . 'lib/inc/io.php';
include_once $realPath . 'lib/inc/layout.php';
include_once $realPath . 'lib/inc/page.php';
include_once $realPath . 'lib/inc/xml.php';
include_once $realPath . 'lib/inc/json.php';
include_once $realPath . 'lib/inc/code.php';

$conf = array();
$lang = array();

require_once $realPath . 'conf/settings.php';

if ( @file_exists($realPath . 'conf/local.php') )
    require_once $realPath . 'conf/local.php';

require_once $realPath . 'lib/lang/en/lang.php';

if ( $conf['lang'] && $conf['lang'] != 'en' )
    require_once $realPath . 'lib/lang/' . $conf['lang'] . '/lang.php';

include_once $realPath . 'lib/inc/lang.php';

if ( !( headers_sent() ) )
    session_start();

/**
 * Kill magic quotes
 */
if ( get_magic_quotes_gpc() && !( defined('MAGIC_QUOTES_STRIPPED') ) )
{
    if ( !( empty($_GET) ) ) removeMagicQuotes($_GET);
    if ( !( empty($_POST) ) ) removeMagicQuotes($_POST);
    if ( !( empty($_COOKIE) ) ) removeMagicQuotes($_COOKIE);
    if ( !( empty($_REQUEST) ) ) removeMagicQuotes($_REQUEST);

    @ini_set('magic_quotes_gpc', 0);
    @define('MAGIC_QUOTES_STRIPPED', 1);
}

@set_magic_quotes_runtime(0);
@ini_set('magic_quotes_sybase', 0);

/**
 * Remove magic quotes recursivly
 *
 * @author Andreas Gohr <andi@splitbrain.org>
 * @param array $array
 */
function removeMagicQuotes( &$array )
{
    foreach ( array_keys($array) as $key )
    {
        if ( is_array($array[$key]) )
            removeMagicQuotes($array[$key]);
        else
            $array[$key] = stripslashes($array[$key]);
    }
}

include_once $realPath . 'lib/tpl/' . $conf['tpl'] . '/default.php';