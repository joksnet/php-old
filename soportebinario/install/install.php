<?php

$root = realpath( dirname( __FILE__ ) );
$root = "$root/..";

if ( !( is_readable("$root/config.php") ) )
    die('<pre><strong>Error</strong>: Falta config.php</pre>');

set_time_limit(0);

include_once "$root/config.php";

$db = mysql_connect($dbConfig['hostname'], $dbConfig['username'], $dbConfig['password']);
      mysql_select_db($dbConfig['database']);

$scheme = file_get_contents("$root/install/scheme.sql");
$lines  = explode(';', $scheme);
$i = 0;

echo '<pre>';

foreach ( $lines as $line )
{
    if ( strlen($line) > 0 )
    {
        $i++; $n = str_pad($i, 3, '0', STR_PAD_LEFT);

        if ( !( mysql_query("$line") ) )
            echo "<strong>[$n]</strong> " . mysql_error() . "<br />";
        else
            echo "<strong>[$n]</strong> OK<br />";

        flush();
    }
}

echo '<strong>[EOF]</strong></pre>';