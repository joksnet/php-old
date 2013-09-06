<?php

include_once 'includes/common.php';
include_once 'includes/logic.php';

header('Content-Type: text/plain');

Logic::load();

echo Logic::toString();