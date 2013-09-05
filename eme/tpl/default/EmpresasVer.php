<?php Web::instance('Header'); ?>

<div id="content">
  <div id="page">
    <div class="title">
      <div class="links">
        <a href="/empresas/<?php e($this->idEmpresas); ?>/personas/agregar" class="red"><?php Lang::e('Agregar una persona'); ?></a> |
        <a href="/empresas/<?php e($this->idEmpresas); ?>/editar" class="red"><?php Lang::e('Editar'); ?></a>
      </div>
      <h2><?php e($this->data['nombre']); ?></h2>
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