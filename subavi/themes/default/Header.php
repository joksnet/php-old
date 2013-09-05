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
        <img src="/images/subavi.gif" alt="<?php e( Config::get('siteName') ); ?>" />
      </a>
    </div>
    <div class="box">
      <label>Men&uacute;:</label>
      <ul>
        <li><a href="/index.html">Principal</a></li>
        <li><a href="/upload.html">Subir Subt&iacute;tulo</a></li>
      </ul>
    </div>
    <div class="box">
      <label>&Uacute;ltimos subt&iacute;tulos:</label>
      <ul>
<?php global $last; ?>
<?php foreach ( $last as $id => $file ) : ?>
        <li><a href="/<?php e( $id ); ?>.html"><?php e( $file ); ?></a></li>
<?php endforeach; ?>
      </ul>
    </div>
    <div class="box">
      <label>&Uacute;ltimas b&uacute;squedas:</label>
      <ul>
<?php global $lastSearch; ?>
<?php foreach ( $lastSearch as $id => $row ) : ?>
        <li><a href="/q/<?php e( $id ); ?>.html"><?php e( $row['query'] ); ?></a> <span class="new">(<?php e( $row['cant'] ); ?>)</span></li>
<?php endforeach; ?>
      </ul>
    </div>
    <div class="box">
      <label>Top cr&eacute;ditos:</label>
      <ul>
<?php global $topCredits; ?>
<?php foreach ( $topCredits as $name => $cant ) : ?>
        <li><a href="/u/<?php echo urlencode( $name ); ?>.html"><?php e( $name ); ?></a> <span class="new">(<?php e( $cant ); ?>)</span></li>
<?php endforeach; ?>
      </ul>
    </div>
  </div>
  <div id="widebar">
    <form action="/query.php" method="post">
      <div>
        <input type="text" name="q" size="60" tabindex="1" />
        <input type="submit" value="Buscar Subt&iacute;tulo" />
      </div>
    </form>
