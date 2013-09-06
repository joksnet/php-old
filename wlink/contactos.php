<?php

include_once 'includes/common.php';

if ( isset($_GET['save']) )
{
    if ( isset($_POST['nombre']) && isset($_POST['empresa']) )
    {
        if ( isset($_POST['cid']) )
            $sql = "UPDATE contactos
                    SET nombre = '{$_POST['nombre']}',
                        empresa = '{$_POST['empresa']}',
                        meet_in_person = '{$_POST['meet_in_person']}'
                    WHERE contacto_id = '{$_POST['cid']}'";
        else
            $sql = "INSERT INTO contactos ( user_id, nombre, empresa, meet_in_person )
                    VALUES ( '{$_SESSION['UID']}', '{$_POST['nombre']}', '{$_POST['empresa']}', '{$_POST['meet_in_person']}' )";

        if ( !( $result = mysql_query($sql) ) )
            error(mysql_error(), __FILE__, __LINE__);
    }

    header('Location: contactos.php');
}

if ( !( isset($_GET['add']) || isset($_GET['edit']) ) )
{
    $sql = "SELECT contacto_id, nombre, empresa, meet_in_person, COUNT(relaciones.contacto_id_left) AS relaciones
            FROM contactos
            LEFT JOIN relaciones ON contacto_id_left = contacto_id
            WHERE user_id = '{$_SESSION['UID']}'
            GROUP BY contacto_id, nombre, empresa
            ORDER BY empresa, nombre";

    if ( !( $result = mysql_query($sql) ) )
        error(mysql_error(), __FILE__, __LINE__);

    $contactos = array();

    while ( $row = mysql_fetch_assoc($result) )
        $contactos[] = $row;
}

if ( isset($_GET['edit']) )
{
    $sql = "SELECT contacto_id, nombre, empresa, meet_in_person
            FROM contactos
            WHERE contacto_id = '{$_GET['cid']}'";

    if ( !( $result = mysql_query($sql) ) )
        error(mysql_error(), __FILE__, __LINE__);

    $contacto = mysql_fetch_assoc($result);
}

?>
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
  <title>WLink</title>
  <link rel="shortcut icon" href="/favicon.ico" />
  <style type="text/css">
  /*<![CDATA[*/
    @import 'styles/screen.css';
  /*]]>*/
  </style>
</head>

<body>
  <div id="wrap">
    <div id="left">
<?php menu(); ?>
    </div>
    <div id="right">
<?php if ( isset($_GET['add']) || isset($_GET['edit']) ) { ?>
      <form action="?save" method="post">
        <dl>
          <dt><label for="nombre">Nombre y Apellido</label></dt>
          <dd><input type="text" name="nombre" id="nombre"<?php if ( isset($_GET['edit']) && strlen($contacto['nombre']) > 0 ) echo " value=\"{$contacto['nombre']}\""; ?> /></dd>

          <dt><label for="empresa">Empresa</label></dt>
          <dd><input type="text" name="empresa" id="empresa"<?php if ( isset($_GET['edit']) && strlen($contacto['empresa']) > 0 ) echo " value=\"{$contacto['empresa']}\""; ?> /></dd>

          <dt><label for="meet_in_person">Meet in Person</label></dt>
          <dd><input type="checkbox" name="meet_in_person" id="meet_in_person" value="1"<?php if ( isset($_GET['add']) || ( isset($_GET['edit']) && $contacto['meet_in_person'] == 1 ) ) echo ' checked="checked"'; ?> /></dd>

          <dd><?php if ( isset($_GET['cid']) ) echo '<input type="hidden" name="cid" value="' . $_GET['cid'] . '" />'; ?><input type="submit" class="submit" value="Guardar" /></dd>
        </dl>
      </form>
<?php } else { ?>
      <table cellpadding="0" cellspacing="0">
        <thead>
          <tr>
            <th>#</th>
            <th>Nombre</th>
            <th>Empresa</th>
            <th style="width: 145px;">&nbsp;</th>
            <th><a href="?add">agregar</a></th>
          </tr>
        </thead>
        <tbody>
<?php foreach ( $contactos as $contacto ) { ?>
          <tr>
            <td class="buddy<?php if ( $contacto['meet_in_person'] == 1 ) echo ' meetinperson'; ?>"><?php // echo $i; ?></td>
            <td><?php echo ( strlen($contacto['nombre']) > 0 ) ? $contacto['nombre'] : '&nbsp;'; ?></td>
            <td><?php echo ( strlen($contacto['empresa']) > 0 ) ? $contacto['empresa'] : '&nbsp;'; ?></td>
            <td style="text-align: right;"><?php echo $contacto['relaciones']; ?> relaciones, <a href="relaciones.php?cid=<?php echo $contacto['contacto_id']; ?>">agregar</a></td>
            <td style="text-align: center;"><a href="?edit&amp;cid=<?php echo $contacto['contacto_id']; ?>">editar</a></td>
          </tr>
<?php } ?>
        </tbody>
      </table>
<?php } ?>
    </div>
    <div class="clear"><!-- --></div>
  </div>
</body>
</html>