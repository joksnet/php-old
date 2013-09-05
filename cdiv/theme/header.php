<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
  <title>cdiv</title>
  <link rel="shortcut icon" href="<?php _u('/theme/favicon.ico'); ?>" />
  <link rel="stylesheet" href="<?php _u('/theme/cdiv.css'); ?>" type="text/css" media="all" />
</head>

<body>
  <div id="wrap">
    <div id="header">
      <h1><a href="<?php _u('/'); ?>">cdiv</a></h1>
      <div id="nav">
        <ul>
          <li><a href="<?php _u('/'); ?>"<?php echo ( !( defined('PAGE_POST') || defined('RAND') ) ) ? ' class="current"' : ''; ?>><?php _l('Home'); ?></a></li>
          <li><a href="<?php _u('/random'); ?>"<?php echo ( defined('RAND') ) ? ' class="current"' : ''; ?>><?php _l('Random'); ?></a></li>
          <li><a href="<?php _u('/post'); ?>"<?php echo ( defined('PAGE_POST') ) ? ' class="current"' : ''; ?>><?php _l('Post'); ?></a></li>
        </ul>
      </div>
    </div>
    <div id="container">
      <div id="content">
