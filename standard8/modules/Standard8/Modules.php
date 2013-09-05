<?php

class Modules extends Wax_Document_Body_Element
{
    public function __construct( Standard8 $Standard8 )
    {
        parent::__construct('div');

        $this->appendChild( $Standard8->createTitleNav( __('Modulos') ) )
             ->appendChild( Wax_Document::createElement('div')
                 ->innerHTML('404 Not Found')
             );
    }
}