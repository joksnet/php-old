<?php

session_start();

if ( isset($_SESSION['UID']) )
    header('Location: panel.php');

?>
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
  <title>WLink</title>
  <link rel="shortcut icon" href="/favicon.ico" />
  <style type="text/css">
  /*<![CDATA[*/
    @import 'styles/screen.css';
  /*]]>*/
  </style>
</head>

<body>
  <div id="wrap">
    <form action="/login.php" method="post">
      <dl>
        <dt><label for="username">Username</label></dt>
        <dd><input type="text" name="username" id="username" /></dd>

        <dt><label for="password">Password</label></dt>
        <dd><input type="password" name="password" id="password" /></dd>

        <dd><input type="submit" class="submit" value="Login" /></dd>
      </dl>
    </form>
  </div>
</body>
</html>