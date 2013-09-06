<?php Theme::_('Header', array( 'title' => 'Proponer Estilo' )); ?>

<script type="text/javascript" src="/themes/<?php e( Config::get('siteTheme') ); ?>/scripts/jQuery.js"></script>
<script type="text/javascript" src="/themes/<?php e( Config::get('siteTheme') ); ?>/scripts/Style.js"></script>

<h1>Proponer Estilo</h1>
<form action="/style.php" method="post">
  <dl>
    <dt><label for="nombre">Nombre</label></dt>
    <dd><input type="text" name="nombre" id="nombre" /></dd>

    <dt><label>Estilo</label></dt>
    <dd>
      <div class="tabs">
        <ul>
          <li><a href="#colors">Colores</a></li>
          <li><a href="#css"><acronym title="Cascading Style Sheets">CSS</acronym></a></li>
        </ul>
        <div id="colors" style="display: none;">
          <dl>
            <dt><label for="background">Fondo</label></dt>
            <dd>#<input type="text" name="background" id="background" size="7" value="FFFFFF" /></dd>

            <dt><label for="border">Borde</label></dt>
            <dd>#<input type="text" name="border" id="border" size="7" value="FFFFFF" /></dd>

            <dt><label for="font">Fuente</label></dt>
            <dd>#<input type="text" name="font" id="font" size="7" value="000000" /></dd>

            <dt><label for="link">Enlace</label></dt>
            <dd>#<input type="text" name="link" id="link" size="7" value="5A7FE0" /></dd>
          </dl>
        </div>
        <div id="css" style="display: none;">
          <textarea rows="6" cols="69" name="style" id="style"></textarea>
        </div>
      </div>
    </dd>

    <dd>
      <input type="hidden" name="type" id="type" value="" />
      <input type="submit" class="submit" value="Crear Estilo" />
    </dd>
  </dl>
</form>

<?php Theme::_('Footer'); ?>