<?php dhTheme('header'); ?>

<?php if ( dhHasGet('n') ) $network = dhGlobal('network'); ?>
<div class="breadcrumb">
  <a href="<?php echo dhUrl('index'); ?>"><?php echo dhLang('Principal'); ?></a>
  &raquo; <a href="<?php echo dhUrl('networks'); ?>"><?php echo dhLang('Redes'); ?></a>
<?php if ( $network ) : ?>
  &raquo; <a href="<?php echo dhUrl('networks', array('n' => $network['id_networks'])); ?>"><?php echo $network['netname']; ?></a>
<?php endif; ?>
</div>
<?php if ( $network ) : ?>
<div id="tabs-wrapper" class="clear-block">
  <h2 class="with-tabs"><?php echo $network['netname']; ?></h2>
  <ul class="tabs primary">
    <li class="active"><a href="<?php echo dhUrl('networks', array( 'n' => $network['id_networks'] )); ?>"><?php echo dhLang('Ver'); ?></a></li>
    <li><a href="<?php echo dhUrl('networks', array( 'n' => $network['id_networks'], 'action' => 'edit' )); ?>"><?php echo dhLang('Editar'); ?></a></li>
    <li><a href="<?php echo dhUrl('networks', array( 'n' => $network['id_networks'], 'action' => 'delete' )); ?>"><?php echo dhLang('Eliminar'); ?></a></li>
  </ul>
</div>
<div class="clear-block">
 Content
</div>
<?php else : ?>
<div id="tabs-wrapper" class="clear-block">
  <h2 class="with-tabs"><?php echo dhLang('Redes'); ?></h2>
  <ul class="tabs primary">
    <li class="active"><a href="<?php echo dhUrl('networks'); ?>"><?php echo dhLang('Listar'); ?></a></li>
    <li><a href="<?php echo dhUrl('networks', array( 'action' => 'add' )); ?>"><?php echo dhLang('Agregar'); ?></a></li>
  </ul>
</div>
<?php if ( dhHasGet('success') || dhHasGet('edit') || dhHasGet('delete') ) : ?>
<div class="messages">
  <ul>
<?php if ( dhHasGet('success') ) : ?>
    <li><?php echo sprintf(dhLang('Se agrego la Red %s exitosamente.'), urldecode( dhGet('success') )); ?></li>
<?php endif; ?>
<?php if ( dhHasGet('edit') ) : ?>
    <li><?php echo sprintf(dhLang('Se modifico la Red %s exitosamente.'), urldecode( dhGet('edit') )); ?></li>
<?php endif; ?>
<?php if ( dhHasGet('delete') ) : ?>
    <li><?php echo sprintf(dhLang('Se elimino la Red %s exitosamente.'), urldecode( dhGet('delete') )); ?></li>
<?php endif; ?>
  </ul>
</div>
<?php endif; ?>
<div class="clear-block">
<?php $networks = dhNetworks(); ?>
<?php foreach ( $networks as $network ) : ?>
  <a href="<?php echo dhUrl('networks', array('n' => $network['id_networks'])) ?>" class="network">
    <img src="/themes/garland/images/network.png" alt="<?php echo $network['netname']; ?>" />
    <label><?php echo $network['netname']; ?></label>
  </a>
<?php endforeach; ?>
</div>
<?php endif; // $network ?>

<?php dhTheme('footer'); ?>