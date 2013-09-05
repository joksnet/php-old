<?php Theme::_('header', array( 'titulo' => 'Sonico' )); ?>

<div class="breadcrumb">
  <a href="#">Inicio</a> &raquo;
  <a href="#">Compa&ntilde;ias</a> &raquo;
  <a href="#">Sonico</a>
</div>

<div id="company">
  <div class="image">
    <img src="/images/logos/sonico/sonico.gif" alt="Sonico" />
  </div>
  <div class="info">
    <h1><a href="#">Sonico</a></h1>
    <span>Amigos Conectados</span>
  </div>
  <form action="/sonico/agregar" method="get">
    <div>
      <label for="cuerpo">&iquest;Que pregunta desea hacer?</label><textarea name="cuerpo" id="cuerpo" rows="3" cols="49"></textarea><input type="image" class="submit" src="/themes/default/images/agregar.gif" /><input type="hidden" name="tipo" id="tipo" value="pregunta" />
    </div>
  </form>
  <div class="clear"><!-- --></div>
</div>

<div id="content">
  <h2>&Uacute;ltima actividad en Sonico</h2>
  <div class="tabs">
    <ul>
      <li class="active"><a href="#">&Uacute;ltima actividad</a></li>
      <li><a href="#">Sin responder</a></li>
    </ul>
  </div>

  <div class="listing">
    <ul>
      <li class="pregunta">
        <div class="info">
          <span class="respuestas"><strong>10</strong> respuesta/s</span>
          <span class="seguidores"><strong>8</strong> seguidor/es</span>
        </div>
        <h3><a href="#"><span>&iquest;Qu&eacute; tama&ntilde;o deben tener las im&aacute;genes de avatar?</span></a></h3>
        <div class="creator"><a href="#">joksnet</a> hizo esta pregunta hace 3 d&iacute;as. &Uacute;ltima respuesta fu&eacute; hace 39 minutos.</div>
        <div class="clear"><!-- --></div>
      </li>
      <li class="idea">
        <div class="info">
          <span class="respuestas"><strong>1</strong> respuesta/s</span>
          <span class="seguidores"><strong>12</strong> seguidor/es</span>
        </div>
        <h3><a href="#"><span>Mensajeria Instant&aacute;nea como en GMail</span></a></h3>
        <div class="creator"><a href="#">liberd</a> propuso esta idea hace 2 semanas. &Uacute;ltima respuesta fu&eacute; hace 2 d&iacute;as.</div>
        <div class="clear"><!-- --></div>
      </li>
      <li class="problema">
        <div class="info">
          <span class="respuestas"><strong>0</strong> respuesta/s</span>
          <span class="seguidores"><strong>0</strong> seguidor/es</span>
        </div>
        <h3><a href="#"><span>No me puedo loguear, no me acuerdo quien soy</span></a></h3>
        <div class="creator"><a href="#">jcruz</a> report&oacute; este problema hace 1 mes.</div>
        <div class="clear"><!-- --></div>
      </li>
      <li class="idea">
        <div class="info">
          <span class="respuestas"><strong>0</strong> respuesta/s</span>
          <span class="seguidores"><strong>1</strong> seguidor/es</span>
        </div>
        <h3><a href="#"><span>Que sea m&aacute;s f&aacute;cil as&iacute; lo puede usar mi abuela</span></a></h3>
        <div class="creator"><a href="#">joksnet</a> propuso esta idea hace 4 meses.</div>
        <div class="clear"><!-- --></div>
      </li>
    </ul>

    <div class="pagination">
      <a href="#">1</a>
      <a href="#">2</a>
      <a href="#">3</a>
      <span>...</span>
      <a href="#">5</a>
      <a href="#" class="active">6</a>
      <a href="#">7</a>
      <span>...</span>
      <a href="#">10</a>
      <a href="#">11</a>
      <a href="#">12</a>
    </div>
  </div>
</div>

<div id="sidebar">
  <ul id="filter">
    <li class="active"><img src="/themes/default/images/todos.gif" alt="Todos" /><a href="#">Todos</a> (129)</li>
    <li><img src="/themes/default/images/preguntas.gif" alt="Preguntas" /><a href="#">Preguntas</a> (72)</li>
    <li><img src="/themes/default/images/ideas.gif" alt="Ideas" /><a href="#">Ideas</a> (18)</li>
    <li><img src="/themes/default/images/problemas.gif" alt="Problemas" /><a href="#">Problemas</a> (39)</li>
  </ul>

  <div id="newlist">
    <h4>Cre&aacute; un nuevo tema</h4>
    <ul>
      <li class="pregunta"><a href="#">Hace una pregunta</a></li>
      <li class="idea"><a href="#">Compart&iacute; una idea</a></li>
      <li class="problema"><a href="#">Report&aacute; un problema</a></li>
    </ul>
  </div>

  <div id="tagcloud">
    <h4>Tags</h4>
    <a href="#" class="s4">sonico</a>, <a href="#">api</a>, <a href="#" class="s2">mensajes</a>, <a href="#">notificaciones</a>, <a href="#" class="s3">bugs</a>, <a href="#">amigos</a>
  </div>
</div>

<div class="clear"><!-- --></div>

<div id="about">
  <div id="about-links">
    <ul>
      <li><a href="http://sonico.com">Sitio Oficial</a></li>
      <li><a href="http://blog.sonico.com">Blog</a></li>
    </ul>
  </div>
  <div id="about-info">
    <h3>Acerca de Sonico</h3>
    <p>Sonico es una herramienta que te permite compartir informaci&oacute;n con tus amigos y familia de una forma segura y divertida.</p>
  </div>
  <div class="clear"><!-- --></div>
</div>

<?php Theme::_('footer'); ?>