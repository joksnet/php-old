<?php Theme::_('Header', array( 'title' => 'Subir Subtítulo « Subtítulos' )); ?>

<h1>Subir Subt&iacute;tulo</h1>
<p><b>NOTA:</b> El nombre del archivo debe coincidir con el nombre del archivo de video.</p>
<p>Por ejmeplo: si vas a subir el subtitulo para la pelicula <code>National.Treasure[2004]DvDrip-aXXo.avi</code>, el archivo se tiene que llamar <code>National.Treasure[2004]DvDrip-aXXo.srt</code> si es que est&aacute; en <a href="http://en.wikipedia.org/wiki/SubRip">SubRip</a>, sino con su extensi&oacute;n correspondiente.</p>
<p>Si queres subir los subtitulos para una pelicula de 2 cds, vas a tener que subir el los dos archivos por separado.</p>
<p>El sistema se encarga autom&aacute;ticamente de encontrar con que extensi&oacute;n lo subiste.</p>

<?php if ( $this->msg ) : ?>
<big class="red"><?php e( $this->msg ); ?></big>
<?php endif; ?>

<form action="upload.php" method="post" enctype="multipart/form-data">
  <dl>
    <dt><label for="sub">Archivo:</label></dt>
    <dd>
      <input type="file" name="sub" id="sub" size="50" />
    </dd>

    <dt><label for="frame">Frame rate:</label></dt>
    <dd>
      <input type="text" name="frame" id="frame" size="5" maxlength="6" />
    </dd>

    <dt><label for="comment">Comentario:</label></dt>
    <dd>
      <textarea rows="6" cols="50" name="comment" id="comment"></textarea>
    </dd>

    <dt><label for="credits">Cr&eacute;ditos:</label></dt>
    <dd>
      <input type="text" name="credits" id="credits" size="20" maxlength="64" />
    </dd>

    <dd>
      <input type="submit" value="Subir Subt&iacute;tulo" />
    </dd>
  </dl>
</form>

<?php Theme::_('Footer'); ?>