<?php Theme::_('header', array( 'titulo' => $this->Empresa["nombre"] )); ?>

<div class="breadcrumb">
  <a href="#">Inicio</a> &raquo;
  <a href="#">Compa&ntilde;ias</a> &raquo;
  <a href="empresa.php?id=<?php e($this->Empresa["id"]); ?>"><?php e($this->Empresa["nombre"]); ?></a>
  <?php if($this->TemaId!=0){ ?>
   &raquo;<a href="empresa.php?id=<?php e($this->Empresa["id"]); ?>"><?php e($this->Tema); ?></a>
  <? } ?>
</div>

<div id="company">
  <div class="image">
    <img src="/images/logos/sonico/sonico.gif" alt="Sonico" />
  </div>
  <div class="info">
    <h1><a href="empresa.php?id=<?php e($this->Empresa["id"]); ?>"><?php e($this->Empresa["nombre"]); ?></a></h1>
    <span><?php e($this->Empresa["slogan"]); ?></span>
  </div>
  <div>
    <div id="newlist">
      <h4>Productos</h4>
      <ul>
        <?php
        if(count($this->Productos)==0)
        {
        ?>
          <li>No hay productos registrados para esta empresa</li>        
        <?php
        }else{
          foreach($this->Productos as $producto)
          {
          ?>
            <li><a href="producto.php?id=<?php e($producto["id"]); ?>"><?php e($producto["nombre"]); ?></a></li>        
          <?php
          }
        }
        ?>
      </ul>
    </div>
  </div>
  <div class="clear"><!-- --></div>
</div>

<div id="content">
  <h2>&Uacute;ltima actividad en <?php e($this->Empresa["nombre"]); ?></h2>
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
          <h3><a href=""><span>No hay <?php e($this->Tema); ?> disponibles para esta empresa</span></a></h3>
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
    <li class="active"><img src="/themes/default/images/todos.gif" alt="Todos" /><a href="empresa.php?id=<?php e($this->Empresa["id"]); ?>">Todos</a> (<?php e($this->CountTemas); ?>)</li>
    <li><img src="/themes/default/images/preguntas.gif" alt="Preguntas" /><a href="empresa.php?id=<?php e($this->Empresa["id"]); ?>&Tipo=1">Preguntas</a> (<?php e($this->CountPreguntas); ?>)</li>
    <li><img src="/themes/default/images/ideas.gif" alt="Ideas" /><a href="empresa.php?id=<?php e($this->Empresa["id"]); ?>&Tipo=2">Ideas</a> (<?php e($this->CountIdeas); ?>)</li>
    <li><img src="/themes/default/images/problemas.gif" alt="Problemas" /><a href="empresa.php?id=<?php e($this->Empresa["id"]); ?>&Tipo=3">Problemas</a> (<?php e($this->CountProblemas); ?>)</li>
  </ul>
  
  <div id="newlist">
    <h4>Cre&aacute; un nuevo tema</h4>
    <ul>
      <li class="pregunta"><a href="temas-agregar.php?id=<?php e($this->Empresa["id"]); ?>&style=q">Hace una pregunta</a></li>
      <li class="idea"><a href="temas-agregar.php?id=<?php e($this->Empresa["id"]); ?>&style=i">Compart&iacute; una idea</a></li>
      <li class="problema"><a href="temas-agregar.php?id=<?php e($this->Empresa["id"]); ?>&style=p">Report&aacute; un problema</a></li>
    </ul>
  </div>
</div>
<div class="clear"><!-- --></div>

<div id="about">
  <div id="about-links">
    <ul>
      <li><a href="<?php e($this->Empresa["web"]); ?>">Sitio Oficial</a></li>
    </ul>
  </div>
  <div id="about-info">
    <h3>Acerca de <?php e($this->Empresa["nombre"]); ?></h3>
    <p><?php e($this->Empresa["descripcion"]); ?></p>
  </div>
  <div class="clear"><!-- --></div>
</div>

<?php Theme::_('footer'); ?>
