<?php

function getToken()
{
    $username = '';
    $password = '';

    $time = time();
    $hash = md5("$username@$password@$time");
    $auth = "$username@$time@$hash";

    $ip = ( isset($_SERVER['REMOTE_ADDR']) ) ? $_SERVER['REMOTE_ADDR'] : '';

    $url = 'http://axe.mappy.com/1v1/token/generate.aspx?'
         . 'auth=' . urlencode($auth) . '&'
         . 'ip=' . urldecode($ip);

    $remote = @fopen($url, 'rb');

    if ( false === $remote )
        return false;

    $token = '';

    while ( !( feof($remote) ) )
        $token .= fread($remote, 8192);

    fclose($remote);

    return $token;
}

mysql_connect('localhost', 'root', '');
mysql_select_db('mappy');

$coords = null;

$lst = ( isset($_GET['lst']) );
$add = ( isset($_GET['add']) );

$world = ( isset($_GET['world']) );

if ( $add )
{
    if ( sizeof($_POST) > 0 )
    {
        foreach ( $_POST as $key => $value )
            $_POST[$key] = addslashes($value);

        mysql_query(
            "INSERT INTO mappy ( name, country, region, ville, x, y )
             VALUES ( '{$_POST['name']}', '{$_POST['country']}', '{$_POST['region']}', '{$_POST['ville']}', '{$_POST['x']}', '{$_POST['y']}' )"
        );

        if ( mysql_errno() > 0 )
            header('Location: ?add&error=' . mysql_errno());
        else
            header('Location: ?add&saved=' . mysql_insert_id());
        exit();
    }
}
else
{
    $coords = array();
    $result = mysql_query(
        "SELECT x,y FROM mappy"
    );

    while ( $row = mysql_fetch_assoc($result) )
        $coords[] = "[{$row['x']},{$row['y']}]";

    $coords = implode(',', $coords);
}

?>
<html>
  <head>
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
    <script type="text/javascript" src="http://axe.mappy.com/1v1/init/get.aspx?auth=<?php echo getToken(); ?>&version=2.01&solution=ajax"></script>
    <script type="text/javascript">
        var lst = <?php echo $lst ? 'true' : 'false' ?>;
        var add = <?php echo $add ? 'true' : 'false' ?>;

        var world = <?php echo $world ? 'true' : 'false' ?>;

        var geocode = function( event )
        {
            var placemark = event[0].Placemark;

            var name        = placemark.name;
            var country     = placemark.AddressDetails.Country.CountryName;
            var countryCode = placemark.AddressDetails.Country.CountryNameCode.value;
            var region      = placemark.AddressDetails.Country.AdministrativeArea.AdministrativeAreaName;
            var ville       = placemark.AddressDetails.Country.AdministrativeArea.Locality.LocalityName;
            var x           = placemark.Point.coordinates[0];
            var y           = placemark.Point.coordinates[1];

            $('#name').val(name);
            $('#name-label').html(name);

            $('#country').val(country);
            $('#country-label').html(country);
            $('#country-code').html(countryCode);

            $('#region').val(region);
            $('#region-label').html(region);

            $('#ville').val(ville);
            $('#ville-label').html(ville);

            $('#x').val(x);
            $('#x-label').html(x);

            $('#y').val(y);
            $('#y-label').html(y);

            // console.log(placemark);
        };

        $(function()
        {
            var map = new Mappy.api.map.Map({
                container : '#map'
            });

            map.setCenter(
                new Mappy.api.geo.Coordinates(2.396579, 47.082892), 3
            );
/*
            map.addTool(
                new Mappy.api.map.tools.ToolBar({ zoom : true },
                    new Mappy.api.map.tools.ToolPosition('lt',
                        new Mappy.api.types.Point(10, 10)
                    )
                )
            );
 */
            var markers = new Mappy.api.map.layer.MarkerLayer(90);

            map.addLayer(markers);

            if ( add )
            {
                $('#add').show();

                var marker = new Mappy.api.map.Marker(
                    new Mappy.api.geo.Coordinates(2.396579, 47.082892)
                );

                markers.addMarker(marker);

                marker.addDraggable();
                marker.geocode(geocode);
                marker.addListener('dragstop', function()
                {
                    marker.geocode(geocode);
                });
            }
            else
            {
                if ( world )
                {
                    $('#map').css({
                        position : 'absolute',
                        top : '0', left : '0',
                        width  : '100%',
                        height : '100%'
                    });
                }

                var coords = [<?php echo $coords; ?>];

                for ( var i in coords )
                {
                    var marker = new Mappy.api.map.Marker(
                        new Mappy.api.geo.Coordinates(coords[i][0], coords[i][1])
                    );

                    markers.addMarker(marker);
                }
            }
        });
    </script>
  </head>
  <body>
    <p><a href="?lst">List</a> | <a href="?add">Add</a></p>
    <div id="map" style="width: 440px; height: 400px; float: left;"></div>
    <form action="" method="post">
      <dl id="add" style="float: left; margin: 0 0 0 10px; display: none;">
<?php if ( isset($_GET['saved']) ) : ?>
        <dd style="margin: 0 0 10px 0; padding: 5px; background-color: lightgreen; border: 1px solid green;">Saved</dd>
<?php endif; ?>

        <dt>Name</dt>
        <dd>
          <input type="hidden" name="name" id="name" value="" />
          <span id="name-label"></span>
        </dd>

        <dt>Country</dt>
        <dd>
          <input type="hidden" name="country" id="country" value="" />
          <span id="country-label"></span> (<span id="country-code"></span>)
        </dd>

        <dt>Region</dt>
        <dd>
          <input type="hidden" name="region" id="region" value="" />
          <span id="region-label"></span>
        </dd>

        <dt>Ville</dt>
        <dd>
          <input type="hidden" name="ville" id="ville" value="" />
          <span id="ville-label"></span>
        </dd>

        <dt>X</dt>
        <dd>
          <input type="hidden" name="x" id="x" value="" />
          <span id="x-label"></span>
        </dd>

        <dt>Y</dt>
        <dd>
          <input type="hidden" name="y" id="y" value="" />
          <span id="y-label"></span>
        </dd>

        <dt>&nbsp;</dt>
        <dd>
          <input type="submit" value="Save" />
        </dd>
      </dl>
    </form>
  </body>
</html>
