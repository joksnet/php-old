<?php

class Perro
{
    public $jObjects = true;

    public $nombre = '';
    public $raza = '';
    public $edad = 0;

    public function __construct( $nombre, $raza = 'Dalmata' )
    {
        $this->nombre = $nombre;
        $this->raza = $raza;
    }

    public function getNombreYRaza()
    {
        return 'Nombre: ' . $this->nombre . '\nRaza: ' . $this->raza;
    }

    public function setRandomEdad( $from = 1, $to = 18 )
    {
        $this->edad = rand($from, $to);
    }
}

include_once 'jObjects.php';

?>
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
  <title>jObjects</title>
  <link rel="shortcut icon" href="/favicon.ico" />
  <script type="text/javascript" src="jObjects.js"></script>
  <script type="text/javascript">
function testObject()
{
    // Set the URL where the calls will be made
    jObject.options.url = 'index.php';

    // Create a new instance of your class, called Perro in this example
    // and send the parameters in an array.
    var rocky = jObject('Perro', ['Rocky', 'Bulldog']);

    // Change the value of a variable in your instance
    rocky.val('nombre', 'Jorge');

    // Call a method with no results, and send parameters in an array
    rocky.call('setRandomEdad', [4, 12]);

    // Call a method with results
    var nombreYRaza = rocky.call('getNombreYRaza');

    // Get the value of a variable
    var edad = rocky.val('edad');

    alert( nombreYRaza + '\nEdad: ' + edad );

    // Destroy all data
    rocky.die();
}
  </script>
</head>

<body>
  <h1>jObjects</h1>
  <p>An way to use <acronym title="PHP: Hypertext Preprocessor">PHP</acronym> objects in JavaScript</p>
  <h2>Example</h2>
  <pre>
<span style="color: grey;">// Set the URL where the calls will be made</span>
jObject.options.url = 'index.php';

<span style="color: grey;">// Create a new instance of your class, called Perro in this example</span>
<span style="color: grey;">// and send the parameters in an array.</span>
<span style="color: blue;">var</span> rocky = jObject(<span style="color: green;">'Perro'</span>, [<span style="color: green;">'Rocky'</span>, <span style="color: green;">'Bulldog'</span>]);

<span style="color: grey;">// Change the value of a variable in your instance</span>
rocky.val(<span style="color: green;">'nombre'</span>, <span style="color: green;">'Jorge'</span>);

<span style="color: grey;">// Call a method with no results, and send parameters in an array</span>
rocky.call(<span style="color: green;">'setRandomEdad'</span>, [<span style="color: red;">4</span>, <span style="color: red;">12</span>]);

<span style="color: grey;">// Call a method with results</span>
<span style="color: blue;">var</span> nombreYRaza = rocky.call(<span style="color: green;">'getNombreYRaza'</span>);

<span style="color: grey;">// Get the value of a variable</span>
<span style="color: blue;">var</span> edad = rocky.val(<span style="color: green;">'edad'</span>);

alert( nombreYRaza + <span style="color: green;">'\nEdad: '</span> + edad );

<span style="color: grey;">// Destroy all data</span>
rocky.die();
  </pre>
  <p><input type="button" onclick="testObject();" value="Test Example" /> or <a href="index.phps">download</a> this file</p>
  <h2>Downloads</h2>
  <ul>
    <li>2008.01.29 <a href="jObjects.v0.4.rar" title="8 Kb (8,192 bytes)">jObjects.v0.4.rar</a></li>
    <li>2007.11.22 <a href="jObjects.v0.3.zip" title="4 Kb (4,096 bytes)">jObjects.v0.3.zip</a></li>
  </ul>
  <h2>News</h2>
  <p><b>2008.01.29:</b> Now you can include an autogenerated <acronym title="JavaScript">JS</acronym> for calling your <acronym title="PHP: Hypertext Preprocessor">PHP</acronym> classes is <acronym title="JavaScript">JS</acronym>, just like they where <acronym title="JavaScript">JS</acronym> classes. Now <acronym title="PHP: Hypertext Preprocessor">PHP</acronym> classes need a public property called <code>jObjects</code> to work.</p>
  <address>Juan Manuel Martinez - joksnet [at] gmail [dot] com</address>
</body>
</html>