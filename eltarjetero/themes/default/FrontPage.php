<?php Theme::_('Header', array( 'title' => Config::get('siteTagline') )); ?>

<h1><?php e( Config::get('siteName') ); ?>: <?php e( Config::get('siteTagline') ); ?></h1>
<form action="/query.php" method="post">
  <dl>
    <dt><label for="nombre">Nombre</label></dt>
    <dd><input type="text" name="nombre" id="nombre" /></dd>

    <dt><label for="empresa">Empresa</label></dt>
    <dd><input type="text" name="empresa" id="empresa" /></dd>

    <dd><input type="submit" class="submit" value="Buscar Tarjeta" /></dd>
  </dl>
</form>

<?php Theme::_('Footer'); ?>