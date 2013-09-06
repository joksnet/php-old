<?php Theme::_('Header', array( 'title' => $this->title )); ?>

<?php if ( $this->tarjeta ) : ?>
<h1><?php e($this->titleH1); ?></h1>
<p>Tarjeta listada <?php echo $this->tarjeta['listadas']; ?> veces; vista <?php echo $this->tarjeta['vistas']; ?> veces; descargada <?php echo $this->tarjeta['descargas']; ?> veces; con <?php echo $this->tarjeta['abusos']; ?> reportes de abuso.</p>
<div class="tarjeta <?php echo $this->clase; ?>">
  <div class="inner">
    <div class="avatar">
      <img src="http://www.gravatar.com/avatar/<?php echo $this->tarjeta['avatar']; ?>?s=80" alt="<?php e($this->title); ?>" />
    </div>
    <div class="info">
      <span class="nombre"><?php e($this->tarjeta['nombre']); ?></span>
<?php if ( $this->tarjeta['cargo'] ) : ?>
      <span class="cargo"><?php e($this->tarjeta['cargo']); ?></span>
<?php endif; ?>
<?php if ( $this->tarjeta['empresa'] ) : ?>
      <span class="empresa"><?php e($this->tarjeta['empresa']); ?></span>
<?php endif; ?>
      <span class="email">
        <img src="/<?php e($this->tarjeta['id']); ?>.png" alt="<?php e($this->title); ?>" />
      </span>
    </div>
<?php if ( $this->tarjeta['direccion1'] ) : ?>
    <div class="address">
      <span class="direccion1"><?php e($this->tarjeta['direccion1']); ?></span>
<?php if ( $this->tarjeta['direccion2'] ) : ?>
      <span class="direccion2"><?php e($this->tarjeta['direccion2']); ?></span>
<?php endif; ?>
<?php if ( $this->tarjeta['pais'] ) : ?>
      <div class="region">
<?php if ( $this->tarjeta['ciudad'] ) : ?>
        <span class="ciudad"><?php e($this->tarjeta['ciudad']); ?></span>,
<?php endif; ?>
<?php if ( $this->tarjeta['estado'] ) : ?>
        <span class="estado"><?php e($this->tarjeta['estado']); ?></span>,
<?php endif; ?>
        <span class="pais"><?php e($this->tarjeta['pais']); ?></span>
      </div>
<?php endif; ?>
    </div>
<?php endif; ?>
<?php if ( $this->tarjeta['telefono'] || $this->tarjeta['fax'] ) : ?>
    <div class="telefonos">
<?php if ( $this->tarjeta['telefono'] ) : ?>
      <span class="telefono"><label>Tel.</label><?php e($this->tarjeta['telefono']); ?></span>
<?php endif; ?>
<?php if ( $this->tarjeta['fax'] ) : ?>
      <span class="fax"><label>Fax.</label><?php e($this->tarjeta['fax']); ?></span>
<?php endif; ?>
    </div>
<?php endif; ?>
<?php if ( $this->tarjeta['web'] ) : ?>
    <div class="web">
      <a href="<?php e($this->tarjeta['web']); ?>"><?php e($this->tarjeta['web']); ?></a>
    </div>
<?php endif; ?>
  </div>
  <div class="clear"><!-- --></div>
</div>
<p>Acciones: <a href="/v/<?php e($this->tarjeta['id']); ?>.html">descargar</a>, <a href="/r/<?php e($this->tarjeta['id']); ?>.html">reportar abuso</a><!-- , <a href="/m/<?php e($this->tarjeta['id']); ?>.html">modificar</a>, <a href="/e/<?php e($this->tarjeta['id']); ?>.html">eliminar</a> -->.</p>
<?php else : ?>
<big>Contacto Inexistente</big>
<?php endif; ?>

<?php Theme::_('Footer'); ?>