<?php

include_once 'includes/common.php';

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
    <div id="left">
<?php menu(); ?>
    </div>
    <div id="right">
      <div id="graph">
        <img src="graph.php" alt="" />
      </div>
    </div>
    <div class="clear"><!-- --></div>
  </div>
</body>
</html>