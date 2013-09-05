<?php dhTheme('header'); ?>

<div class="breadcrumb"><a href="<?php echo dhUrl('index'); ?>"><?php echo dhLang('Principal'); ?></a> &raquo; <a href="<?php echo dhUrl('lostpassword'); ?>"><?php echo dhLang('Recuperar Clave'); ?></a></div>
<div id="tabs-wrapper" class="clear-block">
  <h2 class="with-tabs"><?php echo dhLang('Recuperar Clave'); ?></h2>
  <ul class="tabs primary">
    <li><a href="<?php echo dhUrl('register'); ?>"><?php echo dhLang('Registrese'); ?></a></li>
    <li><a href="<?php echo dhUrl('login'); ?>"><?php echo dhLang('Ingresar'); ?></a></li>
    <li class="active"><a href="<?php echo dhUrl('lostpassword'); ?>"><?php echo dhLang('Recuperar Clave'); ?></a></li>
  </ul>
</div>
<?php if ( dhNValid() ) : ?>
<div class="messages error">
  <ul>
<?php if ( dhNValid('name') ) : ?>
    <li><?php echo dhLang('Ingrese su nombre de usuario.'); ?></li>
<?php endif; ?>
  </ul>
</div>
<?php endif; ?>
<div class="clear-block">
  <form action="<?php echo dhUrl('lostpassword'); ?>" method="post">
    <div>
      <div id="edit-name-wrapper" class="form-item">
        <label for="edit-name"><?php echo dhLang('Nombre de Usuario:'); ?><span class="form-required" title="<?php echo dhLang('Este campo es requerido.'); ?>">*</span></label>
        <input type="text" id="edit-name" name="name" class="form-text required<?php echo ( dhNValid('name') ) ? ' error' : ''; ?>" maxlength="60" size="60" value="<?php echo dhPost('name'); ?>" />
        <div class="description"><?php echo dhLang('Ingrese su nombre de usuario.'); ?></div>
      </div>
      <input type="submit" class="form-submit" value="<?php echo dhLang('Recuperar Clave'); ?>" />
    </div>
  </form>
</div>

<?php dhTheme('footer'); ?>