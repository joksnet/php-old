<?php

/**
 * ALTER TABLE xxx DEFAULT CHARSET latin1 COLLATE latin1_spanish_ci;
 */

function e( $name, $value = '', $level = 0 )
{
    $title = false;

    if ( substr($name, 0, 1) == "\n" )
    {
        echo "\n";

        $name  = '[' . substr($name, 1) . ']';
        $title = true;
    }

    if ( strlen($value) > 0 )
    {
        if ( is_array($value) )
            $value = implode('][', $value);

        $value = preg_replace('/!/', '<span style="color: red;">!</span>', $value);

        if ( $value == 'OK' )
            $value = '<span style="color: green;">' . $value . '</span>';
        else
            $value = '<span style="color: blue;">' . $value . '</span>';

        if ( $title )
            echo '<span style="color: red;">' . str_pad( str_repeat('+ ', $level) . $name, 56, ' ', STR_PAD_RIGHT ) . '</span>' . "[$value]\n";
        else
            echo str_pad( str_repeat('+ ', $level) . $name, 56, '.', STR_PAD_RIGHT ) . "[$value]\n";
    }
    else
        echo "\n<span style=\"color: red\">[$name]</span>\n";

    flush();
}

function infoCollation()
{
    e( 'COLLATION' );

    static $collation = null;

    if ( null === $collation )
    {
        $collation = array();
        $collationSet = true;
    }

    $result = mysql_query('SHOW VARIABLES LIKE "collation_%"');

    while ( $row = mysql_fetch_array($result) )
    {
        $var = array_shift($row);
        $value = array_pop($row);

        if ( $collationSet )
            $collation[$var] = $value;

        e( $var, $value . ( ( $collation[$var] != $value ) ? '!' : '' ), 1 );
    }
}

$db = mysql_connect('localhost', 'root', '');

if ( isset($_POST['go']) )
{
    echo '<pre>';

    if ( isset($_POST['collate']) )
    {
        $collate = $_POST['collate'];
        $charset = array_shift( explode('_', $collate) );
    }
    else
    {
        $collate = 'latin1_spanish_ci';
        $charset = 'latin1';
    }

    e( 'PARAMETERS' );
    e( 'charset', $charset, 1 );
    e( 'collate', $collate, 1 );

    $databases = array();

    if ( isset($_POST['databases']) )
    {
        foreach ( $_POST['databases'] as $database => $isOK )
            if ( $isOK == 'ok' )
                $databases[] = $database;

        e( 'databases', implode(', ', $databases), 1 );
    }

    infoCollation();

    if ( @mysql_query("SET NAMES $charset COLLATE $collate") )
        e( "\n" . 'CONNECTION', 'OK' );
    else
        e( "\n" . 'CONNECTION', array( 'ERROR', mysql_error() ) );

    foreach ( $databases as $database )
    {
        $dbcurrent = mysql_select_db($database);
        $result    = mysql_query('SHOW TABLES');

        if ( @mysql_query("ALTER DATABASE $database DEFAULT CHARACTER SET $charset COLLATE $collate") )
            e( "\n" . strtoupper($database), 'OK' );
        else
            e( "\n" . strtoupper($database), array( 'ERROR', mysql_error() ) );

        while ( $row = mysql_fetch_array($result) )
        {
            $table = array_shift($row);

            if ( !( @mysql_query("ALTER TABLE $table DEFAULT CHARSET $charset COLLATE $collate") ) )
                e( $table, array( 'ERROR', mysql_error() ), 1 );
            else
                e( $table, 'OK', 1 );
        }
    }

    infoCollation();

    e( 'EOF' );
}
else
{
    $result    = mysql_query('SHOW COLLATION');
    $collation = array();

    while ( $row = mysql_fetch_assoc($result) )
        $collation[$row['Collation']] = $row;

    $result    = mysql_query('SHOW DATABASES');
    $databases = array();

    while ( $row = mysql_fetch_assoc($result) )
        $databases[] = $row['Database'];
?>
<form action="" method="post">
  <dl>
    <dt>
      <label>Tables</label>
    </dt>
    <dd>
      <ul>
<?php foreach ( $databases as $database ) : ?>
        <li>
          <input type="checkbox" name="databases[<?php echo $database; ?>]" id="<?php echo $database; ?>" value="ok" />
          <label for="<?php echo $database; ?>"><?php echo $database; ?></label>
        </li>
<?php endforeach; ?>
      </ul>
    </dd>

    <dt>
      <label for="collate">Collate</label>
    </dt>
    <dd>
      <select name="collate" id="collate">
<?php $collateCharset = ''; ?>
<?php foreach ( $collation as $collate => $collateData ) : ?>
<?php if ( $collateCharset != $collateData['Charset'] ) : ?>
<?php $collateCharset = $collateData['Charset']; ?>
        <optgroup label="<?php echo $collateCharset; ?>">
<?php endif; ?>
        <option value="<?php echo $collate; ?>"><?php echo $collate; ?></option>
<?php endforeach; ?>
      </select>
    </dd>

    <dd>
      <input type="submit" name="go" value="Go" />
    </dd>
  </dl>
</form>
<?php
}
