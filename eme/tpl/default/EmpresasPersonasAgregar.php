<?php Web::instance('Header'); ?>

<div id="content">
  <div id="page">
    <div class="title">
      <h2><?php e( Lang::__('Agregar una personas a %s'), $this->data['nombre'] ); ?></h2>
    </div>

<?php if ( !( $this->validationFlag ) ) : ?>
    <div class="errorExplanation">
      <h2><?php Lang::e('La persona no se pudo agregar porque el formulario contiene errores'); ?></h2>
      <p><?php Lang::e('Hubo problemas con los siguientes campos:'); ?></p>
      <ul>
<?php if ( !( $this->validation['nombre']['required'] ) ) : ?>
        <li><?php Lang::e('El nombre no puede quedar en blanco.'); ?></li>
<?php endif; ?>
<?php if ( !( $this->validation['apellido']['required'] ) ) : ?>
        <li><?php Lang::e('El apellido no puede quedar en blanco.'); ?></li>
<?php endif; ?>
      </ul>
    </div>
<?php endif; ?>

    <div class="inner">
      <form action="/empresas/<?php e($this->idEmpresas); ?>/personas/agregar" method="post">
        <p class="grey"><?php Lang::e('El nombre y el apellido son datos obligatorios.'); ?></p>
        <div class="box">
          <dl>
            <dt><label for="nombre"><?php Lang::e('Nombre'); ?></label></dt>
            <dd>
              <input type="text" name="nombre" id="nombre" size="32" maxlength="60" />
            </dd>

            <dt><label for="apellido"><?php Lang::e('Apellido'); ?></label></dt>
            <dd>
              <input type="text" name="apellido" id="apellido" size="32" maxlength="80" />
            </dd>

            <dt><label for="correo"><?php Lang::e('Correo @'); ?></label></dt>
            <dd>
              <input type="text" name="correo" id="correo" size="45" maxlength="140" />
            </dd>
          </dl>
        </div>

        <p class="grey"><?php e( Lang::__('Puede también ingresar el cargo en %s de la persona.'), $this->data['nombre']); ?></p>
        <div class="box">
          <dl>
            <dt><label for="cargo"><?php Lang::e('Cargo'); ?></label></dt>
            <dd>
              <input type="text" name="cargo" id="cargo" size="34" maxlength="60" />
            </dd>
          </dl>
        </div>

        <div class="actions">
          <input type="submit" class="submit" name="guardar" value="<?php Lang::e('Agregar esta persona'); ?>" />
          <?php Lang::e('ó'); ?> <a href="/empresas/<?php e($this->idEmpresas); ?>" class="red"><?php Lang::e('Cancelar'); ?></a>
        </div>
      </form>
    </div>
  </div>
  <div id="bottom"><!-- --></div>
</div>

<div id="sidebar">
<?php if ( $this->data['logo'] ) : ?>
  <div id="logo" class="white">
    <img src="/upl/logos/<?php e($this->data['logo']); ?>" alt="<?php e($this->data['nombre']); ?>" />
  </div>
<?php endif; ?>
</div>

<?php Web::instance('Footer'); ?>