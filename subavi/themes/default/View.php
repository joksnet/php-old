<?php Theme::_('Header', array( 'title' => $this->filename . ' « Subtítulos' )); ?>

<h1><?php e( $this->filename ); ?></h1>

<p>
  Subido
<?php if ( $this->credits ) : ?>
  por <a href="/u/<?php echo urlencode( $this->credits ); ?>.html"><?php e( $this->credits ); ?></a>
<?php endif; ?>
  el <?php e( date('d/m', $this->time) ); ?>;
  descargado <?php e( number_format($this->downloads) ); ?> veces;
  <?php e( number_format($this->frame) ); ?> fps
  en formato <a href="http://en.wikipedia.org/wiki/<?php e( Formats::get( $this->ext ) ); ?>"><?php e( Formats::get( $this->ext ) ); ?></a>.
</p>

<?php if ( $this->comment ) : ?>
<p><?php e( $this->comment ); ?></p>
<?php endif; ?>

<big>
  <a href="/d/<?php e( $this->id ); ?>.html">Descargar</a>
</big>

<?php Theme::_('Footer'); ?>