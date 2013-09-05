<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <title><?php e( Config::get('nombre') ); ?> &raquo; <?php Lang::e('Inicio de sesión'); ?></title>
  <link rel="stylesheet" href="/tpl/<?php e( Config::get('template') ); ?>/css/default.css" type="text/css" media="all" />
  <!--[if IE]><link rel="stylesheet" href="/tpl/<?php e( Config::get('template') ); ?>/css/default-ie.css" type="text/css" media="all" /><![endif]-->
</head>

<body>
  <div id="wrap" class="box">
    <div id="logo"<?php e( ( Config::get('logoWhite') ) ? ' class="white"' : '' ); ?>>
      <img src="<?php e( Config::get('logo') ); ?>" alt="<?php e( Config::get('nombre') ); ?>" />
    </div>
    <div id="dialog">
<?php if ( !( $this->validationFlag ) ) : ?>
      <div class="flash error">
        <span><?php Lang::e('El usuario y/o la contraseña que ha introducido no es válido.'); ?></span>
      </div>
<?php endif; ?>
      <form action="/ingresar" method="post">
        <dl>
          <dt><label for="usuario"><?php Lang::e('Usuario'); ?></label></dt>
          <dd>
            <input type="text" name="usuario" id="usuario" value="<?php e( $this->usuario ); ?>" />
          </dd>

          <dt><label for="contrasena"><?php Lang::e('Contraseña'); ?></label></dt>
          <dd>
            <input type="password" name="contrasena" id="contrasena" />
            <span>(<a href="/ingresar/amnesia"><?php Lang::e('He olvidado mi contraseña/usuario'); ?></a>)</span>
          </dd>

          <dd>
            <input type="checkbox" class="checkbox" name="recordar" id="recordar" value="1" />
            <label for="recordar"><?php Lang::e('Recordarme en este equipo'); ?></label>
          </dd>

          <dd>
            <input type="submit" class="submit" value="<?php Lang::e('Ingresar'); ?>" />
          </dd>
        </dl>
      </form>
    </div>
    <div id="below">
      <p>Copyright &copy; 2008 <a href="http://bundleweb.com.ar/" title="Software as a Service">Bundle Software</a></p>
    </div>
  </div>
</body>
</html>