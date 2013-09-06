<?php

include_once 'includes/common.php';

if ( isset($_SESSION['UID']) )
    header('Location: panel.php');

if ( isset($_POST['username']) && isset($_POST['password']) )
{
    $sql = "SELECT user_id FROM users
            WHERE username = '{$_POST['username']}'
              AND password = '{$_POST['password']}'";

	if ( !( $result = mysql_query($sql) ) )
        error(mysql_error(), __FILE__, __LINE__);

   if ( $row = mysql_fetch_assoc($result) )
   {
        $_SESSION['UID'] = $row['user_id'];
        $_SESSION['USERNAME'] = $_POST['username'];
        $_SESSION['PASSWORD'] = $_POST['password'];

        header('Location: panel.php');
   }
}

header('Location: index.php');