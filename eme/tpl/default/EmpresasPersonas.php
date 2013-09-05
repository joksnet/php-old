<?php Web::instance('Header'); ?>

<div id="content" class="full">
  <div id="page">
    <div class="title">
      <div class="links">
        <a href="/empresas/<?php e($this->idEmpresas); ?>/personas/agregar" class="red"><?php Lang::e('Agregar una persona'); ?></a> |
        <a href="/empresas/<?php e($this->idEmpresas); ?>/editar" class="red"><?php Lang::e('Editar'); ?></a>
      </div>
      <h2><?php e( Lang::__('Personas en %s'), $this->data['nombre'] ); ?></h2>
    </div>

<div class="company">
<?php if ( $this->data['personas'] ) : ?>
<?php foreach ( $this->data['personas'] as $idPersonas => $persona ) : ?>
  <div class="column">
    <div class="avatar">
<?php if ( $persona['foto'] ) : ?>
      <img src="/upl/avatars/<?php e($persona['foto']); ?>" alt="<?php e($persona['nombre']); ?>" />
<?php else : ?>
      <img src="/tpl/<?php e( Config::get('template') ); ?>/css/img/person.gif" alt="<?php e($persona['nombre']); ?>" />
<?php endif; ?>
    </div>
    <div class="body">
      <h3><?php e($persona['nombre']); ?></h3>
<?php if ( $persona['cargo'] ) : ?>
      <p><?php e($persona['cargo']); ?></p>
<?php endif; ?>
<?php if ( $persona['correo'] ) : ?>
      <p><a href="mailto:<?php e($persona['correo']); ?>"><?php e($persona['correo']); ?></a></p>
<?php endif; ?>
<?php if ( $persona['tel_oficina'] ) : ?>
      <p><label><?php Lang::e('Of.'); ?></label> <?php e($persona['tel_oficina']); ?></p>
<?php endif; ?>
<?php if ( $persona['tel_celular'] ) : ?>
      <p><label><?php Lang::e('Cel.'); ?></label> <?php e($persona['tel_celular']); ?></p>
<?php endif; ?>
<?php if ( $persona['tel_fax'] ) : ?>
      <p><label><?php Lang::e('Fax'); ?></label> <?php e($persona['tel_fax']); ?></p>
<?php endif; ?>
      <div class="edit">
        <p><a href="/personas/<?php e($idPersonas); ?>/editar" class="red">Editar</a> esta persona</p>
      </div>
    </div>
  </div>
<?php endforeach; ?>
<?php endif; ?>
  <div class="clear"><!-- --></div>
</div>

  </div>
  <div id="bottom"><!-- --></div>
</div>

<?php Web::instance('Footer'); ?>