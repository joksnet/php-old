<?php dhTheme('header'); ?>

<div class="breadcrumb"><a href="<?php echo dhUrl('index'); ?>"><?php echo dhLang('Principal'); ?></a> &raquo; <a href="<?php echo dhUrl('networks'); ?>"><?php echo dhLang('Redes'); ?></a> &raquo; <a href="<?php echo dhUrl('networks', array( 'action' => 'add' )); ?>"><?php echo dhLang('Agregar'); ?></a></div>
<div id="tabs-wrapper" class="clear-block">
  <h2 class="with-tabs"><?php echo dhLang('Agregar Red'); ?></h2>
  <ul class="tabs primary">
    <li><a href="<?php echo dhUrl('networks'); ?>"><?php echo dhLang('Listar'); ?></a></li>
    <li class="active"><a href="<?php echo dhUrl('networks', array( 'action' => 'add' )); ?>"><?php echo dhLang('Agregar'); ?></a></li>
  </ul>
</div>
<?php if ( dhNValid() ) : ?>
<div class="messages error">
  <ul>
<?php if ( dhNValid('netname') ) : ?>
    <li><?php echo dhLang('Ingrese un Nombre de Red.'); ?></li>
<?php endif; ?>
  </ul>
</div>
<?php endif; ?>
<div class="clear-block">
  <form action="<?php echo dhUrl('networks', array( 'action' => 'add' )); ?>" method="post">
    <div>
      <div id="edit-netname-wrapper" class="form-item">
        <label for="edit-netname"><?php echo dhLang('Nombre de Red:'); ?><span class="form-required" title="<?php echo dhLang('Este campo es requerido.'); ?>">*</span></label>
        <input type="text" id="edit-netname" name="netname" class="form-text required<?php echo ( dhNValid('netname') ) ? ' error' : ''; ?>" maxlength="90" size="60" value="<?php echo dhPost('netname'); ?>" />
        <div class="description"><?php echo dhLang('Ej: WORKGROUP.'); ?></div>
      </div>
      <input type="submit" class="form-submit" value="<?php echo dhLang('Agregar Red'); ?>" />
    </div>
  </form>
</div>

<?php dhTheme('footer'); ?>