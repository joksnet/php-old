<?php

/**
 * This is a dog class
 *
 * @param string $nombre
 * @param string optional $raza
 */
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

    /**
     * This method returns a  formated string with the $nombre and $raza of the
     * dog.
     *
     * @return string
     */
    public function getNombreYRaza()
    {
        return 'Nombre: ' . $this->nombre . '\nRaza: ' . $this->raza;
    }

    /**
     * Set an $edad at random from a number, to a number.
     *
     * @param int $from
     * @param int $to
     */
    public function setRandomEdad( $from = 1, $to = 18 )
    {
        $this->edad = rand($from, $to);
    }
}

class Gato
{
    public $jObjects = true;

    public $nombre = '';
    public $siames = false;

    public function __construct( $nombre, $siames = false, $sexo = 'F' )
    {
        $this->nombre = $nombre;
        $this->siames = $siames;
    }
}

include_once 'jObjects.js.php';