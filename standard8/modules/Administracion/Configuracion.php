<?php

class Configuracion extends Wax_Document_Body_Element
{
    public function __construct( Standard8 $Standard8, $tabSelected = 'General' )
    {
        parent::__construct('div');

        $this->appendChild( $Standard8->createTitleNav( __('Configuracion') )
                 ->appendNav( __('General'), Standard8_Uri::createUri('Configuracion_General', 'Administracion'), ( strtolower($tabSelected) == 'general' ) )
                 ->appendNav( __('Avanzada'), Standard8_Uri::createUri('Configuracion_Avanzada', 'Administracion'), ( strtolower($tabSelected) == 'avanzada' ) )
             )
             ->appendChild( Wax_Document::$body->createForm()
                 ->appendMessage('question', __('PreguntaEsObligatorio') )
                 ->appendText('question', __('Pregunta'), null, 100, true)

                 ->appendMessage('ascii', __('ASCIIEsObligatorio') )
                 ->appendNumeric('ascii', __('ASCII'), 0, array(0, 255), true)

                 ->appendSubmit('submit')
            );

        $Standard8->appendSidebarNav( __('Primero') )
                  ->appendSidebarNav( __('Segundo') );
    }
}