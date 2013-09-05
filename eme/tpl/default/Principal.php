<?php Web::instance('Header'); ?>

<div id="content">
  <div id="page">
    <div class="title">
      <h2><?php Lang::e('Última actividad'); ?></h2>
    </div>
<!--
    <table cellpadding="0" cellspacing="0" class="overview">
      <tbody>
        <tr>
          <td class="what"><span class="comment"><?php Lang::e('Comentario'); ?></span></td>
          <td class="item"><a href="#">Re: Consulta de Opcional</a></td>
          <td class="action">Escrito por</td>
          <td class="name">Juan Manuel M.</td>
          <td class="date"><span class="today"><?php Lang::e('Hoy'); ?></span></td>
        </tr>
        <tr class="even">
          <td class="what"><span class="message"><?php Lang::e('Mensaje'); ?></span></td>
          <td class="item"><a href="#">Y ademas...</a></td>
          <td class="action">Escrito por</td>
          <td class="name">Roberto Noble</td>
          <td class="date">29 Apr</td>
        </tr>
        <tr>
          <td class="what"><span class="todo"><?php Lang::e('Tarea'); ?></span></td>
          <td class="item"><span class="strike">Revisar Revista Oh</span> (<a href="#" class="grey">Pendientes</a>)</td>
          <td class="action">Completado por</td>
          <td class="name">Nicolas Mescia</td>
          <td class="date">14 Abr</td>
        </tr>
        <tr class="even">
          <td class="what"><span class="calendar"><?php Lang::e('Calendario'); ?></span></td>
          <td class="item">Cumplea&ntilde;os de Manuel Massa Mautino del La Prensa de Curic&oacute; de Chile</td>
          <td class="action">Abierto por</td>
          <td class="name">Juan Manuel M.</td>
          <td class="date">09 Abr</td>
        </tr>
        <tr>
          <td class="what"><span class="todo"><?php Lang::e('Tarea'); ?></span></td>
          <td class="item">Ordenar papeles (<a href="#" class="grey">Oficina</a>)</td>
          <td class="action">Asignado a</td>
          <td class="name">Nicol&aacute;s Mescia</td>
          <td class="date">18 Mar</td>
        </tr>
        <tr class="even">
          <td class="what"><span class="message"><?php Lang::e('Mensaje'); ?></span></td>
          <td class="item"><a href="#">Consulta de Opcional</a></td>
          <td class="action">Escrito por</td>
          <td class="name">Roberto Noble</td>
          <td class="date">04 Mar</td>
        </tr>
        <tr>
          <td class="what"><span class="meeting"><?php Lang::e('Reunion'); ?></span></td>
          <td class="item">Definir los Colores del Curso de Cocina para El Observador de Uruguay</td>
          <td class="action">Abierta por</td>
          <td class="name">Nicol&aacute;s Mescia</td>
          <td class="date">16 Feb</td>
        </tr>
      </tbody>
    </table>
 -->
  </div>
  <div id="bottom"><!-- --></div>
</div>

<div id="sidebar">
  <div id="logo"<?php e( ( Config::get('logoWhite') ) ? ' class="white"' : '' ); ?>>
    <img src="<?php e( Config::get('logo') ); ?>" alt="<?php e( Config::get('nombre') ); ?>" />
  </div>
</div>

<?php Web::instance('Footer'); ?>