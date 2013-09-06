<?php Theme::_('Header', array( 'title' => $this->title . ' « ' . Config::get('siteTagline') )); ?>

<h1><?php e($this->title); ?></h1>
<?php if ( $this->tarjetas ) : ?>
<table cellpadding="0" cellspacing="0">
  <thead>
    <tr>
      <th>#</th>
      <th>Contacto</th>
      <th>Empresa</th>
      <th>&nbsp;</th>
    </tr>
  </thead>
  <tbody>
<?php $i = 0; ?>
<?php foreach ( $this->tarjetas as $tarjeta ) : ?>
<?php $i++; ?>
    <tr<?php echo ( $i % 2 == 0 ) ? '' : ' class="alt"'; ?>>
      <td><?php echo $i; ?></td>
      <td><?php e($tarjeta['nombre']); ?></td>
      <td><?php e($tarjeta['empresa']); ?></td>
      <td><a href="/t/<?php e($tarjeta['id']); ?>.html">ver</a></td>
    </tr>
<?php endforeach; ?>
  </tbody>
</table>
<?php else : ?>
<big>no hay resultados</big>
<ul>
  <li>Comprob&aacute; lo escrito</li>
  <li>Us&aacute; nombres parciales</li>
  <li>Ingres&aacute; m&aacute;s de 2 caracteres</li>
</ul>
<?php endif; ?>

<?php Theme::_('Footer'); ?>