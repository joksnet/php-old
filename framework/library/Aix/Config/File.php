<?php
/**
 * Aix Framework
 * Copyright (c) 2009, Juan M Mart('i)nez
 */

/**
 * @see Aix_Config
 */
require_once 'Aix/Config.php';

/**
 * @package Aix_Config
 */
class Aix_Config_File extends Aix_Config
{
    /**
     * @param string $file
     */
    public function __construct( $file )
    {
        parent::__construct(
            require $file
        );
    }
}