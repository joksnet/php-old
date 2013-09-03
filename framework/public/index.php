<?php

$root = realpath(dirname(__FILE__) . '/..');

/**
 * Add library to include path.
 */
set_include_path(implode(PATH_SEPARATOR, array(
    "$root/application/controllers",
    "$root/application/models",
    "$root/application/views",
    "$root/library"
)));

