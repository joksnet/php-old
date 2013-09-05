<?php

$options = array(
    'version' => '1',
    'rootPath' => '/desktop/',
    'hide' => array(
        'index.php',
        'icons',
        '.htaccess',
        '.htpasswd'
    )
);

//-->

$dirs = array();
$files = array();

//-->

$opendir = './'; $download = '';
$requestUri = urldecode( str_replace($options['rootPath'], '',  $_SERVER['REQUEST_URI']) );

$pathLocal = realpath('.') . '/' . $requestUri;
$pathSymLink = realpath( $requestUri );

//
// echo $requestUri . '<br />';
// echo $pathLocal . '<br />';
// echo $pathSymLink . '<br />';
//

if ( substr($requestUri, -1) == '/' )
    $requestUri = substr($requestUri, 0, -1);

if ( $pathLocal != $pathSymLink )
{
    if ( is_file($pathLocal) )
    {
        $path_parts = pathinfo($pathLocal);
        $opendir = $path_parts['dirname'];
        $download = $path_parts['basename'];
    }
    else
        $opendir = $pathLocal;
}
elseif ( is_dir($requestUri) )
    $opendir = $requestUri;
elseif ( is_file($requestUri) )
{
    $path_parts = pathinfo($requestUri);
    $opendir = $path_parts['dirname'];
    $download = $path_parts['basename'];
}

if ( substr($opendir, -1) != '/' )
    $opendir .= '/';

//-->

if ( $handle = opendir($opendir) )
{
    while ( false !== ( $file = readdir($handle) ) )
    {
        if ( $file == '.' || $file == '..' )
            continue;

        $discard = false;
        $original = $file;
        $file = $opendir . $file;

        for ( $hi = 0; $hi < sizeof($options['hide']); $hi++ )
            if ( strpos($file, $options['hide'][$hi]) !==false )
                $discard = true;

        if ( $discard )
            continue;

        $is_dir = ( @filetype($file) == 'dir' );

        if ( @filetype($file) == 'link' )
        {
            if ( realpath($file) != realpath($opendir) . "/$file" )
                $is_dir = true;
        }

        if ( $is_dir )
        {
            $n++;

            if ( $_GET['sort'] == 'date' )
                $key = @filemtime($file) . ".$n";
            else
                $key = $n;

            $dirs[$key] = "$original/";
        }
        else
        {
            $n++;

            if ( $_GET['sort'] == 'date' )
                $key = @filemtime($file) . ".$n";
            elseif ( $_GET['sort'] == 'size' )
                $key = @filesize($file) . ".$n";
            else
                $key = $n;

            $files[$key] = $original;
        }
    }

    closedir($handle);
}

if ( $_GET['sort'] == 'date')
{
    @ksort($dirs, SORT_NUMERIC);
    @ksort($files, SORT_NUMERIC);
}
elseif ( $_GET['sort'] == 'size' )
{
    @natcasesort($dirs);
    @ksort($files, SORT_NUMERIC);
}
else
{
    @natcasesort($dirs);
    @natcasesort($files);
}

if ( $_GET['order'] == 'desc' && $_GET['sort'] != 'size' )
    $dirs = @array_reverse($dirs);

if ( $_GET['order'] == 'desc' )
    $files = @array_reverse($files);

$dirs = @array_values($dirs);
$files = @array_values($files);

//-->

array_unshift($dirs, '../');

//-->

function niceSize( $size )
{
    if ( !( is_numeric($size) ) )
        $size = 0;

    if ( $size >= 1073741824 )
        $size = round($size / 1073741824) . "gb";
    elseif ( $size >= 1048756 )
        $size = round($size / 1048576) . "mb";
    elseif ( $size >= 1024 )
        $size = round($size / 1024) . "kb";
    else
        $size = $size . "b";

    return $size;
}

//-->

echo <<<HTML
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
  <title>Desktop{$options['version']}</title>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <style type="text/css">
