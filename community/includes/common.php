<?php

require_once('db.php');
require_once('functions.php');

$db = new Conn();
$preferences = array();
$start = ( isset($_GET['start']) ) ? $_GET['start'] : ( ( isset($_POST['start']) ) ? $_POST['start'] : 0 );

load_preferences();

?>