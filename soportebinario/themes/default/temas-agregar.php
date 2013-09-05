<?php Theme::_('header', array( 'titulo' => "{$this->title} « {$this->empresa['nombre']}" )); ?>

<div id="content">
  <h2><?php e( $this->title ); ?> en <?php e( $this->empresa['nombre'] ); ?></h2>
  <form action="#" method="post">
    <dl>
      <dt><label for="titulo">T&iacute;tulo</label></dt>
      <dd>
        <input type="text" name="titulo" id="titulo" size="60" maxlength="200" />
      </dd>

      <dt><label for="descripcion">Descripci&oacute;n</label></dt>
      <dd>
        <textarea name="descripcion" id="descripcion" rows="8" cols="60"></textarea>
      </dd>

      <dd>
        <input type="hidden" name="producto" id="producto" value="0" />
        <input type="submit" class="submit" value="Enviar" />
        &oacute; <a href="#">Cancelar</a>
      </dd>
    </dl>
  </form>
</div>

<script type="text/javascript">
/*<![CDATA[*/
function toogle( a, id )
{
    var l = a.parentNode.parentNode.getElementsByTagName('a');

    for ( i in l )
        l[i].className = '';

    if ( a.className )
        a.className = '';
    else
    {
        a.className = 'active';
        document.getElementById('producto').value = id;
    }
}
/*]]>*/
</script>

<div id="sidebar">
  <div>
    <h4>Seleccione un Producto</h4>
    <ul id="products">
<?php foreach ( $this->productos as $producto ) : ?>
      <li>
        <a href="javascript:void(0);" onclick="toogle(this,<?php e( $producto['id'] ); ?>);" title="<?php e( $producto['nombre'] ); ?>">
          <img src="/images/logos/<?php e( $producto['logo'] ); ?>" alt="<?php e( $producto['nombre'] ); ?>" />
        </a>
      </li>
<?php endforeach; ?>
    </ul>
  </div>
</div>

<div class="clear"><!-- --></div>

<?php Theme::_('footer'); ?>