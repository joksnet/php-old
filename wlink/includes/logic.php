<?php

class Logic
{
    public static $contacts = array();

    public static function load()
    {
        $sql = "SELECT c1.contacto_id
                     , c1.nombre
                     , c1.empresa
                     , c1.meet_in_person
                     , c2.contacto_id AS contacto_id_2
                     , c2.nombre AS nombre_2
                     , c2.empresa AS empresa_2
                     , c2.meet_in_person AS meet_in_person_2
                     , COUNT(c2.contacto_id) AS relaciones
                FROM contactos c1
                LEFT JOIN relaciones r ON ( r.contacto_id_left = c1.contacto_id )
                LEFT JOIN contactos c2 ON ( c2.contacto_id = r.contacto_id_right )
                WHERE c1.user_id = '{$_SESSION['UID']}'
                GROUP BY c1.contacto_id
                       , c1.nombre
                       , c1.empresa
                       , c1.meet_in_person
                       , c2.contacto_id
                       , c2.nombre
                       , c2.empresa
                       , c2.meet_in_person
                ORDER BY relaciones DESC";

        if ( !( $result = mysql_query($sql) ) )
            error(mysql_error(), __FILE__, __LINE__);

        if ( ( $rowCount = mysql_num_rows($result) ) > 0 )
        {
            $rowI = 0;
            $row = mysql_fetch_assoc($result);

            while ( $rowI < $rowCount )
            {
                $contactoID = $row['contacto_id'];

                if ( !( isset(self::$contacts[$row['contacto_id']]) ) )
                {
                    self::$contacts[$row['contacto_id']] = new Contacts(array(
                        'contacto_id'    => $row['contacto_id'],
                        'nombre'         => $row['nombre'],
                        'empresa'        => $row['empresa'],
                        'meet_in_person' => $row['meet_in_person']
                    ));
                }

                while ( $rowI < $rowCount && $row['contacto_id'] == $contactoID )
                {
                    if ( strlen($row['contacto_id_2']) > 0 )
                    {
                        if ( !( isset(self::$contacts[$row['contacto_id_2']]) ) )
                        {
                            self::$contacts[$row['contacto_id_2']] = new Contacts(array(
                                'contacto_id'    => $row['contacto_id_2'],
                                'nombre'         => $row['nombre_2'],
                                'empresa'        => $row['empresa_2'],
                                'meet_in_person' => $row['meet_in_person_2']
                            ));
                        }

                        self::$contacts[$contactoID]->addRelation( &self::$contacts[$row['contacto_id_2']] );
                    }

                    $row = mysql_fetch_assoc($result);
                    $rowI++;
                }
            }
        }
    }

    public static function toString()
    {
        $return = '';

        if ( sizeof(self::$contacts) > 0 )
            foreach ( self::$contacts as $contact )
                $return .= "\n" . $contact->toString();

        return $return;
    }
}

class Contacts
{
    public $relations = array();

    public $id = 0;
    public $nombre = '';
    public $empresa = '';
    public $meetInPerson = false;

    public $color;
    public $isDraw = false;
    public $rect = array(
        'x' => 0, 'y' => 0
    );

    public function __construct( $info )
    {
        $this->id           = $info['contacto_id'];
        $this->nombre       = $info['nombre'];
        $this->empresa      = $info['empresa'];
        $this->meetInPerson = $info['meet_in_person'];
    }

    public function addRelation( &$contact )
    {
        $this->relations[$contact->id] = $contact;
    }

    public function toString( $deep = 1 )
    {
        $relations = '';

        if ( sizeof($this->relations) > 0 )
        {
            foreach ( $this->relations as $relation )
                $relations .= "\n" . str_repeat('    ', $deep) . $relation->toString($deep + 1);
        }

        $strEmpresa = ( strlen($this->empresa) > 0 ) ? " $this->empresa" : '';
        $strNombre = ( strlen($this->nombre) > 0 ) ? " $this->nombre" : '';

        return "<Contact {$this->id}$strNombre$strEmpresa>$relations";
    }
}