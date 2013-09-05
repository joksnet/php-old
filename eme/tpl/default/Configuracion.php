<?php Web::instance('Header'); ?>

<div id="content">
  <div id="page">
    <div class="title">
      <strong><?php Lang::e('Configuración'); ?></strong> |
      <a href="/configuracion/apariencia"><?php Lang::e('Apariencia'); ?></a>
    </div>

    <div class="inner">
      <form action="/configuracion" method="post">
        <!-- p class="grey"><?php Lang::e('Su logotipo aparecerá en la pantalla de ingreso, y la página principal. Debe estar en formato GIF, JPG o PNG y no puede ser de más de 200 píxeles de ancho. Si desea que el logotipo de la mezcle con el fondo, debería poner el logotipo sobre un fondo gris con el color hexadecimal #e5e5e5 y luego subir esa versión del logotipo.'); ?></p -->

        <h2><?php Lang::e('Nombre del sitio'); ?></h2>
        <p class="grey"><?php Lang::e('El nombre y el slogan aparecerán en la parte superior de cada página.'); ?></p>
        <div class="box">
          <dl>
            <dt><label for="nombre">Nombre</label></dt>
            <dd>
              <input type="text" name="nombre" id="nombre" size="32" maxlength="140" value="<?php e( Config::get('nombre') ); ?>" />
            </dd>

            <dt><label for="slogan">Slogan</label></dt>
            <dd>
              <input type="text" name="slogan" id="slogan" size="32" maxlength="140" value="<?php e( Config::get('slogan') ); ?>" />
            </dd>
          </dl>
        </div>

        <div class="actions">
          <input type="submit" class="submit" name="guardar" value="<?php Lang::e('Guardar'); ?>" />
          <?php Lang::e('ó'); ?> <a href="/configuracion" class="red"><?php Lang::e('Cancelar'); ?></a>
        </div>
      </form>
    </div>
  </div>
  <div id="bottom"><!-- --></div>
</div>

<div id="sidebar">

</div>

<?php Web::instance('Footer'); ?>