<?php

function _e( $string ) { echo $string; }
function _u( $url ) { global $rootURL; echo "$rootURL$url"; }
function _l( $lang ) { global $_ln; if ( isset($_ln[$lang]) ) echo $_ln[$lang]; else echo $lang; }
function _t( $pee )
{
    $br = true;

    $pee = $pee . "\n"; // just to make things a little easier, pad the end
    $pee = preg_replace('|<br />\s*<br />|', "\n\n", $pee);

    // Space things out a little
    $allblocks = '(?:table|thead|tfoot|caption|colgroup|tbody|tr|td|th|div|dl|dd|dt|ul|ol|li|pre|select|form|map|area|blockquote|address|math|style|input|p|h[1-6]|hr)';
    $pee = preg_replace('!(<' . $allblocks . '[^>]*>)!', "\n$1", $pee);
    $pee = preg_replace('!(</' . $allblocks . '>)!', "$1\n\n", $pee);

    $pee = str_replace(array("\r\n", "\r"), "\n", $pee); // cross-platform newlines

    if ( strpos($pee, '<object') !== false )
    {
        $pee = preg_replace('|\s*<param([^>]*)>\s*|', "<param$1>", $pee); // no pee inside object/embed
        $pee = preg_replace('|\s*</embed>\s*|', '</embed>', $pee);
    }

    $pee = preg_replace("/\n\n+/", "\n\n", $pee); // take care of duplicates
    $pee = preg_replace('/\n?(.+?)(?:\n\s*\n|\z)/s', "<p>$1</p>\n", $pee); // make paragraphs, including one at the end
    $pee = preg_replace('|<p>\s*?</p>|', '', $pee); // under certain strange conditions it could create a P of entirely whitespace
    $pee = preg_replace('!<p>([^<]+)\s*?(</(?:div|address|form)[^>]*>)!', "<p>$1</p>$2", $pee);
    $pee = preg_replace( '|<p>|', "$1<p>", $pee );
    $pee = preg_replace('!<p>\s*(</?' . $allblocks . '[^>]*>)\s*</p>!', "$1", $pee); // don't pee all over a tag
    $pee = preg_replace("|<p>(<li.+?)</p>|", "$1", $pee); // problem with nested lists
    $pee = preg_replace('|<p><blockquote([^>]*)>|i', "<blockquote$1><p>", $pee);
    $pee = str_replace('</blockquote></p>', '</p></blockquote>', $pee);
    $pee = preg_replace('!<p>\s*(</?' . $allblocks . '[^>]*>)!', "$1", $pee);
    $pee = preg_replace('!(</?' . $allblocks . '[^>]*>)\s*</p>!', "$1", $pee);

    if ( $br )
    {
        $pee = preg_replace('/<(script|style).*?<\/\\1>/se', 'str_replace("\n", "<PreserveNewline />", "\\0")', $pee);
        $pee = preg_replace('|(?<!<br />)\s*\n|', "<br />\n", $pee); // optionally make line breaks
        $pee = str_replace('<PreserveNewline />', "\n", $pee);
    }

    $pee = preg_replace('!(</?' . $allblocks . '[^>]*>)\s*<br />!', "$1", $pee);
    $pee = preg_replace('!<br />(\s*</?(?:p|li|div|dl|dd|dt|th|pre|td|ul|ol)[^>]*>)!', '$1', $pee);

    if ( strpos($pee, '<pre') !== false )
        $pee = preg_replace_callback('!(<pre.*?>)(.*?)</pre>!is', 'cleanPre', $pee);

    $pee = preg_replace("|\n</p>$|", '</p>', $pee);

    echo $pee;
}

function getRecent( $length = 10 )
{
    settype($length, 'int');

    $sql = "SELECT id, name
            FROM errors
            ORDER BY posted DESC
            LIMIT $length";

    if ( !( $result = @mysql_query($sql) ) )
        die( mysql_error() );

    $data = array();

    while ( $row = @mysql_fetch_assoc($result) )
        $data[$row['id']] = $row['name'];

    return $data;
}

function getPopular( $length = 10 )
{
    $sql = "SELECT id, name, url
            FROM errors
            ORDER BY votes DESC
            LIMIT $length";

    if ( !( $result = @mysql_query($sql) ) )
        die( mysql_error() );

    $data = array();

    while ( $row = @mysql_fetch_assoc($result) )
    {
        $id   = $row['id'];
        $name = $row['name'];
        $url  = $row['url'];
        $tags = array();

        $sqlTags = "SELECT tag
                    FROM errors_tags
                    WHERE id = '$id'
                    ORDER BY tag
                    LIMIT 6";

        if ( !( $resultTags = @mysql_query($sqlTags) ) )
            die( mysql_error() );

        while ( $rowTags = @mysql_fetch_assoc($resultTags) )
            $tags[] = $rowTags['tag'];

        $data[$id] = array(
            'name' => $name,
            'url'  => $url,
            'tags' => $tags
        );
    }

    return $data;
}

function isValidURL( $url ) { return preg_match('|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i', $url); }
function isValidEmail( $email ) { return preg_match("/^([a-zA-Z0-9])+@([a-zA-Z0-9_-])+(\.[a-zA-Z0-9_-]+)+/", $email); }