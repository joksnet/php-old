<?php

include_once 'includes/common.php';

if ( !( isset($_GET['cid']) ) )
    die(':|');

if ( isset($_GET['save']) )
{
    $sql = "INSERT INTO relaciones ( contacto_id_left, contacto_id_right )
            VALUES ( '{$_GET['cid']}', '{$_POST['relacion']}' )";

    if ( !( $result = mysql_query($sql) ) )
        error(mysql_error(), __FILE__, __LINE__);

    header('Location: contactos.php');
}

$sql = "SELECT nombre
        FROM contactos
        WHERE contacto_id = '{$_GET['cid']}'
          AND user_id = '{$_SESSION['UID']}'
        LIMIT 1";

if ( !( $result = mysql_query($sql) ) )
    error(mysql_error(), __FILE__, __LINE__);

if ( $row = mysql_fetch_assoc($result) )
    $nombre = $row['nombre'];

$sql = "SELECT c.contacto_id, c.nombre
        FROM contactos c
        #LEFT JOIN relaciones r ON r.contacto_id_left = c.contacto_id
        WHERE c.user_id = '{$_SESSION['UID']}'
        AND c.contacto_id != '{$_GET['cid']}'
        #AND c.contacto_id != r.contacto_id_right
        ORDER BY c.empresa, c.nombre";

if ( !( $result = mysql_query($sql) ) )
    error(mysql_error(), __FILE__, __LINE__);

$contactos = array();

while ( $row = mysql_fetch_assoc($result) )
    if ( strlen($row['nombre']) > 0 )
        $contactos[] = $row;

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
<?php if ( isset($_GET['cid']) ) { ?>
      <form action="?save&cid=<?php echo $_GET['cid']; ?>" method="post">
        <dl>
          <dt><label for="relacion">Agregar relaci&oacute;n para <em><?php echo $nombre; ?></em>.</label></dt>
          <dd>
            <select name="relacion" id="relacion">
<?php foreach ( $contactos as $contacto ) { ?>
              <option value="<?php echo $contacto['contacto_id']; ?>"><?php echo $contacto['nombre']; ?></option>
<?php } ?>
            </select>
          </dd>

          <dd><input type="submit" class="submit" value="Guardar" /></dd>
        </dl>
      </form>
<?php } ?>
    </div>
    <div class="clear"><!-- --></div>
  </div>
</body>
</html>