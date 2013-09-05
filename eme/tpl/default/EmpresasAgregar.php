<?php Web::instance('Header'); ?>

<div id="content">
  <div id="page">
    <div class="title">
      <h2><?php Lang::e('Agregar una nueva empresa'); ?></h2>
    </div>

<?php if ( !( $this->validationFlag ) ) : ?>
    <div class="flash error">
      <span><?php Lang::e('Debe completar el nombre de la empresa.'); ?></span>
    </div>
<?php endif; ?>

    <div class="inner">
      <form action="" method="post">
        <p><?php Lang::e('Introduzca el nombre de la empresa'); ?></p>
        <p>
          <input type="text" name="nombre" id="nombre" size="42" maxlength="96" />
        </p>
        <p>
          <input type="submit" class="submit" name="guardar" value="<?php Lang::e('Crear empresa'); ?>" />
          <?php Lang::e('ó'); ?> <a href="/empresas" class="red"><?php Lang::e('Cancelar'); ?></a>
        </p>
      </form>
    </div>
  </div>
  <div id="bottom"><!-- --></div>
</div>

<div id="sidebar">
  <h3 class="first"><?php Lang::e('Agregar una empresa'); ?></h3>
  <p><?php Lang::e('Después de agregar una empresa podrá añadir personas a esa empresa.'); ?></p>
</div>

<?php Web::instance('Footer'); ?>