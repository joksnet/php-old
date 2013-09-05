<?php Web::instance('Header'); ?>

<div id="content">
  <div id="page">
    <div class="title">
      <a href="/configuracion"><?php Lang::e('Configuración'); ?></a> |
      <strong><?php Lang::e('Apariencia'); ?></strong>
    </div>

    <div class="inner">
      <form action="/configuracion/apariencia" method="post">
        <p>Elija un esquema de colores.</p>

        <div class="color">
          <input type="radio" class="checkbox" name="color" value="blue"<?php e( ( Config::get('theme') == 'blue' ) ? ' checked="checked"' : '' ); ?> />
          <div style="background-color: #003366;">
            <span style="background-color: #ffcc00;">&nbsp;</span>
            <span style="background-color: #1a4f85;">&nbsp;</span>
            <span style="background-color: #009900;">&nbsp;</span>
          </div>
        </div>
        <div class="color">
          <input type="radio" class="checkbox" name="color" value="red"<?php e( ( Config::get('theme') == 'red' ) ? ' checked="checked"' : '' ); ?> />
          <div style="background-color: #990000;">
            <span style="background-color: #ffcc00;">&nbsp;</span>
            <span style="background-color: #fff;">&nbsp;</span>
            <span style="background-color: #cc0000;">&nbsp;</span>
          </div>
        </div>
        <div class="color">
          <input type="radio" class="checkbox" name="color" value="green"<?php e( ( Config::get('theme') == 'green' ) ? ' checked="checked"' : '' ); ?> />
          <div style="background-color: #195d00;">
            <span style="background-color: #d1eeff;">&nbsp;</span>
            <span style="background-color: #217a00;">&nbsp;</span>
            <span style="background-color: #e5e5e5;">&nbsp;</span>
          </div>
        </div>

        <div class="actions">
          <input type="submit" class="submit" name="guardar" value="<?php Lang::e('Guardar'); ?>" />
          <?php Lang::e('ó'); ?> <a href="/configuracion/apariencia" class="red"><?php Lang::e('Cancelar'); ?></a>
        </div>
      </form>
    </div>
  </div>
  <div id="bottom"><!-- --></div>
</div>

<div id="sidebar">

</div>

<?php Web::instance('Footer'); ?>