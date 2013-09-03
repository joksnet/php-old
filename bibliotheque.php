<?php
/**
 * Bibliotheque
 *
 * This script was created in the year 2009 in less than one hour, so my
 * girlfriend could keep record of her books.
 */

/**
CREATE TABLE IF NOT EXISTS `bibliotheque_auteurs` (
  `id_auteurs` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `prenom` varchar(40) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `nom` varchar(40) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id_auteurs`)
);

CREATE TABLE IF NOT EXISTS `bibliotheque_categories` (
  `id_categories` tinyint(2) unsigned NOT NULL AUTO_INCREMENT,
  `nom` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id_categories`)
);

CREATE TABLE IF NOT EXISTS `bibliotheque_langues` (
  `id_langues` tinyint(2) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(2) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `nom` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id_langues`)
);

CREATE TABLE IF NOT EXISTS `bibliotheque_livres` (
  `id_livres` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `id_langues` tinyint(2) unsigned NOT NULL DEFAULT '0',
  `id_auteurs` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `id_categories` tinyint(2) unsigned NOT NULL DEFAULT '0',
  `titre` varchar(140) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id_livres`)
);
 */

session_start();

define('THEPASSWORD', 'pa$$word');

mysql_connect('localhost', 'root', '');
mysql_select_db('bibliotheque');

$prefix = 'bibliotheque_';

$relations = array( 'langues' => array( 'code', 'nom' ), 'categories' => array( 'nom' ), 'auteurs' => array( 'prenom', 'nom' ) );
$relationsDisplay = array( 'langues' => 'nom', 'categories' => 'nom', 'auteurs' => array( 'prenom', 'nom' ) );
$relationsNew = array( 'langues' => false, 'categories' => true, 'auteurs' => true );
$relationsNone = array( 'langues' => false, 'categories' => false, 'auteurs' => true );

$isPost = ( $_SERVER['REQUEST_METHOD'] == 'POST' );
$isRelations = false;

define('MSG_AJOUTE', 'ajoute');
define('MSG_ELIMINE', 'elimine');
define('MSG_MODIFIE', 'modifie');

define('MSG_TROUVE', 'trouve');
define('MSG_PASTROUVE', 'pastrouve');

foreach ( $relations as $relation => $relationFields )
{
    if ( isset($_GET[$relation]) )
    {
        $isRelations = true; break;
    }
}

