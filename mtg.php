<?php

$db = mysql_connect('localhost', 'root', '');
      mysql_select_db('mtg');

$result = mysql_query(
    'SELECT cards.id_cards
          , cards.name
          , cards.type
          , cards.color
          , cards.foil
          , cards.stock
          , cards.cost
     FROM cards
     ORDER BY cards.type
            , cards.color
            , cards.name'
);

$options = array(
    'us2ar' => 3,
    'showHeader' => true,
    'showPrices' => true,
    'hidePrices' => false
);

if ( ( $numRows = mysql_num_rows($result) ) > 0 )
{
    echo '<pre style="font-family: \'Courier New\'; font-size: 11px; background-color: #CCC;">';

    if ( $options['showHeader'] )
    {
        echo '<strong>Cartas Magic</strong><br /><br />'
           . 'Las cartas est&aacute;n en exelentes condiciones.<br />';

        if ( $options['showPrices'] )
        {
            if ( $options['showPrices'] === true )
                echo 'Los precios son por unidad.<br />';
            else
                echo 'El precio por carta es de Ar$ ' . ( $options['showPrices'] * $options['us2ar'] ) . '.-<br />';
        }


        echo 'Cualquier duda que tengas no dudes en consultar.<br /><br />'
           . 'Saludos,<br />'
           . 'Juan Manuel<br /><br />';
    }

    $rowIndex = 0;
    $row = mysql_fetch_assoc($result);

    while ( $rowIndex < $numRows )
    {
        $cardType = $row['type'];

        if ( $rowIndex > 0 )
            echo '<br />';

        echo $cardType . '<br />';

        while ( $rowIndex < $numRows && $cardType == $row['type'] )
        {
            $cardId = $row['id_cards'];
            $cardName = $row['name'];
            $cardColor = $row['color'];
            $cardStock = $row['stock'];

            $cardPriceUs = floatval( $row['cost'] );
            $cardPriceAr = floatval( $cardPriceUs * $options['us2ar'] );

            if ( $cardStock > 0 )
            {
                $cardLink = $cardStock . 'x '
                          . ( ( $row['foil'] == 'yes' ) ? '*FOIL* ' : '' )
                          . '<a href="http://ww2.wizards.com/Gatherer/CardDetails.aspx?id=' . $cardId . '"'
                          . ' style="color: ' . strtolower( $cardColor ) . ';">'
                          . htmlentities( $cardName ) . '</a>';

                if ( $options['showPrices'] )
                {
                    $cardString = $cardStock . 'x '
                                . ( ( $row['foil'] == 'yes' ) ? '*FOIL* ' : '' )
                                . $cardName;

                    $cardSpaces = str_repeat(' ', 40 - strlen($cardString) );

                    if ( strpos($cardPriceAr, '.') === false )
                        $cardPriceAr .= '.00';
                    else
                    {
                        list($integer, $decimal) = explode('.', $cardPriceAr);

                        $decimal .= str_repeat('0', 2 - strlen($decimal) );
                        $cardPriceAr = $integer . '.' . $decimal;
                    }

                    if ( $options['showPrices'] === true )
                        echo $cardLink . $cardSpaces . 'Ar$ ' . $cardPriceAr . '<br />';
                    else
                    {
                        if ( $cardPriceUs <= $options['showPrices'] )
                            if ( $options['hidePrices'] )
                                echo $cardLink . '<br />';
                            else
                                echo $cardLink . $cardSpaces . 'Ar$ ' . $cardPriceAr . '<br />';
                    }
                }
                else
                    echo $cardLink . '<br />';
            }

            $rowIndex++;
            $row = mysql_fetch_assoc($result);
        }
    }

    echo '</pre>';
}
else
    echo '(Vacio)';

mysql_free_result($result);
