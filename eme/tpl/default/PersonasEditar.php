<?php Web::instance('Header'); ?>

<div id="content">
  <div id="page">
    <div class="title">
      <div class="links">
        <a href="/personas/<?php e($this->idPersonas); ?>/eliminar" class="red"><?php Lang::e('Eliminar esta persona'); ?></a>
      </div>
      <h2><?php e( Lang::__('Editar %s'), $this->data['nombre_completo'] ); ?></h2>
    </div>

<?php if ( !( $this->validationFlag ) ) : ?>
    <div class="errorExplanation">
      <h2><?php Lang::e('La persona no se pudo guardar porque el formulario contiene errores'); ?></h2>
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
      <form action="/personas/<?php e($this->idPersonas); ?>/editar" method="post">
        <p class="grey"><?php Lang::e('El nombre aparecerá con sus mensajes, comentarios y archivos y cada vez que es responsable de una tarea.'); ?></p>
        <div class="box">
          <dl>
            <dt><label for="nombre"><?php Lang::e('Nombre'); ?></label></dt>
            <dd>
              <input type="text" name="nombre" id="nombre" size="32" maxlength="60" value="<?php e( $this->data['nombre'] ); ?>" />
            </dd>

            <dt><label for="apellido"><?php Lang::e('Apellido'); ?></label></dt>
            <dd>
              <input type="text" name="apellido" id="apellido" size="32" maxlength="80" value="<?php e( $this->data['apellido'] ); ?>" />
            </dd>

            <dt><label for="correo"><?php Lang::e('Correo @'); ?></label></dt>
            <dd>
              <input type="text" name="correo" id="correo" size="45" maxlength="140" value="<?php e( $this->data['correo'] ); ?>" />
            </dd>
          </dl>
        </div>

<?php if ( $this->data['usuario'] ) : ?>
        <p class="grey"><?php Lang::e('Elija un nombre de usuario y contraseña para que esta persona pueda acceder (que puede cambiar más adelante).'); ?></p>
        <div class="box">
          <dl>
            <dt><label for="usuario"><?php Lang::e('Usuario'); ?></label></dt>
            <dd>
              <input type="text" name="usuario" id="usuario" size="32" value="<?php e($this->data['usuario']); ?>" />
            </dd>

            <dt><label for="contrasena"><?php Lang::e('Contraseña'); ?></label></dt>
            <dd>
              <input type="password" name="contrasena" id="contrasena" />
            </dd>
          </dl>
        </div>
<?php endif; ?>

        <p class="grey"><?php Lang::e('El resto es opcional, pero algunos datos de contacto le vendrán bien cuando se quiera comunicar fuera de línea.'); ?></p>
        <div class="box">
          <dl>
            <dt><label for="cargo"><?php Lang::e('Cargo'); ?></label></dt>
            <dd>
              <input type="text" name="cargo" id="cargo" size="34" maxlength="60" value="<?php e($this->data['cargo']); ?>" />
            </dd>

            <dt><label for="tel_oficina"><?php Lang::e('# Oficina'); ?></label></dt>
            <dd>
              <input type="text" name="tel_oficina" id="tel_oficina" size="20" maxlength="48" value="<?php e($this->data['tel_oficina']); ?>" />
              <?php Lang::e('int'); ?> <input type="text" name="tel_oficina_int" id="tel_oficina_int" size="5" maxlength="4" value="<?php e($this->data['tel_oficina_int']); ?>" />
            </dd>

            <dt><label for="tel_celular"><?php Lang::e('# Celular'); ?></label></dt>
            <dd>
              <input type="text" name="tel_celular" id="tel_celular" size="20" maxlength="48" value="<?php e($this->data['tel_celular']); ?>" />
            </dd>

            <dt><label for="tel_fax"><?php Lang::e('# Fax'); ?></label></dt>
            <dd>
              <input type="text" name="tel_fax" id="tel_fax" size="20" maxlength="48" value="<?php e($this->data['tel_fax']); ?>" />
            </dd>

            <dt><label for="tel_casa"><?php Lang::e('# Casa'); ?></label></dt>
            <dd>
              <input type="text" name="tel_casa" id="tel_casa" size="20" maxlength="48" value="<?php e($this->data['tel_casa']); ?>" />
            </dd>
          </dl>
        </div>

        <div class="actions">
          <input type="submit" class="submit" name="guardar" value="<?php Lang::e('Guardar'); ?>" />
          <?php Lang::e('ó'); ?> <a href="/personas/<?php e($this->idPersonas); ?>" class="red"><?php Lang::e('Cancelar'); ?></a>
        </div>

        <h2><?php Lang::e('Foto'); ?></h2>
        <p class="grey"><?php Lang::e('Puedes subir una pequeña foto (GIF, JPG, o PNG), que será remidensionada a una imagen cuadrada de 48 píxeles de lado (si no es ya que el tamaño) y que será visible al lado de la persona.'); ?></p>
        <div class="box">
          <input type="file" name="foto" id="foto" size="57" />
        </div>

<?php if ( $this->data['foto'] ) : ?>
        <div class="photo">
          <img width="48" height="48" src="/upl/avatars/<?php e($this->data['foto']); ?>" alt="<?php e($this->data['nombre_completo']); ?>" />
          <p>
            <a href="/personas/<?php e($this->idPersonas); ?>/editar/foto" class="red"><?php Lang::e('Eliminar foto'); ?></a>
          </p>
        </div>
<?php endif; ?>

        <div class="actions">
          <input type="submit" class="submit" name="subir" value="<?php Lang::e('Subir foto'); ?>" />
        </div>
      </form>
    </div>
  </div>
  <div id="bottom"><!-- --></div>
</div>

<div id="sidebar">
  <h3 class="first"><?php Lang::e('Versión original'); ?></h3>
  <div class="box">
    <div class="avatar">
<?php if ( $this->data['foto'] ) : ?>
      <img src="/upl/avatars/<?php e($this->data['foto']); ?>" alt="<?php e($this->data['nombre_completo']); ?>" />
<?php else : ?>
      <img src="/tpl/<?php e( Config::get('template') ); ?>/css/img/person.gif" alt="<?php e($this->data['nombre_completo']); ?>" />
<?php endif; ?>
    </div>
    <div class="body">
      <h4><?php e($this->data['nombre_completo']); ?></h4>
<?php if ( $this->data['cargo'] ) : ?>
      <p><?php e($this->data['cargo']); ?></p>
<?php endif; ?>
<?php if ( $this->data['correo'] ) : ?>
      <p><a href="mailto:<?php e($this->data['correo']); ?>"><?php e($this->data['correo']); ?></a></p>
<?php endif; ?>
<?php if ( $this->data['tel_oficina_completo'] ) : ?>
      <p><label>Of.</label> <?php e($this->data['tel_oficina_completo']); ?></p>
<?php endif; ?>
<?php if ( $this->data['tel_celular'] ) : ?>
      <p><label>Cel.</label> <?php e($this->data['tel_celular']); ?></p>
<?php endif; ?>
<?php if ( $this->data['tel_fax'] ) : ?>
      <p><label>Fax.</label> <?php e($this->data['tel_fax']); ?></p>
<?php endif; ?>
<?php if ( $this->data['tel_casa'] ) : ?>
      <p><label>Ca.</label> <?php e($this->data['tel_casa']); ?></p>
<?php endif; ?>
    </div>
  </div>

  <h3><?php e( Lang::__('%s puede acceder a...'), $this->data['nombre'] ); ?></h3>
  <div class="box">

  </div>
</div>

<?php Web::instance('Footer'); ?>