* { margin: 0pt; padding: 0pt; }
body { padding: 10px; padding-top: 0px; }
a { text-decoration: none; }
h2 { font: 18px Geneva, Arial, Helvetica, sans-serif; padding-top: 10px; }
ul { font: 12px monospace, sans-serif; }
ul li { list-style-type: none; position: relative; padding: 6px; padding-left: 26px; }
ul li img { position: absolute; top: 6px; left: 6px; }
ul li:hover { background-color: #FFE59D; cursor: default; }
ul li.lhead { font-weight: bold; }
ul li.lhead a { text-decoration: none; color: black; }
ul li.lhead:hover { background-color: transparent; }
  </style>
</head>
<body>
HTML;

if ( sizeof($dirs) > 0 )
{
    $directory = str_replace('#', '&nbsp;', str_pad('Directory', 100, '#', STR_PAD_RIGHT) );


    echo "<h2>Directories</h2>\n<ul>\n";
    echo "  <li class=\"lhead\">{$directory}Date</li>\n";

    foreach ( $dirs as $directory )
    {
        $link = $directory;
        $icon = 'folder';

        if ( $directory == '../' )
        {
            $directory = 'Parent Directory';
            $icon = 'parent';
        }

        $original = $directory;

        $directory = str_replace($original, '', str_replace('#', '&nbsp;', str_pad($original, 100, '#', STR_PAD_RIGHT) ) );
        $dirDate = date( 'Y.m.d H:i:s', @filemtime($original) );

        echo "  <li><img src=\"{$options['rootPath']}icons/$icon.png\" alt=\"$original\" /> <a href=\"$link\" title=\"$original\">$original</a>$directory$dirDate</li>\n";
    }

    echo "</ul>\n";
}

//-->

if ( sizeof($files) > 0 )
{
    $file_order = ( ( $_GET['sort'] == 'filename' || !( isset($_GET['sort']) ) ) && $_GET['order'] != 'desc' ) ? '&amp;order=desc' : '&amp;order=asc';
    $size_order = ( ( $_GET['sort'] == 'size' ) && $_GET['order'] != 'desc' ) ? '&amp;order=desc' : '&amp;order=asc';
    $date_order = ( ( $_GET['sort'] == 'date' ) && $_GET['order'] != 'desc' ) ? '&amp;order=desc' : '&amp;order=asc';

    $file = str_replace('#', '&nbsp;', str_pad('Name', 90, '#', STR_PAD_RIGHT) );
    $file = str_replace('Name', '<a href="?sort=filename' . $file_order . '">Name</a>', $file);

    $size = str_replace('#', '&nbsp;', str_pad('Size', 10, '#', STR_PAD_RIGHT) );
    $size = str_replace('Size', '<a href="?sort=size' . $size_order . '">Size</a>', $size);

    $date = '<a href="?sort=date' . $date_order . '">Date</a>';

    echo "<h2>Files</h2>\n<ul>\n";
    echo "  <li class=\"lhead\">$file$size$date</li>\n";

    foreach ( $files as $file )
    {
        $original = $file;

        $path_parts = pathinfo($file);
        $extension = strtolower($path_parts['extension']);

        if ( file_exists('icons/' . $extension . '.png') )
            $icon = "<img src=\"{$options['rootPath']}icons/$extension.png\" alt=\"$file\" /> ";
        else
            $icon = "<img src=\"{$options['rootPath']}icons/li.png\" alt=\"$file\" /> ";

        $file = str_replace($original, '', str_replace('#', '&nbsp;', str_pad($original, 90, '#', STR_PAD_RIGHT) ) );
        $size = str_replace('#', '&nbsp;', str_pad(niceSize(@filesize($original)), 10, '#', STR_PAD_RIGHT) );
        $date = date( 'Y.m.d H:i:s', @filemtime($original) );

        echo "  <li>$icon<a href=\"$original\" title=\"$original\">$original</a>$file$size$date</li>\n";
    }

    echo "</ul>\n";
}

echo "</body>\n</html>";