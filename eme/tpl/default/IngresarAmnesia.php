<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <title><?php e( Config::get('nombre') ); ?> &raquo; <?php Lang::e('Inicio de sesión'); ?> &raquo; <?php Lang::e('Recuperar contraseña'); ?></title>
  <link rel="stylesheet" href="/tpl/<?php e( Config::get('template') ); ?>/css/default.css" type="text/css" media="all" />
  <!--[if IE]><link rel="stylesheet" href="/tpl/<?php e( Config::get('template') ); ?>/css/default-ie.css" type="text/css" media="all" /><![endif]-->
</head>

<body>
  <div id="wrap" class="box">
    <div id="logo"<?php e( ( Config::get('logoWhite') ) ? ' class="white"' : '' ); ?>>
      <img src="<?php e( Config::get('logo') ); ?>" alt="<?php e( Config::get('nombre') ); ?>" />
    </div>
    <div id="dialog">
      <form action="/ingresar/amnesia" method="post">
        <h2><?php Lang::e('¿Olvidó su contraseña?'); ?></h2>
        <p><?php Lang::e('Introduce tu nombre de usuario a continuación, y nosotros nos encargaremos de enviar su contraseña por correo electrónico a la dirección de correo electrónico que tenemos en archivo.'); ?></p>
        <p>
          <input type="text" name="usuario" id="usuario" />
          <input type="submit" class="submit" value="<?php Lang::e('Enviar mi contraseña'); ?>" />
          <a href="/ingresar" class="red"><?php Lang::e('Cancelar'); ?></a>
        </p>

        <h2 class="second"><?php Lang::e('¿Olvidó su nombre de usuario?'); ?></h2>
        <p><?php Lang::e('Si no recuerda su nombre de usuario, póngase en contacto con Juan Manuel Martínez.'); ?></p>
      </form>
    </div>
    <div id="below">
      <p>Copyright &copy; 2008 <a href="http://bundleweb.com.ar/" title="Software as a Service">Bundle Software</a></p>
    </div>
  </div>
</body>
</html>