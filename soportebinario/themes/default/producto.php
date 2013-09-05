<?php Theme::_('header', array( 'titulo' => $this->Producto["nombre"] )); ?>

<div class="breadcrumb">
  <a href="">Inicio</a> &raquo;
  <a href="empresa.php?id=<?php e($this->Empresa["id"]); ?>"><?php e($this->Empresa["nombre"]); ?></a> &raquo;
  <a href="producto.php?id=<?php e($this->Producto["id"]); ?>"><?php e($this->Producto["nombre"]); ?></a>
  <?php if($this->TemaId!=0){ ?>
   &raquo;<a href="producto.php?id=<?php e($this->producto["id"]); ?>"><?php e($this->Tema); ?></a>
  <? } ?>
</div>

<div id="company">
  <div class="image">
    <img src="/images/logos/sonico/sonico.gif" alt="Sonico" />
  </div>
  <div class="info">
    <h1><a href="producto.php?id=<?php e($this->Producto["id"]); ?>"><?php e($this->Producto["nombre"]); ?></a></h1>
    <span><?php e($this->Producto["slogan"]); ?></span>
  </div>
  <form action="/sonico/agregar" method="get">
    <div>
      <label for="cuerpo">&iquest;Que pregunta desea hacer?</label><textarea name="cuerpo" id="cuerpo" rows="3" cols="49"></textarea><input type="image" class="submit" src="/themes/default/images/agregar.gif" /><input type="hidden" name="tipo" id="tipo" value="pregunta" />
    </div>
  </form>
  <div class="clear"><!-- --></div>
</div>

<div id="content">
  <h2>&Uacute;ltima actividad en <?php e($this->Producto["nombre"]); ?></h2>
  <div class="tabs">
    <ul>
      <li class="active"><a href="#">&Uacute;ltima actividad</a></li>
      <li><a href="#">Sin responder</a></li>
    </ul>
  </div>

  <div class="listing">
    <ul>
      <?php
      if(count($this->Temas)==0)
      {
        ?>
        <li class="problema">
          <div class="info">
            <span class="respuestas"></span>
            <span class="seguidores"></span>
          </div>
          <h3><a href=""><span>No hay <?php e($this->Tema); ?> disponibles para este producto</span></a></h3>
          <div class="creator"></div>
          <div class="clear"><!-- --></div>
        </li>
        <?
      }else{
        foreach($this->Temas as $tema)
        {
        ?>
          <li class="<?php echo $this->TipoTemas[$tema["tipo"]]; ?>">
            <div class="info">
              <span class="respuestas"><strong><?php e($tema["respuestas"]); ?></strong> respuesta/s</span>
              <span class="seguidores"><strong>8</strong> seguidor/es</span>
            </div>
            <h3><a href="#"><span><?php e($tema["titulo"]); ?></span></a></h3>
            <div class="creator"><a href="#"><?php e($tema["usuario"]); ?></a> hizo esta pregunta hace 3 d&iacute;as. &Uacute;ltima respuesta fu&eacute; hace 30 minutos.</div>
            <div class="clear"><!-- --></div>
          </li>
        <?php
        }
      }
      ?>
    </ul>
  </div>
</div>

<div id="sidebar">
  <ul id="filter">
    <li class="active"><img src="/themes/default/images/todos.gif" alt="Todos" /><a href="producto.php?id=<?php e($this->Producto["id"]); ?>">Todos</a> (<?php e($this->CountTemas); ?>)</li>
    <li><img src="/themes/default/images/preguntas.gif" alt="Preguntas" /><a href="producto.php?id=<?php e($this->Producto["id"]); ?>&Tipo=1">Preguntas</a> (<?php e($this->CountPreguntas); ?>)</li>
    <li><img src="/themes/default/images/ideas.gif" alt="Ideas" /><a href="producto.php?id=<?php e($this->Producto["id"]); ?>&Tipo=2">Ideas</a> (<?php e($this->CountIdeas); ?>)</li>
    <li><img src="/themes/default/images/problemas.gif" alt="Problemas" /><a href="producto.php?id=<?php e($this->Producto["id"]); ?>&Tipo=3">Problemas</a> (<?php e($this->CountProblemas); ?>)</li>
  </ul>

  <div id="newlist">
    <h4>Cre&aacute; un nuevo tema</h4>
    <ul>
      <li class="pregunta"><a href="temas-agregar.php?id=<?php e($this->Producto["id_empresa"]); ?>&producto_id=<?php e($this->Producto["id"]); ?>&style=q">Hace una pregunta</a></li>
      <li class="idea"><a href="temas-agregar.php?id=<?php e($this->Producto["id"]); ?>&style=i">Compart&iacute; una idea</a></li>
      <li class="problema"><a href="temas-agregar.php?id=<?php e($this->Producto["id"]); ?>&style=p">Report&aacute; un problema</a></li>
    </ul>
  </div>
</div>

<div class="clear"><!-- --></div>

<div id="about">
  <div id="about-links">
    <ul>
      <li><a href="<?php e($this->Producto["web"]); ?>">Sitio Oficial</a></li>
    </ul>
  </div>
  <div id="about-info">
    <h3>Acerca de <?php e($this->Empresa["nombre"]); ?></h3>
    <p><?php e($this->Empresa["descripcion"]); ?></p>
  </div>
  <div class="clear"><!-- --></div>
</div>

<?php Theme::_('footer'); ?>