if ( $isRelations )
{
    if ( !( isset($_SESSION['login']) ) )
    {
        header('Location: ?login'); exit();
    }

    $table = null;
    $tableName = null;

    $fields = array();
    $values = array();

    foreach ( $relations as $relation => $relationFields )
    {
        if ( isset($_GET[$relation]) )
        {
            $table = $prefix . $relation;
            $tableName = $relation;
            $fields = $relationFields;

            break;
        }
    }

    if ( null === $tableName )
    {
        header('Location: ?livres'); exit();
    }

    if ( isset($_GET['eliminer']) )
    {
        if ( !( $id = (int) $_GET['eliminer'] ) )
        {
            header("Location: ?$tableName&msg=" . MSG_PASTROUVE); exit();
        }

        $result = mysql_query(
            "SELECT livres.id_livres
             FROM livres
             WHERE id_$tableName = '$id'"
        );

        if ( mysql_num_rows($result) > 0 )
        {
            header("Location: ?$tableName&msg=" . MSG_TROUVE); exit();
        }

        mysql_query(
            "DELETE FROM $table
             WHERE id_$tableName = '$id'"
        );

        header("Location: ?$tableName&msg=" . MSG_ELIMINE); exit();
    }

    foreach ( $fields as $field )
        $values[$field] = '';

    if ( isset($_GET['ajouter']) )
    {
        $valid = true;

        if ( !( $isPost ) )
        {
            header("Location: ?$tableName"); exit();
        }

        foreach ( $fields as $field )
        {
            $values[$field] = ( isset($_POST[$field]) ) ? $_POST[$field] : '';

            if ( empty($values[$field]) )
            {
                $valid = false; break;
            }
        }

        if ( $valid )
        {
            mysql_query(
                "INSERT INTO $table ( " . implode(', ', $fields) . " )
                 VALUES ( '" . implode("', '", $values) . "' )"
            );

            header("Location: ?$tableName&msg=" . MSG_AJOUTE); exit();
        }
    }
    elseif ( isset($_GET['modifier']) )
    {
        if ( !( $id = (int) $_GET['modifier'] ) )
        {
            header("Location: ?$tableName&msg=" . MSG_MODIFIE); exit();
        }

        $result = mysql_query(
            "SELECT $tableName.*
             FROM $table AS $tableName
             WHERE id_$tableName = '$id'"
        );

        if ( !( $row = mysql_fetch_assoc($result) ) )
        {
            header("Location: ?$tableName&msg=" . MSG_PASTROUVE); exit();
        }

        $values = $row;

        if ( $isPost )
        {
            $valid = true;
            $update = array();

            foreach ( $fields as $field )
            {
                $values[$field] = ( isset($_POST[$field]) ) ? $_POST[$field] : '';
                $update[] = "$field = '{$values[$field]}'";

                if ( empty($values[$field]) )
                {
                    $valid = false; break;
                }
            }

            if ( $valid )
            {
                mysql_query(
                    "UPDATE $table
                     SET " . implode(', ', $update) . "
                     WHERE id_$tableName = '$id'"
                );

                header("Location: ?$tableName&msg=" . MSG_MODIFIE); exit();
            }
        }
    }

    $all = array();

    $result = mysql_query(
        "SELECT $tableName.*
         FROM $table AS $tableName
         ORDER BY $tableName.nom"
    );

    while ( $row = mysql_fetch_assoc($result) )
        $all[] = $row;
}
elseif ( isset($_GET['livres']) )
{
    $tableLivres = $prefix . 'livres';

    if ( !( isset($_SESSION['login']) ) )
    {
        header('Location: ?login'); exit();
    }

    if ( isset($_GET['eliminer']) )
    {
        if ( !( $id = (int) $_GET['eliminer'] ) )
        {
            header('Location: ?livres&msg=' . MSG_PASTROUVE); exit();
        }

        mysql_query(
            "DELETE FROM livres
             WHERE id_livres = '$id'"
        );

        header('Location: ?livres&msg=' . MSG_ELIMINE); exit();
    }
    elseif ( isset($_GET['ajouter']) || isset($_GET['modifier']) )
    {
        $id = null; $values = array(); $types = array();

        foreach ( $relations as $relation => $relationFields )
            $types[$relation] = ( isset($_GET['ajouter']) ) ? 'new' : 'old';

        if ( isset($_GET['modifier']) && !( $id = (int) $_GET['modifier'] ) )
        {
            header('Location: ?livres&msg=' . MSG_PASTROUVE); exit();
        }

        if ( isset($_GET['modifier']) )
        {
            $result = mysql_query(
                "SELECT livres.*
                 FROM $tableLivres AS livres
                 WHERE id_livres = '$id'"
            );

            if ( !( $row = mysql_fetch_assoc($result) ) )
            {
                header('Location: ?livres&msg=' . MSG_PASTROUVE); exit();
            }

            $values = $row;
        }

        if ( $isPost )
        {
            $valid = true;
            $update = array();

            $insertFields = array();
            $insertValues = array();

            foreach ( $relations as $relation => $relationFields )
            {
                $relationTable = $prefix . $relation;

                $valid2 = true;
                $value = ( isset($_POST[$relation]) ) ? $_POST[$relation] : '';
                $types[$relation] = ( isset($_POST[$relation . '_type']) ) ? $_POST[$relation . '_type'] : 'new';

                if ( $relationsNew[$relation] && isset($_POST[$relation . '_type']) && 'new' == $_POST[$relation . '_type'] )
                {
                    if ( is_array($relationsDisplay[$relation]) )
                        $fields2 = $relationsDisplay[$relation];
                    else
                        $fields2 = array( $relationsDisplay[$relation] );

                    $insertFields2 = array();
                    $insertValues2 = array();

                    foreach ( $fields2 as $field2 )
                    {
                        $value2 = ( isset($_POST[$relation . '_' . $field2]) ) ? $_POST[$relation . '_' . $field2] : '';

                        if ( empty($value2) )
                        {
                            $valid2 = false; break;
                        }

                        $insertFields2[] = $field2;
                        $insertValues2[] = $value2;

                        $values[$relation . '_' . $field2] = $value2;
                    }

                    if ( $valid2 )
                    {
                        $types[$relation] = 'old';

                        mysql_query(
                            "INSERT INTO $relationTable ( " . implode(', ', $insertFields2) . " )
                             VALUES ( '" . implode("', '", $insertValues2) . "' )"
                        );

                        $value = mysql_insert_id();
                    }
                }
                elseif ( isset($_POST[$relation . '_type']) && 'null' == $_POST[$relation . '_type'] )
                {
                    $value = 0;
                    $types[$relation] = 'null';
                }

                if ( ( empty($value) && 'null' != $types[$relation] ) || !( $valid2 ) )
                {
                    $valid = false; continue;
                }

                $values["id_$relation"] = $value;

                $insertFields[] = "id_$relation";
                $insertValues[] = $value;

                $update[] = "id_$relation = '$value'";
            }

            $value = ( isset($_POST['titre']) ) ? $_POST['titre'] : '';

            if ( empty($value) )
                $valid = false;
            else
            {
                $values['titre'] = $value;

                $insertFields[] = 'titre';
                $insertValues[] = $value;

                $update[] = "titre = '$value'";
            }

            if ( $valid )
            {
                if ( isset($_GET['modifier']) )
                {
                    $msg = MSG_MODIFIE;

                    mysql_query(
                        "UPDATE $tableLivres
                         SET " . implode(', ', $update) . "
                         WHERE id_livres = '$id'"
                    );
                }
                else
                {
                    $msg = MSG_AJOUTE;

                    mysql_query(
                        "INSERT INTO $tableLivres ( " . implode(', ', $insertFields) . " )
                         VALUES ( '" . implode("', '", $insertValues) . "' )"
                    );
                }

                header("Location: ?livres&msg=$msg"); exit();
            }
        }

        $relationsValues = array();

        foreach ( $relations as $relation => $relationFields )
        {
            $relationsValues[$relation] = array();
            $relationTable = $prefix . $relation;

            $result = mysql_query(
                "SELECT $relation.*
                 FROM $relationTable AS $relation
                 ORDER BY $relation.nom"
            );

            while ( $row = mysql_fetch_assoc($result) )
                $relationsValues[$relation][] = $row;
        }
    }
    else
    {
        if ( $isPost )
        {
            $query = '';

            if ( isset($_POST['q']) )
                $query .= '&q=' . str_replace(' ', '+', $_POST['q']);

            if ( isset($_POST['order']) )
                $query .= '&order=' . $_POST['order'];

            if ( isset($_POST['dir']) )
                $query .= '&dir=' . $_POST['dir'];

            header("Location: ?livres$query"); exit();
        }

        $orderField = 'titre';
        $orderDir = ( isset($_GET['dir']) && 'asc' == $_GET['dir'] ) ? 'ASC' : 'DESC';
        $order = array( "livres.titre $orderDir" );

        if ( isset($_GET['order']) )
        {
            $orderFound = array();

            foreach ( $relations as $relation => $relationFields )
            {
                if ( $relation == $_GET['order'] )
                {
                    $orderField = $relation;

                    foreach ( $relationFields as $relationField )
                        $orderFound[] = "$relation.$relationField $orderDir";

                    break;
                }
            }

            if ( $orderFound )
                $order = $orderFound;
        }

        $where = array();

        if ( $q = ( isset($_GET['q']) ) ? str_replace('+', ' ', $_GET['q']) : '' )
        {
            $where[] = "livres.titre LIKE '%" . str_replace(' ', '%', $q) . "%'";
        }

        $selectFields = array();
        $selectJoins = array();

        foreach ( $relations as $relation => $relationFields )
        {
            foreach ( $relationFields as $relationField )
                $selectFields[] = "$relation.$relationField AS {$relation}_$relationField";

            $selectJoins[] = "LEFT JOIN $prefix$relation AS $relation ON ( $relation.id_$relation = livres.id_$relation )";
        }

        $livres = array();
        $result = mysql_query(
            "SELECT livres.id_livres
                  , livres.titre
                  , " . implode(', ', $selectFields) . "
             FROM $tableLivres AS livres
            " . implode(' ', $selectJoins) . "
            " . ( $where ? 'WHERE ' . implode(' AND ', $where) : '' ) . "
             ORDER BY " . implode(', ', $order)
        );

        while ( $row = mysql_fetch_assoc($result) )
            $livres[] = $row;
    }
}
elseif ( isset($_GET['login']) )
{
    if ( isset($_SESSION['login']) )
    {
        header('Location: ?livres'); exit();
    }

    if ( $isPost )
    {
        if ( isset($_POST['password']) && $_POST['password'] == THEPASSWORD )
        {
            $_SESSION['login'] = true;

            header('Location: ?livres');
            exit();
        }
    }
}
else
{
    if ( isset($_SESSION['login']) )
        header('Location: ?livres');
    else
        header('Location: ?login');

    exit();
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
   "http://www.w3.org/TR/html4/strict.dtd">
<html>
  <head>
    <title>Notre Bibliotheque</title>
    <style type="text/css">
      body { font: 13px Arial, sans-serif; }
      body a { color: blue; text-decoration: none; padding: 1px 3px; }
      body a:hover { background-color: blue; color: white; }
      body a.eliminer { color: red; }
      body a.eliminer:hover { background-color: red; color: white; }
      div.clear { clear: left; }
      ul { list-style-type: none; margin-top: 0; padding-left: 0; display: inline; float: left; }
      ul.right { margin-bottom: 0; float: right; }
      ul li { display: inline; float: left; margin-right: 5px; }
      p.message { background-color: #D2FF6F; padding: 5px; margin-top: 0; }
      p.error { background-color: #B31A14; color: #FFF; padding: 5px; margin-top: 0; }
      table { width: 100%; margin-bottom: 1em; font: inherit; }
      table thead tr th, table tbody tr td { height: 30px; padding: 0 5px; border-bottom: 3px solid #FFF; }
      table thead tr th { text-align: left; font-weight: normal; background-color: #CCC; }
      table tbody tr td { background-color: #EEE; }
      table tbody tr.even td { background-color: #FAFAFA; }
      form dl { margin-top: 0.5em; }
      form dl dt { width: 100px; float: left; text-align: right; padding-top: 4px; }
      form dl dd { padding-left: 110px; margin-left: 0; margin-bottom: 10px; padding-bottom: 10px; border-bottom: 1px solid #CCC; }
      form dl dd div { margin-top: 10px; }
      form dl dd label { line-height: 24px; }
      form p { padding-left: 10px; margin-bottom: 0; }
      form fieldset { border: 1px solid #AAA; }
      form fieldset legend { border: 1px solid #AAA; padding: 3px 4px; }
      form fieldset#login { width: 300px; margin: 15% auto; }
      form input, form select { border-width: 1px; border-style: solid; border-color: #BBBBBB #DDDDDD #DDDDDD #BBBBBB; background-color: #FBFBFB; padding: 3px 2px; }
      form input.checkbox { margin: 0; position: relative; top: 6px; float: left; margin-right: 5px; }
      form input.submit { cursor: pointer; border-width: 1px; border-style: solid; border-color: #DDDDDD #BBBBBB #BBBBBB #DDDDDD; background-color: #FBFBFB; padding: 1px 2px; }
      form input#q { width: 280px; }
    </style>
  </head>
  <body>
<?php if ( !( isset($_GET['login']) ) ) : ?>
    <ul>
      <li><a href="?livres">Livres</a></li>
<?php foreach ( $relations as $relation => $relationFields ) : ?>
      <li><a href="?<?php echo $relation; ?>"><?php echo ucfirst($relation); ?></a></li>
<?php endforeach; ?>
    </ul>
    <div class="clear"><!-- --></div>
<?php if ( isset($_GET['msg']) ) : ?>
<?php switch ( $_GET['msg'] ) : ?>
<?php case MSG_AJOUTE: ?>
    <p class="message">Enregistrement ajout&eacute;.</p>
<?php break; ?>
<?php case MSG_ELIMINE: ?>
    <p class="message">Enregistrement &eacute;limin&eacute;.</p>
<?php break; ?>
<?php case MSG_MODIFIE: ?>
    <p class="message">Enregistrement modifi&eacute;.</p>
<?php break; ?>
<?php case MSG_TROUVE: ?>
    <p class="message">Enregistrement trouve.</p>
<?php break; ?>
<?php case MSG_PASTROUVE: ?>
    <p class="message">Enregistrement pas trouve.</p>
<?php break; ?>
<?php endswitch; ?>
<?php endif; ?>
<?php endif; ?>
<?php if ( $isRelations ) : ?>
    <table cellpadding="0" cellspacing="0">
      <thead>
        <tr>
          <th>#</th>
<?php foreach ( $fields as $field ) : ?>
          <th><?php echo ucwords($field); ?></th>
<?php endforeach; ?>
          <th>&nbsp;</th>
        </tr>
      </thead>
      <tbody>
<?php if ( $all ) : ?>
<?php foreach ( $all as $i => $each ) : ?>
        <tr class="<?php echo ( $i % 2 == 0 ) ? 'odd' : 'even'; ?>">
          <td><?php echo ( $i + 1 ); ?></td>
<?php foreach ( $fields as $field ) : ?>
          <td><?php echo $each[$field]; ?></td>
<?php endforeach; ?>
          <td>
            <ul class="right">
              <li><a href="?<?php echo $tableName; ?>&amp;modifier=<?php echo $each["id_$tableName"]; ?>">Modifier</a></li>
              <li><a href="?<?php echo $tableName; ?>&amp;eliminer=<?php echo $each["id_$tableName"]; ?>" class="eliminer">&Eacute;liminer</a></li>
            </ul>
            <div class="clear"><!-- --></div>
          </td>
        </tr>
<?php endforeach; ?>
<?php else : ?>
        <tr>
          <td colspan="<?php echo sizeof($fields) + 2; ?>">Pas de <?php echo $tableName; ?>.</td>
        </tr>
<?php endif; ?>
      </tbody>
    </table>
    <form action="?<?php echo $tableName; ?>&amp;<?php echo ( isset($_GET['modifier']) ) ? 'modifier=' . $id : 'ajouter'; ?>" method="post">
      <fieldset>
        <legend><?php echo ( isset($_GET['modifier']) ) ? 'Modifier #' . $id : 'Nouvelle'; ?></legend>
<?php if ( $isPost ) : ?>
          <p class="error">Tous les domaines sont obligatoires.</p>
<?php endif; ?>
        <dl>
<?php foreach ( $fields as $field ) : ?>
          <dt><label for="<?php echo $field; ?>"><?php echo ucwords($field); ?></label></dt>
          <dd><input type="text" name="<?php echo $field; ?>" id="<?php echo $field; ?>" value="<?php echo ( isset($values[$field]) ) ? $values[$field] : ''; ?>" /></dd>
<?php endforeach; ?>
        </dl>
        <p>
          <input type="submit" class="submit" value="<?php echo ( isset($_GET['modifier']) ) ? 'Modifier' : 'Ajouter'; ?>" />
        </p>
      </fieldset>
    </form>
<?php elseif ( isset($_GET['login']) ) : ?>
    <form action="?login" method="post">
      <fieldset id="login">
        <legend>S'identifier</legend>
<?php if ( $isPost ) : ?>
        <p class="error">Cle incorrecte.</p>
<?php endif; ?>
        <dl>
          <dt><label for="password">Mot de passe</label></dt>
          <dd><input type="password" name="password" id="password" /></dd>
        </dl>
        <p>
          <input type="submit" class="submit" value="Ouvrir" />
        </p>
      </fieldset>
    </form>
<?php else : ?>
<?php if ( isset($_GET['ajouter']) || isset($_GET['modifier']) ) : ?>
    <form action="?livres&amp;<?php echo ( isset($_GET['modifier']) ) ? 'modifier=' . $id : 'ajouter'; ?>" method="post">
<?php if ( $isPost ) : ?>
      <p class="error">Tous les domaines sont obligatoires.</p>
<?php endif; ?>
      <dl>
<?php foreach ( $relationsValues as $relation => $relationRows ) : ?>
        <dt><label for="<?php echo $relation; ?>"><?php echo ucfirst($relation); ?></label></dt>
        <dd>
          <div>
<?php if ( $relationsNew[$relation] ) : ?>
            <input type="radio" class="checkbox" name="<?php echo $relation; ?>_type" id="<?php echo $relation; ?>_type_old" value="old"<?php echo ( isset($types[$relation]) && 'old' == $types[$relation] ) ? ' checked="checked"' : ''; ?> />
<?php endif; ?>
            <select name="<?php echo $relation; ?>" id="<?php echo $relation; ?>">
<?php if ( !( isset($values["id_$relation"]) ) ) : ?>
              <option value="" disabled="disabled" selected="selected">S&eacute;lectionner</option>
<?php else : ?>
              <option value="" disabled="disabled">S&eacute;lectionner</option>
<?php endif; ?>
              <option value="" disabled="disabled">--</option>
<?php foreach ( $relationRows as $i => $relationRow ) : ?>
              <option value="<?php echo $relationRow["id_$relation"]; ?>"<?php echo ( isset($values["id_$relation"]) && $relationRow["id_$relation"] == $values["id_$relation"] ) ? ' selected="selected"' : ''; ?>>
<?php if ( is_array($relationsDisplay[$relation]) ) : ?>
<?php foreach ( $relationsDisplay[$relation] as $relationsDisplayField ) : ?>
<?php if ( $relationRow[$relationsDisplayField] ) : ?>
                <!-- --><?php echo $relationRow[$relationsDisplayField]; ?><!-- -->
<?php endif; ?>
<?php endforeach; ?>
<?php elseif ( $relationRow[$relationsDisplay[$relation]] ) : ?>
                <!-- --><?php echo $relationRow[$relationsDisplay[$relation]]; ?><!-- -->
<?php else : ?>
                <!-- -->-<!-- -->
<?php endif; ?>
              </option>
<?php endforeach; ?>
            </select>
          </div>
<?php if ( $relationsNew[$relation] ) : ?>
          <div>
            <input type="radio" class="checkbox" name="<?php echo $relation; ?>_type" id="<?php echo $relation; ?>_type_new" value="new"<?php echo ( isset($types[$relation]) && 'new' == $types[$relation] ) ? ' checked="checked"' : ''; ?> />
<?php if ( is_array($relationsDisplay[$relation]) ) : ?>
            <ul>
<?php foreach ( $relationsDisplay[$relation] as $relationsDisplayField ) : ?>
              <li>
                <label for="<?php echo $relation; ?>_<?php echo $relationsDisplayField; ?>"><?php echo ucfirst($relationsDisplayField); ?></label><br />
                <input type="text" name="<?php echo $relation; ?>_<?php echo $relationsDisplayField; ?>" id="<?php echo $relation; ?>_<?php echo $relationsDisplayField; ?>" value="<?php echo ( isset($values[$relation . '_' . $relationsDisplayField]) ) ? $values[$relation . '_' . $relationsDisplayField] : ''; ?>" />
              </li>
<?php endforeach; ?>
            </ul>
            <div class="clear"><!-- --></div>
<?php else : ?>
            <input type="text" name="<?php echo $relation; ?>_<?php echo $relationsDisplay[$relation]; ?>" id="<?php echo $relation; ?>_<?php echo $relationsDisplay[$relation]; ?>" value="<?php echo ( isset($values[$relation . '_' . $relationsDisplay[$relation]]) ) ? $values[$relation . '_' . $relationsDisplay[$relation]] : ''; ?>" />
<?php endif; ?>
          </div>
<?php endif; ?>
<?php if ( $relationsNone[$relation] ) : ?>
          <div>
            <input type="radio" class="checkbox" name="<?php echo $relation; ?>_type" id="<?php echo $relation; ?>_type_null" value="null"<?php echo ( isset($types[$relation]) && 'null' == $types[$relation] ) ? ' checked="checked"' : ''; ?> />
            <label for="<?php echo $relation; ?>_type_null">Aucun</label>
          </div>
<?php endif; ?>
        </dd>
<?php endforeach; ?>
        <dt><label for="titre">Titre</label></dt>
        <dd><input type="text" name="titre" id="titre" maxlength="140" value="<?php echo ( isset($values['titre']) ) ? $values['titre'] : ''; ?>" /></dd>
      </dl>
      <p>
        <input type="submit" class="submit" value="<?php echo ( isset($_GET['modifier']) ) ? 'Modifier' : 'Ajouter'; ?>" /> ou <a href="?livres">Annuler</a>
      </p>
    </form>
<?php else : ?>
    <table cellpadding="0" cellspacing="0">
      <thead>
        <tr>
          <th>#</th>
<?php foreach ( $relations as $relation => $relationFields ) : ?>
          <th>
            <span><?php echo ucfirst($relation); ?></span>
            <a href="?livres<?php echo ( $q ) ? '&amp;q=' . str_replace(' ', '+', $q) : ''; ?>&amp;order=<?php echo $relation; ?><?php echo ( $orderField == $relation && $orderDir == 'DESC' ) ? '&amp;dir=asc' : ''; ?>"><?php echo ( $orderField == $relation && $orderDir == 'ASC' ) ? '&darr;' : '&uarr;'; ?></a>
          </th>
<?php endforeach; ?>
          <th>
            <span>Titre</span>
            <a href="?livres<?php echo ( $q ) ? '&amp;q=' . str_replace(' ', '+', $q) : ''; ?>&amp;order=titre<?php echo ( $orderField == 'titre' && $orderDir == 'DESC' ) ? '&amp;dir=asc' : ''; ?>"><?php echo ( $orderField == 'titre' && $orderDir == 'ASC' ) ? '&darr;' : '&uarr;'; ?></a>
          </th>
          <th>&nbsp;</th>
        </tr>
      </thead>
      <tfoot>
        <tr>
          <td colspan="<?php echo sizeof($relations) + 3; ?>">
            <ul class="right">
              <li><a href="?livres&amp;ajouter">Ajouter</a></li>
            </ul>
          </td>
        </tr>
      </tfoot>
      <tbody>
<?php if ( $livres ) : ?>
<?php foreach ( $livres as $i => $livre ) : ?>
        <tr class="<?php echo ( $i % 2 == 0 ) ? 'odd' : 'even'; ?>">
          <td><?php echo ( $i + 1 ); ?></td>
<?php foreach ( $relations as $relation => $relationFields ) : ?>
<?php if ( is_array($relationsDisplay[$relation]) ) : ?>
<?php $relationsDisplayNone = false; ?>
          <td>
<?php foreach ( $relationsDisplay[$relation] as $relationsDisplayField ) : ?>
<?php if ( isset($livre[$relation . '_' . $relationsDisplayField]) ) : ?>
            <span><?php echo $livre[$relation . '_' . $relationsDisplayField]; ?></span>
<?php else : ?>
<?php $relationsDisplayNone = true; ?>
<?php endif; ?>
<?php endforeach; ?>
<?php if ( $relationsDisplayNone ) : ?>
            <span>Aucun</span>
<?php endif; ?>
          </td>
<?php elseif ( isset($livre[$relation . '_' . $relationsDisplay[$relation]]) ) : ?>
          <td><?php echo $livre[$relation . '_' . $relationsDisplay[$relation]]; ?></td>
<?php else : ?>
          <td>-</td>
<?php endif; ?>
<?php endforeach; ?>
          <td><?php echo $livre['titre']; ?></td>
          <td>
            <ul class="right">
              <li><a href="?livres&amp;modifier=<?php echo $livre['id_livres']; ?>">Modifier</a></li>
              <li><a href="?livres&amp;eliminer=<?php echo $livre['id_livres']; ?>" class="eliminer">&Eacute;liminer</a></li>
            </ul>
          </td>
        </tr>
<?php endforeach; ?>
<?php else : ?>
        <tr>
          <td colspan="<?php echo sizeof($relations) + 3; ?>">Pas de livres.</td>
        </tr>
<?php endif; ?>
      </tbody>
    </table>
    <form action="?livres" method="post">
      <fieldset>
        <legend>Rechercher</legend>
        <div>
          <input type="text" name="q" id="q" value="<?php echo $q; ?>" />
          <input type="hidden" name="order" id="order" value="<?php echo $orderField; ?>" />
<?php if ( $orderField == $relation && $orderDir == 'DESC' ) : ?>
          <input type="hidden" name="dir" id="dir" value="asc" />
<?php endif; ?>
          <input type="submit" class="submit" value="Aller" />
        </div>
      </fieldset>
    </form>
<?php endif; ?>
<?php endif; ?>
  </body>
</html>
