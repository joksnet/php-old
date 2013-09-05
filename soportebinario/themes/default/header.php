<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
  <title><?php e( $this->titulo ); ?> | <?php e( Config::get('siteName') ); ?></title>
  <link rel="shortcut icon" href="/themes/default/favicon.ico" type="image/x-icon" />
  <link rel="stylesheet" href="/themes/default/styles/default.css" type="text/css" media="all" />
  <!--[if IE]><link rel="stylesheet" href="/themes/default/styles/default-ie.css" type="text/css" media="all" /><![endif]-->
</head>

<body>
  <div id="wrap">
    <div id="nav">
      <div id="nav-left">
        <ul>
          <li class="active"><a href="#">Inicio</a></li>
          <li><a href="#">Compa&ntilde;ias</a></li>
          <li><a href="#">Productos</a></li>
        </ul>
      </div>
      <div id="nav-right">
        <a href="#">Ingres&aacute;</a> &oacute;
        <a href="#">Registrate</a> para recibir soporte
      </div>
    </div>
    <div id="header">
      <h1><span><?php e( Config::get('siteName') ); ?></span></h1>
      <div id="tagline"><?php e( Config::get('siteTagline') ); ?></div>
      <div id="search">
        <form action="/buscar" method="get">
          <div>
            <input type="text" name="q" id="q" accesskey="f" />
            <input type="submit" class="submit" value="Buscar" />
          </div>
        </form>
      </div>
    </div>
    <div id="container">
