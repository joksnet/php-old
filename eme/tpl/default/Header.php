<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <title><?php e( Config::get('nombre') ); ?></title>
<?php Web::instance('Styles'); ?>

</head>

<body>
  <div id="wrap">
    <div id="header" class="<?php e( Config::get('theme') ); ?>">
      <ul id="info">
        <li><?php e(Session::$nombre); ?></li>
        <li class="pipe">|</li>
        <li><a href="/personas/<?php e( Session::uid() ); ?>/editar" title="<?php Lang::e('Revisar y editar la información de su cuenta'); ?>"><?php lang::e('Perfil'); ?></a></li>
        <li class="pipe">|</li>
        <li><a href="/salir"><?php Lang::e('Salir'); ?></a></li>
      </ul>
      <h1><?php e( Config::get('nombre') ); ?><span><?php e( Config::get('slogan') ); ?></span></h1>
      <ul id="tabs">
        <li><a href="/"<?php e( ( Web::$match == 'Principal' ) ? ' class="selected"' : '' ); ?>><?php Lang::e('Principal'); ?></a></li>

        <li class="right"><a href="/configuracion"<?php e( ( strncmp(Web::$match, 'Configuracion', 13) == 0 ) ? ' class="selected"' : '' ); ?>><?php Lang::e('Configuración'); ?></a></li>
        <li class="right"><a href="/buscar"><?php Lang::e('Buscar'); ?></a></li>
        <li class="right"><a href="/personas"<?php e( ( strncmp(Web::$match, 'Personas', 8) == 0 || strncmp(Web::$match, 'Empresas', 8) == 0 ) ? ' class="selected"' : '' ); ?>><?php Lang::e('Empresas y Personas'); ?></a></li>
      </ul>
    </div>
    <div id="container">
