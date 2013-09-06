<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
  <meta name="description" content="<?php e( Config::get('siteDescription') ); ?>" />
  <meta name="keywords" content="<?php e( Config::get('siteKeywords') ); ?>" />
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
  <title><?php e( $this->title ); ?> | <?php e( Config::get('siteName') ); ?></title>
  <link rel="stylesheet" href="/themes/<?php e( Config::get('siteTheme') ); ?>/styles/all.css" type="text/css" media="all" />
  <link rel="shortcut icon" type="image/x-icon" href="/favicon.ico" />
</head>

<body>
  <div id="sidebar">
    <div class="box">
      <a href="/" title="<?php e( Config::get('siteName') ); ?>" id="logo">
        <img src="/images/eltarjetero.gif" alt="<?php e( Config::get('siteName') ); ?>" />
      </a>
    </div>
    <div class="box">
      <label>Men&uacute;:</label>
      <ul>
        <li><a href="/index.html">Principal</a></li>
      </ul>
    </div>
    <div class="box">
      <label>por Nombre</label>
<?php global $letters; ?>
<?php foreach ( $letters as $letter => $n ) : ?>
        <a href="/l/n/<?php echo $letter; ?>.html" class="letter"><?php echo $letter; ?></a>
<?php endforeach; ?>
    </div>
    <div class="box">
      <label>por Empresa</label>
<?php global $letters; ?>
<?php foreach ( $letters as $letter => $n ) : ?>
        <a href="/l/e/<?php echo $letter; ?>.html" class="letter"><?php echo $letter; ?></a>
<?php endforeach; ?>
    </div>
  </div>
  <div id="widebar">
