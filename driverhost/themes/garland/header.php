<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" dir="ltr">
  <head>
    <title><?php echo dhConfig('siteName'); ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="shortcut icon" href="/themes/garland/favicon.ico" type="image/x-icon" />
<?php dhTheme('styles'); ?>
  </head>
  <body class="<?php echo ( dhIsLogin() ) ? 'sidebar-left' : ''; ?>">
    <div id="header-region" class="clear-block"></div>
    <div id="wrapper">
      <div id="container" class="clear-block">
        <div id="header">
          <div id="logo-floater"><h1><a href="<?php echo dhUrl('index'); ?>" title="<?php echo dhConfig('siteName'); ?>"><img src="/themes/garland/images/logo.png" alt="<?php echo dhConfig('siteName'); ?>" id="logo" /><span><?php echo dhConfig('siteName'); ?></span></a></h1></div>
        </div>
<?php if ( dhIsLogin() ) : ?>
        <div id="sidebar-left" class="sidebar">
          <div class="clear-block block block-user">
            <h2>joksnet</h2>
            <div class="content">
              <ul class="menu">
                <li class="leaf first<?php echo dhIsUrl('index') ? ' active-trail' : ''; ?>"><a href="<?php echo dhUrl('index'); ?>"<?php echo dhIsUrl('index') ? ' class="active"' : ''; ?>><?php echo dhLang('Principal'); ?></a></li>
<?php function headerMenu( $items, $ul = true ) { ?>
<?php if ( $ul ) : ?>
              <ul class="menu">
<?php endif; ?>
<?php foreach ( $items as $item ) : ?>
                <li class="<?php echo ( !( isset($item['items']) ) ) ? 'leaf' : ( ( dhIsUrl($item['url'], $item['params']) ) ? 'expanded' : 'collapsed' ); ?><?php echo dhIsUrl($item['url'], $item['params']) ? ' active-trail' : ''; ?>">
                  <a href="<?php echo dhUrl($item['url'], $item['params']); ?>"<?php echo dhIsUrl($item['url'], $item['params']) ? ' class="active"' : ''; ?>><?php echo dhLang($item['label']); ?></a>
<?php if ( isset($item['items']) && ( dhIsUrl($item['url'], $item['params']) || dhIsUrlArray($item['items']) !== false ) ) headerMenu($item['items']); ?>
                </li>
<?php endforeach; ?>
<?php if ( $ul ) : ?>
              </ul>
<?php endif; ?>
<?php } ?>
<?php headerMenu( dhMenu(), false ); ?>
                <li class="leaf last"><a href="<?php echo dhUrl('logout'); ?>" class="logout"><?php echo dhLang('Salir'); ?></a></li>
              </ul>
            </div>
          </div>
        </div>
<?php endif; // dhIsLogin() ?>
        <div id="center">
          <div id="squeeze">
            <div class="right-corner">
              <div class="left-corner">