<?php Theme::_('Header', array( 'title' => $this->title . ' « Subtítulos' )); ?>

<h1><?php e( $this->title ); ?></h1>
<table cellpadding="0" cellspacing="0">
  <thead>
    <tr>
      <th>Nombre</th>
      <th>Frame</th>
      <th>Comentario</th>
      <th>Cr&eacute;ditos</th>
      <th>Fecha</th>
      <th>Descargas</th>
      <th>&nbsp;</th>
    </tr>
  </thead>
  <tbody>
<?php $i = 0; ?>
<?php foreach ( $this->data as $row ) : ?>
    <tr<?php echo ( $i % 2 == 0 ) ? ' class="alt"' : ''; ?>>
      <td><a href="/<?php e( $row['id'] ); ?>.html"><?php e( $row['filename'] ); ?>.<?php e( $row['ext'] ); ?></a></td>
      <td><?php e( number_format($row['frame']) ); ?> fps</td>
      <td><?php e( ( $row['comment'] ) ? $row['comment'] : '-' ); ?></td>
<?php if ( $row['credits'] ) : ?>
      <td><a href="/u/<?php echo urlencode( $row['credits'] ); ?>.html"><?php e( $row['credits'] ); ?></a></td>
<?php else : ?>
      <td>-</td>
<?php endif; ?>
      <td><?php e( date('d/m', $row['time']) ); ?></td>
      <td class="center"><?php e( number_format($row['downloads']) ); ?></td>
      <td><a href="/d/<?php e( $row['id'] ); ?>.html">descargar</a></td>
    </tr>
<?php $i++; ?>
<?php endforeach; ?>
  </tbody>
</table>

<?php Theme::_('Footer'); ?>