<?php

/**
 * Read an entire file into a string in th real path
 * of the app.
 *
 * @author Juan Manuel Martinez <joksnet@gmail.com>
 * @param string $fileName
 * @return string
 */
function fileGetContents( $fileName )
{
    global $realPath;

    if ( substr($fileName, 0, 1) == '/' )
        $fileName = substr($fileName, 1);

    $path = $realPath . $fileName;

    if ( @file_exists($path) )
        return file_get_contents($path);
    else
        trigger_error("The file $fileName not exists.");
}