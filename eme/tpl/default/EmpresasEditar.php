<?php Web::instance('Header'); ?>

<div id="content">
  <div id="page">
    <div class="title">
      <div class="links">
        <a href="/empresas/<?php e($this->idEmpresas); ?>/eliminar" class="red"><?php Lang::e('Eliminar esta empresa'); ?></a>
      </div>
      <h2><?php e( Lang::__('Editar %s'), $this->data['nombre'] ); ?></h2>
    </div>

<?php if ( !( $this->validationFlag ) ) : ?>
    <div class="flash error">
      <span><?php Lang::e('Debe completar el nombre de la empresa.'); ?></span>
    </div>
<?php endif; ?>

    <div class="inner">
      <form action="" method="post">
        <p class="grey"><?php Lang::e('Solo el nombre es obligatorio, pero algunos datos de contacto le vendrán bien cuando se quiera comunicar fuera de línea.'); ?></p>
        <div class="box">
          <dl>
            <dt><label for="nombre"><?php Lang::e('Nombre'); ?></label></dt>
            <dd>
              <input type="text" name="nombre" id="nombre" size="32" maxlength="96" value="<?php e($this->data['nombre']); ?>" />
            </dd>

            <dt><label for="direccion_1"><?php Lang::e('Dirección'); ?></label></dt>
            <dd>
              <input type="text" name="direccion_1" id="direccion_1" size="45" maxlength="140" value="<?php e($this->data['direccion_1']); ?>" />
            </dd>
            <dd>
              <input type="text" name="direccion_2" id="direccion_2" size="45" maxlength="140" value="<?php e($this->data['direccion_2']); ?>" />
            </dd>

            <dt><label for="ciudad"><?php Lang::e('Ciudad'); ?></label></dt>
            <dd>
              <input type="text" name="ciudad" id="ciudad" size="22" maxlength="64" value="<?php e($this->data['ciudad']); ?>" />
            </dd>

            <dt><label for="estado"><?php Lang::e('Estado'); ?></label></dt>
            <dd>
              <input type="text" name="estado" id="estado" size="22" maxlength="64" value="<?php e($this->data['estado']); ?>" />
            </dd>

            <dt><label for="cod_postal"><?php Lang::e('Cod. Postal'); ?></label></dt>
            <dd>
              <input type="text" name="cod_postal" id="cod_postal" size="12" maxlength="15" value="<?php e($this->data['cod_postal']); ?>" />
            </dd>

            <dt><label for="id_paises"><?php Lang::e('País'); ?></label></dt>
            <dd>
              <select name="id_paises" id="id_paises">
<?php foreach ( $this->paises as $idPaises => $pais ) : ?>
                <option value="<?php e($idPaises); ?>"<?php e( ( $idPaises == $this->data['id_paises'] ) ? ' selected="selected"' : '' ); ?>><?php e($pais); ?></option>
<?php endforeach; ?>
              </select>
            </dd>

            <dt><label for="web"><?php Lang::e('Web'); ?></label></dt>
            <dd>
              <input type="text" name="web" id="web" size="45" maxlength="160" value="<?php e($this->data['web']); ?>" />
            </dd>

            <dt><label for="tel_oficina"><?php Lang::e('# Oficina'); ?></label></dt>
            <dd>
              <input type="text" name="tel_oficina" id="tel_oficina" size="20" maxlength="48" value="<?php e($this->data['tel_oficina']); ?>" />
            </dd>

            <dt><label for="tel_fax"><?php Lang::e('# Fax'); ?></label></dt>
            <dd>
              <input type="text" name="tel_fax" id="tel_fax" size="20" maxlength="48" value="<?php e($this->data['tel_fax']); ?>" />
            </dd>
          </dl>
        </div>

        <div class="actions">
          <input type="submit" class="submit" name="guardar" value="<?php Lang::e('Guardar'); ?>" />
          <?php Lang::e('ó'); ?> <a href="/empresas/<?php e($this->idEmpresas); ?>" class="red"><?php Lang::e('Cancelar'); ?></a>
        </div>

        <h2><?php Lang::e('Logo'); ?></h2>
        <p class="grey"><?php Lang::e('El logo debe tener formato GIF, JPG, o PNG y no puede ser mayor a 200 pixeles de ancho.'); ?></p>
        <div class="box">
          <input type="file" name="logo" id="logo" size="57" />
        </div>

<?php if ( $this->data['logo'] ) : ?>
        <div class="photo">
          <img src="/upl/logos/<?php e($this->data['logo']); ?>" alt="<?php e($this->data['nombre']); ?>" />
          <p>
            <a href="/empresas/<?php e($this->idEmpresas); ?>/editar/logo" class="red"><?php Lang::e('Eliminar logo'); ?></a>
          </p>
        </div>
<?php endif; ?>

        <div class="actions">
          <input type="submit" class="submit" name="subir" value="<?php Lang::e('Subir logo'); ?>" />
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
  <h3 class="first"><?php Lang::e('Versión original'); ?></h3>
  <div class="box">
    <h4><?php e($this->data['nombre']); ?></h4>
    <p><?php e($this->data['direccion']); ?></p>
    <p><?php e($this->data['lugar']); ?></p>
<?php if ( $this->data['tel_oficina'] ) : ?>
    <p><label>Of.</label> <?php e($this->data['tel_oficina']); ?></p>
<?php endif; ?>
<?php if ( $this->data['tel_fax'] ) : ?>
    <p><label>Fax.</label> <?php e($this->data['tel_fax']); ?></p>
<?php endif; ?>
<?php if ( $this->data['web'] ) : ?>
    <p><a href="<?php e($this->data['web']); ?>"><?php e($this->data['web']); ?></a></p>
<?php endif; ?>
  </div>
</div>

<?php Web::instance('Footer'); ?>