<?php Web::instance('Header'); ?>

<div id="content" class="full">
  <div id="page">
    <div class="title">
      <div class="links">
        <a href="/empresas/agregar" class="red"><?php Lang::e('Agregar una nueva empresa'); ?></a>
        <?php Lang::e('(después puede añadir personas)'); ?>
      </div>
      <h2><?php Lang::e('Cada empresa y persona en el sistema'); ?></h2>
    </div>

<?php if ( $this->data ) : ?>
<?php foreach ( $this->data as $idEmpresas => $empresa ) : ?>
<div class="company">
  <h1>
    <?php e($empresa['nombre']); ?>
<?php if ( $empresa['pais'] ) : ?>
    <span>de <?php e($empresa['pais']); ?></span>
<?php endif; ?>
  </h1>
  <div class="column first">
    <div class="avatar">
      <img src="/tpl/<?php e( Config::get('template') ); ?>/css/img/company.gif" alt="<?php e($empresa['nombre']); ?>" />
    </div>
    <div class="body">
      <h3><?php e($empresa['nombre']); ?></h3>
<?php if ( $empresa['direccion'] ) : ?>
      <p><?php e($empresa['direccion']); ?></p>
<?php endif; ?>
<?php if ( $empresa['lugar'] ) : ?>
      <p><?php e($empresa['lugar']); ?></p>
<?php endif; ?>
<?php if ( $empresa['tel_oficina'] ) : ?>
      <p><label><?php Lang::e('Of.'); ?></label> <?php e($empresa['tel_oficina']); ?></p>
<?php endif; ?>
<?php if ( $empresa['tel_fax'] ) : ?>
      <p><label><?php Lang::e('Fax'); ?></label> <?php e($empresa['tel_fax']); ?></p>
<?php endif; ?>
<?php if ( $empresa['web'] ) : ?>
      <p><a href="<?php e($empresa['web']); ?>"><?php e($empresa['web']); ?></a></p>
<?php endif; ?>
      <div class="edit">
        <p><a href="/empresas/<?php e($idEmpresas); ?>/editar" class="red">Editar</a> esta empresa</p>
        <p><a href="/empresas/<?php e($idEmpresas); ?>/personas/agregar" class="red">Agregar persona</a> a <?php e($empresa['nombre']); ?></p>
      </div>
    </div>
  </div>
<?php if ( $empresa['personas'] ) : ?>
<?php foreach ( $empresa['personas'] as $idPersonas => $persona ) : ?>
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
<?php endforeach; ?>
<?php endif; // $this->data ?>

  </div>
  <div id="bottom"><!-- --></div>
</div>

<?php Web::instance('Footer'); ?>