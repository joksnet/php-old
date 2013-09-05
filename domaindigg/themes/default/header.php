<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=<?php echo Lang::charset(); ?>" />
  <title><?php if ( $title ) e("$title: "); ?><?php e($config['sitename']); ?></title>
  <link type="text/css" rel="stylesheet" href="<?php echo $config['root']; ?>themes/<?php echo $theme; ?>/screen.css" media="screen" />
</head>
<body id="<?php echo ( $bodyid ) ? $bodyid : 'default'; ?>">
  <div id="wrap">
    <div id="header">
      <h1><a href="<?php echo $config['root']; ?>"><?php e($config['sitename']); ?></a></h1>
    </div>
    <div id="content">
<!-- BEGIN CONTENT -->
