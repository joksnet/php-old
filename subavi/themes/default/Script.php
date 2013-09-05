<?php Theme::_('HeaderBasic', array( 'title' => 'Script « Subtítulos' )); ?>

<h1><?php e( Config::get('siteName') ); ?> Script</h1>
<p>Es un peque&ntilde;o script en <a href="http://python.org">Python</a>, realizado con fines educativos, para obtener subt&iacute;tulos de uno de los sitios m&aacute;s conocidos. Este recorre todas las p&aacute;ginas, las parsea, baja los archivos, los descomprime y los inserta en una base de datos.</p>
<big>
  <a href="/subavi-script.zip">Descargar</a>
</big>

<h2>Requerimientos</h2>
<ul>
  <li><a href="http://python.org">Python</a></li>
  <li><a href="http://sourceforge.net/projects/mysql-python">MySQLdb</a></li>
  <li><a href="http://www.averdevelopment.com/python/UnRAR.html">UnRar</a></li>
</ul>

<h2>Instrucciones</h2>
<ol>
  <li>Descargar el <a href="/subavi-script.zip">script</a>,</li>
  <li>Crear una nueva base de datos MySQL,</li>
  <li>Importar el archivo <acronym title="Structured Query Language">SQL</acronym> en la base de datos creada,</li>
  <li>Abrir el archivo <acronym title="Python">PY</acronym> con un editor de textos,</li>
  <li>En la linea 407, modificar los datos de conexi&oacute;n a MySQL,</li>
  <li>Correr el script desde una consola (<code>cmd.exe</code> en Windows).</li>
</ol>

<h2>&Uacute;ltimos subt&iacute;tulos</h2>
<p>Para obtener los &uacute;ltimos subt&iacute;tulos hay que emplear la funci&oacute;n <code>last()</code>. Esta obtiene dos parametros. El primero es <code>n</code>, que se refiere a cuantos subt&iacute;tulos se desea descargar. Y el segundo <code>p</code>, la p&aacute;gina por la que se desea empezar.</p>

<p><a href="/index.html">Volver</a></p>

<?php Theme::_('Footer'); ?>