<?php dhTheme('header'); ?>

<div class="breadcrumb"><a href="<?php echo dhUrl('index'); ?>"><?php echo dhLang('Principal'); ?></a> &raquo; <a href="<?php echo dhUrl('register'); ?>"><?php echo dhLang('Registrese'); ?></a></div>
<div id="tabs-wrapper" class="clear-block">
  <h2 class="with-tabs"><?php echo dhLang('Registrese'); ?></h2>
  <ul class="tabs primary">
    <li class="active"><a href="<?php echo dhUrl('register'); ?>"><?php echo dhLang('Registrese'); ?></a></li>
    <li><a href="<?php echo dhUrl('login'); ?>"><?php echo dhLang('Ingresar'); ?></a></li>
    <li><a href="<?php echo dhUrl('lostpassword'); ?>"><?php echo dhLang('Recuperar Clave'); ?></a></li>
  </ul>
</div>
<div class="clear-block">
  <p><?php echo sprintf(dhLang('Felicitaciones. Ya se encuentra registrado como %s.'), dhGet('name')); ?></p>
</div>

<?php dhTheme('footer'); ?>