<?php

define('LIB_PATH', dirname(__FILE__) . '/../lib');
define('BIN_PATH', dirname(__FILE__) . '/../bin');

set_include_path(get_include_path()
  . PATH_SEPARATOR . LIB_PATH
);

include_once 'dev.php';

Dev::getInstance()
  ->add('polls')
  ->dispatch();
