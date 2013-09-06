<?php

include_once 'includes/common.php';

unset($_SESSION['UID']);
unset($_SESSION['USERNAME']);
unset($_SESSION['PASSWORD']);

session_destroy();

header('Location: index.php');