<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <title><?php echo Config::get('nombre'); ?> &raquo; <?php Lang::e('La página que buscas no existe.'); ?></title>
  <link rel="stylesheet" href="/tpl/<?php e( Config::get('template') ); ?>/css/default.css" type="text/css" media="all" />
  <!--[if IE]><link rel="stylesheet" href="/tpl/<?php e( Config::get('template') ); ?>/css/default-ie.css" type="text/css" media="all" /><![endif]-->
</head>

<body>
  <div id="wrap" class="box">
    <div id="logo"<?php e( ( Config::get('logoWhite') ) ? ' class="white"' : '' ); ?>>
      <img src="<?php echo Config::get('logo'); ?>" alt="<?php echo Config::get('nombre'); ?>" />
    </div>
    <div id="dialog">
      <p><?php Lang::e('La página que buscas no existe.'); ?></p>
      <p><?php Lang::e('Usted puede tener un error o la dirección de la página se han movido.'); ?></p>
      <p><a href="/" onclick="history.back(); return false;" title="<?php Lang::e('Volver a la página anterior'); ?>"><?php Lang::e('Volver a la página anterior'); ?></a></p>
    </div>
    <div id="below">
      <p>Copyright &copy; 2008 <a href="http://bundleweb.com.ar/" title="Software as a Service">Bundle Software</a></p>
    </div>
  </div>
</body>
</html>